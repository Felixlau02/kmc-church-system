@extends('layouts.user')

@section('content')
<style>
    .booking-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin: 0;
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
        background: white;
        color: #667eea;
        box-shadow: 0 4px 6px rgba(255, 255, 255, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 255, 255, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
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

    .bookings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .booking-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
    }

    .booking-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .booking-card.pending {
        border-left-color: #f59e0b;
    }

    .booking-card.approved {
        border-left-color: #10b981;
    }

    .booking-card.rejected {
        border-left-color: #ef4444;
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 1rem;
    }

    .booking-room {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
    }

    .status-badge {
        padding: 0.5rem 1rem;
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

    .booking-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #4a5568;
        font-size: 0.95rem;
    }

    .detail-icon {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
        color: #667eea;
    }

    .detail-label {
        font-weight: 600;
        min-width: 80px;
        color: #2d3748;
    }

    .detail-value {
        flex: 1;
        color: #4a5568;
    }

    .time-range {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .time-separator {
        color: #667eea;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .purpose-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .purpose-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .purpose-text {
        color: #4a5568;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    .card-actions {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .booking-container {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .bookings-grid {
            grid-template-columns: 1fr;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .action-buttons {
            width: 100%;
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="booking-container">
    <div class="header-section">
        <h1 class="page-title">📅 My Room Bookings</h1>
        <div class="action-buttons">
            <a href="{{ route('user.roombooking.history') }}" class="btn btn-secondary">
                <span>📚</span> View History
            </a>
            <a href="{{ route('user.roombooking.create') }}" class="btn btn-primary">
                <span>➕</span> New Booking
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            <span style="font-size: 1.5rem;">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($bookings && count($bookings) > 0)
        <!-- Bookings Grid -->
        <div class="bookings-grid">
            @foreach($bookings as $booking)
                <div class="booking-card {{ strtolower($booking->status) }}">
                    <div class="booking-header">
                        <h3 class="booking-room">{{ $booking->room_id }}</h3>
                        <span class="status-badge status-{{ strtolower($booking->status) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-row">
                            <span class="detail-icon">👤</span>
                            <span class="detail-label">Member:</span>
                            <span class="detail-value">{{ $booking->member_name }}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-icon">📅</span>
                            <span class="detail-label">Date:</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-icon">🕐</span>
                            <span class="detail-label">Time:</span>
                            <div class="detail-value time-range">
                                <span>{{ \Carbon\Carbon::parse($booking->start_time ?? $booking->booking_time)->format('g:i A') }}</span>
                                <span class="time-separator">→</span>
                                <span>{{ \Carbon\Carbon::parse($booking->end_time ?? $booking->booking_time)->format('g:i A') }}</span>
                            </div>
                        </div>

                        @if($booking->boxing_description)
                            <div class="purpose-section">
                                <div class="purpose-label">
                                    <span>📝</span> Purpose
                                </div>
                                <div class="purpose-text">{{ $booking->boxing_description }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Archive Action (only for approved/rejected bookings) -->
                    @if(in_array($booking->status, ['approved', 'rejected']))
                        <div class="card-actions">
                            <form method="POST" action="{{ route('user.roombooking.archive', $booking->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Move this booking to history?')">
                                    <span>📦</span> Archive
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h2 class="empty-title">No Bookings Yet</h2>
            <p class="empty-text">You haven't made any room bookings. Start by creating your first booking!</p>
        </div>
    @endif
</div>
@endsection