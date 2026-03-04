@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-left">
            <h1 class="hero-title">
                <i class="fas fa-life-ring hero-icon"></i>
                Help & Support Center
            </h1>
        </div>
        <div class="hero-actions">
            <a href="{{ route('user.support.create') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
                Submit New Request
            </a>
        </div>
    </div>

    <!-- Success/Error Alert -->
    @if(session('success'))
    <div class="alert alert-success" id="successAlert">
        <i class="fas fa-check-circle"></i>
        <div>
            <strong>Success!</strong><br>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error" id="errorAlert">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Error!</strong><br>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Main Grid -->
    <div class="main-grid">
        <!-- Tickets Section -->
        <div class="tickets-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-ticket-alt"></i>
                    My Support Requests
                </h3>
            </div>
            <div class="tickets-list">
                @forelse($tickets as $ticket)
                <div class="ticket-item">
                    <div class="ticket-top">
                        <span class="ticket-id">#ST-{{ $ticket->id }}</span>
                        <span class="ticket-status status-{{ strtolower($ticket->status) }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                    
                    <h4 class="ticket-subject">{{ $ticket->subject }}</h4>
                    
                    <p class="ticket-message">
                        {{ Str::limit($ticket->message, 120) }}
                    </p>
                    
                    <div class="ticket-footer">
                        <div class="ticket-meta">
                            <span class="meta-item">
                                <i class="fas fa-calendar"></i>
                                {{ $ticket->created_at->format('M d, Y') }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-clock"></i>
                                {{ $ticket->created_at->format('g:i A') }}
                            </span>
                        </div>
                        <div class="ticket-actions">
                            <a href="{{ route('user.support.show', $ticket->id) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            <form action="{{ route('user.support.destroy', $ticket->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="empty-title">No Support Requests Yet</h3>
                    <p class="empty-text">
                        You haven't submitted any support requests.<br>
                        Need help? Create your first ticket now!
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
    }

    .container-fluid {
        max-width: 100%;
        margin: 0 auto;
        padding: 0 !important;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .hero-left {
        flex: 1;
    }

    .hero-title {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .hero-icon {
        font-size: 2rem;
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: white;
        color: #667eea;
        box-shadow: 0 4px 6px rgba(255, 255, 255, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 255, 255, 0.4);
    }

    /* Alert */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.4s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .alert i {
        font-size: 1.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Main Grid */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Tickets Section */
    .tickets-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .tickets-list {
        padding: 1.5rem;
    }

    .ticket-item {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .ticket-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .ticket-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .ticket-id {
        display: inline-block;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .ticket-subject {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.75rem 0;
    }

    .ticket-status {
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .status-open {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .status-closed {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }

    .ticket-message {
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }

    .ticket-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .ticket-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .meta-item i {
        font-size: 0.85rem;
    }

    .ticket-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .btn-view {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-view:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-title {
            font-size: 1.5rem;
        }

        .hero-actions {
            width: 100%;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .ticket-top {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .ticket-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .ticket-actions {
            width: 100%;
        }

        .btn-action {
            flex: 1;
        }
    }
</style>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this support request? This action cannot be undone.');
    }

    // Auto-hide alerts after 5 seconds
    window.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 5000);
        }
        
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.display = 'none';
            }, 5000);
        }
    });
</script>
@endsection