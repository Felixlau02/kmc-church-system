@extends('layouts.admin')

@section('content')
<style>
    .qr-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 20px;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 40px rgba(139, 92, 246, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header-left {
        flex: 1;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: white;
        color: #7c3aed;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #7c3aed;
    }

    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        animation: slideIn 0.3s ease;
    }

    .error-alert {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
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

    .content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .upload-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .upload-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #f59e0b;
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-box p {
        margin: 0;
        color: #92400e;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-file {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-input:focus,
    .form-file:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .form-file {
        padding: 1rem;
        cursor: pointer;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .qr-display {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .qr-display-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .qr-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .qr-card {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
        max-width: 350px;
        width: 100%;
    }

    .qr-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        border-color: #8b5cf6;
    }

    .qr-image-wrapper {
        width: 100%;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        overflow: hidden;
    }

    .qr-image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .qr-info {
        padding: 1.5rem;
    }

    .qr-label {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        word-break: break-word;
        font-size: 1.1rem;
    }

    .qr-date {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .qr-actions {
        display: flex;
        justify-content: center;
    }

    .qr-delete-form {
        display: inline;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .empty-message {
        font-size: 1.1rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .empty-hint {
        font-size: 0.95rem;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .page-title {
            font-size: 1.75rem;
        }
    }
</style>

<div class="qr-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">📱 QR Code Management</h1>
                <p class="page-subtitle">Manage your donation QR code (Only one QR code allowed)</p>
            </div>
            <a href="{{ route('admin.donation.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Donation
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="error-alert">
            <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Current QR Code Display -->
    @if($qrCode)
    <div class="qr-display">
        <h2 class="qr-display-title">
            <span>📱</span>
            Current QR Code
        </h2>

        <div class="qr-wrapper">
            <div class="qr-card">
                <div class="qr-image-wrapper">
                    <img src="{{ $qrCode->qr_code_url }}" alt="{{ $qrCode->qr_code_label }}">
                </div>
                <div class="qr-info">
                    <div class="qr-label">{{ $qrCode->qr_code_label }}</div>
                    <div class="qr-date">
                        <i class="fas fa-calendar-alt"></i>
                        Uploaded: {{ $qrCode->created_at->format('d M Y, h:i A') }}
                    </div>
                    <div class="qr-actions">
                        <form action="{{ route('admin.donation.delete-qr', $qrCode->id) }}" 
                              method="POST" 
                              class="qr-delete-form" 
                              onsubmit="return confirm('Are you sure you want to delete this QR code? You can upload a new one afterwards.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                                Delete QR Code
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Upload Form (Only shown if no QR code exists) -->
    <div class="content-grid">
        <div class="upload-card">
            <h2 class="upload-title">
                <span>⬆️</span>
                Upload QR Code
            </h2>

            <div class="info-box">
                <p>
                    <i class="fas fa-info-circle"></i>
                    <span>Only one QR code can be active at a time. Upload your donation QR code here.</span>
                </p>
            </div>

            <form action="{{ route('admin.donation.upload-qr') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="qr_label">QR Code Label</label>
                    <input 
                        type="text" 
                        id="qr_label" 
                        name="qr_label" 
                        class="form-input" 
                        placeholder="e.g., Church Donation Account"
                        required
                    >
                    @error('qr_label')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="qr_image">Select QR Code Image</label>
                    <input 
                        type="file" 
                        id="qr_image" 
                        name="qr_image" 
                        class="form-file" 
                        accept="image/*"
                        required
                    >
                    <small style="color: #6b7280;">Supported formats: JPEG, PNG, JPG, GIF (Max 2MB)</small>
                    @error('qr_image')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i>
                    Upload QR Code
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection