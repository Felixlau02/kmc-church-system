@extends('layouts.user')

@section('content')
<div class="sunday-service-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-main">
                <h1 class="page-title">
                    <span class="header-icon">🙏</span>
                    Sunday Service Teams
                </h1>
            </div>
            <p class="page-subtitle">Join a team and serve in our Sunday Service</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
    @endif

    @if(!$userMember)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <span>You need to <a href="{{ route('user.member.create') }}" style="text-decoration: underline; font-weight: bold;">create a member profile</a> before you can join a team.</span>
    </div>
    @endif

    <!-- Teams Grid -->
    <div class="teams-container">
        @forelse($teams as $team)
        <div class="team-card">
            <!-- Team Header -->
            <div class="team-header">
                <div class="team-icon">
                    @switch($team->team_type)
                        @case('worship')
                            🎵
                            @break
                        @case('usher')
                            👋
                            @break
                        @case('technical')
                            🎛️
                            @break
                        @default
                            ⭐
                    @endswitch
                </div>
                <div class="team-info">
                    <h3 class="team-name">{{ $team->team_name }}</h3>
                    <span class="team-type-badge">{{ $team->team_type_name }}</span>
                </div>
                <div class="team-count">
                    <i class="fas fa-users"></i>
                    <span>{{ $team->members->where('status', 'approved')->count() }} members</span>
                </div>
            </div>

            <!-- Team Members List -->
            <div class="team-members">
                <h4 class="members-title">
                    <i class="fas fa-user-friends"></i>
                    Current Members
                </h4>
                @php
                    $approvedMembers = $team->members->where('status', 'approved');
                @endphp
                @if($approvedMembers->isEmpty())
                    <div class="no-members">
                        <i class="fas fa-user-plus"></i>
                        <p>Be the first to join this team!</p>
                    </div>
                @else
                    <div class="members-list">
                        @foreach($approvedMembers as $registration)
                        <div class="member-item">
                            <div class="member-avatar">
                                {{ substr($registration->member->name, 0, 1) }}
                            </div>
                            <div class="member-details">
                                <div class="member-name">{{ $registration->member->name }}</div>
                                <div class="member-role">{{ $registration->role }}</div>
                            </div>
                            @if($registration->member_id === $userMember?->id)
                                <span class="you-badge">You</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Team Actions -->
            <div class="team-actions">
                @php
                    $userRegistration = $userRegistrations->get($team->id);
                    $isInTeam = !is_null($userRegistration);
                @endphp

                @if($isInTeam)
                    <!-- Already in team or pending -->
                    @if($userRegistration->status === 'pending')
                        <div class="pending-status">
                            <i class="fas fa-clock"></i>
                            <span>Your request as <strong>{{ $userRegistration->role }}</strong> is pending approval</span>
                        </div>
                    @else
                        <div class="joined-status">
                            <i class="fas fa-check-circle"></i>
                            <span>You're in this team as <strong>{{ $userRegistration->role }}</strong></span>
                        </div>
                    @endif
                    <form action="{{ route('user.volunteer.cancel-team-request', $team->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this team?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-leave" {{ !$userMember ? 'disabled' : '' }}>
                            <i class="fas fa-sign-out-alt"></i>
                            {{ $userRegistration->status === 'pending' ? 'Cancel Request' : 'Leave Team' }}
                        </button>
                    </form>
                @else
                    <!-- Join team button -->
                    <button type="button" class="btn btn-join" onclick="openJoinModal({{ $team->id }}, '{{ $team->team_name }}', '{{ $team->team_type }}')" {{ !$userMember ? 'disabled' : '' }}>
                        <i class="fas fa-hand-paper"></i>
                        Request to Join
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h3>No Teams Available</h3>
            <p>Teams will be added soon. Check back later!</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Join Team Modal -->
