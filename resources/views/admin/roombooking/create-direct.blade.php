@extends('layouts.admin')

@section('content')
<style>
    .booking-form-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid #e2e8f0;
    }

    .form-title {
        font-size: 1.875rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: #718096;
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.625rem;
        font-size: 0.95rem;
    }

    .form-label .required {
        color: #e53e3e;
        margin-left: 0.25rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1.125rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        font-family: inherit;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
    }

    .admin-info-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border-left: 4px solid #f59e0b;
    }

    .admin-info-icon {
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .admin-info-text {
        color: #92400e;
        font-size: 0.925rem;
        line-height: 1.6;
    }

    .admin-info-text strong {
        font-weight: 700;
        display: block;
        margin-bottom: 0.25rem;
    }

    .error-alert {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border-left: 4px solid #dc2626;
        animation: slideIn 0.3s ease;
    }

    .error-alert-icon {
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .error-alert-text {
        flex: 1;
        font-size: 0.925rem;
        line-height: 1.6;
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

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 3px solid #e2e8f0;
        justify-content: flex-end;
    }

    .btn {
        padding: 0.875rem 2.25rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.5);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: white;
        color: #4a5568;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
        transform: translateY(-2px);
    }

    .error-message {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .input-icon {
        position: relative;
    }

    .input-icon input,
    .input-icon select,
    .input-icon textarea {
        padding-left: 2.75rem;
    }

    .input-icon::before {
        content: attr(data-icon);
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.25rem;
        color: #667eea;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .booking-form-container {
            padding: 1rem;
        }

        .form-card {
            padding: 1.75rem;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .form-row,
        .form-row-3 {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="booking-form-container">
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">➕ Create Room Booking</h2>
            <p class="form-subtitle">
                Create a new room booking (Admin access)
            </p>
        </div>

        @if (session('error'))
            <div class="error-alert">
                <span class="error-alert-icon">⚠️</span>
                <div class="error-alert-text">
                    <strong>Booking Conflict Detected</strong><br>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="error-alert">
                <span class="error-alert-icon">⚠️</span>
                <div class="error-alert-text">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="admin-info-box">
            <span class="admin-info-icon">ℹ️</span>
            <div class="admin-info-text">
                <strong>Admin Booking</strong>
                Bookings created here will be automatically approved if there are no conflicts.
            </div>
        </div>

        <form method="POST" action="{{ route('admin.roombooking.store-direct') }}">
            @csrf

            <div class="form-group">
                <label for="member_name" class="form-label">
                    Member Name<span class="required">*</span>
                </label>
                <div class="input-icon" data-icon="👤">
                    <input type="text" 
                           name="member_name" 
                           id="member_name"
                           class="form-input"
                           value="{{ old('member_name') }}" 
                           placeholder="Enter the name of the person this booking is for"
                           required>
                </div>
                @error('member_name')
                    <div class="error-message">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="room_id" class="form-label">
                    Select Room<span class="required">*</span>
                </label>
                <div class="input-icon" data-icon="🚪">
                    <select name="room_id" id="room_id" class="form-select" required>
                        <option value="" disabled selected>Choose a room...</option>
                        <option value="Room 1 (30 person)" {{ old('room_id') == 'Room 1 (30 person)' ? 'selected' : '' }}>
                            Room 1 (30 person)
                        </option>
                        <option value="Room 2 (40 person)" {{ old('room_id') == 'Room 2 (40 person)' ? 'selected' : '' }}>
                            Room 2 (40 person)
                        </option>
                        <option value="Room 3 (50 person)" {{ old('room_id') == 'Room 3 (50 person)' ? 'selected' : '' }}>
                            Room 3 (50 person)
                        </option>
                        <option value="Main Hall (100 person)" {{ old('room_id') == 'Main Hall (100 person)' ? 'selected' : '' }}>
                            Main Hall (100 person)
                        </option>
                    </select>
                </div>
                @error('room_id')
                    <div class="error-message">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label for="booking_date" class="form-label">
                        Booking Date<span class="required">*</span>
                    </label>
                    <div class="input-icon" data-icon="📅">
                        <input type="date" 
                               name="booking_date" 
                               id="booking_date"
                               class="form-input"
                               value="{{ old('booking_date') }}" 
                               required>
                    </div>
                    @error('booking_date')
                        <div class="error-message">⚠️ {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time" class="form-label">
                        Start Time<span class="required">*</span>
                    </label>
                    <div class="input-icon" data-icon="🕐">
                        <input type="time" 
                               name="start_time" 
                               id="start_time"
                               class="form-input"
                               value="{{ old('start_time') }}" 
                               required>
                    </div>
                    @error('start_time')
                        <div class="error-message">⚠️ {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time" class="form-label">
                        End Time<span class="required">*</span>
                    </label>
                    <div class="input-icon" data-icon="🕐">
                        <input type="time" 
                               name="end_time" 
                               id="end_time"
                               class="form-input"
                               value="{{ old('end_time') }}" 
                               required>
                    </div>
                    @error('end_time')
                        <div class="error-message">⚠️ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="boxing_description" class="form-label">
                    Booking Purpose<span class="required">*</span>
                </label>
                <div class="input-icon" data-icon="📝" style="display: block;">
                    <textarea name="boxing_description" 
                              id="boxing_description"
                              class="form-textarea"
                              placeholder="Describe the purpose of this booking (e.g., Emergency Meeting, VIP Session, Administrative Task)"
                              required>{{ old('boxing_description') }}</textarea>
                </div>
                @error('boxing_description')
                    <div class="error-message">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.roombooking.index') }}" class="btn btn-secondary">
                    ← Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <span>⚡</span>
                    Create & Approve
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validate that end time is after start time
document.getElementById('end_time').addEventListener('change', function() {
    const startTime = document.getElementById('start_time').value;
    const endTime = this.value;
    
    if (startTime && endTime && endTime <= startTime) {
        alert('End time must be after start time');
        this.value = '';
    }
});
</script>
@endsection