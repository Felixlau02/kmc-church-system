@extends('layouts.admin')

@section('content')
<style>
    .tracking-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
    }

    .back-button:hover {
        color: #667eea;
        transform: translateX(-3px);
    }

    .event-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }

    .event-info-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1.5rem;
    }

    .event-info-title h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .event-badge {
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .event-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        backdrop-filter: blur(10px);
    }

    .detail-content p {
        margin: 0;
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .detail-content strong {
        font-size: 1.1rem;
        display: block;
        margin-top: 0.25rem;
    }

    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tracking-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .tracking-header {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tracking-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .tracking-stats {
        display: flex;
        gap: 2rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .search-box {
        padding: 1.5rem 2rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .members-list {
        padding: 1rem;
    }

    .member-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s;
    }

    .member-row:hover {
        background: #f9fafb;
    }

    .member-row:last-child {
        border-bottom: none;
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .member-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
    }

    .member-details h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .member-details p {
        margin: 0;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .self-marked-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .attendance-controls {
        display: flex;
        gap: 0.75rem;
    }

    .radio-option {
        position: relative;
    }

    .radio-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .radio-option input[type="radio"]:checked + .radio-label.present {
        border-color: #10b981;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .radio-option input[type="radio"]:checked + .radio-label.absent {
        border-color: #ef4444;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .radio-option input[type="radio"]:checked + .radio-label.late {
        border-color: #f59e0b;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .radio-label:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .form-actions {
        padding: 2rem;
        background: #f9fafb;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .action-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .btn-save {
        padding: 1rem 3rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .event-info-header {
            flex-direction: column;
            gap: 1rem;
        }

        .tracking-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .member-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .attendance-controls {
            width: 100%;
        }

        .radio-label {
            flex: 1;
            justify-content: center;
        }
    }

    .info-alert {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        animation: slideDown 0.3s ease;
    }
    
    .warning-alert {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        animation: slideDown 0.3s ease;
    }
    
    .empty-members-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-members-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-members-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .empty-members-text {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

</style>

<div class="tracking-container">
    <!-- Back Button -->
    <a href="{{ route('admin.attendance.index') }}" class="back-button">
        <span>←</span>
        Back to Attendance
    </a>

    <!-- Event Information Card -->
    <div class="event-info-card">
        <div class="event-info-header">
            <div class="event-info-title">
                <h1>📌 {{ $event->title }}</h1>
                <p style="margin: 0; opacity: 0.9;">Track attendance for this event</p>
            </div>
            <div class="event-badge">
                @if(\Carbon\Carbon::parse($event->start_time)->isFuture())
                    🎯 Upcoming
                @else
                    📋 Past Event
                @endif
            </div>
        </div>

        <div class="event-details">
            <div class="detail-item">
                <div class="detail-icon">📅</div>
                <div class="detail-content">
                    <p>Date</p>
                    <strong>{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</strong>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-icon">🕐</div>
                <div class="detail-content">
                    <p>Start Time</p>
                    <strong>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</strong>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-icon">🕐</div>
                <div class="detail-content">
                    <p>End Time</p>
                    <strong>{{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    <!-- Info Message -->
    @if(session('info'))
        <div class="info-alert">
            <i class="fas fa-info-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif
    
    <!-- Warning Message -->
    @if(session('warning'))
        <div class="warning-alert">
            <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem;"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if($members->isEmpty())
        <!-- Empty State when no members registered -->
        <div class="empty-members-state">
            <div class="empty-members-icon">👥</div>
            <h3 class="empty-members-title">No Registered Members</h3>
            <p class="empty-members-text">
                No members have registered for this event yet.<br>
                Only registered members will appear in the attendance list.
            </p>
        </div>
    @else
        <!-- Tracking Form -->
        <form method="POST" action="{{ route('admin.attendance.store', $event->id) }}" id="attendanceForm">
            @csrf
            
            <div class="tracking-card">
                <!-- Tracking Header -->
                <div class="tracking-header">
                    <h2 class="tracking-title">
                        <span>👥</span>
                        Registered Members
                    </h2>
                    <div class="tracking-stats">
                        <div class="stat-item">
                            <div class="stat-value" id="presentCount">0</div>
                            <div class="stat-label">Present</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="absentCount">0</div>
                            <div class="stat-label">Absent</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="lateCount">0</div>
                            <div class="stat-label">Late</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="unmarkedCount">{{ $members->count() }}</div>
                            <div class="stat-label">Unmarked</div>
                        </div>
                    </div>
                </div>

                <!-- Search Box -->
                <div class="search-box">
                    <input 
                        type="text" 
                        class="search-input" 
                        id="searchMember" 
                        placeholder="🔍 Search members by name..."
                        onkeyup="searchMembers()">
                </div>

                <!-- Members List -->
                <div class="members-list" id="membersList">
                    @foreach($members as $member)
                        @php
                            // Get the attendance record for this member
                            $attendance = $attendances->get($member->id);
                            $status = $attendance ? $attendance->status : null;
                            $notes = $attendance ? $attendance->notes : null;
                            $selfMarked = $notes && str_contains($notes, 'Self-marked');
                        @endphp
                        
                        <div class="member-row" data-name="{{ strtolower($member->name) }}">
                            <div class="member-info">
                                <div class="member-avatar">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div class="member-details">
                                    <h4>
                                        {{ $member->name }}
                                        @if($selfMarked)
                                            <span class="self-marked-badge">
                                                <span>✓</span>
                                                Self-marked
                                            </span>
                                        @endif
                                    </h4>
                                    <p>{{ $member->email ?? $member->phone ?? 'No contact info' }}</p>
                                </div>
                            </div>

                            <div class="attendance-controls">
                                <div class="radio-option">
                                    <input 
                                        type="radio" 
                                        name="attendance[{{ $member->id }}]" 
                                        value="present" 
                                        id="present_{{ $member->id }}"
                                        {{ $status == 'present' ? 'checked' : '' }}
                                        onchange="updateStats()"
                                        onclick="handleRadioClick(this, {{ $member->id }})">
                                    <label for="present_{{ $member->id }}" class="radio-label present">
                                        <span>✅</span>
                                        Present
                                    </label>
                                </div>

                                <div class="radio-option">
                                    <input 
                                        type="radio" 
                                        name="attendance[{{ $member->id }}]" 
                                        value="absent" 
                                        id="absent_{{ $member->id }}"
                                        {{ $status == 'absent' ? 'checked' : '' }}
                                        onchange="updateStats()"
                                        onclick="handleRadioClick(this, {{ $member->id }})">
                                    <label for="absent_{{ $member->id }}" class="radio-label absent">
                                        <span>❌</span>
                                        Absent
                                    </label>
                                </div>

                                <div class="radio-option">
                                    <input 
                                        type="radio" 
                                        name="attendance[{{ $member->id }}]" 
                                        value="late" 
                                        id="late_{{ $member->id }}"
                                        {{ $status == 'late' ? 'checked' : '' }}
                                        onchange="updateStats()"
                                        onclick="handleRadioClick(this, {{ $member->id }})">
                                    <label for="late_{{ $member->id }}" class="radio-label late">
                                        <span>⏰</span>
                                        Late
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="action-info">
                        <span>💡</span>
                        <span>Click the same option again to unmark it</span>
                    </div>
                    <button type="submit" class="btn-save">
                        💾 Save Attendance
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>

<script>
// Store the last clicked radio for each member
const lastClicked = {};

// Handle radio button clicks with cancel functionality
function handleRadioClick(radio, memberId) {
    // If this radio was just clicked, uncheck it
    if (lastClicked[memberId] === radio) {
        radio.checked = false;
        lastClicked[memberId] = null;
        updateStats();
    } else {
        lastClicked[memberId] = radio;
    }
}

// Update statistics when radio buttons change
function updateStats() {
    const present = document.querySelectorAll('input[value="present"]:checked').length;
    const absent = document.querySelectorAll('input[value="absent"]:checked').length;
    const late = document.querySelectorAll('input[value="late"]:checked').length;
    const total = {{ $members->count() }};
    const unmarked = total - present - absent - late;

    document.getElementById('presentCount').textContent = present;
    document.getElementById('absentCount').textContent = absent;
    document.getElementById('lateCount').textContent = late;
    document.getElementById('unmarkedCount').textContent = unmarked;
}

// Search members
function searchMembers() {
    const searchTerm = document.getElementById('searchMember').value.toLowerCase();
    const memberRows = document.querySelectorAll('.member-row');

    memberRows.forEach(row => {
        const name = row.getAttribute('data-name');
        if (name.includes(searchTerm)) {
            row.style.display = 'flex';
        } else {
            row.style.display = 'none';
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize stats
    updateStats();
    
    // Initialize lastClicked with current checked radios
    document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
        const memberId = radio.name.match(/\d+/)[0];
        lastClicked[memberId] = radio;
    });
});

// Before form submission, remove unmarked members from submission
document.getElementById('attendanceForm')?.addEventListener('submit', function(e) {
    // Get all radio groups
    const memberIds = [...new Set(Array.from(document.querySelectorAll('input[type="radio"]')).map(r => r.name))];
    
    // Check each member and remove name attribute if not marked
    memberIds.forEach(name => {
        const radios = document.querySelectorAll(`input[name="${name}"]`);
        const isChecked = Array.from(radios).some(r => r.checked);
        
        // If no radio is checked, remove the name attribute so it won't be submitted
        if (!isChecked) {
            radios.forEach(r => r.removeAttribute('name'));
        }
    });
});
</script>
@endsection