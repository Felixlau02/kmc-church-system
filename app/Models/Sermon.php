<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Sermon extends Model
{
    use HasFactory;

    protected $fillable = [
        'reverend_name',
        'sermon_theme',
        'sermon_description',
        'sermon_date',
        'scripture_references',
        'video_path',
        'video_thumbnail',
        'video_duration',
        'ai_transcript',
        'ai_summary',
        'ai_key_points',
        'ai_translations',
        'processing_status',
        'processing_error',
    ];

    protected $casts = [
        'sermon_date' => 'date',
        'ai_key_points' => 'array',
        'ai_translations' => 'array',
    ];

    /**
     * FIXED: Only clean null bytes, preserve all valid UTF-8
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($sermon) {
            // Only remove null bytes from string fields
            $stringFields = [
                'reverend_name',
                'sermon_theme',
                'sermon_description',
                'scripture_references',
                'ai_transcript',
                'ai_summary',
                'processing_error',
            ];

            foreach ($stringFields as $field) {
                if (!empty($sermon->$field)) {
                    $sermon->$field = self::cleanNullBytes($sermon->$field);
                }
            }

            // Clean array fields
            if (!empty($sermon->ai_key_points) && is_array($sermon->ai_key_points)) {
                $sermon->ai_key_points = array_map([self::class, 'cleanNullBytes'], $sermon->ai_key_points);
            }

            if (!empty($sermon->ai_translations) && is_array($sermon->ai_translations)) {
                $cleaned = [];
                foreach ($sermon->ai_translations as $key => $value) {
                    $cleaned[$key] = self::cleanNullBytes($value);
                }
                $sermon->ai_translations = $cleaned;
            }
        });
    }

    /**
     * FIXED: Only remove null bytes, preserve all valid UTF-8 characters
     */
    private static function cleanNullBytes($text)
    {
        if (!is_string($text)) {
            return $text;
        }

        // Only remove null bytes - preserve ALL other UTF-8 characters
        $text = str_replace("\0", '', $text);
        
        // Ensure valid UTF-8 (but don't strip characters)
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        return trim($text);
    }

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->video_duration 
                ? gmdate('H:i:s', $this->video_duration) 
                : null,
        );
    }

    public function hasVideo(): bool
    {
        return !empty($this->video_path);
    }

    public function isProcessed(): bool
    {
        return $this->processing_status === 'completed';
    }

    public function getAvailableLanguages(): array
    {
        return $this->ai_translations ? array_keys($this->ai_translations) : [];
    }
}