@extends('layouts.user')

@section('content')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
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
        flex-wrap: wrap;
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

    #calendar {
        margin-top: 1rem;
    }

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
        transition: all 0.2s;
    }

    .fc-event:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
    
    .fc-event-time {
        display: none;
    }

    .fc-daygrid-event {
        white-space: normal !important;
    }

    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
    }

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

    .modal-badge.registered { background: #d1fae5; color: #065f46; }
    .modal-badge.available { background: #dbeafe; color: #1e40af; }
    .modal-badge.pending { background: #fef3c7; color: #92400e; }
    .modal-badge.approved { background: #dbeafe; color: #1e40af; }
    .modal-badge.rejected { background: #fee2e2; color: #991b1b; }

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

    .modal-actions {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 0.75rem;
    }

    .modal-btn {
        flex: 1;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-btn.register { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .modal-btn.register:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }
    .modal-btn.cancel { background: #fee2e2; color: #991b1b; }
    .modal-btn.cancel:hover { background: #ef4444; color: white; }
    .modal-btn.close { background: #e2e8f0; color: #475569; }
    .modal-btn.close:hover { background: #cbd5e1; }
    .modal-btn.archive { background: #fef3c7; color: #92400e; }
    .modal-btn.archive:hover { background: #fbbf24; color: white; }

    .quick-actions {
        background: linear-gradient(135deg, #f0f9ff 0%, #faf5ff 100%);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .quick-actions-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        text-decoration: none;
        color: #1e293b;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .action-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .action-btn:nth-child(1) .action-icon { background: #dbeafe; color: #3b82f6; }
    .action-btn:nth-child(2) .action-icon { background: #ede9fe; color: #8b5cf6; }
    .action-btn:nth-child(3) .action-icon { background: #d1fae5; color: #10b981; }
    .action-btn:nth-child(4) .action-icon { background: #fef3c7; color: #f59e0b; }

    .action-label {
        font-weight: 600;
        font-size: 0.9rem;
    }

    @media (max-width: 1024px) {
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

        .actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .calendar-legend {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1 class="welcome-title">Welcome Back, {{ auth()->user()->name }}!</h1>
        <p class="welcome-subtitle">Here's what's happening in your church community.</p>
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
                    <div class="legend-color" style="background: #10b981;"></div>
                    <span>Event</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #818cf8;"></div>
                    <span>Room Booking</span>
                </div>
            </div>
        </div>
        <div id="calendar"></div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3 class="quick-actions-title">
            <span>⚡</span>
            Quick Actions
        </h3>
        <div class="actions-grid">
            <a href="{{ route('user.roombooking.create') }}" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <span class="action-label">Book Room</span>
            </a>

            <a href="{{ route('user.event.index') }}" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <span class="action-label">Join Event</span>
            </a>

            <a href="{{ route('user.donation.index') }}" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <span class="action-label">Donate</span>
            </a>

            <a href="{{ route('user.volunteer.index') }}" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <span class="action-label">Volunteer</span>
            </a>
        </div>
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

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: '{{ route("user.dashboard.calendar-data") }}',
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                showModal(info.event);
            },
            height: 'auto',
            contentHeight: 600,
            aspectRatio: 1.8,
            displayEventTime: false
        });

        calendar.render();

        function showModal(event) {
            const modal = document.getElementById('eventModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            
            const itemType = event.extendedProps.itemType;
            
            if (itemType === 'event') {
                showEventModal(event, modalTitle, modalBody);
            } else if (itemType === 'booking') {
                showBookingModal(event, modalTitle, modalBody);
            }
            
            modal.classList.add('active');
        }

        function showEventModal(event, modalTitle, modalBody) {
            const isRegistered = event.extendedProps.isRegistered;
            
            modalTitle.innerHTML = '<i class="fas fa-calendar-alt" style="margin-right: 0.5rem;"></i>' + event.title;
            
            let bodyContent = `
                <span class="modal-badge ${isRegistered ? 'registered' : 'available'}">
                    ${isRegistered ? '✓ You are registered' : 'Available to register'}
                </span>
                <div class="modal-info">
                    <div class="modal-label"><i class="far fa-clock" style="margin-right: 0.25rem;"></i> Date & Time</div>
                    <div>${formatDate(event.start)} - ${formatTime(event.start)} to ${formatTime(event.end)}</div>
                </div>
                ${event.extendedProps.location ? `
                    <div class="modal-info">
                        <div class="modal-label"><i class="fas fa-map-marker-alt" style="margin-right: 0.25rem;"></i> Location</div>
                        <div>${event.extendedProps.location}</div>
                    </div>
                ` : ''}
                ${event.extendedProps.type ? `
                    <div class="modal-info">
                        <div class="modal-label"><i class="fas fa-tag" style="margin-right: 0.25rem;"></i> Event Type</div>
                        <div>${event.extendedProps.type}</div>
                    </div>
                ` : ''}
                ${event.extendedProps.description ? `
                    <div class="modal-info">
                        <div class="modal-label"><i class="fas fa-info-circle" style="margin-right: 0.25rem;"></i> Description</div>
                        <div>${event.extendedProps.description}</div>
                    </div>
                ` : ''}
                <div class="modal-info">
                    <div class="modal-label"><i class="fas fa-users" style="margin-right: 0.25rem;"></i> Registrations</div>
                    <div>${event.extendedProps.registrationCount} people registered</div>
                </div>
                <div class="modal-actions">
                    <button onclick="closeModal()" class="modal-btn close">Close</button>
                    ${isRegistered ? 
                        `<form action="/user/events/${event.extendedProps.eventId}/cancel" method="POST" style="flex: 1;" onsubmit="return confirm('Cancel registration?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="modal-btn cancel"><i class="fas fa-times-circle"></i> Cancel Registration</button>
                        </form>` :
                        `<form action="/user/events/${event.extendedProps.eventId}/register" method="POST" style="flex: 1;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="modal-btn register"><i class="fas fa-check-circle"></i> Register Now</button>
                        </form>`
                    }
                </div>`;
            
            modalBody.innerHTML = bodyContent;
        }

        function showBookingModal(event, modalTitle, modalBody) {
            const status = event.extendedProps.status;
            const duration = event.extendedProps.duration || 3;
            const endTime = event.extendedProps.bookingEndTime;
            
            modalTitle.innerHTML = '<i class="fas fa-door-open" style="margin-right: 0.5rem;"></i>' + event.extendedProps.room + ' Booking';
            
            let bodyContent = `
                <span class="modal-badge approved">
                    ✓ Approved
                </span>
                <div class="modal-info">
                    <div class="modal-label"><i class="far fa-calendar" style="margin-right: 0.25rem;"></i> Booking Date</div>
                    <div>${formatDate(event.start)}</div>
                </div>
                <div class="modal-info">
                    <div class="modal-label"><i class="far fa-clock" style="margin-right: 0.25rem;"></i> Booking Time</div>
                    <div>${event.extendedProps.bookingTime}${endTime ? ' - ' + endTime : ''} (${duration} hour${duration > 1 ? 's' : ''})</div>
                </div>
                <div class="modal-info">
                    <div class="modal-label"><i class="fas fa-door-open" style="margin-right: 0.25rem;"></i> Room</div>
                    <div>${event.extendedProps.room}</div>
                </div>
                ${event.extendedProps.description ? `
                    <div class="modal-info">
                        <div class="modal-label"><i class="fas fa-info-circle" style="margin-right: 0.25rem;"></i> Description</div>
                        <div>${event.extendedProps.description}</div>
                    </div>
                ` : ''}
                <div class="modal-info">
                    <div class="modal-label"><i class="fas fa-check-circle" style="margin-right: 0.25rem;"></i> Status</div>
                    <div>Your booking has been approved! The room is reserved for you.</div>
                </div>
                <div class="modal-actions">
                    <button onclick="closeModal()" class="modal-btn close">Close</button>
                    <form action="/user/roombooking/${event.extendedProps.bookingId}/archive" method="POST" style="flex: 1;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="modal-btn archive"><i class="fas fa-archive"></i> Move to History</button>
                    </form>
                </div>`;
            
            modalBody.innerHTML = bodyContent;
        }

        window.closeModal = function() {
            document.getElementById('eventModal').classList.remove('active');
        };

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

        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    });
</script>
@endsection