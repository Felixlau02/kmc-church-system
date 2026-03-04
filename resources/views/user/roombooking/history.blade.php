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
        display: flex;
        align-items: center;
        gap: 0.75rem;
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
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.4);
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

    .info-banner {
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid #8b5cf6;
    }

    .info-icon {
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .info-text {
        color: #5b21b6;
        font-size: 0.95rem;
        line-height: 1.5;
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
        border-left: 4px solid #718096;
        position: relative;
        opacity: 0.9;
    }

    .booking-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        opacity: 1;
    }

    .booking-card.approved {
        border-left-color: #10b981;
    }

    .booking-card.rejected {
        border-left-color: #ef4444;
    }

    .booking-card.completed {
        border-left-color: #3b82f6;
    }

    .archived-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #f3f4f6;
        color: #6b7280;
        padding: 0.375rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 1rem;
        padding-right: 100px;
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

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-completed {
        background: #dbeafe;
        color: #1e40af;
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
        color: #718096;
    }

    .detail-label {
        font-weight: 600;
        min-width: 90px;
        color: #2d3748;
    }

    .detail-value {
        flex: 1;
        color: #4a5568;
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

        .booking-header {
            padding-right: 0;
        }

        .archived-badge {
            position: static;
            margin-bottom: 0.5rem;
            width: fit-content;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .action-buttons {
            width: 100%;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="booking-container">
    <div class="header-section">
        <h1 class="page-title">
            <span>📚</span> Booking History
        </h1>
        <div class="action-buttons">
            <a href="{{ route('user.roombooking.index') }}" class="btn btn-secondary">
                <span>←</span> Back to Active Bookings
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
                    <div class="archived-badge">
                        <span>📦</span> Archived
                    </div>

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
                            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-icon">📦</span>
                            <span class="detail-label">Last Updated:</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->updated_at)->format('M d, Y g:i A') }}</span>
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

                    <div class="card-actions">
                        <form method="POST" action="{{ route('user.roombooking.restore', $booking->id) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Restore this booking from history?')">
                                <span>↩️</span> Restore
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h2 class="empty-title">No History Yet</h2>
            <p class="empty-text">You don't have any archived bookings. Completed or past bookings will appear here.</p>
        </div>
    @endif
</div>
@endsection