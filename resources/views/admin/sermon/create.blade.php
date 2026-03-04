@extends('layouts.admin')

@section('content')
<style>
    .create-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    /* Header */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2.5rem;
        border-radius: 25px;
        margin-bottom: 2.5rem;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '✨';
        position: absolute;
        font-size: 15rem;
        opacity: 0.1;
        right: -3rem;
        top: -4rem;
        transform: rotate(-15deg);
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        font-size: 1.15rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    /* Form Container */
    .form-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Form Sections */
    .form-section {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #f3f4f6;
    }

    .section-icon {
        font-size: 2rem;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .section-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    /* Video Upload Zone */
    .video-upload-area {
        border: 3px dashed #667eea;
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .video-upload-area:hover {
        border-color: #764ba2;
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.15);
    }

    .upload-icon-large {
        font-size: 4rem;
        margin-bottom: 1rem;
        filter: drop-shadow(0 8px 16px rgba(102, 126, 234, 0.2));
    }

    .upload-content {
        position: relative;
        z-index: 1;
    }

    .upload-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .upload-subtitle {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        font-size: 0.9rem;
        color: #9ca3af;
    }

    /* Video Preview */
    .video-preview-box {
        display: none;
        margin-top: 1.5rem;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .video-preview-box video {
        width: 100%;
        max-height: 450px;
        background: #000;
    }

    .video-info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 15px;
        margin-top: 1.5rem;
        display: none;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 700;
        color: #495057;
    }

    .info-value {
        color: #6c757d;
    }

    .btn-remove-video {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        margin-top: 1rem;
        display: none;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        transition: all 0.3s;
    }

    .btn-remove-video:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    /* Form Fields */
    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        display: block;
        font-size: 1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .required-star {
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .help-text {
        display: block;
        font-size: 0.9rem;
        color: #6b7280;
        font-weight: 400;
        margin-top: 0.25rem;
    }

    .form-input {
        width: 100%;
        padding: 1rem 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #f9fafb;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    textarea.form-input {
        min-height: 150px;
        resize: vertical;
        font-family: inherit;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon .icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        color: #9ca3af;
    }

    .input-with-icon .form-input {
        padding-left: 3.5rem;
    }

    .char-counter {
        text-align: right;
        font-size: 0.9rem;
        color: #9ca3af;
        margin-top: 0.5rem;
    }

    /* Scripture Chips */
    .scripture-quick-select {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .scripture-chip {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
    }

    .scripture-chip:hover {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        flex: 1;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem 3rem;
        border-radius: 50px;
        border: none;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
        padding: 1.25rem 2.5rem;
        border-radius: 50px;
        border: 2px solid #e5e7eb;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .create-page {
            padding: 1rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
        }

        .form-section {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
        }
    }
</style>

<div class="create-page">
    <!-- Header -->
    <div class="page-header">
        <h1>➕ Create New Sermon</h1>
        <p>Add a new sermon to your library</p>
    </div>

    <div class="form-wrapper">
        <form action="{{ route('admin.sermon.store') }}" method="POST" enctype="multipart/form-data" id="sermonForm">
            @csrf

            <!-- Video Upload Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🎥</div>
                    <h3>Upload Sermon Video (Optional)</h3>
                </div>

                <div class="video-upload-area" id="videoUploadZone" onclick="document.getElementById('videoInput').click()">
                    <input type="file" id="videoInput" name="video" accept="video/*" style="display: none;">
                    <div class="upload-content">
                        <div class="upload-icon-large">🎥</div>
                        <div class="upload-title">Drop your video here or click to browse</div>
                        <div class="upload-subtitle">Upload your sermon video</div>
                        <div class="upload-hint">Supports: MP4, MOV, AVI, WMV • Max 500MB</div>
                    </div>
                </div>

                <div class="video-preview-box" id="videoPreview">
                    <video controls id="videoPlayer"></video>
                </div>

                <div class="video-info-card" id="videoInfo">
                    <div class="info-row">
                        <span class="info-label">🎬 File Name:</span>
                        <span class="info-value" id="fileName"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📊 File Size:</span>
                        <span class="info-value" id="fileSize"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">⏱️ Duration:</span>
                        <span class="info-value" id="fileDuration"></span>
                    </div>
                </div>

                <button type="button" class="btn-remove-video" id="removeVideoBtn" onclick="removeVideo()">
                    🗑️ Remove Video
                </button>

                @error('video')
                    <div style="color: #ef4444; margin-top: 1rem; font-weight: 600;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">👤</div>
                    <h3>Basic Information</h3>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Reverend / Speaker Name<span class="required-star">*</span>
                        <span class="help-text">Who will be delivering this sermon?</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="icon">👤</span>
                        <input type="text" name="reverend_name" class="form-input" 
                               placeholder="e.g., Rev. John Smith" required 
                               value="{{ old('reverend_name') }}">
                    </div>
                    @error('reverend_name')
                        <div style="color: #ef4444; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Sermon Theme / Title<span class="required-star">*</span>
                        <span class="help-text">A compelling title that captures the sermon's message</span>
                    </label>
                    <input type="text" name="sermon_theme" class="form-input" 
                           placeholder="e.g., Walking in Faith: Trusting God's Plan" required
                           value="{{ old('sermon_theme') }}"
                           maxlength="200" id="themeInput">
                    <div class="char-counter">
                        <span id="themeCount">0</span>/200 characters
                    </div>
                    @error('sermon_theme')
                        <div style="color: #ef4444; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Sermon Date<span class="required-star">*</span>
                        <span class="help-text">When will this sermon be delivered?</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="icon">📅</span>
                        <input type="date" name="sermon_date" class="form-input" required
                               value="{{ old('sermon_date', date('Y-m-d')) }}">
                    </div>
                    @error('sermon_date')
                        <div style="color: #ef4444; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Content Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📝</div>
                    <h3>Sermon Content</h3>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Sermon Description / Summary
                        <span class="help-text">Provide a brief overview of the sermon</span>
                    </label>
                    <textarea name="sermon_description" class="form-input" 
                              placeholder="Enter sermon summary, key points, or main message..."
                              maxlength="1000" id="descriptionInput">{{ old('sermon_description') }}</textarea>
                    <div class="char-counter">
                        <span id="descriptionCount">0</span>/1000 characters
                    </div>
                    @error('sermon_description')
                        <div style="color: #ef4444; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Scripture References -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📜</div>
                    <h3>Scripture References</h3>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Bible Verses Referenced
                        <span class="help-text">Which Bible passages will be discussed?</span>
                    </label>
                    <input type="text" name="scripture_references" class="form-input" 
                           placeholder="e.g., Matthew 5:1-12, Psalm 23:1-6, John 3:16"
                           value="{{ old('scripture_references') }}"
                           id="scriptureInput">
                    @error('scripture_references')
                        <div style="color: #ef4444; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror

                    <div class="scripture-quick-select">
                        <span class="scripture-chip" onclick="addScripture('John 3:16')">John 3:16</span>
                        <span class="scripture-chip" onclick="addScripture('Psalm 23')">Psalm 23</span>
                        <span class="scripture-chip" onclick="addScripture('Romans 8:28')">Romans 8:28</span>
                        <span class="scripture-chip" onclick="addScripture('Matthew 5:1-12')">Matthew 5:1-12</span>
                        <span class="scripture-chip" onclick="addScripture('Philippians 4:13')">Philippians 4:13</span>
                        <span class="scripture-chip" onclick="addScripture('1 Corinthians 13')">1 Corinthians 13</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <span>✅</span> Create Sermon
                </button>
                <a href="{{ route('admin.sermon.index') }}" class="btn-secondary">
                    <span>✖️</span> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Character counters
const themeInput = document.getElementById('themeInput');
const themeCount = document.getElementById('themeCount');
if (themeInput) {
    themeInput.addEventListener('input', function() {
        themeCount.textContent = this.value.length;
    });
    themeCount.textContent = themeInput.value.length;
}

const descriptionInput = document.getElementById('descriptionInput');
const descriptionCount = document.getElementById('descriptionCount');
if (descriptionInput) {
    descriptionInput.addEventListener('input', function() {
        descriptionCount.textContent = this.value.length;
    });
    descriptionCount.textContent = descriptionInput.value.length;
}

// Scripture functions
function addScripture(scripture) {
    const scriptureInput = document.getElementById('scriptureInput');
    const currentValue = scriptureInput.value.trim();
    
    if (currentValue === '') {
        scriptureInput.value = scripture;
    } else {
        if (!currentValue.includes(scripture)) {
            scriptureInput.value = currentValue + ', ' + scripture;
        }
    }
    
    event.target.style.transform = 'scale(0.95)';
    setTimeout(() => {
        event.target.style.transform = 'scale(1)';
    }, 100);
}

// Video Upload Handling
const videoInput = document.getElementById('videoInput');
const videoUploadZone = document.getElementById('videoUploadZone');
const videoPreview = document.getElementById('videoPreview');
const videoPlayer = document.getElementById('videoPlayer');
const videoInfo = document.getElementById('videoInfo');
const removeVideoBtn = document.getElementById('removeVideoBtn');

videoUploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    videoUploadZone.style.borderColor = '#764ba2';
});

