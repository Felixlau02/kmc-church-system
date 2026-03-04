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

    .form-section {
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
        position: relative;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .label-icon {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        color: white;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    }

    .required {
        color: #ef4444;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 1rem 1rem 1rem 4rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
        background: #fafbfc;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
        background: white;
    }

    .form-input.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .form-input.is-invalid:focus, .form-select.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.1rem;
        pointer-events: none;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        color: white;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
    }

    .input-wrapper:focus-within .input-icon {
        transform: translateY(-50%) scale(1.08);
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.5);
    }

    .input-wrapper {
        position: relative;
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
        color: #ef4444;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-helper {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .password-strength {
        margin-top: 0.75rem;
    }

    .strength-bar {
        height: 6px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-progress {
        height: 100%;
        width: 0%;
        transition: all 0.3s;
        border-radius: 10px;
    }

    .strength-weak { 
        width: 33%; 
        background: #ef4444; 
    }
    .strength-medium { 
        width: 66%; 
        background: #f59e0b; 
    }
    .strength-strong { 
        width: 100%; 
        background: #10b981; 
    }

    .strength-text {
        font-size: 0.85rem;
        font-weight: 600;
    }

    .info-box {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        padding: 1.25rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        border-left: 4px solid #3b82f6;
    }

    .info-icon {
        font-size: 1.5rem;
        color: #1e40af;
        flex-shrink: 0;
    }

    .info-content {
        color: #1e40af;
        line-height: 1.6;
    }

    .form-actions {
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

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .form-row {
            grid-template-columns: 1fr;
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
            <h1 class="profile-title">Edit Your Profile</h1>
            <p class="profile-subtitle">Update your personal information and settings</p>
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

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-icon">💡</div>
            <div class="info-content">
                <strong>Keep your profile updated!</strong> Your information helps us serve you better and keep you connected with the church community.
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h3>

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        <span class="label-icon">
                            <i class="fas fa-signature"></i>
                        </span>
                        Full Name<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input 
                            id="name" 
                            type="text" 
                            class="form-input @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name', auth()->user()->name) }}" 
                            placeholder="Enter your full name"
                            required>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <span class="label-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                        Email Address<span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input 
                            id="email" 
                            type="email" 
                            class="form-input @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}" 
                            placeholder="your.email@example.com"
                            required>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-helper">
                        <i class="fas fa-info-circle"></i>
                        <span>We'll use this email for important notifications</span>
                    </div>
                </div>

                @if(auth()->user()->role !== 'admin')
                <!-- Gender and Phone (Hidden for Admin) -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender" class="form-label">
                            <span class="label-icon">
                                <i class="fas fa-venus-mars"></i>
                            </span>
                            Gender<span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <select 
                                id="gender" 
                                name="gender"
                                class="form-select @error('gender') is-invalid @enderror"
                                required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $member->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $member->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <div class="input-icon">
                                <i class="fas fa-venus-mars"></i>
                            </div>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <span class="label-icon">
                                <i class="fas fa-phone"></i>
                            </span>
                            Contact Number
                        </label>
                        <div class="input-wrapper">
                            <input 
    id="phone" 
    type="text" 
    class="form-input @error('phone') is-invalid @enderror" 
    name="phone" 
    value="{{ old('phone', $member->phone ?? '') }}" 
    placeholder="+60 11 2586 9857"
    maxlength="17"
    oninput="formatPhoneNumber(this)">
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-helper">
                            <i class="fas fa-info-circle"></i>
                            <span>Format: +60 10 960 2422 or +60 11 2586 9857</span>
                        </div>
                    </div>
                </div>

                <!-- Fellowship and Baptism (Hidden for Admin) -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="fellowship" class="form-label">
                            <span class="label-icon">
                                <i class="fas fa-users"></i>
                            </span>
                            Fellowship
                        </label>
                        <div class="input-wrapper">
                            <select 
                                id="fellowship" 
                                name="fellowship"
                                class="form-select @error('fellowship') is-invalid @enderror">
                                <option value="">Select Fellowship</option>
                                <option value="Junior Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'Junior Fellowship' ? 'selected' : '' }}>Junior Fellowship</option>
                                <option value="College Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'College Fellowship' ? 'selected' : '' }}>College Fellowship</option>
                                <option value="Youth Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'Youth Fellowship' ? 'selected' : '' }}>Youth Fellowship</option>
                                <option value="Adult Fellowship" {{ old('fellowship', $member->fellowship ?? '') == 'Adult Fellowship' ? 'selected' : '' }}>Adult Fellowship</option>
                            </select>
                            <div class="input-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        @error('fellowship')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="baptism" class="form-label">
                            <span class="label-icon">
                                <i class="fas fa-water"></i>
                            </span>
                            Baptism Status<span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <select 
                                id="baptism" 
                                name="baptism"
                                class="form-select @error('baptism') is-invalid @enderror"
                                required>
                                <option value="">Select Status</option>
                                <option value="Yes" {{ old('baptism', $member->baptism ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('baptism', $member->baptism ?? '') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                            <div class="input-icon">
                                <i class="fas fa-water"></i>
                            </div>
                        </div>
                        @error('baptism')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                @else
                <!-- Hidden fields for admin to pass validation -->
                <input type="hidden" name="gender" value="{{ old('gender', $member->gender ?? 'Male') }}">
                <input type="hidden" name="baptism" value="{{ old('baptism', $member->baptism ?? 'No') }}">
                @endif
            </div>

            <!-- Security Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i>
                    Security Settings
                </h3>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <span class="label-icon">
                            <i class="fas fa-key"></i>
                        </span>
                        New Password
                    </label>
                    <div class="input-wrapper">
                        <input 
                            id="password" 
                            type="password" 
                            class="form-input @error('password') is-invalid @enderror" 
                            name="password"
                            placeholder="Enter new password (optional)"
                            onkeyup="checkPasswordStrength(this.value)">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-progress" id="strengthBar"></div>
                        </div>
                        <div class="strength-text" id="strengthText"></div>
                    </div>
                    <div class="form-helper">
                        <i class="fas fa-info-circle"></i>
                        <span>Leave blank if you don't want to change your password</span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <span class="label-icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        Confirm New Password
                    </label>
                    <div class="input-wrapper">
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="form-input" 
                            name="password_confirmation"
                            placeholder="Confirm your new password">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function formatPhoneNumber(input) {
    // Remove all non-digits
    let digits = input.value.replace(/\D/g, '');
    
    // Ensure starts with 60
    if (digits.startsWith('0')) {
        digits = '60' + digits.slice(1);
    }
    if (!digits.startsWith('60')) {
        digits = '60' + digits;
    }
    
    // Limit to 12 digits total (60 + 10 digits)
    digits = digits.slice(0, 12);
    
    let formatted = '+60';
    const number = digits.slice(2); // Get digits after 60
    
    if (number.startsWith('1') && number.length >= 3) {
        // Mobile numbers starting with 1 (010, 011, 012, etc.)
        // Format: +60 1X XXXX XXXX (3-4-4)
        formatted += ' ' + number.slice(0, 2);
        if (number.length > 2) formatted += ' ' + number.slice(2, 6);
        if (number.length > 6) formatted += ' ' + number.slice(6, 10);
    } else {
        // Other numbers
        // Format: +60 X XXXX XXXX (1-4-4)
        formatted += ' ' + number.slice(0, 1);
        if (number.length > 1) formatted += ' ' + number.slice(1, 5);
        if (number.length > 5) formatted += ' ' + number.slice(5, 9);
    }
    
    input.value = formatted;
}

function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }
    
    strengthDiv.style.display = 'block';
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    strengthBar.className = 'strength-progress';
    
    if (strength <= 2) {
        strengthBar.classList.add('strength-weak');
        strengthText.textContent = '❌ Weak password';
        strengthText.style.color = '#ef4444';
    } else if (strength <= 4) {
        strengthBar.classList.add('strength-medium');
        strengthText.textContent = '⚠️ Medium password';
        strengthText.style.color = '#f59e0b';
    } else {
        strengthBar.classList.add('strength-strong');
        strengthText.textContent = '✅ Strong password';
        strengthText.style.color = '#10b981';
    }
}
</script>
@endsection