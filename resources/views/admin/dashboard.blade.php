@extends('layouts.admin')

@section('content')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .dashboard-header {
        margin-bottom: 2.5rem;
    }

    .welcome-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
        margin-bottom: 2rem;
    }

    .welcome-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.75rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .stat-card.members { border-left-color: #3b82f6; }
    .stat-card.bookings { border-left-color: #8b5cf6; }
    .stat-card.events { border-left-color: #10b981; }
    .stat-card.volunteers { border-left-color: #f59e0b; }
    .stat-card.sermons { border-left-color: #ec4899; }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-card.members .stat-icon { background: #dbeafe; color: #3b82f6; }
    .stat-card.bookings .stat-icon { background: #ede9fe; color: #8b5cf6; }
    .stat-card.events .stat-icon { background: #d1fae5; color: #10b981; }
    .stat-card.volunteers .stat-icon { background: #fef3c7; color: #f59e0b; }
    .stat-card.sermons .stat-icon { background: #fce7f3; color: #ec4899; }

    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: #10b981;
    }

    /* Calendar Styles */
    .calendar-section {
        background: white;
        border-radius: 12px;
        padding: 1.75rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2.5rem;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .calendar-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .calendar-legend {
        display: flex;
        gap: 1.5rem;
        font-size: 0.875rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .legend-color.event { background: #10b981; }
    .legend-color.booking { background: #3b82f6; }

    #calendar {
        margin-top: 1rem;
    }

    /* FullCalendar Customization */
    .fc {
        font-family: inherit;
    }

    .fc .fc-button-primary {
        background-color: #667eea;
        border-color: #667eea;
    }

    .fc .fc-button-primary:hover {
        background-color: #764ba2;
        border-color: #764ba2;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #764ba2;
        border-color: #764ba2;
    }

    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        font-size: 0.813rem;
        padding: 2px 4px;
    }
    
    /* Hide event time prefix */
    .fc-event-time {
        display: none;
    }

    /* Event Detail Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        flex: 1;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .modal-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .modal-badge.event {
        background: #d1fae5;
        color: #065f46;
    }

    .modal-badge.booking {
        background: #dbeafe;
        color: #1e40af;
    }

    .modal-body {
        color: #64748b;
    }

    .modal-info {
        margin-bottom: 1rem;
    }

    .modal-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 1.75rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-all-link {
        color: #667eea;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: color 0.2s;
    }

    .view-all-link:hover {
        color: #764ba2;
    }

    .booking-item {
        padding: 1rem;
        border-left: 3px solid;
        margin-bottom: 1rem;
        border-radius: 6px;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .booking-item:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .booking-item.pending { border-left-color: #f59e0b; background: #fffbeb; }
    .booking-item.approved { border-left-color: #10b981; background: #ecfdf5; }
    .booking-item.rejected { border-left-color: #ef4444; background: #fef2f2; }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .booking-room {
        font-weight: 600;
        color: #1e293b;
    }

    .booking-status {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .booking-status.pending { background: #fef3c7; color: #92400e; }
    .booking-status.approved { background: #d1fae5; color: #065f46; }
    .booking-status.rejected { background: #fee2e2; color: #991b1b; }

    .booking-info {
        font-size: 0.875rem;
        color: #64748b;
    }

    .event-item {
        padding: 1rem;
        border-radius: 8px;
        background: #f8fafc;
        margin-bottom: 1rem;
        display: flex;
        gap: 1rem;
        transition: all 0.2s;
    }

    .event-item:hover {
        background: #f1f5f9;
    }

    .event-date {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        padding: 0.75rem;
        text-align: center;
        min-width: 60px;
    }

    .event-day {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .event-month {
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-top: 0.25rem;
    }

    .event-details {
        flex: 1;
    }

    .event-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .event-time {
        font-size: 0.875rem;
        color: #64748b;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .action-btn {
        padding: 1rem;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: white;
        text-decoration: none;
        color: #1e293b;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        border-color: #667eea;
        background: #f8f9ff;
        transform: translateY(-2px);
    }

    .action-icon {
        font-size: 1.75rem;
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:nth-child(1) .action-icon { background: #dbeafe; color: #3b82f6; }
    .action-btn:nth-child(2) .action-icon { background: #ede9fe; color: #8b5cf6; }
    .action-btn:nth-child(3) .action-icon { background: #d1fae5; color: #10b981; }
    .action-btn:nth-child(4) .action-icon { background: #fef3c7; color: #f59e0b; }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .calendar-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .welcome-title {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .calendar-legend {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="dashboard-header">
        <div class="welcome-section">
            <h1 class="welcome-title">Good {{ now()->format('A') == 'AM' ? 'morning' : (now()->format('H') < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}! 👋</h1>
            <p class="welcome-subtitle">Welcome to KMC Management System - {{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card members">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Members</div>
                    <div class="stat-value">{{ $totalMembers ?? 0 }}</div>
                    <div class="stat-change positive">
                        <span>↑</span>
                        <span>+12% from last month</span>
                    </div>
                </div>
                <div class="stat-icon">👥</div>
            </div>
        </div>

        <div class="stat-card bookings">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Room Bookings</div>
                    <div class="stat-value">{{ $totalBookings ?? 0 }}</div>
                    <div class="stat-change positive">
                        <span>↑</span>
                        <span>{{ $pendingBookings ?? 0 }} pending</span>
                    </div>
                </div>
                <div class="stat-icon">📅</div>
            </div>
        </div>

        <div class="stat-card events">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Upcoming Events</div>
                    <div class="stat-value">{{ $upcomingEvents ?? 0 }}</div>
                    <div class="stat-change positive">
                        <span>↑</span>
                        <span>{{ $thisWeekEvents ?? 0 }} this week</span>
                    </div>
                </div>
                <div class="stat-icon">🎉</div>
            </div>
        </div>

        <div class="stat-card volunteers">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Active Volunteers</div>
                    <div class="stat-value">{{ $activeVolunteers ?? 0 }}</div>
                    <div class="stat-change positive">
                        <span>↑</span>
                        <span>{{ $newVolunteers ?? 0 }} new this month</span>
                    </div>
                </div>
                <div class="stat-icon">🤝</div>
            </div>
        </div>

        <div class="stat-card sermons">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Sermons</div>
                    <div class="stat-value">{{ $totalSermons ?? 0 }}</div>
                    <div class="stat-change positive">
                        <span>↑</span>
                        <span>{{ $newSermons ?? 0 }} new this month</span>
                    </div>
                </div>
                <div class="stat-icon">🎤</div>
            </div>
        </div>
    </div>

    <!-- Church Calendar -->
    <div class="calendar-section">
        <div class="calendar-header">
            <h2 class="calendar-title">
                <span>📆</span>
                Church Calendar
            </h2>
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color event"></div>
                    <span>Events</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color booking"></div>
                    <span>Room Bookings</span>
                </div>
            </div>
        </div>
        <div id="calendar"></div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal-overlay" id="eventModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle"></h3>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        // Get calendar events from Laravel
        const calendarEvents = {!! $calendarEvents ?? '[]' !!};

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: calendarEvents,
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            eventMouseEnter: function(info) {
                info.el.style.cursor = 'pointer';
            },
            height: 'auto',
            contentHeight: 600,
            aspectRatio: 1.8,
            displayEventTime: false
        });

        calendar.render();

        // Function to show event details in modal
        window.showEventDetails = function(event) {
            const modal = document.getElementById('eventModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');

            modalTitle.textContent = event.title;

            let bodyContent = '';
            
            if (event.extendedProps.type === 'event') {
                bodyContent = `
                    <span class="modal-badge event">Church Event</span>
                    <div class="modal-info">
                        <div class="modal-label">Date & Time</div>
                        <div>${formatDate(event.start)} - ${formatTime(event.start)} to ${formatTime(event.end)}</div>
                    </div>
                    <div class="modal-info">
                        <div class="modal-label">Location</div>
                        <div>${event.extendedProps.location}</div>
                    </div>
                    <div class="modal-info">
                        <div class="modal-label">Event Type</div>
                        <div>${event.extendedProps.eventType}</div>
                    </div>
                    ${event.extendedProps.description ? `
                    <div class="modal-info">
                        <div class="modal-label">Description</div>
                        <div>${event.extendedProps.description}</div>
                    </div>
                    ` : ''}
                `;
            } else {
                bodyContent = `
                    <span class="modal-badge booking">Room Booking</span>
                    <div class="modal-info">
                        <div class="modal-label">Room</div>
                        <div>${event.extendedProps.room}</div>
                    </div>
                    <div class="modal-info">
                        <div class="modal-label">Booked By</div>
                        <div>${event.extendedProps.memberName}</div>
                    </div>
                    <div class="modal-info">
                        <div class="modal-label">Date & Time</div>
                        <div>${formatDate(event.start)} - ${formatTime(event.start)} to ${formatTime(event.end)}</div>
                    </div>
                    ${event.extendedProps.description ? `
                    <div class="modal-info">
                        <div class="modal-label">Purpose</div>
                        <div>${event.extendedProps.description}</div>
                    </div>
                    ` : ''}
                `;
            }

            modalBody.innerHTML = bodyContent;
            modal.classList.add('active');
        };

        window.closeModal = function() {
            document.getElementById('eventModal').classList.remove('active');
        };

        // Close modal when clicking outside
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Helper functions
        function formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function formatTime(date) {
            return new Date(date).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }
    });
</script>
@endsection