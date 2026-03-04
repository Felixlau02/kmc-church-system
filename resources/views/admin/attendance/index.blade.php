@extends('layouts.admin')

@section('content')
<style>
    .attendance-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 20px;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
    }

    .error-alert {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        background: white;
        padding: 0.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .filter-tab {
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #6b7280;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .event-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .event-card.locked {
        opacity: 0.7;
    }

    .event-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1.5rem;
        color: white;
    }

    .event-header.locked {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    }

    .event-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .event-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        opacity: 0.95;
    }

    .event-body {
        padding: 1.5rem;
    }

    .event-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .meta-icon {
        width: 32px;
        height: 32px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
    }

    .attendance-status {
        padding: 1rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .status-label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .progress-bar-container {
        background: #e5e7eb;
        height: 8px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .status-text {
        font-size: 0.875rem;
        color: #374151;
        font-weight: 600;
    }

    .event-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-track {
        flex: 1;
        padding: 0.875rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-track:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-track.disabled {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
        cursor: not-allowed;
        pointer-events: none;
    }

    .locked-notice {
        padding: 1rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .locked-notice-text {
        font-size: 0.875rem;
        color: #92400e;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: #6b7280;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.75rem;
        }

        .events-grid {
            grid-template-columns: 1fr;
        }

        .filter-tabs {
            flex-wrap: wrap;
        }

        .event-meta {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .filter-tabs {
            width: 100%;
        }
    }
</style>

<div class="attendance-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1>📊 Attendance Management</h1>
        <p class="page-subtitle">Track and manage event attendance with ease</p>
    </div>

    <!-- Error Message -->
    @if(session('error'))
        <div class="error-alert">
            <span style="font-size: 1.5rem;">⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Section Header with Filters -->
    <div class="section-header">
        <h2 class="section-title">
            <span>🗓️</span>
            Events List
        </h2>
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterEvents('all', this)">All Events</button>
            <button class="filter-tab" onclick="filterEvents('upcoming', this)">Upcoming</button>
            <button class="filter-tab" onclick="filterEvents('past', this)">Past</button>
            <button class="filter-tab" onclick="filterEvents('tracked', this)">Tracked</button>
        </div>
    </div>

    <!-- Events Grid -->
    @if($events->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h3 class="empty-title">No Events Available</h3>
            <p class="empty-text">Create your first event to start tracking attendance</p>
        </div>
    @else
        <div class="events-grid" id="eventsGrid">
            @foreach($events as $event)
                @php
                    $totalAttendance = $event->attendances->count();
                    $attended = $event->attendances->where('status', 'present')->count();
                    $attendanceRate = $totalAttendance > 0 ? round(($attended / $totalAttendance) * 100) : 0;
                    $eventDate = \Carbon\Carbon::parse($event->start_time)->startOfDay();
                    $today = \Carbon\Carbon::today();
                    $isLocked = $eventDate->isFuture();
                    $isUpcoming = \Carbon\Carbon::parse($event->start_time)->isFuture();
                    $isPast = \Carbon\Carbon::parse($event->start_time)->isPast();
                    $isTracked = $totalAttendance > 0;
                @endphp
                
                <div class="event-card {{ $isLocked ? 'locked' : '' }}" 
                     data-filter="{{ $isUpcoming ? 'upcoming' : 'past' }} {{ $isTracked ? 'tracked' : '' }}">
                    <div class="event-header {{ $isLocked ? 'locked' : '' }}">
                        <h3 class="event-title">{{ $event->title }}</h3>
                        <div class="event-date">
                            <span>📅</span>
                            <span>{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="event-body">
                        <div class="event-meta">
                            <div class="meta-item">
                                <div class="meta-icon">🕐</div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #9ca3af;">Start</div>
                                    <div style="font-weight: 600; color: #1f2937;">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                    </div>
                                </div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-icon">🕐</div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #9ca3af;">End</div>
                                    <div style="font-weight: 600; color: #1f2937;">
                                        {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($isLocked)
                            <div class="locked-notice">
                                <span>🔒</span>
                                <div class="locked-notice-text">
                                    Attendance tracking will be available on {{ $eventDate->format('M d, Y') }}
                                </div>
                            </div>
                        @elseif($isTracked)
                            <div class="attendance-status">
                                <div class="status-label">Attendance Progress</div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $attendanceRate }}%"></div>
                                </div>
                                <div class="status-text">
                                    {{ $attended }} / {{ $totalAttendance }} attended ({{ $attendanceRate }}%)
                                </div>
                            </div>
                        @else
                            <div class="attendance-status" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                                <div class="status-label" style="color: #92400e;">Status</div>
                                <div class="status-text" style="color: #92400e;">
                                    ⚠️ Attendance not yet tracked
                                </div>
                            </div>
                        @endif

                        <div class="event-actions">
                            <a href="{{ route('admin.attendance.track', ['event' => $event->id]) }}" 
                               class="btn-track {{ $isLocked ? 'disabled' : '' }}">
                                <span>{{ $isLocked ? '🔒' : '✏️' }}</span>
                                {{ $isLocked ? 'Locked' : 'Track Attendance' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function filterEvents(filter, buttonElement) {
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    buttonElement.classList.add('active');

    // Filter events
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        const cardFilters = card.getAttribute('data-filter');
        
        if (filter === 'all') {
            card.style.display = 'block';
        } else if (cardFilters.includes(filter)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection