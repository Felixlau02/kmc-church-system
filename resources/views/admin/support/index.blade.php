@extends('layouts.admin')

@section('content')
<style>
    .support-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-content h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.75rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        font-weight: 400;
    }

    .success-alert, .error-alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        animation: slideDown 0.3s ease;
    }

    .success-alert {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .error-alert {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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

    .filters-form {
        display: flex;
        gap: 1rem;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .filter-input, .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: white;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #667eea;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .tickets-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tickets-table {
        width: 100%;
        border-collapse: collapse;
    }

    .tickets-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: white;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #667eea;
    }

    .tickets-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
    }

    .tickets-table tr:hover {
        background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    .ticket-user {
        font-weight: 600;
        color: #667eea;
    }

    .ticket-subject {
        font-weight: 600;
        color: #764ba2;
        margin-bottom: 0.25rem;
    }

    .ticket-preview {
        color: #9ca3af;
        font-size: 0.875rem;
    }

    .ticket-status {
        padding: 0.35rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }

    .status-open {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .status-pending {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .status-closed {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
    }

    .ticket-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.4rem 0.875rem;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
        font-size: 0.8rem;
    }

    .btn-view {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-view:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #6b7280;
    }

    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 2px solid #e5e7eb;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1.5rem;
        }

        .header-content h2 {
            font-size: 1.75rem;
        }

        .tickets-table {
            display: block;
            overflow-x: auto;
        }

        .filters-form {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }
    }
</style>

<div class="support-container">
    <div class="page-header">
        <div class="header-content">
            <h2>
                <span class="header-icon">
                    <i class="fas fa-headset"></i>
                </span>
                Support Message Management
            </h2>
            <p class="header-subtitle">View and manage all user support requests</p>
        </div>
    </div>

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

    <div class="tickets-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i>
                All Support Messages
            </h3>
        </div>

        <div style="padding: 1.5rem; background: #fafbfc; border-bottom: 2px solid #e5e7eb;">
            <form action="{{ route('admin.support.index') }}" method="GET" class="filters-form">
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input 
                        type="text" 
                        name="search" 
                        class="filter-input" 
                        placeholder="Search by subject or message..."
                        value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if($tickets->count() > 0)
            <table class="tickets-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Subject & Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>
                                <div class="ticket-user">{{ $ticket->user->name }}</div>
                                <div style="font-size: 0.8rem; color: #9ca3af;">{{ $ticket->user->email }}</div>
                            </td>
                            <td>
                                <div class="ticket-subject">{{ $ticket->subject }}</div>
                                <div class="ticket-preview">{{ Str::limit($ticket->message, 80) }}</div>
                            </td>
                            <td>
                                <span class="ticket-status status-{{ strtolower($ticket->status) }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.8rem; color: #9ca3af;">{{ $ticket->created_at->format('g:i A') }}</div>
                            </td>
                            <td>
                                <div class="ticket-actions">
                                    <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn-action btn-view">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <form action="{{ route('admin.support.destroy', $ticket->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 class="empty-title">No Support Tickets Found</h3>
                <p class="empty-text">There are no support tickets matching your criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection