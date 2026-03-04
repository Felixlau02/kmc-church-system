@extends('layouts.admin')

@section('content')
<style>
    .events-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
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

    .btn-header {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.875rem 1.75rem;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
        border: 2px solid transparent;
    }

    .btn-history {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-color: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-history:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
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

    /* Filter Tabs */
    .filter-tabs {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-label {
        font-weight: 600;
        color: #6b7280;
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }

    .filter-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        cursor: pointer;
    }

    .filter-tab:hover {
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .filter-tab .tab-icon {
        font-size: 1.1rem;
    }

    .success-alert {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        animation: slideDown 0.3s ease;
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

    /* Compact List View */
    .events-list {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .event-item {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
        display: grid;
        grid-template-columns: auto 1fr auto auto;
        gap: 1.5rem;
        align-items: center;
    }

    .event-item:hover {
        background: #f9fafb;
    }

    .event-item:last-child {
        border-bottom: none;
    }

    .event-indicator {
        width: 4px;
        height: 50px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .indicator-cell-meeting {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .indicator-sunday-service {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .indicator-prayer-meeting {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .event-main {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 0;
    }

    .event-title-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .event-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .event-type-badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 16px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-cell-meeting {
        background: #ede9fe;
        color: #6b21a8;
    }

    .badge-sunday-service {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-prayer-meeting {
        background: #fef3c7;
        color: #92400e;
    }

    .event-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .meta-icon {
        color: #667eea;
        font-size: 0.9rem;
    }

    .event-stats {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.75rem 1.25rem;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 10px;
        border: 2px solid #bae6fd;
        min-width: 100px;
    }

    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e40af;
        line-height: 1;
    }

    .stats-label {
        font-size: 0.75rem;
        color: #1e40af;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 0.25rem;
    }

    .event-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        white-space: nowrap;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: #6b7280;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 992px) {
        .event-item {
            grid-template-columns: auto 1fr;
            gap: 1rem;
        }

        .event-stats {
            grid-column: 2;
            justify-self: start;
        }

        .event-actions {
            grid-column: 2;
            justify-self: start;
            width: 100%;
        }

        .btn-action {
            flex: 1;
        }
    }

    @media (max-width: 768px) {
        .events-container {
            padding: 1rem;
        }

        .page-header {
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

        .btn-header,
        .btn-create {
            flex: 1;
            justify-content: center;
        }

        .filter-tabs {
            padding: 0.75rem;
            gap: 0.5rem;
        }

        .filter-label {
            width: 100%;
            margin-bottom: 0.25rem;
        }

        .filter-tab {
            flex: 1;
            justify-content: center;
            padding: 0.625rem 1rem;
            font-size: 0.85rem;
        }

        .event-item {
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 1rem;
        }

        .event-indicator {
            display: none;
        }

        .event-meta {
            gap: 1rem;
        }

        .event-stats {
            grid-column: 1;
        }

        .event-actions {
            grid-column: 1;
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="events-container">
    <div class="page-header">
        <div class="header-top">
            <div class="header-content">
                <h2 class="page-title">
                    <span class="header-icon">📅</span>
                    Active Events
                </h2>
                <p class="page-subtitle">Manage and track upcoming church events</p>
            </div>
            <div class="header-buttons">
                <a href="{{ route('admin.event.history') }}" class="btn-header btn-history">
                    <i class="fas fa-history"></i>
                    <span>View History</span>
                </a>
                <a href="{{ route('admin.event.create') }}" class="btn-create">
                    <i class="fas fa-plus"></i>
                    <span>Create Event</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('admin.event.index') }}" 
           class="filter-tab {{ !$filterType || $filterType == 'all' ? 'active' : '' }}">
            <span class="tab-icon">🎯</span>
            <span>All Events</span>
        </a>
        <a href="{{ route('admin.event.index', ['type' => 'Cell Meeting']) }}" 
           class="filter-tab {{ $filterType == 'Cell Meeting' ? 'active' : '' }}">
            <span class="tab-icon">👥</span>
            <span>Cell Meetings</span>
        </a>
        <a href="{{ route('admin.event.index', ['type' => 'Sunday Service']) }}" 
           class="filter-tab {{ $filterType == 'Sunday Service' ? 'active' : '' }}">
            <span class="tab-icon">⛪</span>
            <span>Sunday Services</span>
        </a>
        <a href="{{ route('admin.event.index', ['type' => 'Prayer Meeting']) }}" 
           class="filter-tab {{ $filterType == 'Prayer Meeting' ? 'active' : '' }}">
            <span class="tab-icon">🙏</span>
            <span>Prayer Meetings</span>
        </a>
    </div>

    @if($events && count($events) > 0)
        <div class="events-list">
            @foreach($events as $event)
                @php
                    $typeClass = strtolower(str_replace(' ', '-', $event->type));
                    $badgeClass = 'badge-' . $typeClass;
                    $indicatorClass = 'indicator-' . $typeClass;
                    $registrationCount = $event->registrations()->where('status', '!=', 'cancelled')->count();
                @endphp
                <div class="event-item">
                    <div class="event-indicator {{ $indicatorClass }}"></div>
                    
                    <div class="event-main">
                        <div class="event-title-row">
                            <h3 class="event-title">{{ $event->title }}</h3>
                            <span class="event-type-badge {{ $badgeClass }}">
                                {{ $event->type }}
                            </span>
                        </div>
                        <div class="event-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar meta-icon"></i>
                                <span>{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock meta-icon"></i>
                                <span>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</span>
                            </div>
                            @if($event->location)
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt meta-icon"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="event-stats">
                        <div class="stats-number">{{ $registrationCount }}</div>
                        <div class="stats-label">Registered</div>
                    </div>

                    <div class="event-actions">
                        <a href="{{ route('admin.event.edit', $event->id) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this event?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h3 class="empty-title">
                @if($filterType && $filterType != 'all')
                    No {{ $filterType }}s Found
                @else
                    No Active Events
                @endif
            </h3>
            <p class="empty-text">
                @if($filterType && $filterType != 'all')
                    There are no active {{ strtolower($filterType) }}s at the moment.
                @else
                    Start by creating your first church event!
                @endif
            </p>
            @if($filterType && $filterType != 'all')
                <a href="{{ route('admin.event.index') }}" class="filter-tab">
                    <span class="tab-icon">🎯</span>
                    <span>View All Events</span>
                </a>
            @endif
        </div>
    @endif

    @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
        <div class="pagination-wrapper">
            {{ $events->appends(['type' => $filterType])->links() }}
        </div>
    @endif
</div>
@endsection