videoUploadZone.addEventListener('dragleave', () => {
    videoUploadZone.style.borderColor = '#667eea';
});

videoUploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    videoUploadZone.style.borderColor = '#667eea';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        videoInput.files = files;
        handleVideoUpload(files[0]);
    }
});

videoInput.addEventListener('change', function(e) {
    if (this.files.length > 0) {
        handleVideoUpload(this.files[0]);
    }
});

function handleVideoUpload(file) {
    const validTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'];
    if (!validTypes.includes(file.type)) {
        alert('⚠️ Please upload a valid video file (MP4, MOV, AVI, WMV)');
        return;
    }

    if (file.size > 500 * 1024 * 1024) {
        alert('⚠️ Video file is too large. Maximum size is 500MB');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        videoPlayer.src = e.target.result;
        videoPreview.style.display = 'block';
        videoInfo.style.display = 'block';
        removeVideoBtn.style.display = 'inline-block';
        videoUploadZone.style.display = 'none';
    };
    reader.readAsDataURL(file);

    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);

    videoPlayer.addEventListener('loadedmetadata', function() {
        document.getElementById('fileDuration').textContent = formatDuration(videoPlayer.duration);
    });
}

function removeVideo() {
    videoInput.value = '';
    videoPlayer.src = '';
    videoPreview.style.display = 'none';
    videoInfo.style.display = 'none';
    removeVideoBtn.style.display = 'none';
    videoUploadZone.style.display = 'block';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function formatDuration(seconds) {
    const hrs = Math.floor(seconds / 3600);
    const mins = Math.floor((seconds % 3600) / 60);
    const secs = Math.floor(seconds % 60);
    
    if (hrs > 0) {
        return `${hrs}h ${mins}m ${secs}s`;
    } else if (mins > 0) {
        return `${mins}m ${secs}s`;
    } else {
        return `${secs}s`;
    }
}
</script>
@endsection