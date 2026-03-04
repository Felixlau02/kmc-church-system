@extends('layouts.admin')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: 2rem auto;
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
        border-bottom: 3px solid #e5e7eb;
    }

    .form-title {
        font-size: 1.875rem;
        font-weight: 700;
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.625rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label .required {
        color: #ef4444;
    }

    .label-icon {
        font-size: 1rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1.125rem;
        border: 2px solid #e5e7eb;
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
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        transform: translateY(-1px);
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #9ca3af;
    }

    .location-other-field {
        margin-top: 0.75rem;
        display: none;
    }

    .location-other-field.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.25rem;
        color: #8b5cf6;
        pointer-events: none;
    }

    .input-with-icon {
        padding-left: 3rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-helper {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .info-box {
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: start;
        gap: 1rem;
        border-left: 4px solid #8b5cf6;
    }

    .info-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .info-text {
        color: #5b21b6;
        font-size: 0.925rem;
        line-height: 1.5;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 3px solid #e5e7eb;
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
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(139, 92, 246, 0.5);
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
        .form-container {
            margin: 1rem;
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

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">
                <i class="fas fa-edit"></i> Edit Event
            </h2>
            <p class="form-subtitle">
                Update the event information below
            </p>
        </div>

        <div class="info-box">
            <span class="info-icon">ℹ️</span>
            <div class="info-text">
                You are editing <strong>{{ $event->title }}</strong>. 
                Make sure all information is accurate before updating.
            </div>
        </div>

        <form action="{{ route('admin.event.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">
                    <i class="fas fa-heading label-icon"></i>
                    Event Title<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">📝</span>
                    <input 
                        type="text" 
                        name="title" 
                        id="title"
                        class="form-input input-with-icon"
                        value="{{ old('title', $event->title) }}" 
                        placeholder="e.g., Sunday Morning Worship Service"
                        required>
                </div>
                @error('title')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="type" class="form-label">
                    <i class="fas fa-tag label-icon"></i>
                    Event Type<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">🏷️</span>
                    <select name="type" id="type" class="form-select input-with-icon" required>
                        <option value="">-- Select Event Type --</option>
                        <option value="Cell Meeting" {{ old('type', $event->type) == 'Cell Meeting' ? 'selected' : '' }}>Cell Meeting</option>
                        <option value="Sunday Service" {{ old('type', $event->type) == 'Sunday Service' ? 'selected' : '' }}>Sunday Service</option>
                        <option value="Prayer Meeting" {{ old('type', $event->type) == 'Prayer Meeting' ? 'selected' : '' }}>Prayer Meeting</option>
                    </select>
                </div>
                <div class="form-helper">
                    <i class="fas fa-info-circle"></i>
                    <span>Select the type of church event</span>
                </div>
                @error('type')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="start_time" class="form-label">
                        <i class="fas fa-clock label-icon"></i>
                        Start Time<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">🕐</span>
                        <input 
                            type="datetime-local" 
                            name="start_time" 
                            id="start_time"
                            class="form-input input-with-icon"
                            value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}" 
                            required>
                    </div>
                    @error('start_time')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time" class="form-label">
                        <i class="fas fa-clock label-icon"></i>
                        End Time<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">🕐</span>
                        <input 
                            type="datetime-local" 
                            name="end_time" 
                            id="end_time"
                            class="form-input input-with-icon"
                            value="{{ old('end_time', $event->end_time->format('Y-m-d\TH:i')) }}" 
                            required>
                    </div>
                    @error('end_time')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="location" class="form-label">
                    <i class="fas fa-map-marker-alt label-icon"></i>
                    Location<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">📍</span>
                    <select name="location" id="location" class="form-select input-with-icon" required>
                        <option value="">-- Select Room --</option>
                        @foreach($rooms as $room)
                            @if($room == 'Other')
                                <option value="{{ $room }}" {{ old('location', !in_array($event->location, ['Room 1', 'Room 2', 'Room 3', 'Main Hall']) ? 'Other' : '') == $room ? 'selected' : '' }}>
                                    {{ $room }}
                                </option>
                            @else
                                <option value="{{ $room }}" {{ old('location', $event->location) == $room ? 'selected' : '' }}>
                                    {{ $room }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="location-other-field {{ old('location') == 'Other' || !in_array($event->location, ['Room 1', 'Room 2', 'Room 3', 'Main Hall']) ? 'show' : '' }}" id="location-other-container">
                    <div class="input-wrapper">
                        <span class="input-icon">✏️</span>
                        <input 
                            type="text" 
                            name="location_other" 
                            id="location_other"
                            class="form-input input-with-icon"
                            value="{{ old('location_other', !in_array($event->location, ['Room 1', 'Room 2', 'Room 3', 'Main Hall']) ? $event->location : '') }}" 
                            placeholder="Enter custom location">
                    </div>
                </div>
                
                <div class="form-helper">
                    <i class="fas fa-info-circle"></i>
                    <span>Select the room where the event will take place</span>
                </div>
                @error('location')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
                @error('location_other')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="max_participants" class="form-label">
                    <i class="fas fa-users label-icon"></i>
                    Maximum Participants
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">👥</span>
                    <input 
                        type="number" 
                        name="max_participants" 
                        id="max_participants"
                        class="form-input input-with-icon"
                        value="{{ old('max_participants', $event->max_participants ?? '') }}" 
                        placeholder="Leave empty for unlimited"
                        min="1">
                </div>
                <div class="form-helper">
                    <i class="fas fa-info-circle"></i>
                    <span>Optional. Set a limit on how many people can register. Leave empty for unlimited registration.</span>
                </div>
                @if(isset($event) && $event->getCurrentRegistrationCount() > 0)
                    <div class="form-helper" style="color: #8b5cf6; font-weight: 600; margin-top: 0.5rem;">
                        <i class="fas fa-users"></i>
                        <span>Current registrations: {{ $event->getCurrentRegistrationCount() }}</span>
                    </div>
                @endif
                @error('max_participants')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left label-icon"></i>
                    Description
                </label>
                <textarea 
                    name="description" 
                    id="description"
                    class="form-textarea"
                    placeholder="Enter event details, agenda, or special instructions...">{{ old('description', $event->description) }}</textarea>
                <div class="form-helper">
                    <i class="fas fa-info-circle"></i>
                    <span>Optional. Provide additional details about the event</span>
                </div>
                @error('description')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationSelect = document.getElementById('location');
    const locationOtherContainer = document.getElementById('location-other-container');
    const locationOtherInput = document.getElementById('location_other');
    
    locationSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            locationOtherContainer.classList.add('show');
            locationOtherInput.required = true;
        } else {
            locationOtherContainer.classList.remove('show');
            locationOtherInput.required = false;
            locationOtherInput.value = '';
        }
    });
});
</script>
@endsection