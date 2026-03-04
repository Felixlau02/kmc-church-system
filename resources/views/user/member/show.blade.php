@extends('layouts.user')

@section('content')
<style>
    .detail-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .back-btn:hover {
        transform: translateX(-4px);
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 2rem;
        text-align: center;
        color: white;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        color: #667eea;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .profile-body {
        padding: 2.5rem 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .info-item {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 1.5rem;
        background: #f7fafc;
        border-radius: 12px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: #edf2f7;
        transform: translateX(5px);
    }

    .info-icon {
        font-size: 1.5rem;
        color: #667eea;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.875rem;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 600;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .badge-male {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-female {
        background: #fce7f3;
        color: #9f1239;
    }

    .badge-fellowship {
        background: #f0fdf4;
        color: #15803d;
    }

    .badge-yes {
        background: #dcfce7;
        color: #166534;
    }

    .badge-no {
        background: #fee2e2;
        color: #991b1b;
    }

    .info-value.empty {
        color: #cbd5e0;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .detail-container {
            padding: 1rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-header {
            padding: 2rem 1.5rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }

        .profile-name {
            font-size: 1.5rem;
        }
    }
</style>

<div class="detail-container">
    <a href="{{ route('user.member.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Directory
    </a>

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <div class="profile-name">{{ $member->name }}</div>
        </div>

        <div class="profile-body">
            <div class="info-grid">
                <!-- Gender -->
                <div class="info-item">
                    <span class="info-icon">⚧</span>
                    <div class="info-content">
                        <div class="info-label">Gender</div>
                        <div class="info-value">
                            @if($member->gender)
                                <span class="badge {{ $member->gender == 'Male' ? 'badge-male' : 'badge-female' }}">
                                    {{ $member->gender == 'Male' ? '♂' : '♀' }}
                                    {{ $member->gender }}
                                </span>
                            @else
                                <span class="empty">Not specified</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Fellowship -->
                <div class="info-item">
                    <span class="info-icon">🤝</span>
                    <div class="info-content">
                        <div class="info-label">Fellowship</div>
                        <div class="info-value">
                            @if($member->fellowship)
                                <span class="badge badge-fellowship">
                                    {{ $member->fellowship }}
                                </span>
                            @else
                                <span class="empty">Not assigned</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Baptism -->
                <div class="info-item">
                    <span class="info-icon">💧</span>
                    <div class="info-content">
                        <div class="info-label">Baptism Status</div>
                        <div class="info-value">
                            @if($member->baptism)
                                <span class="badge {{ $member->baptism == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                                    {{ $member->baptism == 'Yes' ? '✓' : '✗' }}
                                    {{ $member->baptism }}
                                </span>
                            @else
                                <span class="empty">Not specified</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Member Since -->
                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <div class="info-content">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">
                            {{ $member->created_at->format('F d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection