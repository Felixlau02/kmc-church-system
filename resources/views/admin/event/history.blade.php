@extends('layouts.admin')

@section('content')
<style>
    .events-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 50%, #c084fc 100%);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(124, 58, 237, 0.25);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
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
        gap: 0.75rem;
    }

    .page-title .icon-box {
        font-size: 2rem;
        background: white;
        padding: 0.5rem 0.6rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
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
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        border: 2px solid transparent;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.9);
        border-color: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        color: white;
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
        border-color: #7c3aed;
        color: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
        border-color: #7c3aed;
        color: white;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }

    .filter-tab .tab-icon {
        font-size: 1.1rem;
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

    .history-table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
    }

    .history-table thead {
        background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
    }

    .history-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: white;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .history-table td {
        padding: 1rem 1.5rem;
        color: #6b7280;
        border-bottom: 1px solid #f3f4f6;
    }

    .history-table tbody tr {
        transition: all 0.2s ease;
        opacity: 0.85;
    }

    .history-table tbody tr:hover {
        background: #f9fafb;
        opacity: 1;
    }

    .event-title-cell {
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .past-badge {
        background: #e5e7eb;
        color: #6b7280;
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .event-type-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
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

    .date-text {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .attendance-count {
        font-weight: 600;
        color: #4b5563;
        font-size: 1rem;
    }

    .action-buttons {
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
    }

    .btn-view {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-view:hover {
        background: #6b7280;
        color: white;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #ef4444;
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
        font-size: 1.05rem;
    }

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .events-container {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem 1rem;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-title .icon-box {
            font-size: 1.5rem;
            padding: 0.4rem 0.5rem;
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

        .btn-header {
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

        .history-table th,
        .history-table td {
            padding: 0.75rem;
            font-size: 0.875rem;
        }

        .action-buttons {
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
                    <span class="icon-box">📚</span>
                    Event History
                </h2>
                <p class="page-subtitle">View and manage past church events</p>
            </div>
            <div class="header-buttons">
                <a href="{{ route('admin.event.index') }}" class="btn-header btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Active Events</span>
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
        <a href="{{ route('admin.event.history') }}" 
           class="filter-tab {{ !$filterType || $filterType == 'all' ? 'active' : '' }}">
            <span class="tab-icon">🎯</span>
            <span>All Events</span>
        </a>
        <a href="{{ route('admin.event.history', ['type' => 'Cell Meeting']) }}" 
           class="filter-tab {{ $filterType == 'Cell Meeting' ? 'active' : '' }}">
            <span class="tab-icon">👥</span>
            <span>Cell Meetings</span>
        </a>
        <a href="{{ route('admin.event.history', ['type' => 'Sunday Service']) }}" 
           class="filter-tab {{ $filterType == 'Sunday Service' ? 'active' : '' }}">
            <span class="tab-icon">⛪</span>
            <span>Sunday Services</span>
        </a>
        <a href="{{ route('admin.event.history', ['type' => 'Prayer Meeting']) }}" 
           class="filter-tab {{ $filterType == 'Prayer Meeting' ? 'active' : '' }}">
            <span class="tab-icon">🙏</span>
            <span>Prayer Meetings</span>
        </a>
    </div>

    @if($events && count($events) > 0)
        <div class="history-table-card">
            <div class="table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Attendance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            @php
                                $typeClass = strtolower(str_replace(' ', '-', $event->type));
                                $badgeClass = 'badge-' . $typeClass;
                                $registrationCount = $event->registrations()->where('status', '!=', 'cancelled')->count();
                            @endphp
                            <tr>
                                <td>
                                    <div class="event-title-cell">
                                        <span>{{ $event->title }}</span>
                                        <span class="past-badge">Past</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="event-type-badge {{ $badgeClass }}">
                                        {{ $event->type }}
                                    </span>
                                </td>
                                <td>
                                    <div class="date-text">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}
                                        <br>
                                        <small style="color: #9ca3af;">{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="attendance-count">{{ $registrationCount }}</span>
                                    <small style="color: #9ca3af;"> people</small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.event.edit', $event->id) }}" class="btn-action btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <form action="{{ route('admin.event.destroy', $event->id) }}?from_history=1" method="POST" 
                                              onsubmit="return confirm('Delete this past event?')"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h3 class="empty-title">
                @if($filterType && $filterType != 'all')
                    No Past {{ $filterType }}s
                @else
                    No Past Events
                @endif
            </h3>
            <p class="empty-text">
                @if($filterType && $filterType != 'all')
                    There are no past {{ strtolower($filterType) }}s in the history.
                @else
                    Past events will appear here once they have ended.
                @endif
            </p>
            @if($filterType && $filterType != 'all')
                <a href="{{ route('admin.event.history') }}" class="filter-tab">
                    <span class="tab-icon">🎯</span>
                    <span>View All Past Events</span>
                </a>
            @else
                <a href="{{ route('admin.event.index') }}" class="filter-tab">
                    <i class="fas fa-calendar-alt"></i>
                    <span>View Active Events</span>
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