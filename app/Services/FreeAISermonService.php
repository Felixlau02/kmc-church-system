<?php

namespace App\Services;

use App\Models\Sermon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class FreeAISermonService
{
    private $groqKey;

    public function __construct()
    {
        $this->groqKey = config('services.groq.key');
    }

    /**
     * Clean UTF-8 string - FIXED VERSION
     * Only removes null bytes and validates UTF-8, preserves all valid characters
     */
    private function cleanUtf8($text)
    {
        if (empty($text)) {
            return '';
        }

        // Remove null bytes only
        $text = str_replace("\0", '', $text);
        
        // Ensure valid UTF-8 encoding (keeps all valid UTF-8 including emojis and Chinese)
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }
        
        return trim($text);
    }

    public function processSermonVideo(Sermon $sermon)
    {
        try {
            $sermon->update(['processing_status' => 'processing']);

            Log::info("Step 1: Extracting audio from video...");
            $audioPath = $this->extractAudio($sermon->video_path);

            Log::info("Step 2: Generating thumbnail...");
            $this->generateThumbnail($sermon);

            Log::info("Step 3: Transcribing audio with Groq Whisper...");
            $transcript = $this->transcribeWithGroq($audioPath);
            $sermon->update(['ai_transcript' => $transcript]);

            Log::info("Step 4: Generating AI summary and key points...");
            $aiAnalysis = $this->generateSummaryWithGroq($transcript);
            $sermon->update([
                'ai_summary' => $aiAnalysis['summary'],
                'ai_key_points' => $aiAnalysis['key_points'],
            ]);

            Log::info("Step 5: Generating translations...");
            $translations = $this->translateSummaryWithGroq($aiAnalysis['summary']);
            $sermon->update(['ai_translations' => $translations]);

            $sermon->update(['processing_status' => 'completed']);
            Log::info("Processing completed successfully!");

            Storage::delete($audioPath);

            return true;

        } catch (\Exception $e) {
            Log::error('Sermon processing failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $sermon->update([
                'processing_status' => 'failed',
                'processing_error' => $this->cleanUtf8($e->getMessage())
            ]);
            return false;
        }
    }

    private function extractAudio($videoPath)
    {
        try {
            Log::info("Starting audio extraction from: {$videoPath}");
            
            $ffmpegPath = $this->findFFmpegPath();
            $ffprobePath = $this->findFFprobePath();
            
            Log::info("FFmpeg path: {$ffmpegPath}");
            Log::info("FFprobe path: {$ffprobePath}");
            
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => $ffmpegPath,
                'ffprobe.binaries' => $ffprobePath,
                'timeout'          => 3600,
                'ffmpeg.threads'   => 4,
            ]);
            
            $fullPath = Storage::path($videoPath);
            Log::info("Full video path: {$fullPath}");
            
            if (!file_exists($fullPath)) {
                throw new \Exception("Video file not found: {$fullPath}");
            }
            
            $video = $ffmpeg->open($fullPath);
            
            $audioPath = 'temp/audio_' . uniqid() . '.mp3';
            $fullAudioPath = Storage::path($audioPath);
            
            Log::info("Saving audio to: {$fullAudioPath}");
            
            $format = new \FFMpeg\Format\Audio\Mp3();
            $format->setAudioChannels(1);
            $format->setAudioKiloBitrate(64);
            
            $outputDir = dirname($fullAudioPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $video->save($format, $fullAudioPath);
            
            Log::info("Audio extraction completed successfully!");
            
            return $audioPath;
            
        } catch (\Exception $e) {
            Log::error('Audio extraction failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateThumbnail(Sermon $sermon)
    {
        try {
            Log::info("Generating thumbnail for sermon: {$sermon->id}");
            
            $ffmpegPath = $this->findFFmpegPath();
            $ffprobePath = $this->findFFprobePath();
            
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => $ffmpegPath,
                'ffprobe.binaries' => $ffprobePath,
                'timeout'          => 600,
            ]);
            
            $fullVideoPath = Storage::path($sermon->video_path);
            $video = $ffmpeg->open($fullVideoPath);
            
            $thumbnailPath = 'thumbnails/sermon_' . $sermon->id . '.jpg';
            $fullThumbnailPath = Storage::path($thumbnailPath);
            
            $thumbnailDir = dirname($fullThumbnailPath);
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }
            
            $frame = $video->frame(TimeCode::fromSeconds(10));
            $frame->save($fullThumbnailPath);
            
            $sermon->update(['video_thumbnail' => $thumbnailPath]);
            
            Log::info("Thumbnail generated successfully!");
            
            $duration = $ffmpeg->getFFProbe()
                ->format($fullVideoPath)
                ->get('duration');
            $sermon->update(['video_duration' => round($duration)]);
            
            Log::info("Video duration: {$duration} seconds");
            
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            $sermon->update(['video_thumbnail' => null, 'video_duration' => null]);
        }
    }

    private function transcribeWithGroq($audioPath)
    {
        Log::info("Using Groq Whisper for transcription...");
        
        $audioData = file_get_contents(Storage::path($audioPath));
        $audioSize = strlen($audioData);
        
        Log::info("Audio file size: " . round($audioSize / 1024 / 1024, 2) . " MB");
        
        if ($audioSize > 25 * 1024 * 1024) {
            throw new \Exception('Audio file too large (max 25MB). Please use shorter videos or compress the video.');
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->groqKey,
            ])->timeout(300)
              ->attach('file', $audioData, 'audio.mp3')
              ->attach('model', 'whisper-large-v3')
              ->post('https://api.groq.com/openai/v1/audio/transcriptions');

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['text'] ?? 'Transcription not available';
                $text = $this->cleanUtf8($text);
                
                Log::info("Transcription successful!");
                Log::info("Transcript length: " . strlen($text) . " characters");
                
                return $text;
            }

            Log::error("Groq Whisper failed: " . $response->status());
            Log::error("Response: " . $response->body());
            
            throw new \Exception('Transcription failed: ' . $response->body());
            
        } catch (\Exception $e) {
            Log::error('Transcription error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateSummaryWithGroq($transcript)
    {
        Log::info("Generating summary with Groq AI...");
        
        $cleanTranscript = $this->cleanUtf8($transcript);
        $cleanTranscript = substr($cleanTranscript, 0, 6000);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->groqKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)
              ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a theological expert. Analyze this sermon and provide: 1) A summary (200 words), 2) 5-7 key points. Respond ONLY in valid JSON format with keys "summary" and "key_points" (array of strings).'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Sermon transcript:\n\n" . $cleanTranscript
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
            ]);

            if (!$response->successful()) {
                Log::error("Groq API error: " . $response->body());
                throw new \Exception('Summary generation failed: ' . $response->body());
            }

            $result = $response->json();
            $contentString = $result['choices'][0]['message']['content'] ?? '{}';
            $content = json_decode($contentString, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from API: ' . json_last_error_msg());
            }
            
            $summary = $this->cleanUtf8($content['summary'] ?? 'Summary not available');
            $keyPoints = array_map([$this, 'cleanUtf8'], $content['key_points'] ?? []);
            
            Log::info("Summary generated successfully!");
            
            return [
                'summary' => $summary,
                'key_points' => $keyPoints,
            ];
            
        } catch (\Exception $e) {
            Log::error("Summary generation error: " . $e->getMessage());
            throw new \Exception('Failed to generate summary: ' . $e->getMessage());
        }
    }

    private function translateSummaryWithGroq($summary)
    {
        Log::info("Generating translations...");
        
        $languages = [
            'ms' => 'Malay / Bahasa Malaysia',
            'zh' => 'Chinese Simplified',
        ];

        $translations = [];

        foreach ($languages as $code => $language) {
            try {
                Log::info("Translating to {$language}...");
                
                $cleanSummary = $this->cleanUtf8($summary);
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->groqKey,
                    'Content-Type' => 'application/json',
                ])->timeout(60)
                  ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Translate this sermon summary to {$language}. Keep the spiritual meaning and tone. Return ONLY the translated text, nothing else."
                        ],
                        [
                            'role' => 'user',
                            'content' => $cleanSummary
                        ]
                    ],
                    'temperature' => 0.3,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $translation = $result['choices'][0]['message']['content'] ?? 'Translation not available';
                    $translation = $this->cleanUtf8($translation);
                    
                    $translations[$code] = $translation;
                    Log::info("Translation to {$language} successful!");
                } else {
                    Log::warning("Translation API failed for {$language}: " . $response->body());
                    $translations[$code] = "Translation not available";
                }
            } catch (\Exception $e) {
                Log::warning("Translation failed for {$language}: " . $e->getMessage());
                $translations[$code] = "Translation not available";
            }
        }

        return $translations;
    }

    private function findFFmpegPath()
    {
        $envPath = env('FFMPEG_BINARIES');
        if (!empty($envPath) && file_exists($envPath)) {
            return $envPath;
        }

        $projectPath = base_path('ffmpeg/bin/ffmpeg.exe');
        if (file_exists($projectPath)) {
            return $projectPath;
        }

        $possiblePaths = [
            'C:\\ffmpeg\\bin\\ffmpeg.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
            exec('where ffmpeg 2>nul'),
        ];

        foreach ($possiblePaths as $path) {
            if (!empty($path) && file_exists($path)) {
                return $path;
            }
        }

        return 'ffmpeg';
    }

    private function findFFprobePath()
    {
        $envPath = env('FFPROBE_BINARIES');
        if (!empty($envPath) && file_exists($envPath)) {
            return $envPath;
        }

        $projectPath = base_path('ffmpeg/bin/ffprobe.exe');
        if (file_exists($projectPath)) {
            return $projectPath;
        }

        $possiblePaths = [
            'C:\\ffmpeg\\bin\\ffprobe.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffprobe.exe',
            exec('where ffprobe 2>nul'),
        ];

        foreach ($possiblePaths as $path) {
            if (!empty($path) && file_exists($path)) {
                return $path;
            }
        }

        return 'ffprobe';
    }
}