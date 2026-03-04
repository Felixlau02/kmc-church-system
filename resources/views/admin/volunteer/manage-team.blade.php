@extends('layouts.admin')

@section('content')
<style>
    .manage-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.2s;
    }

    .back-button:hover {
        color: #667eea;
        transform: translateX(-3px);
    }

    .team-header-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .team-icon-large {
        font-size: 4rem;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-info h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .header-meta {
        display: flex;
        gap: 2rem;
        font-size: 0.95rem;
        opacity: 0.95;
    }

    .alert-modern {
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        border: none;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .add-member-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #374151;
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .add-member-grid {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr;
        gap: 1rem;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    .form-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-select option:disabled {
        color: #94a3b8;
        font-style: italic;
    }

    .btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
    }

    .btn-approve {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-approve:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
    }

    .btn-reject {
        background: #f59e0b;
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-reject:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    .members-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .members-list {
        display: grid;
        gap: 1rem;
    }

    .member-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: #f9fafb;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.2s;
    }

    .member-card.pending {
        background: #fffbeb;
        border-color: #fbbf24;
    }

    .member-card:hover {
        border-color: #667eea;
        transform: translateX(5px);
    }

    .member-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .member-avatar.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .member-info {
        flex: 1;
        min-width: 0;
    }

    .member-name {
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
        font-size: 1.1rem;
    }

    .member-role {
        color: #667eea;
        font-size: 0.95rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .member-email {
        color: #6b7280;
        font-size: 0.875rem;
        margin: 0 0 0.25rem 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background: #d1fae5;
        color: #065f46;
    }

    .member-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .empty-members {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ca3af;
    }

    .empty-members-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-members-text {
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .add-member-grid {
            grid-template-columns: 1fr;
        }

        .header-gradient {
            flex-direction: column;
            text-align: center;
        }

        .header-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .member-card {
            flex-direction: column;
            text-align: center;
        }

        .member-actions {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="manage-container">
    <!-- Back Button -->
    <a href="{{ route('admin.volunteer.index') }}" class="back-button">
        <span>←</span>
        Back to Teams
    </a>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert-modern alert-success">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert-modern alert-danger">
            <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Team Header -->
    <div class="team-header-card">
        <div class="header-gradient">
            <div class="team-icon-large">
                @switch($team->team_type)
                    @case('worship') 🎵 @break
                    @case('usher') 🚪 @break
                    @case('technical') 📊 @break
                    @default ⭐ @break
                @endswitch
            </div>
            <div class="header-info">
                <h1>{{ $team->team_name }}</h1>
                <div class="header-meta">
                    <span>📊 {{ $teamTypes[$team->team_type] ?? 'Team' }}</span>
                    <span>👥 {{ $team->members->where('status', 'approved')->count() }} approved members</span>
                    @if($team->members->where('status', 'pending')->count() > 0)
                        <span>⏳ {{ $team->members->where('status', 'pending')->count() }} pending requests</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Section -->
    @php
        $pendingRequests = $team->members->where('status', 'pending');
    @endphp
    
    @if($pendingRequests->count() > 0)
    <div class="members-card">
        <h2 class="section-title">
            <span>⏳</span>
            Pending Requests ({{ $pendingRequests->count() }})
        </h2>
        
        <div class="members-list">
            @foreach($pendingRequests as $registration)
                <div class="member-card pending">
                    <div class="member-avatar pending">
                        {{ strtoupper(substr($registration->member->name, 0, 1)) }}
                    </div>
                    <div class="member-info">
                        <p class="member-name">{{ $registration->member->name }}</p>
                        <p class="member-role">
                            <i class="fas fa-user-tag"></i>
                            {{ $registration->role }}
                        </p>
                        <p class="member-email">
                            <i class="fas fa-envelope"></i>
                            {{ $registration->member->email }}
                        </p>
                        @if($registration->notes)
                            <p class="member-email">
                                <i class="fas fa-comment"></i>
                                {{ $registration->notes }}
                            </p>
                        @endif
                        <span class="status-badge pending">
                            <i class="fas fa-clock"></i>
                            Pending Approval
                        </span>
                    </div>
                    <div class="member-actions">
                        <form action="{{ route('admin.volunteer.approve-request', $registration->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-approve" 
                                    onclick="return confirm('Approve {{ $registration->member->name }} as {{ $registration->role }}?');">
                                <i class="fas fa-check"></i>
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.volunteer.reject-request', $registration->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-reject"
                                    onclick="return confirm('Reject {{ $registration->member->name }}\'s request?');">
                                <i class="fas fa-times"></i>
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Add Member Section -->
    <div class="add-member-card">
        <h2 class="section-title">
            <span>➕</span>
            Add New Member
        </h2>
        <form action="{{ route('admin.volunteer.add-member', $team->id) }}" method="POST" class="add-member-grid">
            @csrf
            <div class="form-group">
                <label class="form-label">Select Member *</label>
                <select name="member_id" class="form-select" required>
                    <option value="">Choose a member...</option>
                    @foreach($members as $member)
                        @php
                            $alreadyInTeam = $team->members->where('member_id', $member->id)->first();
                        @endphp
                        <option value="{{ $member->id }}" {{ $alreadyInTeam ? 'disabled' : '' }}>
                            {{ $member->name }} - {{ $member->email }}
                            {{ $alreadyInTeam ? '(Already in team)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role" class="form-select" id="roleSelect" required>
                    <option value="">Select role...</option>
                    @php
                        $takenRoles = $team->members->whereIn('status', ['pending', 'approved'])->pluck('role')->toArray();
                    @endphp
                    @foreach(\App\Models\VolunteerTeam::getRolesByType($team->team_type) as $role)
                        <option value="{{ $role }}" {{ in_array($role, $takenRoles) ? 'disabled' : '' }}>
                            {{ $role }}
                            {{ in_array($role, $takenRoles) ? '(Taken)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-user-plus"></i>
                Add Member
            </button>
        </form>
    </div>

    <!-- Approved Members List -->
    <div class="members-card">
        <h2 class="section-title">
            <span>👥</span>
            Approved Members ({{ $team->members->where('status', 'approved')->count() }})
        </h2>
        
        @php
            $approvedMembers = $team->members->where('status', 'approved');
        @endphp
        
        @if($approvedMembers->isEmpty())
            <div class="empty-members">
                <div class="empty-members-icon">👥</div>
                <p class="empty-members-text">No approved members yet.<br>Approve pending requests or use the form above to add members directly!</p>
            </div>
        @else
            <div class="members-list">
                @foreach($approvedMembers as $registration)
                    <div class="member-card">
                        <div class="member-avatar">
                            {{ strtoupper(substr($registration->member->name, 0, 1)) }}
                        </div>
                        <div class="member-info">
                            <p class="member-name">{{ $registration->member->name }}</p>
                            <p class="member-role">
                                <i class="fas fa-user-tag"></i>
                                {{ $registration->role }}
                            </p>
                            <p class="member-email">
                                <i class="fas fa-envelope"></i>
                                {{ $registration->member->email }}
                            </p>
                            @if($registration->notes)
                                <p class="member-email">
                                    <i class="fas fa-comment"></i>
                                    {{ $registration->notes }}
                                </p>
                            @endif
                            <span class="status-badge approved">
                                <i class="fas fa-check-circle"></i>
                                Approved
                            </span>
                        </div>
                        <div class="member-actions">
                            <form action="{{ route('admin.volunteer.remove-member', $registration->id) }}" method="POST"
                                  onsubmit="return confirm('Remove {{ $registration->member->name }} from this team?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i>
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-modern');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });
</script>
@endsection