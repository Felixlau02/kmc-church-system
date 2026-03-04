@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<style>
    .profile-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        border-radius: 20px 20px 0 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .profile-header-content {
        position: relative;
        z-index: 1;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        font-size: 3rem;
        font-weight: 700;
        color: #667eea;
    }

    .profile-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .profile-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
    }

    .profile-card {
        background: white;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 3rem;
    }

    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.25rem;
        border-radius: 12px;
        text-align: center;
        border: 2px solid #e5e7eb;
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    .info-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #f3f4f6;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .info-item {
        padding: 1.25rem;
        background: #f9fafb;
        border-radius: 12px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-size: 1.125rem;
        color: #1e293b;
        font-weight: 600;
    }

    .info-value.empty {
        color: #94a3b8;
        font-style: italic;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 3px solid #f3f4f6;
    }

    .btn {
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        justify-content: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        flex: 1;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem;
        }

        .profile-card {
            padding: 1.5rem;
        }

        .profile-title {
            font-size: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <h1 class="profile-title">{{ auth()->user()->name }}</h1>
            <p class="profile-subtitle">{{ ucfirst(auth()->user()->role ?? 'Member') }}</p>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="profile-card">
        <!-- Success Message -->
        @if(session('success'))
            <div class="success-alert">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- User Stats -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-icon">👤</div>
                <div class="stat-value">{{ ucfirst(auth()->user()->role ?? 'Member') }}</div>
                <div class="stat-label">Your Role</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon">📅</div>
                <div class="stat-value">{{ auth()->user()->created_at->format('M Y') }}</div>
                <div class="stat-label">Member Since</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon">🔄</div>
                <div class="stat-value">{{ auth()->user()->updated_at->diffForHumans() }}</div>
                <div class="stat-label">Last Updated</div>
            </div>
        </div>

        @if(auth()->user()->role !== 'admin')
        <!-- Personal Information (Only for non-admin users) -->
        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                Personal Information
            </h3>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-signature"></i>
                        Full Name
                    </div>
                    <div class="info-value">{{ auth()->user()->name }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </div>
                    <div class="info-value">{{ auth()->user()->email }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-venus-mars"></i>
                        Gender
                    </div>
                    <div class="info-value">{{ $member->gender ?? 'Not specified' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-phone"></i>
                        Contact Number
                    </div>
                    <div class="info-value {{ empty($member->phone) ? 'empty' : '' }}">
                        {{ $member->phone ?? 'Not provided' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-users"></i>
                        Fellowship
                    </div>
                    <div class="info-value {{ empty($member->fellowship) ? 'empty' : '' }}">
                        {{ $member->fellowship ?? 'Not assigned' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-water"></i>
                        Baptism Status
                    </div>
                    <div class="info-value">
                        @if($member->baptism === 'Yes')
                            <span style="color: #10b981;">✓ Baptized</span>
                        @else
                            <span style="color: #f59e0b;">✗ Not Baptized</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.profile.edit') : route('user.profile.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection