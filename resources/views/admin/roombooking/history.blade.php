@extends('layouts.admin')

@section('content')
<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .header-section {
        background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 50%, #c084fc 100%);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        box-shadow: 0 8px 24px rgba(124, 58, 237, 0.25);
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

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.35);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
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

    .info-banner {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid #3b82f6;
    }

    .info-banner-icon {
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .info-banner-text {
        color: #1e40af;
        font-size: 0.925rem;
        line-height: 1.5;
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
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
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
        opacity: 0.85;
    }

    .bookings-table tbody tr:last-child {
        border-bottom: none;
    }

    .bookings-table tbody tr:hover {
        background-color: #f7fafc;
        opacity: 1;
    }

    .bookings-table td {
        padding: 1.25rem 1.25rem;
        color: #2d3748;
        font-size: 0.925rem;
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

    .archived-badge {
        background: #e2e8f0;
        color: #475569;
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-left: 0.5rem;
        display: inline-block;
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

    .btn-view {
        background: #e0e7ff;
        color: #4338ca;
    }

    .btn-view:hover {
        background: #6366f1;
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
        opacity: 0.5;
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

        .header-section {
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
    <div class="header-section">
        <div class="header-content">
            <h2 class="page-title">
                <span class="icon-box">📚</span>
                Booking History
            </h2>
            <p class="page-subtitle">View and manage archived room reservations</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.roombooking.index') }}" class="btn btn-secondary">
                <span>←</span> Back to Active Bookings
            </a>
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
                            <th>Time</th>
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
                                    <span class="archived-badge">Archived</span>
                                </td>
                                <td>{{ $booking->room_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</td>
                                <td>{{ Str::limit($booking->boxing_description, 50) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($booking->status) }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons-cell">
                                        <a href="{{ route('admin.roombooking.edit', $booking->id) }}" class="btn-action btn-view">
                                            <span>👁️</span> View
                                        </a>
                                        
                                        <form action="{{ route('admin.roombooking.destroy', $booking->id) }}" method="POST" style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to permanently delete this booking from history?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <span>🗑️</span> Delete
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
                    <div class="empty-icon">📚</div>
                    <h3 class="empty-title">No Booking History</h3>
                    <p class="empty-text">There are no archived bookings to display. Bookings will appear here automatically after their booking date has passed.</p>
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