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

    .status-info-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid #f59e0b;
    }

    .status-info-icon {
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .status-info-text {
        color: #92400e;
        font-size: 0.925rem;
        line-height: 1.5;
    }

    .status-info-text strong {
        font-weight: 700;
        text-transform: uppercase;
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

        .form-row {
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
            <h2 class="form-title">✏️ Edit Room Booking</h2>
            <p class="form-subtitle">
                Update the booking information below
            </p>
        </div>

        <div class="status-info-box">
            <span class="status-info-icon">ℹ️</span>
            <div class="status-info-text">
                You are editing an existing booking. Current status: <strong>{{ $roomBooking->status }}</strong>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.roombooking.update', $roomBooking->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="member_name" class="form-label">
                    Member Name<span class="required">*</span>
                </label>
                <div class="input-icon" data-icon="👤">
                    <input type="text" 
                           name="member_name" 
                           id="member_name"
                           class="form-input"
                           value="{{ old('member_name', $roomBooking->member_name) }}" 
                           placeholder="Enter member name"
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
                        <option value="" disabled>Choose a room...</option>
                        <option value="Room 1 (30 person)" {{ old('room_id', $roomBooking->room_id) == 'Room 1 (30 person)' ? 'selected' : '' }}>
                            Room 1 (30 person)
                        </option>
                        <option value="Room 2 (40 person)" {{ old('room_id', $roomBooking->room_id) == 'Room 2 (40 person)' ? 'selected' : '' }}>
                            Room 2 (40 person)
                        </option>
                        <option value="Room 3 (50 person)" {{ old('room_id', $roomBooking->room_id) == 'Room 3 (50 person)' ? 'selected' : '' }}>
                            Room 3 (50 person)
                        </option>
                        <option value="Main Hall (100 person)" {{ old('room_id', $roomBooking->room_id) == 'Main Hall (100 person)' ? 'selected' : '' }}>
                            Main Hall (100 person)
                        </option>
                    </select>
                </div>
                @error('room_id')
                    <div class="error-message">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="booking_date" class="form-label">
                        Booking Date<span class="required">*</span>
                    </label>
                    <div class="input-icon" data-icon="📅">
                        <input type="date" 
                               name="booking_date" 
                               id="booking_date"
                               class="form-input"
                               value="{{ old('booking_date', $roomBooking->booking_date) }}" 
                               required>
                    </div>
                    @error('booking_date')
                        <div class="error-message">⚠️ {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="booking_time" class="form-label">
                        Booking Time<span class="required">*</span>
                    </label>
                    <div class="input-icon" data-icon="🕐">
                        <input type="time" 
                               name="booking_time" 
                               id="booking_time"
                               class="form-input"
                               value="{{ old('booking_time', $roomBooking->booking_time) }}" 
                               required>
                    </div>
                    @error('booking_time')
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
                              placeholder="Describe the purpose of this booking"
                              required>{{ old('boxing_description', $roomBooking->boxing_description) }}</textarea>
                </div>
                @error('boxing_description')
                    <div class="error-message">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">
                    Status<span class="required">*</span>
                </label>
                <div class="input-icon" data-icon="🏷️">
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ old('status', $roomBooking->status) == 'pending' ? 'selected' : '' }}>
                            ⏳ Pending
                        </option>
                        <option value="approved" {{ old('status', $roomBooking->status) == 'approved' ? 'selected' : '' }}>
                            ✅ Approved
                        </option>
                        <option value="rejected" {{ old('status', $roomBooking->status) == 'rejected' ? 'selected' : '' }}>
                            ❌ Rejected
                        </option>
                    </select>
                </div>
                @error('status')
                    <div class="error-message">⚠️ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.roombooking.index') }}" class="btn btn-secondary">
                    ← Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <span>💾</span>
                    Update Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection