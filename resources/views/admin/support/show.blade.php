@extends('layouts.admin')

@section('content')
<style>
    .ticket-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .ticket-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .ticket-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ticket-title-section {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .ticket-title {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .ticket-status-badge {
        padding: 0.625rem 1.5rem;
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .status-open {
        background: rgba(209, 250, 229, 0.95);
        color: #065f46;
    }

    .status-pending {
        background: rgba(254, 243, 199, 0.95);
        color: #92400e;
    }

    .status-closed {
        background: rgba(243, 244, 246, 0.95);
        color: #374151;
    }

    .ticket-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        position: relative;
        z-index: 1;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .meta-value {
        font-weight: 700;
        color: white;
        font-size: 1.125rem;
    }

    .ticket-content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .ticket-message-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .ticket-message-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-title i {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.75rem;
    }

    .ticket-message {
        color: #374151;
        line-height: 1.8;
        font-size: 1.05rem;
        white-space: pre-wrap;
        padding: 1.75rem;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 12px;
        border-left: 5px solid #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    }

    /* Response Section */
    .response-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .response-section:hover {
        border-color: #8b5cf6;
        box-shadow: 0 4px 20px rgba(139, 92, 246, 0.15);
    }

    .response-form-group {
        margin-bottom: 1.5rem;
    }

    .response-label {
        display: block;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .response-textarea {
        width: 100%;
        padding: 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        min-height: 180px;
        transition: all 0.2s ease;
    }

    .response-textarea:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }

    .char-counter {
        text-align: right;
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 600;
    }

    .response-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-submit-response {
        flex: 1;
        padding: 1rem 1.75rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        font-size: 1rem;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-submit-response:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
    }

    .btn-delete-response {
        padding: 1rem 1.75rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        font-size: 1rem;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        width: 100%;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-delete-response:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .existing-response {
        padding: 1.75rem;
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
        border-radius: 12px;
        border-left: 5px solid #8b5cf6;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(139, 92, 246, 0.1);
    }

    .existing-response-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .existing-response-title {
        font-weight: 700;
        color: #6d28d9;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .existing-response-content {
        color: #1f2937;
        line-height: 1.8;
        white-space: pre-wrap;
        font-size: 1.05rem;
    }

    .ticket-actions-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .action-section {
        margin-bottom: 1.5rem;
    }

    .action-section:last-child {
        margin-bottom: 0;
    }

    .action-section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .action-section-title i {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .status-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }

    .status-form {
        width: 100%;
    }

    .btn-status {
        width: 100%;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        font-size: 0.95rem;
    }

    .btn-status-open {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .btn-status-open:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-status-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
    }

    .btn-status-pending:hover {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-status-closed {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #374151;
        box-shadow: 0 2px 8px rgba(107, 114, 128, 0.2);
    }

    .btn-status-closed:hover {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
    }

    .btn-delete-ticket {
        width: 100%;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        font-size: 0.95rem;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
    }

    .btn-delete-ticket:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .user-info-card {
        padding: 1.75rem;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
        border: 2px solid rgba(59, 130, 246, 0.1);
    }

    .user-info-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .user-info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 2px solid rgba(59, 130, 246, 0.1);
    }

    .user-info-item:last-child {
        border-bottom: none;
    }

    .user-info-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .user-info-content {
        flex: 1;
    }

    .user-info-label {
        font-size: 0.75rem;
        color: #60a5fa;
        margin-bottom: 0.25rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .user-info-value {
        font-weight: 700;
        color: #1e3a8a;
        font-size: 1rem;
    }

    .success-message {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem 1.75rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideDown 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 1024px) {
        .ticket-content-grid {
            grid-template-columns: 1fr;
        }

        .ticket-actions-card {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .ticket-title-section {
            flex-direction: column;
        }

        .ticket-meta-grid {
            grid-template-columns: 1fr;
        }

        .response-actions {
            flex-direction: column;
        }

        .btn-submit-response {
            width: 100%;
        }
    }
</style>

<div class="ticket-container">
    <a href="{{ route('admin.support.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to All Tickets
    </a>

    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle" style="font-size: 1.75rem;"></i>
            <span style="font-weight: 600; font-size: 1.05rem;">{{ session('success') }}</span>
        </div>
    @endif

    <div class="ticket-header">
        <div class="ticket-title-section">
            <h1 class="ticket-title">{{ $ticket->subject }}</h1>
            <span class="ticket-status-badge status-{{ strtolower($ticket->status) }}">
                {{ $ticket->status }}
            </span>
        </div>

        <div class="ticket-meta-grid">
            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-hashtag"></i>
                    Ticket ID
                </div>
                <div class="meta-value">#{{ $ticket->id }}</div>
            </div>

            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-calendar"></i>
                    Submitted On
                </div>
                <div class="meta-value">{{ $ticket->created_at->format('M d, Y') }}</div>
            </div>

            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-clock"></i>
                    Time
                </div>
                <div class="meta-value">{{ $ticket->created_at->format('g:i A') }}</div>
            </div>

            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-history"></i>
                    Last Updated
                </div>
                <div class="meta-value">{{ $ticket->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>

    <div class="ticket-content-grid">
        <!-- Left Column: Message & Response -->
        <div>
            <!-- User Message -->
            <div class="ticket-message-card">
                <h2 class="card-title">
                    <i class="fas fa-comment-dots"></i>
                    User Message
                </h2>
                <div class="ticket-message">{{ $ticket->message }}</div>
            </div>

            <!-- Admin Response Section -->
            <div class="response-section">
                <h2 class="card-title">
                    <i class="fas fa-reply"></i>
                    Admin Response
                </h2>

                @if($ticket->admin_response)
                    <!-- Show existing response -->
                    <div class="existing-response">
                        <div class="existing-response-header">
                            <span class="existing-response-title">
                                <i class="fas fa-check-circle"></i>
                                Current Response
                            </span>
                        </div>
                        <div class="existing-response-content">{{ $ticket->admin_response }}</div>
                    </div>

                    <!-- Edit Response Form -->
                    <form action="{{ route('admin.support.update-response', $ticket->id) }}" method="POST" id="updateResponseForm">
                        @csrf
                        @method('PATCH')
                        <div class="response-form-group">
                            <label for="admin_response" class="response-label">Update Response</label>
                            <textarea 
                                class="response-textarea" 
                                id="admin_response" 
                                name="admin_response" 
                                maxlength="2000"
                                required
                            >{{ $ticket->admin_response }}</textarea>
                            <div class="char-counter">
                                <span id="charCount">{{ strlen($ticket->admin_response) }}</span> / 2000 characters
                            </div>
                        </div>
                        <div class="response-actions">
                            <button type="submit" class="btn-submit-response">
                                <i class="fas fa-save"></i>
                                Update Response
                            </button>
                        </div>
                    </form>

                    <!-- Delete Response Form (Separate Form) -->
                    <form action="{{ route('admin.support.delete-response', $ticket->id) }}" method="POST" style="margin-top: 1rem;" onsubmit="return confirm('Are you sure you want to delete this response?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete-response">
                            <i class="fas fa-trash"></i>
                            Delete Response
                        </button>
                    </form>
                @else
                    <!-- Add New Response Form -->
                    <form action="{{ route('admin.support.add-response', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="response-form-group">
                            <label for="admin_response" class="response-label">
                                Write your response to the user
                            </label>
                            <textarea 
                                class="response-textarea" 
                                id="admin_response" 
                                name="admin_response" 
                                placeholder="Type your response here... This will be visible to the user."
                                maxlength="2000"
                                required
                            ></textarea>
                            <div class="char-counter">
                                <span id="charCount">0</span> / 2000 characters
                            </div>
                        </div>
                        <button type="submit" class="btn-submit-response">
                            <i class="fas fa-paper-plane"></i>
                            Send Response
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Right Column: Actions Sidebar -->
        <div class="ticket-actions-card">
            <!-- User Information -->
            <div class="user-info-card">
                <div class="user-info-title">Submitted By</div>
                
                <div class="user-info-item">
                    <div class="user-info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-info-content">
                        <div class="user-info-label">Name</div>
                        <div class="user-info-value">{{ $ticket->user->name }}</div>
                    </div>
                </div>

                <div class="user-info-item">
                    <div class="user-info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="user-info-content">
                        <div class="user-info-label">Email</div>
                        <div class="user-info-value">{{ $ticket->user->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Change Status -->
            <div class="action-section">
                <h3 class="action-section-title">
                    <i class="fas fa-tasks"></i>
                    Update Status
                </h3>
                <div class="status-buttons">
                    @if($ticket->status !== 'Open')
                        <form action="{{ route('admin.support.update-status', $ticket->id) }}" method="POST" class="status-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Open">
                            <button type="submit" class="btn-status btn-status-open">
                                <i class="fas fa-envelope-open"></i>
                                Mark as Open
                            </button>
                        </form>
                    @endif

                    @if($ticket->status !== 'Pending')
                        <form action="{{ route('admin.support.update-status', $ticket->id) }}" method="POST" class="status-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Pending">
                            <button type="submit" class="btn-status btn-status-pending">
                                <i class="fas fa-clock"></i>
                                Mark as Pending
                            </button>
                        </form>
                    @endif

                    @if($ticket->status !== 'Closed')
                        <form action="{{ route('admin.support.update-status', $ticket->id) }}" method="POST" class="status-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Closed">
                            <button type="submit" class="btn-status btn-status-closed">
                                <i class="fas fa-check-circle"></i>
                                Mark as Closed
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Delete Ticket -->
            <div class="action-section">
                <h3 class="action-section-title">
                    <i class="fas fa-trash-alt"></i>
                    Danger Zone
                </h3>
                <form action="{{ route('admin.support.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete-ticket">
                        <i class="fas fa-trash"></i>
                        Delete Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Character counter
    const textarea = document.getElementById('admin_response');
    const charCount = document.getElementById('charCount');

    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            // Change color when approaching limit
            if (count > 1800) {
                charCount.style.color = '#ef4444';
            } else if (count > 1500) {
                charCount.style.color = '#f59e0b';
            } else {
                charCount.style.color = '#6b7280';
            }
        });
    }
</script>
@endsection