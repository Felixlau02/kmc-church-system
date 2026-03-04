@extends('layouts.admin')

@section('content')
<style>
    .admin-container {
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

    .page-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.75rem 0;
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

    .header-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        font-weight: 400;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background: white;
        color: #667eea;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        color: #667eea;
    }

    .btn-info {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        backdrop-filter: blur(10px);
    }

    .btn-info:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-container {
        overflow-x: auto;
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bookings-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bookings-table th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .bookings-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .bookings-table tbody tr:last-child {
        border-bottom: none;
    }

    .bookings-table tbody tr:hover {
        background-color: #f7fafc;
    }

    .bookings-table td {
        padding: 1.25rem 1.25rem;
        color: #2d3748;
        font-size: 0.925rem;
    }

    .time-range {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .time-separator {
        color: #667eea;
        font-weight: 600;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .event-badge {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 0.3rem 0.7rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-left: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .action-buttons-cell {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-approve {
        background: #d1fae5;
        color: #065f46;
    }

    .btn-approve:hover {
        background: #10b981;
        color: white;
    }

    .btn-reject {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-reject:hover {
        background: #ef4444;
        color: white;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-delete {
        background: #fecaca;
        color: #7f1d1d;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: white;
    }

    .btn-view-event {
        background: #ede9fe;
        color: #6b21a8;
    }

    .btn-view-event:hover {
        background: #8b5cf6;
        color: white;
    }

    .member-name {
        font-weight: 600;
        color: #1a202c;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #718096;
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .action-buttons {
            width: 100%;
        }

        .action-buttons .btn {
            flex: 1;
        }

        .action-buttons-cell {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="admin-container">
    <div class="page-header">
        <div class="page-header-content">
            <div class="header-left">
                <h2>
                    <span class="header-icon">
                        <i class="fas fa-door-open"></i>
                    </span>
                    Room Bookings Management
                </h2>
                <p class="header-subtitle">Track and manage room reservations with ease</p>
            </div>
            <div class="action-buttons">
                <a href="{{ route('admin.roombooking.history') }}" class="btn btn-info">
                    <i class="fas fa-history"></i> View History
                </a>
                <a href="{{ route('admin.roombooking.create-direct') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Booking
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="success-alert">
            <span style="font-size: 1.5rem;">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="table-card">
        <div class="table-container">
            @if($bookings && count($bookings) > 0)
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Member Name</th>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Time Range</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="member-name">
                                    {{ $booking->member_name }}
                                    @if($booking->event_id)
                                        <span class="event-badge">
                                            <i class="fas fa-calendar-check"></i> Event
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $booking->room_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                                <td>
                                    <div class="time-range">
                                        <span>{{ \Carbon\Carbon::parse($booking->start_time ?? $booking->booking_time)->format('g:i A') }}</span>
                                        <span class="time-separator">→</span>
                                        <span>{{ \Carbon\Carbon::parse($booking->end_time ?? $booking->booking_time)->format('g:i A') }}</span>
                                    </div>
                                </td>
                                <td>{{ Str::limit($booking->boxing_description, 50) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($booking->status) }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons-cell">
                                        @if($booking->event_id)
                                            <a href="{{ route('admin.event.edit', $booking->event_id) }}" class="btn-action btn-view-event">
                                                <span>📅</span> View Event
                                            </a>
                                        @else
                                            @if ($booking->status == 'pending')
                                                <form action="{{ route('admin.roombooking.approve', $booking->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-action btn-approve">
                                                        <span>✓</span> Approve
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.roombooking.reject', $booking->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-action btn-reject">
                                                        <span>✕</span> Reject
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('admin.roombooking.edit', $booking->id) }}" class="btn-action btn-edit">
                                                <span>✎</span> Edit
                                            </a>
                                        @endif
                                        
                                        <form action="{{ route('admin.roombooking.destroy', $booking->id) }}" method="POST" style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this booking?{{ $booking->event_id ? ' This will not delete the associated event.' : '' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <span>✕</span> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📅</div>
                    <h3 class="empty-title">No Active Bookings Found</h3>
                    <p class="empty-text">There are no active room bookings to display at the moment.</p>
                </div>
            @endif
        </div>
    </div>

    @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="margin-top: 1.5rem;">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection