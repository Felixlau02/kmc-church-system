@php
    $notifications = App\Models\Notification::active()
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    $unreadCount = App\Models\Notification::active()
        ->unreadBy(Auth::id())
        ->count();
@endphp

<style>
    .notification-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-title {
        font-weight: 700;
        font-size: 1rem;
        color: #1e293b;
    }

    .mark-all-read {
        background: none;
        border: none;
        color: #3b82f6;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .mark-all-read:hover {
        background: #eff6ff;
    }

    .notification-list {
        overflow-y: auto;
        max-height: 400px;
    }

    .notification-list::-webkit-scrollbar {
        width: 6px;
    }

    .notification-list::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .notification-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .notification-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        gap: 0.875rem;
        position: relative;
        text-decoration: none;
        color: inherit;
    }

    .notification-item:hover {
        background: #f8fafc;
    }

    .notification-item.unread {
        background: #eff6ff;
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #3b82f6;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .notification-icon.info {
        background: #dbeafe;
        color: #3b82f6;
    }

    .notification-icon.success {
        background: #dcfce7;
        color: #22c55e;
    }

    .notification-icon.warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .notification-icon.error {
        background: #fee2e2;
        color: #ef4444;
    }

    .notification-content {
        flex: 1;
    }

    .notification-text {
        font-size: 0.875rem;
        color: #334155;
        line-height: 1.5;
        margin-bottom: 0.25rem;
    }

    .notification-text strong {
        font-weight: 600;
        color: #1e293b;
    }

    .notification-text p {
        margin: 0.25rem 0 0 0;
        color: #64748b;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .no-notifications {
        padding: 3rem 1.25rem;
        text-align: center;
        color: #94a3b8;
    }

    .no-notifications i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        display: block;
    }

    /* Modal Styles */
    .notification-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
        animation: fadeIn 0.2s ease;
    }

    .notification-modal-overlay.show {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .notification-modal {
        background: white;
        border-radius: 16px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }

    .modal-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .modal-icon.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
    }

    .modal-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .modal-icon.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }

    .modal-icon.error {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .modal-header-content {
        flex: 1;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 0.25rem 0;
    }

    .modal-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.813rem;
        color: #9ca3af;
    }

    .modal-close-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .modal-close-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .modal-body {
        padding: 2rem;
        overflow-y: auto;
        flex: 1;
    }

    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .modal-message {
        font-size: 1rem;
        line-height: 1.8;
        color: #334155;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .modal-footer {
        padding: 1rem 2rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }

    .modal-created-by {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .modal-created-by i {
        color: #9ca3af;
        margin-right: 0.5rem;
    }

    .modal-mark-read-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .modal-mark-read-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .modal-mark-read-btn:disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    @media (max-width: 640px) {
        .notification-modal {
            max-width: 100%;
            margin: 0;
            border-radius: 12px;
        }

        .modal-header {
            padding: 1rem 1.25rem;
        }

        .modal-body {
            padding: 1.5rem 1.25rem;
        }

        .modal-footer {
            padding: 0.875rem 1.25rem;
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }

        .modal-mark-read-btn {
            width: 100%;
        }
    }
</style>

<div class="notification-header">
    <span class="notification-title">Notifications</span>
    @if($unreadCount > 0)
        <form action="{{ route('user.notification.markAllAsRead') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="mark-all-read">Mark all read</button>
        </form>
    @endif
</div>

<div class="notification-list">
    @forelse($notifications as $notification)
        <a href="{{ route('user.notification.markAsRead', $notification->id) }}" 
           class="notification-item {{ !$notification->isReadBy(Auth::id()) ? 'unread' : '' }}">
            <div class="notification-icon {{ $notification->type }}">
                <i class="fas {{ $notification->icon }}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text">
                    <strong>{{ $notification->title }}</strong>
                    <p>{{ Str::limit($notification->message, 60) }}</p>
                </div>
                <div class="notification-time">{{ $notification->time_ago }}</div>
            </div>
        </a>
    @empty
        <div class="no-notifications">
            <i class="fas fa-bell-slash"></i>
            <p>No notifications yet</p>
        </div>
    @endforelse
</div>

<!-- Notification Modal -->
<div class="notification-modal-overlay" id="notificationModal" onclick="closeNotificationModal(event)">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="modal-icon" id="modalIcon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="modal-header-content">
                <h3 class="modal-title" id="modalTitle">Notification Title</h3>
                <div class="modal-meta">
                    <span id="modalTime"><i class="fas fa-clock"></i> Loading...</span>
                    <span id="modalExpiry"></span>
                </div>
            </div>
            <button class="modal-close-btn" onclick="closeNotificationModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-message" id="modalMessage">Loading...</div>
        </div>
        <div class="modal-footer">
            <div class="modal-created-by" id="modalCreator">
                <i class="fas fa-user-circle"></i>Loading...
            </div>
            <form id="modalMarkReadForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="modal-mark-read-btn" id="modalMarkReadBtn">
                    <i class="fas fa-check me-1"></i>Mark as Read
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Store notification data
    const notificationData = {
    @foreach($notifications as $notification)
    {{ $notification->id }}: {
        id: {{ $notification->id }},
        type: '{{ $notification->type }}',
        title: {!! json_encode($notification->title) !!},  
        message: {!! json_encode($notification->message) !!},  
        icon: '{{ $notification->icon }}',
        time_ago: '{{ $notification->time_ago }}',
        created_by: {!! json_encode($notification->creator->name) !!},  
        expires_at: '{{ $notification->expires_at ? $notification->expires_at->format("M d, Y") : "" }}',
        is_read: {{ $notification->isReadBy(Auth::id()) ? 'true' : 'false' }}
    },
    @endforeach
    };

    function openNotificationModal(notificationId) {
        const data = notificationData[notificationId];
        if (!data) return;

        // Update modal content
        document.getElementById('modalTitle').textContent = data.title;
        document.getElementById('modalMessage').textContent = data.message;
        document.getElementById('modalTime').innerHTML = `<i class="fas fa-clock"></i> ${data.time_ago}`;
        document.getElementById('modalCreator').innerHTML = `<i class="fas fa-user-circle"></i>Created by ${data.created_by}`;
        
        // Update expiry
        if (data.expires_at) {
            document.getElementById('modalExpiry').innerHTML = `<i class="fas fa-hourglass-end"></i> Expires ${data.expires_at}`;
        } else {
            document.getElementById('modalExpiry').innerHTML = '';
        }

        // Update icon
        const modalIcon = document.getElementById('modalIcon');
        modalIcon.className = `modal-icon ${data.type}`;
        modalIcon.innerHTML = `<i class="fas ${data.icon}"></i>`;

        // Update mark as read button
        const markReadBtn = document.getElementById('modalMarkReadBtn');
        const markReadForm = document.getElementById('modalMarkReadForm');
        
        if (data.is_read) {
            markReadBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i>Already Read';
            markReadBtn.disabled = true;
        } else {
            markReadBtn.innerHTML = '<i class="fas fa-check me-1"></i>Mark as Read';
            markReadBtn.disabled = false;
            markReadForm.action = `{{ url('/notifications') }}/${data.id}/read`;
        }

        // Show modal
        document.getElementById('notificationModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeNotificationModal(event) {
        if (event && event.target !== event.currentTarget) return;
        
        document.getElementById('notificationModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNotificationModal();
        }
    });
</script>