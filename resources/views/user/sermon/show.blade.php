@extends('layouts.user')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    body {
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* Header Section */
    .sermon-detail-header {
        background: var(--primary-gradient);
        color: white;
        padding: 3.5rem 2.5rem;
        border-radius: 25px;
        margin-bottom: 2.5rem;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .sermon-detail-header::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
        top: -200px;
        right: -100px;
    }

    .sermon-title-main {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        position: relative;
        z-index: 1;
    }

    .meta-row {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .meta-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 1.05rem;
    }

    /* Processing Status */
    .status-alert {
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .status-processing {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        border-left: 6px solid #ffc107;
    }

    .status-completed {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 6px solid #28a745;
    }

    .spinner-large {
        border: 4px solid rgba(255,193,7,0.2);
        border-top: 4px solid #ffc107;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Content Layout */
    .content-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
    }

    .main-column {
        min-width: 0;
    }

    .sidebar-column {
        position: sticky;
        top: 2rem;
        height: fit-content;
    }

    /* Video Player */
    .video-player-container {
        background: #000;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .video-player-container video {
        width: 100%;
        display: block;
    }

    /* Content Cards */
    .content-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .card-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #667eea;
    }

    .card-title-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    /* AI Summary */
    .ai-summary {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid #3b82f6;
        padding: 1.75rem;
        border-radius: 12px;
        line-height: 1.8;
        font-size: 1.05rem;
        color: #334155;
    }

    /* Key Points */
    .key-points-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .key-point-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-radius: 12px;
        border-left: 4px solid #764ba2;
        display: flex;
        gap: 1rem;
        transition: all 0.3s;
    }

    .key-point-item:hover {
        transform: translateX(10px);
        box-shadow: 0 6px 25px rgba(118, 75, 162, 0.15);
    }

    .key-point-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .key-point-text {
        font-size: 1.05rem;
        line-height: 1.6;
        color: #334155;
    }

    /* Translations */
    .translation-tabs {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .translation-tab {
        padding: 0.9rem 1.75rem;
        border-radius: 50px;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .translation-tab:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        background: white;
    }

    .translation-tab.active {
        background: var(--primary-gradient);
        color: white;
        border-color: #667eea;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .translation-content {
        display: none;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 2rem;
        border-radius: 12px;
        line-height: 1.9;
        font-size: 1.05rem;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    .translation-content.active {
        display: block;
    }

    /* Transcript */
    .transcript-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 2rem;
        border-radius: 12px;
        max-height: 600px;
        overflow-y: auto;
        line-height: 1.9;
        color: #334155;
        border: 1px solid #e2e8f0;
        font-size: 1.05rem;
    }

    .transcript-box::-webkit-scrollbar {
        width: 10px;
    }

    .transcript-box::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .transcript-box::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 10px;
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .sidebar-card-title {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: 0.5px;
    }

    .sidebar-card-value {
        font-size: 1.2rem;
        color: #1e293b;
        font-weight: 700;
        line-height: 1.4;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .btn-action {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-back {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-back:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-share {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e2e8f0;
    }

    .btn-share:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }

    /* Related Sermons */
    .related-sermon-item {
        padding: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s;
        cursor: pointer;
    }

    .related-sermon-item:last-child {
        border-bottom: none;
    }

    .related-sermon-item:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .related-sermon-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .related-sermon-meta {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .related-sermon-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .related-sermon-link:hover {
        color: #764ba2;
    }

    @media (max-width: 1024px) {
        .content-layout {
            grid-template-columns: 1fr;
        }

        .sidebar-column {
            position: static;
        }

        .sermon-title-main {
            font-size: 2rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Sermon Header -->
    <div class="sermon-detail-header">
        <h1 class="sermon-title-main">{{ $sermon->sermon_theme }}</h1>
        <div class="meta-row">
            <div class="meta-badge">
                👤 {{ $sermon->reverend_name }}
            </div>
            <div class="meta-badge">
                📅 {{ $sermon->sermon_date->format('F d, Y') }}
            </div>
            @if($sermon->video_duration)
            <div class="meta-badge">
                ⏱️ {{ $sermon->formatted_duration }}
            </div>
            @endif
        </div>
    </div>

    <!-- Processing Status -->
    @if($sermon->hasVideo() && $sermon->processing_status === 'processing')
    <div class="status-alert status-processing">
        <div class="spinner-large"></div>
        <div>
            <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: #856404;">
                ⏳ Processing in Progress
            </h3>
            <p style="margin: 0; font-size: 1.05rem; color: #856404;">
                Generating transcript and translations for this sermon. Check back in a few minutes!
            </p>
        </div>
    </div>
    @endif

    <!-- Content Layout -->
    <div class="content-layout">
        <!-- Main Column -->
        <div class="main-column">
            <!-- Video Player -->
            @if($sermon->hasVideo())
            <div class="video-player-container">
                <video controls preload="metadata"
                       @if($sermon->video_thumbnail) poster="{{ asset('storage/' . $sermon->video_thumbnail) }}" @endif>
                    <source src="{{ asset('storage/' . $sermon->video_path) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            @endif

            <!-- Summary & Translations Combined -->
@if($sermon->ai_summary || ($sermon->ai_translations && count($sermon->ai_translations) > 0))
<div class="content-card">
    <h2 class="card-title">
        <span class="card-title-icon">📋</span>
        Sermon Summary & Translations
    </h2>
    
    <!-- Tab Navigation -->
    <div class="translation-tabs">
        @if($sermon->ai_summary)
        <div class="translation-tab active" onclick="showTranslation('summary')">
            🇬🇧 English Summary
        </div>
        @endif
        
        @if($sermon->ai_translations && count($sermon->ai_translations) > 0)
            @php
                $languages = [
                    'ms' => ['name' => 'Bahasa Melayu', 'flag' => '🇲🇾'],
                    'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
                    'ta' => ['name' => 'தமிழ்', 'flag' => '🇮🇳'],
                    'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
                    'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
                ];
            @endphp
            @foreach($sermon->ai_translations as $lang => $translation)
                @if(isset($languages[$lang]))
                <div class="translation-tab" onclick="showTranslation('{{ $lang }}')">
                    {{ $languages[$lang]['flag'] }} {{ $languages[$lang]['name'] }}
                </div>
                @endif
            @endforeach
        @endif
    </div>
    
    <!-- Summary Content -->
    @if($sermon->ai_summary)
    <div class="translation-content active" id="translation-summary">
        <div class="ai-summary" style="border-left-color: #3b82f6;">
            {{ $sermon->ai_summary }}
        </div>
    </div>
    @endif
    
    <!-- Translation Contents -->
    @if($sermon->ai_translations && count($sermon->ai_translations) > 0)
        @foreach($sermon->ai_translations as $lang => $translation)
        <div class="translation-content" id="translation-{{ $lang }}">
            {{ $translation }}
        </div>
        @endforeach
    @endif
</div>
@endif

            <!-- Description -->
            @if($sermon->sermon_description)
            <div class="content-card">
                <h2 class="card-title">
                    <span class="card-title-icon">📝</span>
                    About This Sermon
                </h2>
                <p style="line-height: 1.8; color: #475569; font-size: 1.05rem;">
                    {{ $sermon->sermon_description }}
                </p>
            </div>
            @endif

            <!-- Key Points -->
            @if($sermon->ai_key_points && count($sermon->ai_key_points) > 0)
            <div class="content-card">
                <h2 class="card-title">
                    <span class="card-title-icon">💡</span>
                    Key Takeaways
                </h2>
                <ul class="key-points-list">
                    @foreach($sermon->ai_key_points as $point)
                    <li class="key-point-item">
                        <span class="key-point-icon">✔</span>
                        <span class="key-point-text">{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Transcript -->
            @if($sermon->ai_transcript)
            <div class="content-card">
                <h2 class="card-title">
                    <span class="card-title-icon">📄</span>
                    Full Transcript
                </h2>
                <div class="transcript-box">
                    {{ $sermon->ai_transcript }}
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Column -->
        <div class="sidebar-column">
            <!-- Scripture References -->
            @if($sermon->scripture_references)
            <div class="sidebar-card">
                <div class="sidebar-card-title">📜 Scripture References</div>
                <div class="sidebar-card-value">
                    {{ $sermon->scripture_references }}
                </div>
            </div>
            @endif

            <!-- Date Info -->
            <div class="sidebar-card">
                <div class="sidebar-card-title">📅 Sermon Date</div>
                <div class="sidebar-card-value">
                    {{ $sermon->sermon_date->format('F d, Y') }}
                </div>
            </div>

            <!-- AI Status -->
            @if($sermon->isProcessed())
            <div class="sidebar-card" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-color: #28a745;">
                <div class="sidebar-card-title" style="color: #155724;">CONTENT STATUS</div>
                <div class="sidebar-card-value" style="color: #155724;">
                    ✅ Complete
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="sidebar-card">
                <div class="action-buttons">
                    <a href="{{ route('user.sermon.index') }}" class="btn-action btn-back">
                        ← Back to Library
                    </a>
                    <button class="btn-action btn-share" 
                            onclick="shareSermon('{{ $sermon->sermon_theme }}', '{{ route('user.sermon.show', $sermon->id) }}')">
                        📤 Share Sermon
                    </button>
                </div>
            </div>

            <!-- Related Sermons -->
            @if($relatedSermons->count() > 0)
            <div class="sidebar-card">
                <div class="sidebar-card-title">📚 Related Sermons</div>
                @foreach($relatedSermons as $related)
                <div class="related-sermon-item" onclick="window.location='{{ route('user.sermon.show', $related->id) }}'">
                    <div class="related-sermon-title">
                        {{ Str::limit($related->sermon_theme, 60) }}
                    </div>
                    <div class="related-sermon-meta">
                        👤 {{ $related->reverend_name }} • 
                        📅 {{ $related->sermon_date->format('M d, Y') }}
                    </div>
                    <a href="{{ route('user.sermon.show', $related->id) }}" class="related-sermon-link">
                        Listen Now →
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Translation tabs
function showTranslation(lang) {
    document.querySelectorAll('.translation-content').forEach(content => {
        content.classList.remove('active');
    });
    document.querySelectorAll('.translation-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    document.getElementById('translation-' + lang).classList.add('active');
    event.target.classList.add('active');
}

// Share sermon
function shareSermon(title, url) {
    if (navigator.share) {
        navigator.share({
            title: 'Check out this sermon: ' + title,
            text: title,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}

// Auto-refresh processing status
@if($sermon->hasVideo() && $sermon->processing_status === 'processing')
setInterval(function() {
    fetch('{{ route("user.sermon.status", $sermon->id) }}')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'completed') {
                location.reload();
            }
        });
}, 15000); // Check every 15 seconds
@endif
</script>
@endsection