@extends('layouts.admin')

@section('content')
<style>
    .form-container {
        max-width: 800px;
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
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
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

    .form-input, .form-select {
        width: 100%;
        padding: 0.875rem 1.125rem;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }

    .form-input::placeholder {
        color: #9ca3af;
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
        color: #3b82f6;
        pointer-events: none;
    }

    .input-with-icon {
        padding-left: 3rem;
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
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
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

    .info-box {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: start;
        gap: 1rem;
        border-left: 4px solid #3b82f6;
    }

    .info-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .info-text {
        color: #1e40af;
        font-size: 0.925rem;
        line-height: 1.5;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
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

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">
                @if(isset($member))
                    <span>✏️</span> Edit Member
                @else
                    <span>➕</span> Add New Member
                @endif
            </h2>
            <p class="form-subtitle">
                @if(isset($member))
                    Update the member information below
                @else
                    Fill in the details to add a new member to the system
                @endif
            </p>
        </div>

        @if(isset($member))
            <div class="info-box">
                <span class="info-icon">ℹ️</span>
                <div class="info-text">
                    You are editing <strong>{{ $member->name }}</strong>'s profile. 
                    Make sure all information is accurate before updating.
                </div>
            </div>
        @endif

        <form method="POST" action="{{ isset($member) ? route('admin.member.update', $member) : route('admin.member.store') }}">
            @csrf
            @if(isset($member))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name" class="form-label">
                    <span class="label-icon">👤</span>
                    Full Name<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        class="form-input input-with-icon"
                        value="{{ old('name', $member->name ?? '') }}" 
                        placeholder="Enter full name"
                        required>
                </div>
                @error('name')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gender" class="form-label">
                        <span class="label-icon">⚧</span>
                        Gender<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">⚧</span>
                        <select 
                            name="gender" 
                            id="gender"
                            class="form-select input-with-icon"
                            required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $member->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $member->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    @error('gender')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">
                        <span class="label-icon">📞</span>
                        Contact Number
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">📞</span>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone"
                            class="form-input input-with-icon"
                            value="{{ old('phone', $member->phone ?? '') }}" 
                            placeholder="+60 12-345 6789">
                    </div>
                    @error('phone')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    <span class="label-icon">✉️</span>
                    Email Address<span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">✉️</span>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="form-input input-with-icon"
                        value="{{ old('email', $member->email ?? '') }}" 
                        placeholder="member@example.com"
                        required>
                </div>
                <div class="form-helper">
                    <i class="fas fa-info-circle"></i>
                    <span>This email will be used for communication and notifications</span>
                </div>
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="fellowship" class="form-label">
                        <span class="label-icon">🤝</span>
                        Fellowship
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">🤝</span>
                        <select 
                            name="fellowship" 
                            id="fellowship"
                            class="form-select input-with-icon">
                            <option value="">Select Fellowship</option>
                            <option value="Junior Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'Junior Fellowship' ? 'selected' : '' }}>Junior Fellowship</option>
                            <option value="College Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'College Fellowship' ? 'selected' : '' }}>College Fellowship</option>
                            <option value="Youth Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'Youth Fellowship' ? 'selected' : '' }}>Youth Fellowship</option>
                            <option value="Adult Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'Adult Fellowship' ? 'selected' : '' }}>Adult Fellowship</option>
                        </select>
                    </div>
                    <div class="form-helper">
                        <i class="fas fa-info-circle"></i>
                        <span>Optional - Select member's fellowship group</span>
                    </div>
                    @error('fellowship')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="baptism" class="form-label">
                        <span class="label-icon">💧</span>
                        Baptism Status<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">💧</span>
                        <select 
                            name="baptism" 
                            id="baptism"
                            class="form-select input-with-icon"
                            required>
                            <option value="">Select Status</option>
                            <option value="Yes" {{ old('baptism', $member->baptism ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ old('baptism', $member->baptism ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    @error('baptism')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.member.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    @if(isset($member))
                        <i class="fas fa-save"></i> Update Member
                    @else
                        <i class="fas fa-plus"></i> Create Member
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection