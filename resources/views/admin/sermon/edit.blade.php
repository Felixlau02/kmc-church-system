@extends('layouts.admin')

@section('content')
<style>
    .form-container {
        max-width: 1200px;
        margin: 2rem auto;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
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
        font-family: 'Courier New', monospace;
        line-height: 1.6;
    }

    .transcript-textarea {
        min-height: 400px;
        background: #f9fafb;
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

    .info-box {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-left: 4px solid #3b82f6;
        font-size: 0.9rem;
        color: #1e40af;
    }

    .section-divider {
        margin: 2rem 0;
        border: 0;
        border-top: 2px dashed #e5e7eb;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">
                <i class="fas fa-edit"></i> Edit Sermon
            </h2>
            <p class="form-subtitle">Update sermon information and AI-generated content</p>
        </div>

        <form action="{{ route('admin.sermon.update', $sermon->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                Basic Information
            </div>

            <div class="form-group">
                <label for="reverend_name" class="form-label">
                    Reverend Name<span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    name="reverend_name" 
                    id="reverend_name"
                    class="form-input"
                    value="{{ old('reverend_name', $sermon->reverend_name) }}" 
                    required>
                @error('reverend_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sermon_theme" class="form-label">
                    Sermon Theme<span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    name="sermon_theme" 
                    id="sermon_theme"
                    class="form-input"
                    value="{{ old('sermon_theme', $sermon->sermon_theme) }}" 
                    required>
                @error('sermon_theme')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sermon_description" class="form-label">
                    Description
                </label>
                <textarea 
                    name="sermon_description" 
                    id="sermon_description"
                    class="form-textarea">{{ old('sermon_description', $sermon->sermon_description) }}</textarea>
                @error('sermon_description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sermon_date" class="form-label">
                    Sermon Date<span class="required">*</span>
                </label>
                <input 
                    type="date" 
                    name="sermon_date" 
                    id="sermon_date"
                    class="form-input"
                    value="{{ old('sermon_date', $sermon->sermon_date->format('Y-m-d')) }}" 
                    required>
                @error('sermon_date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="scripture_references" class="form-label">
                    Scripture References
                </label>
                <input 
                    type="text" 
                    name="scripture_references" 
                    id="scripture_references"
                    class="form-input"
                    value="{{ old('scripture_references', $sermon->scripture_references) }}" 
                    placeholder="e.g., John 3:16, Romans 8:28">
                @error('scripture_references')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="video" class="form-label">
                    Upload New Video
                </label>
                @if($sermon->video_path)
                    <div class="info-box">
                        <i class="fas fa-video"></i>
                        <span>Current video: {{ basename($sermon->video_path) }}</span>
                    </div>
                @endif
                <input 
                    type="file" 
                    name="video" 
                    id="video"
                    class="form-input"
                    accept="video/mp4,video/mov,video/avi,video/wmv">
                <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                    Upload a new video to replace the current one. Max size: 500MB
                </small>
                @error('video')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- AI-Generated Content -->
            @if($sermon->ai_transcript)
                <hr class="section-divider">
                
                <div class="section-title">
                    <i class="fas fa-robot"></i>
                    AI-Generated Content (Editable)
                </div>

                <div class="form-group">
                    <label for="ai_transcript" class="form-label">
                        <i class="fas fa-file-alt"></i>
                        Full Transcript
                    </label>
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <span>You can edit the AI-generated transcript to correct any errors or make improvements.</span>
                    </div>
                    <textarea 
                        name="ai_transcript" 
                        id="ai_transcript"
                        class="form-textarea transcript-textarea">{{ old('ai_transcript', $sermon->ai_transcript) }}</textarea>
                    @error('ai_transcript')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="form-actions">
                <a href="{{ route('admin.sermon.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Sermon
                </button>
            </div>
        </form>
    </div>
</div>
@endsection