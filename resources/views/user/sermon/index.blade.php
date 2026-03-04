@extends('layouts.user')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary-color: #667eea;
        --secondary-color: #764ba2;
    }

    body {
        background: #f8f9fa;
        min-height: 100vh;
    }

    .sermon-page-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .sermon-page-header::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
        top: -200px;
        right: -100px;
    }

    .sermon-page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sermon-page-header p {
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .ai-badge-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 0.4rem 1.2rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        animation: glow 2s infinite;
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
        margin-left: 1rem;
    }

    @keyframes glow {
        0%, 100% { box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4); }
        50% { box-shadow: 0 4px 25px rgba(245, 87, 108, 0.6); }
    }

    /* Controls Section */
    .controls-section {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        align-items: center;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 0.95rem 1.25rem 0.95rem 3.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        background: white;
        color: #1e293b;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .search-box input::placeholder {
        color: #94a3b8;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background: #f8fafc;
    }

    .search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.25rem;
        color: #94a3b8;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-section h4 {
        color: #475569;
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-chips {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-chip {
        padding: 0.7rem 1.4rem;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 50px;
        color: #475569;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-chip:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-2px);
    }

    .filter-chip.active {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* Sermon Grid */
    .sermon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .sermon-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .sermon-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        border-color: #667eea;
    }

    /* Thumbnail */
    .sermon-thumbnail {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: rgba(255,255,255,0.2);
    }

    /* Status Badge */
    .status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 1;
    }

    .badge-completed {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }

    .badge-processing {
        background: rgba(255, 193, 7, 0.9);
        color: #856404;
    }

    .badge-video {
        background: rgba(59, 130, 246, 0.9);
        color: white;
    }

    /* Card Content */
    .sermon-card-body {
        padding: 1.75rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .sermon-card-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .sermon-card-reverend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .sermon-card-meta {
        display: flex;
        gap: 1.25rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #64748b;
        font-size: 0.85rem;
    }

    .sermon-card-description {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .sermon-card-scripture {
        background: #fef3c7;
        padding: 0.85rem 1rem;
        border-left: 3px solid #fbbf24;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #92400e;
        font-style: italic;
        margin-bottom: 1rem;
    }

    /* AI Features */
    .ai-features {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .feature-tag {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Card Footer */
    .sermon-card-footer {
        display: flex;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-view {
        flex: 1;
        padding: 0.8rem 1.25rem;
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.35);
    }

    .btn-share {
        padding: 0.8rem 1.25rem;
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-share:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: #64748b;
        grid-column: 1 / -1;
    }

    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-state h3 {
        font-size: 1.75rem;
        color: #475569;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        font-size: 1.05rem;
        color: #64748b;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
    }

    .pagination a,
    .pagination span {
        padding: 0.7rem 1rem;
        border-radius: 10px;
        background: white;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        border: 1px solid #e2e8f0;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        border-color: #667eea;
    }

    .pagination .active span {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--primary-color);
    }

    /* Spinner */
    .spinner-small {
        border: 2px solid rgba(255,255,255,0.3);
        border-top: 2px solid #fff;
        border-radius: 50%;
        width: 14px;
        height: 14px;
        animation: spin 0.8s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sermon-page-header {
            padding: 1.5rem;
        }

        .sermon-page-header h1 {
            font-size: 1.5rem;
        }

        .sermon-page-header p {
            font-size: 0.9rem;
        }

        .sermon-grid {
            grid-template-columns: 1fr;
        }

        .controls-section {
            flex-direction: column;
        }

        .search-box {
            width: 100%;
            min-width: 100%;
        }
    }
</style>

<div class="container-fluid p-0">
    <!-- Header -->
    <div class="sermon-page-header">
        <h1>📖 Sermon Library</h1>
        <p>Discover inspiring sermons and spiritual guidance</p>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Search & Filter Controls -->
        <div class="controls-section">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <form method="GET" action="{{ route('user.sermon.index') }}" id="searchForm">
                    <input type="text" 
                           name="search" 
                           placeholder="Search sermons, speakers, or scripture..." 
                           value="{{ request('search') }}" 
                           onchange="this.form.submit()">
                </form>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h4>🔍 Quick Filters</h4>
            <div class="filter-chips">
                <a href="{{ route('user.sermon.index') }}" 
                   class="filter-chip {{ !request('date_filter') && !request('has_video') && !request('ai_processed') ? 'active' : '' }}">
                    All Sermons
                </a>
                <a href="{{ route('user.sermon.index', ['date_filter' => 'today']) }}" 
                   class="filter-chip {{ request('date_filter') === 'today' ? 'active' : '' }}">
                    📅 Today
                </a>
                <a href="{{ route('user.sermon.index', ['date_filter' => 'week']) }}" 
                   class="filter-chip {{ request('date_filter') === 'week' ? 'active' : '' }}">
                    📅 This Week
                </a>
                <a href="{{ route('user.sermon.index', ['date_filter' => 'month']) }}" 
                   class="filter-chip {{ request('date_filter') === 'month' ? 'active' : '' }}">
                    📅 This Month
                </a>
            </div>
        </div>

        <!-- Sermons Grid -->
        @if($sermons->count() > 0)
            <div class="sermon-grid">
                @foreach($sermons as $sermon)
                    <div class="sermon-card">
                        <!-- Thumbnail -->
                        @if($sermon->video_thumbnail)
                            <img src="{{ asset('storage/' . $sermon->video_thumbnail) }}" 
                                 alt="{{ $sermon->sermon_theme }}" 
                                 class="sermon-thumbnail">
                        @else
                            <div class="sermon-thumbnail">📖</div>
                        @endif

                        <!-- Status Badge -->
                        @if($sermon->hasVideo())
                            @if($sermon->processing_status === 'completed')
                                <div class="status-badge badge-completed">
                                    ✅ Ready
                                </div>
                            @elseif($sermon->processing_status === 'processing')
                                <div class="status-badge badge-processing">
                                    <div class="spinner-small"></div> Processing
                                </div>
                            @else
                                <div class="status-badge badge-video">
                                    🎥 Video
                                </div>
                            @endif
                        @endif

                        <!-- Card Body -->
                        <div class="sermon-card-body">
                            <h3 class="sermon-card-title">{{ $sermon->sermon_theme }}</h3>

                            <div class="sermon-card-reverend">
                                👤 {{ $sermon->reverend_name }}
                            </div>

                            <div class="sermon-card-meta">
                                <div class="meta-item">
                                    📅 {{ $sermon->sermon_date->format('M d, Y') }}
                                </div>
                                @if($sermon->video_duration)
                                <div class="meta-item">
                                    ⏱️ {{ $sermon->formatted_duration }}
                                </div>
                                @endif
                            </div>

                            @if($sermon->ai_summary)
                                <p class="sermon-card-description">
                                    {{ Str::limit($sermon->ai_summary, 130) }}
                                </p>
                            @elseif($sermon->sermon_description)
                                <p class="sermon-card-description">
                                    {{ Str::limit($sermon->sermon_description, 130) }}
                                </p>
                            @endif

                            @if($sermon->scripture_references)
                                <div class="sermon-card-scripture">
                                    📜 {{ $sermon->scripture_references }}
                                </div>
                            @endif

                            @if($sermon->isProcessed())
                                <div class="ai-features">
                                    <span class="feature-tag">📄 Transcript</span>
                                    <span class="feature-tag">📝 Summary</span>
                                    @if($sermon->ai_key_points && count($sermon->ai_key_points) > 0)
                                    <span class="feature-tag">💡 {{ count($sermon->ai_key_points) }} Key Points</span>
                                    @endif
                                    @if($sermon->ai_translations && count($sermon->ai_translations) > 0)
                                    <span class="feature-tag">🌍 {{ count($sermon->ai_translations) }} Languages</span>
                                    @endif
                                </div>
                            @endif

                            <div class="sermon-card-footer">
                                <a href="{{ route('user.sermon.show', $sermon->id) }}" class="btn-view">
                                    👁️ View Sermon
                                </a>
                                <button class="btn-share" onclick="shareSermon('{{ $sermon->sermon_theme }}', '{{ route('user.sermon.show', $sermon->id) }}')">
                                    📤
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $sermons->appends(request()->query())->links() }}
            </div>
        @else
            <div class="sermon-grid">
                <div class="empty-state">
                    <div class="empty-state-icon">📖</div>
                    <h3>No Sermons Found</h3>
                    <p>Try adjusting your search or filters to find sermons.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Share sermon function
function shareSermon(title, url) {
    if (navigator.share) {
        navigator.share({
            title: 'Check out this sermon: ' + title,
            text: title,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}

// Auto-refresh for processing sermons
const processingCards = document.querySelectorAll('.badge-processing');
if (processingCards.length > 0) {
    setInterval(() => {
        location.reload();
    }, 30000); // Refresh every 30 seconds
}
</script>
@endsection