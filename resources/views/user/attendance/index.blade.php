@extends('layouts.user')

@section('content')
<style>
    .attendance-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .attendance-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .attendance-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .hero-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
        font-weight: 400;
        line-height: 1.6;
    }

    .modern-alert {
        border: none;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        animation: slideDown 0.4s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .modern-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-5px);
    }

    .status-badge {
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        letter-spacing: 0.3px;
    }

    .status-admin-marked {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 2px solid #28a745;
    }

    .status-not-marked {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border: 2px solid #ffc107;
    }

    .status-cancelled {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 2px solid #dc3545;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .empty-icon i {
        font-size: 3.5rem;
        color: #cbd5e0;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: #718096;
        font-size: 1.05rem;
        margin-bottom: 2rem;
    }

    .browse-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.875rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .browse-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .table-modern {
        margin-bottom: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .table-modern th {
        border: none;
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #4a5568;
    }

    .table-modern td {
        padding: 1.5rem 1.5rem;
        border: none;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table-modern tbody tr {
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.03) 0%, rgba(255, 255, 255, 0) 100%);
        transform: translateX(5px);
    }

    @media (max-width: 768px) {
        .attendance-hero {
            padding: 1.5rem;
        }

        .hero-title {
            font-size: 1.5rem;
        }

        .hero-subtitle {
            font-size: 0.9rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Hero Header -->
    <div class="attendance-hero">
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="bi bi-calendar-check-fill"></i>
                My Attendance Records
            </h1>
            <p class="hero-subtitle">
                View your attendance records for registered events. Attendance is marked by the admin.
            </p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success modern-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger modern-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    <!-- All Registered Events Section -->
    <div class="modern-card">
        <div class="card-header bg-white border-bottom-0 py-4 px-4">
            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2" style="color: #2d3748;">
                <i class="bi bi-list-check" style="color: #667eea;"></i>
                Your Registered Events
            </h5>
        </div>
        
        <div class="card-body p-0">
            @if($registrations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th style="padding-left: 2rem;">Event</th>
                                <th>Date & Time</th>
                                <th>Attendance Status</th>
                                <th style="padding-right: 2rem;">Marked By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                @php
                                    $isMarkedByAdmin = in_array($registration->event->id, $adminMarkedAttendances);
                                @endphp
                                <tr>
                                    <td style="padding-left: 2rem;">
                                        <div class="fw-bold text-dark mb-1">{{ $registration->event->title }}</div>
                                        <small class="text-muted">{{ $registration->event->type }}</small>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-semibold d-block">
                                            {{ $registration->event->start_time->format('M d, Y') }}
                                        </span>
                                        <small class="text-muted">
                                            {{ $registration->event->start_time->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($isMarkedByAdmin)
                                            <span class="status-badge status-admin-marked">
                                                <i class="bi bi-check-circle-fill"></i>Present
                                            </span>
                                        @elseif($registration->status === 'cancelled')
                                            <span class="status-badge status-cancelled">
                                                <i class="bi bi-x-circle-fill"></i>Cancelled
                                            </span>
                                        @else
                                            <span class="status-badge status-not-marked">
                                                <i class="bi bi-clock-fill"></i>Not Marked Yet
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding-right: 2rem;">
                                        @if($isMarkedByAdmin)
                                            <span class="text-success fw-semibold">
                                                <i class="bi bi-person-check me-1"></i>Admin
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="bi bi-dash-circle me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3 class="empty-title">No Registered Events</h3>
                    <p class="empty-text">You haven't registered for any events yet.</p>
                    <a href="{{ route('user.event.index') }}" class="browse-btn">
                        <i class="bi bi-search me-2"></i>Browse Events
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection