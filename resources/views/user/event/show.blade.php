@extends('layouts.user')

@section('content')
<style>
    .event-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #000000;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1rem;
        transition: opacity 0.3s ease;
    }

    .back-btn:hover {
        opacity: 0.7;
    }

    .event-header-section {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        color: white;
        padding: 3rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(139, 92, 246, 0.3);
    }

    .event-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .event-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .meta-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .event-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-row {
        display: flex;
        align-items: start;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-icon {
        font-size: 1.5rem;
        color: #8b5cf6;
        width: 30px;
        text-align: center;
        flex-shrink: 0;
        margin-top: 0.25rem;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .detail-value {
        color: #6b7280;
        font-size: 1rem;
        line-height: 1.6;
    }

    .attendee-count {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #3b82f6;
    }

    .attendee-count.full {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left-color: #ef4444;
    }

    .attendee-count.limited {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left-color: #f59e0b;
    }

    .count-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1e40af;
    }

    .attendee-count.full .count-number {
        color: #991b1b;
    }

    .attendee-count.limited .count-number {
        color: #92400e;
    }

    .count-label {
        color: #1e40af;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    .attendee-count.full .count-label {
        color: #991b1b;
    }

    .attendee-count.limited .count-label {
        color: #92400e;
    }

    .availability-info {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .attendee-count.full .availability-info {
        color: #991b1b;
    }

    .attendee-count.limited .availability-info {
        color: #92400e;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .btn-register {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-register:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
    }

    .btn-register:disabled {
        background: #d1d5db;
        cursor: not-allowed;
        box-shadow: none;
    }

    .btn-cancel {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-cancel:hover {
        background: #ef4444;
        color: white;
    }

    .status-box {
        background: #d1fae5;
        border: 2px solid #6ee7b7;
        padding: 1rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #065f46;
        font-weight: 600;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    @media (max-width: 768px) {
        .event-container {
            padding: 1rem;
        }

        .event-header-section {
            padding: 1.5rem;
        }

        .event-title {
            font-size: 1.75rem;
        }

        .event-card {
            padding: 1.5rem;
        }

        .detail-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="event-container">
    <a href="{{ route('user.event.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Events
    </a>

    <div class="event-header-section">
        <h1 class="event-title">{{ $event->title }}</h1>
        <div class="event-meta">
            <span class="meta-badge">
                <i class="fas fa-tag"></i> {{ $event->type }}
            </span>
            <span class="meta-badge">
                <i class="fas fa-users"></i> {{ $registrationCount }}
                @if($event->max_participants)
                    / {{ $event->max_participants }}
                @endif
                Registered
            </span>
            @if($isFull)
                <span class="meta-badge" style="background: rgba(239, 68, 68, 0.3);">
                    <i class="fas fa-exclamation-circle"></i> Event Full
                </span>
            @endif
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($isFull && !$isRegistered)
        <div class="alert alert-warning">
            <i class="fas fa-users"></i>
            <span>This event has reached maximum capacity. Registration is closed.</span>
        </div>
    @endif

    @if($isRegistered && $userRegistration && $userRegistration->status !== 'cancelled')
        <div class="event-card">
            <div class="status-box">
                <i class="fas fa-check-circle"></i>
                <span>You are registered for this event</span>
            </div>
        </div>
    @endif

    <div class="event-card">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i> Event Details
        </h2>

        <div class="detail-row">
            <i class="fas fa-calendar-alt detail-icon"></i>
            <div class="detail-content">
                <span class="detail-label">Date</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($event->start_time)->format('l, F d, Y') }}
                </span>
            </div>
        </div>

        <div class="detail-row">
            <i class="fas fa-clock detail-icon"></i>
            <div class="detail-content">
                <span class="detail-label">Start Time</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                </span>
            </div>
        </div>

        <div class="detail-row">
            <i class="fas fa-clock detail-icon"></i>
            <div class="detail-content">
                <span class="detail-label">End Time</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                </span>
            </div>
        </div>

        @if($event->location)
            <div class="detail-row">
                <i class="fas fa-map-marker-alt detail-icon"></i>
                <div class="detail-content">
                    <span class="detail-label">Location</span>
                    <span class="detail-value">{{ $event->location }}</span>
                </div>
            </div>
        @endif

        @if($event->description)
            <div class="detail-row">
                <i class="fas fa-align-left detail-icon"></i>
                <div class="detail-content">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">{{ $event->description }}</span>
                </div>
            </div>
        @endif
    </div>

    <div class="event-card">
        <h2 class="section-title">
            <i class="fas fa-users"></i> Attendance
        </h2>
        <div class="attendee-count {{ $isFull ? 'full' : ($event->max_participants && $availableSpots <= 5 ? 'limited' : '') }}">
            <div class="count-number">
                {{ $registrationCount }}
                @if($event->max_participants)
                    / {{ $event->max_participants }}
                @endif
            </div>
            <div class="count-label">
                @if($event->max_participants)
                    People Registered (Max: {{ $event->max_participants }})
                @else
                    People Registered
                @endif
            </div>
            @if($event->max_participants)
                <div class="availability-info">
                    @if($isFull)
                        <i class="fas fa-times-circle"></i>
                        <span>Event is full</span>
                    @elseif($availableSpots <= 5)
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Only {{ $availableSpots }} spot{{ $availableSpots != 1 ? 's' : '' }} remaining!</span>
                    @else
                        <i class="fas fa-check-circle"></i>
                        <span>{{ $availableSpots }} spot{{ $availableSpots != 1 ? 's' : '' }} available</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="event-card">
        <div class="action-buttons">
            @if(!$isRegistered)
                <form action="{{ route('user.event.register', $event->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-register" {{ $isFull ? 'disabled' : '' }}>
                        @if($isFull)
                            <i class="fas fa-lock"></i> Registration Closed
                        @else
                            <i class="fas fa-check-circle"></i> Register for Event
                        @endif
                    </button>
                </form>
            @else
                <form action="{{ route('user.event.cancel', $event->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-cancel" onclick="return confirm('Are you sure you want to cancel your registration?')">
                        <i class="fas fa-times-circle"></i> Cancel Registration
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection