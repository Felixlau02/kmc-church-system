@extends('layouts.user')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1rem;
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .form-header-content {
        position: relative;
        z-index: 1;
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .form-subtitle {
        font-size: 1rem;
        opacity: 0.95;
        margin: 0;
    }

    .form-body {
        padding: 2.5rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .required {
        color: #ef4444;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-input:disabled,
    .form-select:disabled {
        background-color: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .help-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .input-group {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1rem;
    }

    .qr-reminder {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-left: 4px solid #0284c7;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        color: #0c4a6e;
    }

    .qr-reminder-title {
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .qr-reminder-text {
        font-size: 0.95rem;
        line-height: 1.5;
        margin: 0;
    }

    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid #e5e7eb;
    }

    .btn {
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        flex: 1;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
        flex: 1;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .amount-input-group {
        position: relative;
    }

    .currency-prefix {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-weight: 600;
        pointer-events: none;
    }

    .amount-input-group .form-input {
        padding-left: 3rem;
    }

    .donation-info {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #f59e0b;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1.5rem;
        color: #92400e;
    }

    .donation-info-title {
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .donation-info-text {
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    .success-icon {
        font-size: 1.25rem;
    }

    /* File Upload Styling */
    .file-upload-wrapper {
        position: relative;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-wrapper:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
    }

    .file-upload-wrapper.has-file {
        border-color: #10b981;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        cursor: pointer;
    }

    .file-upload-icon {
        font-size: 3rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }

    .file-upload-wrapper.has-file .file-upload-icon {
        color: #10b981;
    }

    .file-upload-text {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .file-upload-hint {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .file-name-display {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        font-weight: 600;
        color: #059669;
        word-break: break-word;
    }

    .file-upload-wrapper.has-file .file-name-display {
        display: block;
    }

    /* Required Evidence Notice */
    .evidence-notice {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #ef4444;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        color: #991b1b;
    }

    .evidence-notice-title {
        font-weight: 700;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .evidence-notice-text {
        font-size: 0.85rem;
        line-height: 1.4;
        margin: 0;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1rem 0.5rem;
        }

        .form-body {
            padding: 1.5rem;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .input-group {
            grid-template-columns: 1fr;
        }

        .form-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <!-- Form Header -->
        <div class="form-header">
            <div class="form-header-content">
                <h1 class="form-title">
                    <span>💝</span>
                    Record Your Donation
                </h1>
                <p class="form-subtitle">Thank you for supporting our church community</p>
            </div>
        </div>

        <!-- Form Body -->
        <div class="form-body">
            <!-- QR Reminder -->
            @if($qrCodes && $qrCodes->isNotEmpty())
            <div class="qr-reminder">
                <div class="qr-reminder-title">
                    <i class="fas fa-info-circle"></i>
                    Did you scan a QR code?
                </div>
                <p class="qr-reminder-text">
                    If you just completed a donation by scanning a QR code, fill in the details below to record your contribution. Your donation helps us serve the community better.
                </p>
            </div>
            @endif

            <!-- Donation Form -->
            <form action="{{ route('user.donation.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Your Information (Read-only) -->
                <div class="input-group">
                    <div class="form-group">
                        <label class="form-label">Your Name</label>
                        <input 
                            type="text" 
                            class="form-input" 
                            value="{{ auth()->user()->name }}" 
                            disabled
                        >
                        <div class="help-text">
                            <i class="fas fa-check-circle" style="color: #10b981;"></i>
                            Logged in as {{ auth()->user()->name }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Your Email</label>
                        <input 
                            type="email" 
                            class="form-input" 
                            value="{{ auth()->user()->email }}" 
                            disabled
                        >
                    </div>
                </div>

                <!-- Donation Amount -->
                <div class="form-group">
                    <label class="form-label">
                        Donation Amount <span class="required">*</span>
                    </label>
                    <div class="amount-input-group">
                        <span class="currency-prefix">RM</span>
                        <input 
                            type="text" 
                            name="amount" 
                            class="form-input @error('amount') border-red-500 @enderror" 
                            placeholder="0.00"
                            required
                            value="{{ old('amount') }}"
                            pattern="^\d+(\.\d{1,2})?$"
                            title="Please enter a valid amount (e.g., 20 or 20.00)"
                        >
                    </div>
                    @error('amount')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="help-text">
                        <i class="fas fa-info-circle"></i>
                        Please enter the donation amount you transferred (e.g., 20.00)
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label class="form-label">
                        Payment Method
                    </label>
                    <input 
                        type="text" 
                        name="payment_method" 
                        class="form-input" 
                        value="QR Code" 
                        readonly
                        style="background-color: #f9fafb; cursor: default;"
                    >
                    <div class="help-text">
                        <i class="fas fa-qrcode" style="color: #667eea;"></i>
                        Donation via QR code scan only
                    </div>
                </div>

                <!-- Evidence Upload - NOW REQUIRED -->
                <div class="form-group">
                    <label class="form-label">
                        Upload Evidence <span class="required">*</span>
                    </label>

                    <div class="file-upload-wrapper" id="fileUploadWrapper">
                        <input 
                            type="file" 
                            name="evidence" 
                            class="file-input" 
                            id="evidenceFile"
                            accept=".pdf,.jpg,.jpeg,.png"
                            onchange="handleFileSelect(event)"
                            required
                        >
                        <div class="file-upload-icon" id="uploadIcon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="file-upload-text">
                            Click to upload evidence
                        </div>
                        <div class="file-upload-hint">
                            PDF, JPG, or PNG (Max 5MB) - Required
                        </div>
                        <div class="file-name-display" id="fileNameDisplay">
                            <i class="fas fa-file-pdf"></i>
                            <span id="fileName"></span>
                        </div>
                    </div>
                    @error('evidence')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="help-text">
                        <i class="fas fa-shield-alt" style="color: #ef4444;"></i>
                        Upload a screenshot or receipt as proof of donation (Required for verification)
                    </div>
                </div>

                <!-- Note/Comment -->
                <div class="form-group">
                    <label class="form-label">
                        Note or Special Message (Optional)
                    </label>
                    <textarea 
                        name="note" 
                        class="form-textarea @error('note') border-red-500 @enderror"
                        placeholder="e.g., In memory of..., In celebration of..., Or any special message"
                    >{{ old('note') }}</textarea>
                    @error('note')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="help-text">
                        <i class="fas fa-info-circle"></i>
                        Add a personal message if you'd like (optional)
                    </div>
                </div>

                <!-- Donation Info Box -->
                <div class="donation-info">
                    <div class="donation-info-title">
                        <i class="fas fa-check-circle success-icon"></i>
                        Your donation helps us serve
                    </div>
                    <p class="donation-info-text">
                        All donations received are used to support our community programs, maintenance, and charitable activities. Thank you for your generous contribution!
                    </p>
                </div>

                <!-- Form Buttons -->
                <div class="form-buttons">
                    <a href="{{ route('user.donation.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Go Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Record Donation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function handleFileSelect(event) {
    const file = event.target.files[0];
    const wrapper = document.getElementById('fileUploadWrapper');
    const fileNameDisplay = document.getElementById('fileName');
    const uploadIcon = document.getElementById('uploadIcon');
    
    if (file) {
        wrapper.classList.add('has-file');
        fileNameDisplay.textContent = file.name;
        uploadIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
    } else {
        wrapper.classList.remove('has-file');
        fileNameDisplay.textContent = '';
        uploadIcon.innerHTML = '<i class="fas fa-cloud-upload-alt"></i>';
    }
}
</script>
@endsection