<div id="joinModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-user-plus"></i>
                Request to Join Team
            </h2>
            <button type="button" class="modal-close" onclick="closeJoinModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="joinTeamForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-users"></i>
                        Team
                    </label>
                    <input type="text" id="modalTeamName" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label required">
                        <i class="fas fa-user-tag"></i>
                        Select Your Role
                    </label>
                    <select name="role" id="modalRole" class="form-control" required>
                        <option value="">Choose a role...</option>
                    </select>
                    <small class="form-hint">Roles marked as taken are not available</small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-comment"></i>
                        Additional Notes (Optional)
                    </label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Tell us about your experience, availability, or anything else..."></textarea>
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Your request will be reviewed by the admin.</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeJoinModal()">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
    }

    .sunday-service-page {
        max-width: 100%;
        margin: 0;
        padding: 0;
    }

    /* Page Header - Updated to match Events/Sermon */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-main {
        margin-bottom: 0.5rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-icon {
        font-size: 2rem;
    }

    .page-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Alerts */
    .alert {
        padding: 1.25rem 1.75rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideDown 0.4s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .alert-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .alert-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
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

    /* Teams Container */
    .teams-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
    }

    /* Team Card */
    .team-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    /* Team Header */
    .team-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 3px solid #e2e8f0;
    }

    .team-icon {
        font-size: 3rem;
    }

    .team-info {
        flex: 1;
    }

    .team-name {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 0.5rem 0;
    }

    .team-type-badge {
        display: inline-block;
        padding: 0.35rem 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .team-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Team Members */
    .team-members {
        padding: 2rem;
        min-height: 200px;
    }

    .members-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #475569;
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .no-members {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }

    .no-members i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .members-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .member-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .member-item:hover {
        background: #f1f5f9;
        transform: translateX(5px);
    }

    .member-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .member-details {
        flex: 1;
    }

    .member-name {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .member-role {
        font-size: 0.9rem;
        color: #64748b;
    }

    .you-badge {
        padding: 0.35rem 0.9rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Team Actions */
    .team-actions {
        padding: 1.5rem 2rem;
        border-top: 2px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .joined-status {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-radius: 12px;
        color: #1e40af;
        font-weight: 600;
    }

    .joined-status i {
        font-size: 1.5rem;
    }

    .pending-status {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        color: #92400e;
        font-weight: 600;
    }

    .pending-status i {
        font-size: 1.5rem;
    }

    .btn {
        width: 100%;
        padding: 1rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-size: 1rem;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-join {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-join:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-leave {
        background: white;
        color: #ef4444;
        border: 2px solid #ef4444;
    }

    .btn-leave:hover:not(:disabled) {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow: auto;
        animation: modalSlideUp 0.3s ease;
    }

    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 2rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-close {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .form-label.required::after {
        content: '*';
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .form-hint {
        display: block;
        margin-top: 0.5rem;
        color: #64748b;
        font-size: 0.85rem;
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #64748b;
    }

    .form-control option:disabled {
        color: #94a3b8;
        font-style: italic;
    }

    .info-box {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        background: #f0f9ff;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        color: #1e40af;
        font-size: 0.9rem;
        margin-top: 1.5rem;
    }

    .info-box i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 2px solid #f1f5f9;
        display: flex;
        gap: 1rem;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        flex: 1;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        flex: 2;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 6rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: #64748b;
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .header-icon {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .teams-container {
            grid-template-columns: 1fr;
        }

        .modal-footer {
            flex-direction: column;
        }

        .btn-secondary,
        .btn-primary {
            flex: 1;
        }
    }
</style>

<script>
    // Pass taken roles to JavaScript
    const takenRolesByTeam = @json($takenRoles);

    // Role options by team type
    const rolesByType = {
        worship: [
            'Worship Leader',
            'Guitarist',
            'Pianist',
            'Bassist',
            'Drummer',
            'Vocalist',
            'Backing Vocal'
        ],
        usher: [
            'Head Usher',
            'Usher',
            'Greeter',
            'Door Keeper'
        ],
        technical: [
            'Sound Engineer',
            'Lighting Technician',
            'Video Operator',
            'Live Stream Operator',
            'Presentation Operator'
        ],
        other: [
            'Volunteer',
            'Helper',
            'Assistant'
        ]
    };

    function openJoinModal(teamId, teamName, teamType) {
        const modal = document.getElementById('joinModal');
        const form = document.getElementById('joinTeamForm');
        const teamNameInput = document.getElementById('modalTeamName');
        const roleSelect = document.getElementById('modalRole');

        // Set form action
        form.action = `/user/volunteer/team/${teamId}/request-join`;

        // Set team name
        teamNameInput.value = teamName;

        // Get taken roles for this team
        const takenRoles = takenRolesByTeam[teamId] || [];

        // Populate role options
        roleSelect.innerHTML = '<option value="">Choose a role...</option>';
        const roles = rolesByType[teamType] || ['Member'];
        
        roles.forEach(role => {
            const option = document.createElement('option');
            option.value = role;
            
            // Check if role is taken
            if (takenRoles.includes(role)) {
                option.textContent = role + ' (Requested)';
                option.disabled = true;
            } else {
                option.textContent = role;
            }
            
            roleSelect.appendChild(option);
        });

        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeJoinModal() {
        const modal = document.getElementById('joinModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    document.getElementById('joinModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeJoinModal();
        }
    });

    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });
</script>
@endsection