@extends('layouts.admin')

@section('content')
<style>
    .volunteer-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .page-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .page-title h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    .btn-add-team {
        padding: 0.875rem 1.75rem;
        background: white;
        color: #667eea;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9375rem;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .btn-add-team:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        color: #667eea;
    }

    .alert-modern {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: none;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .teams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .team-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .team-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        color: white;
        position: relative;
    }

    .team-card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
    }

    .team-icon-large {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .team-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .team-type-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .team-card-body {
        padding: 1.5rem;
    }

    .team-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        display: block;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .members-preview {
        margin-top: 1rem;
    }

    .members-preview-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .member-avatars {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .member-avatar-small {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .more-members {
        width: 40px;
        height: 40px;
        background: #e5e7eb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-weight: 700;
        font-size: 0.85rem;
        border: 2px solid white;
    }

    .empty-members-text {
        color: #9ca3af;
        font-size: 0.9rem;
        font-style: italic;
    }

    .team-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #f3f4f6;
    }

    .btn-manage {
        flex: 1;
        padding: 0.75rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-manage:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-delete {
        padding: 0.75rem 1rem;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 6rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-title {
        font-size: 2rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        max-width: 600px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s;
    }

    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        margin-bottom: 2rem;
    }

    .modal-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-body {
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        justify-content: center;
        flex: 1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
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
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1.5rem;
        }

        .page-title h1 {
            font-size: 1.75rem;
        }

        .teams-grid {
            grid-template-columns: 1fr;
        }

        .page-header-content {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .btn-add-team {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="volunteer-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <div>
                    <h1>
                        <span>🙏</span>
                        Sunday Service Teams
                    </h1>
                    <p class="page-subtitle">Manage worship, usher, technical teams and volunteers</p>
                </div>
            </div>
            <button onclick="openAddTeamModal()" class="btn-add-team">
                <i class="fas fa-plus"></i>
                Add Team
            </button>
        </div>
    </div>

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

    <!-- Teams Grid -->
    @if($activity->teams->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h3 class="empty-title">No Teams Yet</h3>
            <p class="empty-text">Create your first team to start organizing volunteers</p>
        </div>
    @else
        <div class="teams-grid">
            @foreach($activity->teams as $team)
                <div class="team-card">
                    <div class="team-card-header">
                        <span class="team-icon-large">
                            @switch($team->team_type)
                                @case('worship') 🎵 @break
                                @case('usher') 🚪 @break
                                @case('technical') 🔊 @break
                                @default ⭐ @break
                            @endswitch
                        </span>
                        <h3 class="team-name">{{ $team->team_name }}</h3>
                        <span class="team-type-badge">{{ $teamTypes[$team->team_type] ?? 'Team' }}</span>
                    </div>
                    
                    <div class="team-card-body">
                        <div class="team-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $team->members->count() }}</span>
                                <span class="stat-label">Members</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $team->members->pluck('role')->unique()->count() }}</span>
                                <span class="stat-label">Roles</span>
                            </div>
                        </div>

                        <div class="members-preview">
                            <div class="members-preview-title">Team Members:</div>
                            @if($team->members->isEmpty())
                                <p class="empty-members-text">No members assigned yet</p>
                            @else
                                <div class="member-avatars">
                                    @foreach($team->members->take(5) as $registration)
                                        <div class="member-avatar-small" title="{{ $registration->member->name }} - {{ $registration->role }}">
                                            {{ strtoupper(substr($registration->member->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($team->members->count() > 5)
                                        <div class="more-members" title="{{ $team->members->count() - 5 }} more members">
                                            +{{ $team->members->count() - 5 }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="team-actions">
                            <a href="{{ route('admin.volunteer.manage-team', $team->id) }}" class="btn-manage">
                                <i class="fas fa-users-cog"></i>
                                Manage Team
                            </a>
                            <form action="{{ route('admin.volunteer.delete-team', $team->id) }}" method="POST" 
                                  onsubmit="return confirm('Delete this team and all its members?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Add Team Modal -->
<div id="addTeamModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">
                <span>➕</span>
                Add New Team
            </h2>
        </div>
        <form action="{{ route('admin.volunteer.add-team') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Team Name *</label>
                    <input type="text" name="team_name" class="form-input" 
                           placeholder="e.g., Worship Team A" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Team Type *</label>
                    <select name="team_type" class="form-select" required>
                        <option value="">Select team type...</option>
                        @foreach($teamTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeAddTeamModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i>
                    Create Team
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddTeamModal() {
    document.getElementById('addTeamModal').classList.add('active');
}

function closeAddTeamModal() {
    document.getElementById('addTeamModal').classList.remove('active');
}

window.onclick = function(event) {
    const modal = document.getElementById('addTeamModal');
    if (event.target === modal) {
        closeAddTeamModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddTeamModal();
    }
});
</script>
@endsection