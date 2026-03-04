@extends('layouts.admin')

@section('content')
<style>
    .sermon-detail-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .detail-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid #e5e7eb;
    }

    .detail-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
    }

    .detail-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-icon {
        color: #8b5cf6;
    }

    .video-section {
        margin-bottom: 2rem;
    }

    .video-wrapper {
        position: relative;
        width: 100%;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
    }

    .video-wrapper video {
        width: 100%;
        max-height: 600px;
        display: block;
    }

    .video-error {
        background: #fee;
        border: 2px solid #fcc;
        padding: 1rem;
        border-radius: 8px;
        color: #c00;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-icon {
        font-size: 1.75rem;
    }

    .ai-summary-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #bae6fd;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .language-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .language-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: white;
        color: #6b7280;
    }

    .language-tab.active {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border-color: #8b5cf6;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .language-tab:hover:not(.active) {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .summary-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .summary-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .summary-text {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        line-height: 1.8;
        color: #374151;
        font-size: 1rem;
        border-left: 4px solid #8b5cf6;
    }

    .key-points-section {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #fcd34d;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .key-points-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .key-point-item {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: start;
        gap: 1rem;
        border-left: 4px solid #f59e0b;
    }

    .key-point-number {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .key-point-text {
        color: #374151;
        line-height: 1.6;
        flex: 1;
    }

    .transcript-section {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
    }

    .transcript-text {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        line-height: 1.8;
        color: #374151;
        font-size: 0.95rem;
        white-space: pre-wrap;
        max-height: 500px;
        overflow-y: auto;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(139, 92, 246, 0.5);
    }

    .btn-secondary {
        background: white;
        color: #6b7280;
        border-color: #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5);
    }

    @media (max-width: 768px) {
        .sermon-detail-container {
            padding: 0 1rem;
        }

        .detail-card {
            padding: 1.5rem;
        }

        .detail-title {
            font-size: 1.5rem;
        }

        .language-tabs {
            flex-direction: column;
        }

        .language-tab {
            width: 100%;
            text-align: center;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="sermon-detail-container">
    <div class="detail-card">
        <div class="detail-header">
            <h1 class="detail-title">{{ $sermon->sermon_theme }}</h1>
            <div class="detail-meta">
                <div class="meta-item">
                    <i class="fas fa-user meta-icon"></i>
                    <span>{{ $sermon->reverend_name }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar meta-icon"></i>
                    <span>{{ $sermon->sermon_date->format('F d, Y') }}</span>
                </div>
                @if($sermon->scripture_references)
                    <div class="meta-item">
                        <i class="fas fa-book meta-icon"></i>
                        <span>{{ $sermon->scripture_references }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if($sermon->sermon_description)
            <div style="margin-bottom: 2rem;">
                <p style="color: #6b7280; line-height: 1.8;">{{ $sermon->sermon_description }}</p>
            </div>
        @endif

        @if($sermon->video_path)
            <div class="video-section">
                @php
                    // Check if video file exists
                    $videoExists = \Storage::disk('public')->exists($sermon->video_path);
                    $videoUrl = $videoExists ? asset('storage/' . $sermon->video_path) : null;
                @endphp

                @if($videoExists)
                    <div class="video-wrapper">
                        <video controls controlsList="nodownload" preload="metadata">
                            <source src="{{ $videoUrl }}" type="video/mp4">
                            <source src="{{ $videoUrl }}" type="video/quicktime">
                            <source src="{{ $videoUrl }}" type="video/x-msvideo">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @else
                    <div class="video-error">
                        <strong>⚠️ Video file not found</strong><br>
                        Path: {{ $sermon->video_path }}<br>
                        The video file may have been moved or deleted.
                    </div>
                @endif
            </div>
        @endif

        @if($sermon->isProcessed())
            <!-- AI-Generated Summary (All Languages) -->
            <div class="ai-summary-section">
                <h2 class="section-title">
                    <span class="section-icon">🤖</span>
                    AI-Generated Summary
                </h2>

                <div class="language-tabs">
                    <button class="language-tab active" data-lang="en">
                        🇬🇧 English
                    </button>
                    <button class="language-tab" data-lang="ms">
                        🇲🇾 Bahasa Malaysia
                    </button>
                    <button class="language-tab" data-lang="zh">
                        🇨🇳 中文
                    </button>
                </div>

                <!-- English Summary -->
                <div class="summary-content active" data-content="en">
                    <div class="summary-text">
                        {{ $sermon->ai_summary }}
                    </div>
                </div>

                <!-- Malay Summary -->
                <div class="summary-content" data-content="ms">
                    <div class="summary-text">
                        {{ $sermon->ai_translations['ms'] ?? 'Ringkasan tidak tersedia' }}
                    </div>
                </div>

                <!-- Chinese Summary -->
                <div class="summary-content" data-content="zh">
                    <div class="summary-text" style="font-family: 'Microsoft YaHei', 'PingFang SC', sans-serif;">
                        {{ $sermon->ai_translations['zh'] ?? '摘要不可用' }}
                    </div>
                </div>
            </div>

            <!-- Key Points -->
            @if($sermon->ai_key_points)
                <div class="key-points-section">
                    <h2 class="section-title">
                        <span class="section-icon">💡</span>
                        Key Points
                    </h2>
                    <div class="key-points-list">
                        @foreach($sermon->ai_key_points as $index => $point)
                            <div class="key-point-item">
                                <div class="key-point-number">{{ $index + 1 }}</div>
                                <div class="key-point-text">{{ $point }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Full Transcript -->
            @if($sermon->ai_transcript)
                <div class="transcript-section">
                    <h2 class="section-title">
                        <span class="section-icon">📄</span>
                        Full Transcript
                    </h2>
                    <div class="transcript-text">{{ $sermon->ai_transcript }}</div>
                </div>
            @endif
        @endif

        <div class="action-buttons">
            <a href="{{ route('admin.sermon.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Sermons
            </a>
            <a href="{{ route('admin.sermon.edit', $sermon->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Edit Sermon
            </a>
            @if($sermon->ai_transcript)
                <a href="{{ route('admin.sermon.transcript.download', $sermon->id) }}" class="btn btn-success">
                    <i class="fas fa-download"></i>
                    Download Transcript
                </a>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.language-tab');
    const contents = document.querySelectorAll('.summary-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const lang = this.getAttribute('data-lang');

            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.querySelector(`[data-content="${lang}"]`).classList.add('active');
        });
    });

    // Video error handling
    const video = document.querySelector('video');
    if (video) {
        video.addEventListener('error', function(e) {
            console.error('Video error:', e);
            const wrapper = this.closest('.video-wrapper');
            wrapper.innerHTML = `
                <div class="video-error">
                    <strong>⚠️ Error loading video</strong><br>
                    The video file may be corrupted or in an unsupported format.<br>
                    Error: ${this.error ? this.error.message : 'Unknown error'}
                </div>
            `;
        });

        video.addEventListener('loadedmetadata', function() {
            console.log('Video loaded successfully');
        });
    }
});
</script>
@endsection