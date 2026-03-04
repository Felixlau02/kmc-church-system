@extends('layouts.admin')

@section('content')
<style>
    .member-detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
    }

    .back-button:hover {
        gap: 0.75rem;
    }

    .member-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .header-content {
        display: flex;
        align-items: start;
        gap: 2rem;
    }

    .member-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 3rem;
        flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }

    .member-info {
        flex: 1;
    }

    .member-name-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .member-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #6b7280;
    }

    .detail-value {
        color: #1f2937;
        text-align: right;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 12px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .attendance-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .attendance-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .attendance-item:last-child {
        border-bottom: none;
    }

    .event-info {
        flex: 1;
    }

    .event-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .event-date {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-present {
        background: #dcfce7;
        color: #166534;
    }

    .status-absent {
        background: #fee2e2;
        color: #991b1b;
    }

    .empty-message {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .member-name-title {
            font-size: 1.5rem;
        }

        .member-meta {
            justify-content: center;
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

<div class="member-detail-container">
    <a href="{{ route('admin.member.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to Members
    </a>

    <div class="member-header">
        <div class="header-content">
            <div class="member-avatar-large">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>

            <div class="member-info">
                <h1 class="member-name-title">{{ $member->name }}</h1>
                
                <div class="member-meta">
                    @if($member->gender)
                        <span class="meta-badge" style="background: {{ $member->gender == 'Male' ? '#dbeafe' : '#fce7f3' }}; color: {{ $member->gender == 'Male' ? '#1e40af' : '#9f1239' }};">
                            {{ $member->gender == 'Male' ? '♂' : '♀' }} {{ $member->gender }}
                        </span>
                    @endif

                    @if($member->baptism)
                        <span class="meta-badge" style="background: {{ $member->baptism == 'Yes' ? '#dcfce7' : '#fee2e2' }}; color: {{ $member->baptism == 'Yes' ? '#166534' : '#991b1b' }};">
                            💧 Baptism: {{ $member->baptism }}
                        </span>
                    @endif

                    @if($member->fellowship)
                        <span class="meta-badge" style="background: #f0fdf4; color: #15803d;">
                            🤝 {{ $member->fellowship }}
                        </span>
                    @endif
                </div>

                <div class="action-buttons">
                    <a href="{{ route('admin.member.edit', $member) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Member
                    </a>
                    <form action="{{ route('admin.member.destroy', $member) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this member?')"
                          style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete Member
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-card" style="margin-bottom: 1.5rem;">
        <h2 class="card-title">
            <i class="fas fa-chart-line"></i>
            Attendance Statistics
        </h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $member->events()->count() }}</div>
                <div class="stat-label">Total Events</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $attendedEvents ?? 0 }}</div>
                <div class="stat-label">Attended</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $attendanceRate ?? 0 }}%</div>
                <div class="stat-label">Attendance Rate</div>
            </div>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-card">
            <h2 class="card-title">
                <i class="fas fa-info-circle"></i>
                Contact Information
            </h2>
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $member->email }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Phone</span>
                <span class="detail-value">{{ $member->phone ?: 'N/A' }}</span>
            </div>
        </div>

        <div class="detail-card">
            <h2 class="card-title">
                <i class="fas fa-calendar"></i>
                Membership Information
            </h2>
            <div class="detail-item">
                <span class="detail-label">Member Since</span>
                <span class="detail-value">{{ $member->created_at->format('F d, Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Last Updated</span>
                <span class="detail-value">{{ $member->updated_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <h2 class="card-title">
            <i class="fas fa-history"></i>
            Recent Attendance History
        </h2>
        @if($recentAttendances && count($recentAttendances) > 0)
            <ul class="attendance-list">
                @foreach($recentAttendances as $attendance)
                    <li class="attendance-item">
                        <div class="event-info">
                            <div class="event-name">{{ $attendance->event->title ?? 'Unknown Event' }}</div>
                            <div class="event-date">
                                @if($attendance->event && $attendance->event->start_date)
                                    {{ $attendance->event->start_date->format('F d, Y') }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <span class="status-badge status-{{ strtolower($attendance->status) }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="empty-message">No attendance records found.</p>
        @endif
    </div>
</div>
@endsection