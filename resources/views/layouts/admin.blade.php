<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KMC Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .church-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            border: 4px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            object-fit: cover;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .church-logo:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
        }

        .church-name {
            color: white;
            font-size: 1.125rem;
            font-weight: 700;
            line-height: 1.4;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0 1rem;
            margin-bottom: 0.75rem;
            margin-top: 1rem;
        }

        .nav-section-title:first-child {
            margin-top: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 0.375rem;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: white;
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .main-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            background: white;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .hamburger-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: none;
        }

        .hamburger-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .topbar-greeting {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .topbar-subtext {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-menu {
            position: relative;
        }

        .notification-btn {
            position: relative;
            background: #f1f5f9;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #64748b;
            font-size: 1.25rem;
        }

        .notification-btn:hover {
            background: #e2e8f0;
            color: #3b82f6;
            transform: scale(1.05);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .notification-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 0.75rem);
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            width: 380px;
            max-height: 500px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

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

        .notification-footer {
            padding: 0.875rem 1.25rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .view-all-notifications {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .view-all-notifications:hover {
            color: #2563eb;
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

        .user-menu {
            position: relative;
        }

        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #1e293b;
        }

        .user-menu-btn:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .user-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 0.75rem);
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: #334155;
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .user-dropdown-item:hover {
            background: #f8fafc;
            color: #3b82f6;
        }

        .user-dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0.5rem 0;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 100%;
        }

        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            padding: 1.5rem;
            font-size: 0.875rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        .logo-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s ease;
        }

        .logo-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            animation: zoomIn 0.3s ease;
        }

        .logo-modal-image {
            width: auto;
            height: auto;
            max-width: 90vw;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            display: block;
        }

        .logo-modal-close {
            position: absolute;
            top: -50px;
            right: 0;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            transition: all 0.3s ease;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .logo-modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(90deg);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .hamburger-btn {
                display: block;
            }

            .topbar {
                padding: 1rem;
            }

            .topbar-greeting {
                font-size: 1rem;
            }

            .topbar-subtext {
                display: none;
            }

            .main-content {
                padding: 1rem;
            }

            .user-menu-btn {
                padding: 0.5rem 1rem;
            }

            .user-name {
                display: none;
            }

            .notification-dropdown {
                width: 320px;
                right: -50px;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content > * {
            animation: slideIn 0.5s ease;
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/church.jpg') }}" class="church-logo" alt="Church Logo" id="churchLogo">
            <h2 class="church-name">KingFisher Methodist Church</h2>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section-title">Main Menu</div>
            <a href="{{ route('admin.home') }}" class="nav-link {{ request()->is('admin/home') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.member.index') }}" class="nav-link {{ request()->is('admin/member*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <span>Member</span>
            </a>
            <a href="{{ route('admin.roombooking.index') }}" class="nav-link {{ request()->is('admin/roombooking*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-door-open"></i>
                <span>Room Booking</span>
            </a>
            
            <div class="nav-section-title">Activities</div>
            <a href="{{ route('admin.event.index') }}" class="nav-link {{ request()->is('admin/event*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <span>Event</span>
            </a>
            <a href="{{ route('admin.sermon.index') }}" class="nav-link {{ request()->is('admin/sermon*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-microphone"></i>
                <span>Sermon</span>
            </a>
            <a href="{{ route('admin.volunteer.index') }}" class="nav-link {{ request()->is('admin/volunteer*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hands-helping"></i>
                <span>Volunteer Work</span>
            </a>
            
            <div class="nav-section-title">Management</div>
            <a href="{{ route('admin.donation.index') }}" class="nav-link {{ request()->is('admin/donation*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hand-holding-usd"></i>
                <span>Donation</span>
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->is('admin/attendance*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <span>Attendance</span>
            </a>
            <a href="{{ route('admin.support.index') }}" class="nav-link {{ request()->is('admin/support*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-question-circle"></i>
                <span>Help & Support</span>
            </a>
        </nav>
    </aside>

    <div class="main-wrapper" id="mainWrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" id="hamburgerBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <div class="topbar-greeting">Good morning, Admin!</div>
                    <div class="topbar-subtext">Welcome to KMC Management System</div>
                </div>
            </div>

            <div class="topbar-right">
                <div class="notification-menu">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        @php
                            $unreadCount = App\Models\Notification::active()
                                ->forUser(Auth::id())
                                ->unreadBy(Auth::id())
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="notification-badge" id="notificationBadge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <span class="notification-title">Notifications</span>
                            @if($unreadCount > 0)
                                <form action="{{ route('admin.notification.markAllAsRead') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="mark-all-read">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div class="notification-list" id="notificationList">
                            @php
                                $notifications = App\Models\Notification::active()
                                    ->forUser(Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            
                            @forelse($notifications as $notification)
                                <a href="{{ route('admin.notification.markAsRead', $notification->id) }}" 
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
                    </div>
                </div>

                <div class="user-menu">
                    <button class="user-menu-btn" id="userMenuBtn">
                        <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('admin.profile.show') }}" class="user-dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>My Profile</span>
                        </a>
                        <div class="user-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-dropdown-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="footer">
            <div>&copy; {{ now()->year }} KingFisher Methodist Church. All rights reserved.</div>
        </footer>
    </div>

    <!-- Logo Modal -->
    <div class="logo-modal" id="logoModal">
        <div class="logo-modal-content">
            <button class="logo-modal-close" id="closeLogoModal">&times;</button>
            <img src="{{ asset('images/church.jpg') }}" alt="KingFisher Methodist Church Logo" class="logo-modal-image" id="modalImage">
        </div>
    </div>

    <script>
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        hamburgerBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });

        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        notificationBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            userDropdown?.classList.remove('show');
        });

        userMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            notificationDropdown?.classList.remove('show');
        });

        document.addEventListener('click', (e) => {
            if (!notificationBtn?.contains(e.target) && !notificationDropdown?.contains(e.target)) {
                notificationDropdown?.classList.remove('show');
            }
            if (!userMenuBtn?.contains(e.target)) {
                userDropdown?.classList.remove('show');
            }
        });

        function updateGreeting() {
            const hour = new Date().getHours();
            const greetingEl = document.querySelector('.topbar-greeting');
            let greeting = 'Good morning';
            
            if (hour >= 12 && hour < 17) {
                greeting = 'Good afternoon';
            } else if (hour >= 17) {
                greeting = 'Good evening';
            }
            
            if (greetingEl) {
                greetingEl.textContent = `${greeting}, Admin!`;
            }
        }

        updateGreeting();

        function refreshNotificationCount() {
            fetch('{{ route("admin.notification.count") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                const notificationBtn = document.getElementById('notificationBtn');
                
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        badge.style.display = 'flex';
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'notification-badge';
                        newBadge.id = 'notificationBadge';
                        newBadge.textContent = data.count > 9 ? '9+' : data.count;
                        notificationBtn.appendChild(newBadge);
                    }
                } else {
                    if (badge) {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error refreshing notifications:', error));
        }

        // Auto-refresh notifications when something is deleted
        @if(session('notification_deleted'))
            setTimeout(() => {
                refreshNotificationCount();
                // Reload the notification dropdown content
                window.location.reload();
            }, 500);
        @endif

        // Also refresh when notification is created
        @if(session('notification_created'))
            setTimeout(() => {
                refreshNotificationCount();
                // Reload page to show new notification in list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }, 500);
        @endif

        // Periodic refresh every 30 seconds
        setInterval(refreshNotificationCount, 30000);

        // Logo Modal Functionality
        const churchLogo = document.getElementById('churchLogo');
        const logoModal = document.getElementById('logoModal');
        const closeLogoModal = document.getElementById('closeLogoModal');
        const modalImage = document.getElementById('modalImage');

        churchLogo?.addEventListener('click', () => {
            // Ensure the modal image source matches the clicked logo
            modalImage.src = churchLogo.src;
            logoModal.classList.add('show');
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        });

        closeLogoModal?.addEventListener('click', () => {
            logoModal.classList.remove('show');
            document.body.style.overflow = '';
        });

        logoModal?.addEventListener('click', (e) => {
            if (e.target === logoModal) {
                logoModal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && logoModal.classList.contains('show')) {
                logoModal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>