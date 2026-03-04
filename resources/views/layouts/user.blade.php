<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KMC User Dashboard</title>
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

        /* Sidebar Styles */
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

        .sidebar-hidden {
            transform: translateX(-100%);
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

        /* Main Content Area */
        .main-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .main-wrapper.sidebar-closed {
            margin-left: 0;
        }

        /* Topbar */
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

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
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

        /* Notification Bell Button */
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

        /* Notification Dropdown - Basic Structure */
        #notificationDropdown {
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
            z-index: 1000;
        }

        #notificationDropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* User Dropdown */
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

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 100%;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            padding: 1.5rem;
            font-size: 0.875rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Overlay for mobile */
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

        /* Responsive */
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

            #notificationDropdown {
                width: 320px;
                right: -50px;
            }
        }

        /* Animations */
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
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/church.jpg') }}" class="church-logo" alt="Church Logo">
            <h2 class="church-name">KingFisher Methodist Church</h2>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section-title">Main Menu</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('user.member.index') }}" class="nav-link {{ request()->is('user/member*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <span>Member</span>
            </a>
            <a href="{{ route('user.roombooking.index') }}" class="nav-link {{ request()->is('user/roombooking*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-door-open"></i>
                <span>Room Booking</span>
            </a>
            
            <div class="nav-section-title">Activities</div>
            <a href="{{ route('user.event.index') }}" class="nav-link {{ request()->is('user/event*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
            <a href="{{ route('user.sermon.index') }}" class="nav-link {{ request()->is('user/sermon*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-microphone"></i>
                <span>Sermons</span>
            </a>
            <a href="{{ route('user.volunteer.index') }}" class="nav-link {{ request()->is('user/volunteer*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hands-helping"></i>
                <span>Volunteer Work</span>
            </a>
            
            <div class="nav-section-title">Management</div>
            <a href="{{ route('user.donation.index') }}" class="nav-link {{ request()->is('user/donation*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hand-holding-usd"></i>
                <span>Donations</span>
            </a>
            <a href="{{ route('user.attendance.index') }}" class="nav-link {{ request()->is('user/attendance*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <span>Attendance</span>
            </a>
            <a href="{{ route('user.support.index') }}" class="nav-link {{ request()->is('user/support*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-question-circle"></i>
                <span>Help & Support</span>
            </a>
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" id="hamburgerBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <div class="topbar-greeting">Good morning, {{ auth()->user()->name }}!</div>
                    <div class="topbar-subtext">Welcome to KMC Management System</div>
                </div>
            </div>

            <div class="topbar-right">
                <!-- Notification Bell Button -->
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
                    <!-- Dropdown content will be loaded here via partial -->
                    <div id="notificationDropdown">
                        @include('user.notification.dropdown')
                    </div>
                </div>

                <!-- User Menu -->
                <div class="user-menu">
                    <button class="user-menu-btn" id="userMenuBtn">
                        <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('user.profile.show') }}" class="user-dropdown-item">
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

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div>&copy; {{ now()->year }} KingFisher Methodist Church. All rights reserved.</div>
        </footer>
    </div>

    <script>
        // Sidebar toggle
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

        // Notification dropdown toggle
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

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!notificationBtn?.contains(e.target) && !notificationDropdown?.contains(e.target)) {
                notificationDropdown?.classList.remove('show');
            }
            if (!userMenuBtn?.contains(e.target)) {
                userDropdown?.classList.remove('show');
            }
        });

        // Dynamic greeting based on time
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
                greetingEl.textContent = `${greeting}, {{ auth()->user()->name }}!`;
            }
        }

        updateGreeting();

        // Auto-refresh notification badge count
        function refreshNotificationCount() {
            fetch('{{ route("user.notification.count") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                const bellBtn = document.getElementById('notificationBtn');
                
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        badge.style.display = 'flex';
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'notification-badge';
                        newBadge.id = 'notificationBadge';
                        newBadge.textContent = data.count > 9 ? '9+' : data.count;
                        bellBtn.appendChild(newBadge);
                    }
                } else {
                    if (badge) {
                        badge.remove();
                    }
                }
            })
            .catch(error => console.error('Error refreshing notifications:', error));
        }

        // Refresh every 30 seconds
        setInterval(refreshNotificationCount, 30000);
    </script>
</body>
</html>