@extends('layouts.user')

@section('content')
<style>
    .events-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin: 0;
        font-weight: 400;
    }

    .filter-tabs {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        background: white;
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
        flex-wrap: wrap;
    }

    .tab-item {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
        color: #6b7280;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-item:hover {
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
    }

    .tab-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }

    .tab-icon {
        font-size: 1rem;
    }

    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 1.5rem;
    }

    .event-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 5px solid;
    }

    .event-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .event-card.cell-meeting {
        border-left-color: #8b5cf6;
    }

    .event-card.sunday-service {
        border-left-color: #3b82f6;
    }

    .event-card.prayer-meeting {
        border-left-color: #f59e0b;
    }

    .event-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 1px solid #e5e7eb;
    }

    .event-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .event-type-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
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

    .event-body {
        padding: 1.5rem;
    }

    .event-details {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
        margin-bottom: 1.25rem;
    }

    .event-detail-item {
        display: flex;
        align-items: start;
        gap: 0.75rem;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .detail-icon {
        font-size: 1.1rem;
        color: #8b5cf6;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .detail-label {
        font-weight: 600;
        color: #374151;
        display: block;
    }

    .event-description {
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .event-footer {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .registration-status {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }

    .status-registered {
        background: #d1fae5;
        color: #065f46;
    }

    .btn-register {
        padding: 0.625rem 1.5rem;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(139, 92, 246, 0.3);
    }

    .btn-view {
        padding: 0.625rem 1.5rem;
        background: #dbeafe;
        color: #1e40af;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view:hover {
        background: #3b82f6;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: #6b7280;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .events-container {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .filter-tabs {
            gap: 0.5rem;
            padding: 0.75rem;
        }

        .tab-item {
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
        }

        .events-grid {
            grid-template-columns: 1fr;
        }

        .event-footer {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-register, .btn-view {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="events-container">
    <!-- Page Header with Title and Subtitle -->
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-calendar"></i> Events
        </h2>
        <p class="page-subtitle">
            Browse and register for upcoming church events and activities
        </p>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('user.event.index') }}" 
           class="tab-item {{ request('type') == null ? 'active' : '' }}">
            <span class="tab-icon">🎯</span>
            All Events
        </a>
        <a href="{{ route('user.event.index', ['type' => 'cell-meeting']) }}" 
           class="tab-item {{ request('type') == 'cell-meeting' ? 'active' : '' }}">
            <span class="tab-icon">👥</span>
            Cell Meetings
        </a>
        <a href="{{ route('user.event.index', ['type' => 'sunday-service']) }}" 
           class="tab-item {{ request('type') == 'sunday-service' ? 'active' : '' }}">
            <span class="tab-icon">⛪</span>
            Sunday Services
        </a>
        <a href="{{ route('user.event.index', ['type' => 'prayer-meeting']) }}" 
           class="tab-item {{ request('type') == 'prayer-meeting' ? 'active' : '' }}">
            <span class="tab-icon">🙏</span>
            Prayer Meetings
        </a>
    </div>

    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($events && count($events) > 0)
        <div class="events-grid">
            @foreach($events as $event)
                @php
                    $typeClass = strtolower(str_replace(' ', '-', $event->type));
                    $badgeClass = 'badge-' . $typeClass;
                    $isRegistered = in_array($event->id, $userRegistrations);
                @endphp
                <div class="event-card {{ $typeClass }}">
                    <div class="event-header">
                        <h3 class="event-title">{{ $event->title }}</h3>
                        <span class="event-type-badge {{ $badgeClass }}">
                            {{ $event->type }}
                        </span>
                    </div>

                    <div class="event-body">
                        <div class="event-details">
                            <div class="event-detail-item">
                                <i class="fas fa-clock detail-icon"></i>
                                <div>
                                    <span class="detail-label">Start Time</span>
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('D, M d, Y - g:i A') }}
                                </div>
                            </div>

                            <div class="event-detail-item">
                                <i class="fas fa-clock detail-icon"></i>
                                <div>
                                    <span class="detail-label">End Time</span>
                                    {{ \Carbon\Carbon::parse($event->end_time)->format('D, M d, Y - g:i A') }}
                                </div>
                            </div>

                            @if($event->location)
                                <div class="event-detail-item">
                                    <i class="fas fa-map-marker-alt detail-icon"></i>
                                    <div>
                                        <span class="detail-label">Location</span>
                                        {{ $event->location }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($event->description)
                            <div class="event-description">
                                {{ Str::limit($event->description, 120) }}
                            </div>
                        @endif
                    </div>

                    <div class="event-footer">
                        @if($isRegistered)
                            <span class="registration-status status-registered">
                                <i class="fas fa-check"></i> Registered
                            </span>
                        @endif
                        <a href="{{ route('user.event.show', $event->id) }}" class="btn-view">
                            <i class="fas fa-arrow-right"></i> View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $events->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">🔭</div>
            <h3 class="empty-title">No Events Available</h3>
            <p class="empty-text">
                @if(request('type'))
                    No {{ str_replace('-', ' ', request('type')) }} events found. Try another filter.
                @else
                    Check back later for upcoming church events.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection