@extends('layouts.admin')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .sermon-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    /* Beautiful Header */
    .sermon-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .sermon-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .header-top {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-content {
        flex: 1;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin: 0;
        font-weight: 400;
    }

    .header-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.875rem 1.75rem;
        background: white;
        color: #667eea;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        font-size: 0.9375rem;
        border: 2px solid white;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        background: #f9fafb;
        color: #667eea;
    }

    /* Stats Cards */
    .stats-container {
        display: none;
    }

    .stat-card {
        display: none;
    }

    /* Action Bar */
    .action-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 2rem;
    }

    .search-container {
        flex: 1;
        max-width: 600px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1.5rem 1rem 3.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }

    .search-icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        color: #9ca3af;
    }

    /* Filter Chips */
    .filter-bar {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .filter-chips {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .chip {
        padding: 0.75rem 1.5rem;
        background: #f3f4f6;
        border: 2px solid transparent;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        color: #4b5563;
    }

    .chip:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .chip.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* Sermon Cards */
    .sermons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .sermon-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
    }

    .sermon-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .card-thumbnail {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    }

    .card-content {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .card-reverend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .card-meta {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #6b7280;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .card-description {
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .card-scripture {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-style: italic;
        color: #92400e;
        margin-bottom: 1rem;
        border-left: 4px solid #f59e0b;
    }

    .card-actions {
        display: flex;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 2px solid #f3f4f6;
    }

    .btn-card {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-view {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 0.75rem 1rem;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    /* Alert */
    .alert-success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem 1.75rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        animation: slideDown 0.3s ease;
        font-weight: 600;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sermon-page {
            padding: 1rem;
        }

        .sermon-header {
            padding: 2rem 1.5rem;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .header-icon {
            font-size: 1.5rem;
            padding: 0.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .header-top {
            flex-direction: column;
        }

        .header-buttons {
            width: 100%;
        }

        .btn-create {
            flex: 1;
            justify-content: center;
        }

        .sermons-grid {
            grid-template-columns: 1fr;
        }

        .action-bar {
            flex-direction: column;
        }

        .search-container {
            width: 100%;
            max-width: 100%;
        }
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }

    .pagination a, .pagination span {
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        background: white;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .pagination a:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }

    .pagination .active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>

<div class="sermon-page">
    <!-- Beautiful Header -->
    <div class="sermon-header">
        <div class="header-top">
            <div class="header-content">
                <h2 class="page-title">
                    <span class="header-icon">📖</span>
                    Sermon Library
                </h2>
                <p class="page-subtitle">Manage and organize all your church sermons</p>
            </div>
            <div class="header-buttons">
                <a href="{{ route('admin.sermon.create') }}" class="btn-create">
                    <i class="fas fa-plus"></i>
                    <span>Add New Sermon</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="search-container">
            <span class="search-icon">🔍</span>
            <input type="text" class="search-input" id="searchInput" placeholder="Search sermons by theme, speaker, or scripture..." onkeyup="filterSermons()">
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-chips">
            <div class="chip active" onclick="filterByDate('all')">All Sermons</div>
            <div class="chip" onclick="filterByDate('today')">Today</div>
            <div class="chip" onclick="filterByDate('week')">This Week</div>
            <div class="chip" onclick="filterByDate('month')">This Month</div>
        </div>
    </div>

    <!-- Sermons Grid -->
    <div class="sermons-grid">
        @forelse($sermons as $sermon)
        <div class="sermon-card" 
             data-date="{{ $sermon->sermon_date }}" 
             data-reverend="{{ strtolower($sermon->reverend_name) }}" 
             data-theme="{{ strtolower($sermon->sermon_theme) }}" 
             data-scripture="{{ strtolower($sermon->scripture_references ?? '') }}">
            
            <!-- Thumbnail -->
            @if($sermon->video_thumbnail)
                <img src="{{ asset('storage/' . $sermon->video_thumbnail) }}" alt="Sermon Thumbnail" class="card-thumbnail">
            @elseif($sermon->video_path)
                <video class="card-thumbnail" style="object-fit: cover;">
                    <source src="{{ asset('storage/' . $sermon->video_path) }}" type="video/mp4">
                </video>
            @else
                <div class="card-thumbnail" style="display: flex; align-items: center; justify-content: center; font-size: 4rem; background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                    📖
                </div>
            @endif

            <!-- Card Content -->
            <div class="card-content">
                <h3 class="card-title">{{ $sermon->sermon_theme }}</h3>
                
                <div class="card-reverend">
                    <span>👤</span>
                    <span>{{ $sermon->reverend_name }}</span>
                </div>

                <div class="card-meta">
                    <div class="meta-item">
                        <span>📅</span>
                        <span>{{ $sermon->sermon_date->format('M d, Y') }}</span>
                    </div>
                </div>

                @if($sermon->sermon_description)
                    <p class="card-description">{{ Str::limit($sermon->sermon_description, 120) }}</p>
                @endif

                @if($sermon->scripture_references)
                <div class="card-scripture">
                    📜 {{ $sermon->scripture_references }}
                </div>
                @endif

                <div class="card-actions">
                    <a href="{{ route('admin.sermon.show', $sermon->id) }}" class="btn-card btn-view">
                        👁️ View
                    </a>
                    <a href="{{ route('admin.sermon.edit', $sermon->id) }}" class="btn-card btn-edit">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('admin.sermon.destroy', $sermon->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this sermon?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-card btn-delete">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <div class="empty-icon">📖</div>
            <h3>No Sermons Yet</h3>
            <p>Start building your sermon library by adding your first sermon.</p>
            <a href="{{ route('admin.sermon.create') }}" class="btn-create" style="display: inline-flex;">
                <i class="fas fa-plus"></i> Add Your First Sermon
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination">
        {{ $sermons->links() }}
    </div>
</div>

<script>
function filterSermons() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.sermon-card');
    
    cards.forEach(card => {
        const reverend = card.getAttribute('data-reverend');
        const theme = card.getAttribute('data-theme');
        const scripture = card.getAttribute('data-scripture');
        
        const isMatch = reverend.includes(searchTerm) || 
                       theme.includes(searchTerm) || 
                       scripture.includes(searchTerm);
        
        card.style.display = isMatch ? 'block' : 'none';
    });
}

function filterByDate(filter) {
    const cards = document.querySelectorAll('.sermon-card');
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    document.querySelectorAll('.chip').forEach(chip => chip.classList.remove('active'));
    event.target.classList.add('active');
    
    cards.forEach(card => {
        const sermonDate = new Date(card.getAttribute('data-date'));
        sermonDate.setHours(0, 0, 0, 0);
        
        let show = false;
        
        if (filter === 'all') {
            show = true;
        } else if (filter === 'today') {
            show = sermonDate.getTime() === today.getTime();
        } else if (filter === 'week') {
            const weekAgo = new Date(today);
            weekAgo.setDate(weekAgo.getDate() - 7);
            show = sermonDate >= weekAgo && sermonDate <= today;
        } else if (filter === 'month') {
            const monthAgo = new Date(today);
            monthAgo.setMonth(monthAgo.getMonth() - 1);
            show = sermonDate >= monthAgo && sermonDate <= today;
        }
        
        card.style.display = show ? 'block' : 'none';
    });
}
</script>
@endsection