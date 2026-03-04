@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">
    <div class="ticket-detail-container">
        <!-- Header -->
        <div class="detail-header">
            <a href="{{ route('user.support.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back to All Tickets
            </a>
            
            <div class="header-content">
                <div class="header-left">
                    <span class="ticket-id-badge">#ST-{{ $ticket->id }}</span>
                    <h1 class="ticket-title">{{ $ticket->subject }}</h1>
                    <div class="ticket-meta-bar">
                        <span class="meta-badge">
                            <i class="fas fa-calendar"></i>
                            Created {{ $ticket->created_at->format('M d, Y') }}
                        </span>
                        <span class="meta-badge">
                            <i class="fas fa-clock"></i>
                            {{ $ticket->created_at->format('g:i A') }}
                        </span>
                        <span class="meta-badge">
                            <i class="fas fa-sync-alt"></i>
                            Updated {{ $ticket->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <div class="header-right">
                    <span class="status-badge status-{{ strtolower($ticket->status) }}">
                        <i class="fas fa-circle"></i>
                        {{ $ticket->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="detail-grid">
            <!-- Ticket Message -->
            <div class="message-section">
                <div class="message-card">
                    <div class="message-header">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <h3 class="user-name">{{ $ticket->user->name }}</h3>
                                <p class="user-role">You</p>
                            </div>
                        </div>
                        <span class="message-time">
                            {{ $ticket->created_at->format('M d, Y \a\t g:i A') }}
                        </span>
                    </div>
                    <div class="message-body">
                        <h4 class="message-subject">
                            <i class="fas fa-comment-dots"></i>
                            Original Request
                        </h4>
                        <div class="message-content">
                            {{ $ticket->message }}
                        </div>
                    </div>
                </div>

                <!-- Admin Response -->
                @if($ticket->admin_response)
                <div class="response-card">
                    <div class="response-header">
                        <div class="user-info">
                            <div class="user-avatar admin">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="user-details">
                                <h3 class="user-name">Support Team</h3>
                                <p class="user-role">Administrator</p>
                            </div>
                        </div>
                        <span class="message-time">
                            {{ $ticket->updated_at->format('M d, Y \a\t g:i A') }}
                        </span>
                    </div>
                    <div class="response-body">
                        <h4 class="response-subject">
                            <i class="fas fa-reply"></i>
                            Support Response
                        </h4>
                        <div class="response-content">
                            {{ $ticket->admin_response }}
                        </div>
                    </div>
                </div>
                @else
                <div class="waiting-response">
                    <div class="waiting-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <h3>Waiting for Response</h3>
                    <p>Our support team will review your request and respond shortly.</p>
                </div>
                @endif

                <!-- Actions -->
                <div class="ticket-actions-section">
                    <form action="{{ route('user.support.destroy', $ticket->id) }}" method="POST" onsubmit="return confirmDelete()" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete-full">
                            <i class="fas fa-trash-alt"></i>
                            Delete This Ticket
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar-section">
                <!-- Ticket Information -->
                <div class="info-card">
                    <h3 class="info-title">
                        <i class="fas fa-info-circle"></i>
                        Ticket Information
                    </h3>
                    <div class="info-items">
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="status-indicator status-{{ strtolower($ticket->status) }}">
                                {{ $ticket->status }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ticket ID</span>
                            <span class="info-value">#ST-{{ $ticket->id }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Created</span>
                            <span class="info-value">{{ $ticket->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">{{ $ticket->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Submitted By</span>
                            <span class="info-value">{{ $ticket->user->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Status Guide -->
                <div class="status-guide-card">
                    <h3 class="guide-title">
                        <i class="fas fa-question-circle"></i>
                        Status Guide
                    </h3>
                    <div class="status-items">
                        <div class="status-item">
                            <span class="status-dot open"></span>
                            <div>
                                <h4>Open</h4>
                                <p>Your ticket has been received and is awaiting review</p>
                            </div>
                        </div>
                        <div class="status-item">
                            <span class="status-dot pending"></span>
                            <div>
                                <h4>Pending</h4>
                                <p>Our team has responded and is working on your issue</p>
                            </div>
                        </div>
                        <div class="status-item">
                            <span class="status-dot closed"></span>
                            <div>
                                <h4>Closed</h4>
                                <p>Your issue has been resolved</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Need More Help -->
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Need More Help?</h3>
                    <p>For urgent matters, contact us directly</p>
                    <div class="contact-buttons">
                        <a href="https://wa.me/60109602422" target="_blank" class="contact-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp Support
                        </a>
                        <a href="mailto:admin@example.com" class="contact-btn">
                            <i class="fas fa-envelope"></i>
                            Email Us
                        </a>
                    </div>
                </div>
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

    .ticket-detail-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Header */
    .detail-header {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 2rem;
    }

    .ticket-id-badge {
        display: inline-block;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .ticket-title {
        font-size: 2.25rem;
        font-weight: 900;
        color: #1e293b;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .ticket-meta-bar {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .meta-badge i {
        color: #667eea;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        white-space: nowrap;
    }

    .status-badge i {
        font-size: 0.6rem;
    }

    .status-badge.status-open {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .status-badge.status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .status-badge.status-closed {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    /* Grid Layout */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }

    /* Message Section */
    .message-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .message-card, .response-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .response-card {
        border-left: 5px solid #667eea;
    }

    .message-header, .response-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .user-avatar.admin {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .user-name {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .user-role {
        color: #64748b;
        font-size: 0.875rem;
        margin: 0;
    }

    .message-time {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .message-subject, .response-subject {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .message-subject i, .response-subject i {
        color: #667eea;
    }

    .message-content, .response-content {
        color: #475569;
        line-height: 1.8;
        font-size: 1.05rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 12px;
        border-left: 4px solid #667eea;
    }

    .response-content {
        border-left-color: #10b981;
    }

    /* Waiting Response */
    .waiting-response {
        background: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .waiting-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .waiting-response h3 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }

    .waiting-response p {
        color: #64748b;
        font-size: 1.05rem;
        line-height: 1.6;
    }

    /* Ticket Actions */
    .ticket-actions-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .delete-form {
        display: flex;
        justify-content: center;
    }

    .btn-delete-full {
        padding: 1rem 2rem;
        border-radius: 12px;
        border: 2px solid #ef4444;
        background: white;
        color: #ef4444;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.05rem;
    }

    .btn-delete-full:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
    }

    /* Sidebar */
    .sidebar-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .info-card, .status-guide-card, .help-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .info-title, .guide-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-title i, .guide-title i {
        color: #667eea;
    }

    .info-items {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .info-value {
        color: #1e293b;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .status-indicator {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-indicator.status-open {
        background: #d1fae5;
        color: #065f46;
    }

    .status-indicator.status-pending {
        background: #fed7aa;
        color: #92400e;
    }

    .status-indicator.status-closed {
        background: #e5e7eb;
        color: #374151;
    }

    /* Status Guide */
    .status-items {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .status-item {
        display: flex;
        align-items: start;
        gap: 1rem;
    }

    .status-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .status-dot.open {
        background: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .status-dot.pending {
        background: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
    }

    .status-dot.closed {
        background: #6b7280;
        box-shadow: 0 0 0 4px rgba(107, 114, 128, 0.2);
    }

    .status-item h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.25rem 0;
    }

    .status-item p {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.6;
        margin: 0;
    }

    /* Help Card */
    .help-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
    }

    .help-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        font-size: 2rem;
    }

    .help-card h3 {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
    }

    .help-card p {
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .contact-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .contact-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 0.875rem 1.5rem;
        background: white;
        color: #667eea;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .contact-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .sidebar-section {
            grid-row: 2;
        }
    }

    @media (max-width: 768px) {
        .detail-header {
            padding: 2rem 1.5rem;
        }

        .header-content {
            flex-direction: column;
        }

        .ticket-title {
            font-size: 1.75rem;
        }

        .ticket-meta-bar {
            flex-direction: column;
            gap: 0.75rem;
        }

        .message-card, .response-card {
            padding: 1.5rem;
        }

        .message-header, .response-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this support ticket? This action cannot be undone.');
    }
</script>
@endsection