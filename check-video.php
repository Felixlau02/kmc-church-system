<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sermon = \App\Models\Sermon::find(16); // Change to your sermon ID

if ($sermon && $sermon->video_path) {
    $videoFullPath = storage_path('app/public/' . $sermon->video_path);
    
    echo "=== Video File Check ===\n\n";
    echo "Video Path: {$sermon->video_path}\n";
    echo "Full Path: {$videoFullPath}\n";
    echo "File Exists: " . (file_exists($videoFullPath) ? "YES" : "NO") . "\n";
    
    if (file_exists($videoFullPath)) {
        echo "File Size: " . round(filesize($videoFullPath) / 1024 / 1024, 2) . " MB\n";
        
        // Check video properties using FFProbe
        try {
            $ffprobe = FFMpeg\FFProbe::create();
            $format = $ffprobe->format($videoFullPath);
            
            echo "\n=== Video Properties ===\n";
            echo "Duration: " . gmdate("H:i:s", $format->get('duration')) . "\n";
            echo "Bit Rate: " . round($format->get('bit_rate') / 1000000, 2) . " Mbps\n";
            echo "Format: " . $format->get('format_name') . "\n";
            
            $video = $ffprobe->streams($videoFullPath)->videos()->first();
            if ($video) {
                echo "Codec: " . $video->get('codec_name') . "\n";
                echo "Resolution: " . $video->get('width') . "x" . $video->get('height') . "\n";
                echo "Frame Rate: " . $video->get('r_frame_rate') . "\n";
            }
            
            echo "\n✅ Video file is valid!\n";
            
        } catch (\Exception $e) {
            echo "\n❌ Error reading video: " . $e->getMessage() . "\n";
        }
    } else {
        echo "\n❌ Video file not found!\n";
    }
} else {
    echo "Sermon not found or has no video!\n";
}