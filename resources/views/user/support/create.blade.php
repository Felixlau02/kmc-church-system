@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">
    <div class="create-ticket-container">
        <div class="form-header">
            <div class="header-content">
                <a href="{{ route('user.support.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back to Support
                </a>
                <h1 class="page-title">
                    <i class="fas fa-paper-plane"></i>
                    Submit Support Request
                </h1>
                <p class="page-subtitle">
                    Fill out the form below and our support team will get back to you as soon as possible.
                </p>
            </div>
        </div>

        <div class="form-card">
            <form action="{{ route('user.support.store') }}" method="POST" id="supportForm">
                @csrf

                <!-- Subject Field -->
                <div class="form-group">
                    <label for="subject" class="form-label">
                        <i class="fas fa-heading"></i>
                        Subject <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('subject') is-invalid @enderror" 
                        id="subject" 
                        name="subject" 
                        placeholder="Brief description of your issue"
                        value="{{ old('subject') }}"
                        maxlength="255"
                        required
                    >
                    @error('subject')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        Please provide a clear and concise subject line
                    </div>
                </div>

                <!-- Message Field -->
                <div class="form-group">
                    <label for="message" class="form-label">
                        <i class="fas fa-comment-alt"></i>
                        Message <span class="required">*</span>
                    </label>
                    <textarea 
                        class="form-control @error('message') is-invalid @enderror" 
                        id="message" 
                        name="message" 
                        rows="8"
                        placeholder="Describe your issue in detail. Include any error messages, steps to reproduce, and what you've already tried..."
                        maxlength="2000"
                        required
                    >{{ old('message') }}</textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span> / 2000 characters
                    </div>
                    @error('message')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-hint">
                        <i class="fas fa-lightbulb"></i>
                        The more details you provide, the faster we can help resolve your issue
                    </div>
                </div>

                <!-- Tips Box -->
                <div class="tips-box">
                    <h3 class="tips-title">
                        <i class="fas fa-star"></i>
                        Tips for Better Support
                    </h3>
                    <ul class="tips-list">
                        <li><i class="fas fa-check"></i> Be specific about the problem</li>
                        <li><i class="fas fa-check"></i> Include error messages if any</li>
                        <li><i class="fas fa-check"></i> Mention what you've already tried</li>
                    </ul>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Submit Request
                    </button>
                    <a href="{{ route('user.support.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Side Info Panel -->
        <div class="side-info">
            <div class="info-panel">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Response Time</h3>
                <p>We typically respond within 24 hours during business days</p>
            </div>

            <div class="info-panel">
                <div class="info-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3>Need Urgent Help?</h3>
                <p>WhatsApp us at <strong><a href="https://wa.me/60109602422" target="_blank" style="color: #667eea; text-decoration: none;">010-9602422</a></strong> for immediate assistance</p>
            </div>

            <div class="info-panel">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email Support</h3>
                <p>Send us an email at <strong><a href="mailto:admin@example.com" style="color: #667eea; text-decoration: none;">admin@example.com</a></strong></p>
            </div>
        </div>
    </div>
</div>

<style>
    .container-fluid {
        max-width: 100%;
        margin: 0;
        padding: 0 !important;
    }

    .create-ticket-container {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2rem;
    }

    /* Form Header */
    .form-header {
        grid-column: 1 / -1;
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .back-button:hover {
        transform: translateX(-5px);
        color: #764ba2;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #1e293b;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-title i {
        color: #667eea;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
        font-size: 1.05rem;
    }

    .form-label i {
        color: #667eea;
    }

    .required {
        color: #ef4444;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: inherit;
        background: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 180px;
    }

    .char-counter {
        text-align: right;
        color: #64748b;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 600;
    }

    .error-message {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 600;
    }

    .form-hint {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Tips Box */
    .tips-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 16px;
        padding: 1.75rem;
        border: 2px solid #3b82f6;
        margin-bottom: 2rem;
    }

    .tips-title {
        color: #1e40af;
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tips-list li {
        color: #1e40af;
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
    }

    .tips-list li i {
        color: #10b981;
        font-size: 1.1rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 1rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        font-size: 1.05rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        flex: 1;
        justify-content: center;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
    }

    .btn-cancel {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
        justify-content: center;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    /* Side Info */
    .side-info {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .info-panel {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: all 0.3s ease;
    }

    .info-panel:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .info-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        color: white;
        font-size: 2rem;
    }

    .info-panel h3 {
        color: #1e293b;
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
    }

    .info-panel p {
        color: #64748b;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .info-panel strong {
        color: #667eea;
        font-weight: 700;
    }

    @media (max-width: 1024px) {
        .create-ticket-container {
            grid-template-columns: 1fr;
        }

        .side-info {
            grid-row: 2;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .info-panel {
            flex: 1;
            min-width: 250px;
        }
    }

    @media (max-width: 768px) {
        .form-header {
            padding: 2rem 1.5rem;
        }

        .page-title {
            font-size: 2rem;
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 2rem 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .side-info {
            flex-direction: column;
        }
    }
</style>

<script>
    // Character counter
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    if (messageField && charCount) {
        messageField.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            // Change color when approaching limit
            if (count > 1800) {
                charCount.style.color = '#ef4444';
            } else if (count > 1500) {
                charCount.style.color = '#f59e0b';
            } else {
                charCount.style.color = '#64748b';
            }
        });

        // Initialize counter
        charCount.textContent = messageField.value.length;
    }

    // Form validation
    const form = document.getElementById('supportForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!subject || !message) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }

            if (message.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed description (at least 10 characters).');
                return false;
            }
        });
    }
</script>
@endsection