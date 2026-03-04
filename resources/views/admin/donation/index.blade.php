@extends('layouts.admin')

@section('content')
<style>
    .donation-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
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

    .page-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    .report-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.875rem 1.75rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .report-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    .success-alert {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 5px solid;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        opacity: 0.1;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-card.total-amount {
        border-left-color: #10b981;
    }

    .stat-card.total-amount::before {
        background: #10b981;
    }

    .stat-card.total-donors {
        border-left-color: #3b82f6;
    }

    .stat-card.total-donors::before {
        background: #3b82f6;
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .stat-card.total-amount .stat-icon {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .stat-card.total-donors .stat-icon {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .stat-content h3 {
        font-size: 2.25rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: #1f2937;
    }

    .stat-content p {
        font-size: 0.95rem;
        color: #6b7280;
        margin: 0;
        font-weight: 600;
    }

    /* QR Codes Section */
    .qr-section {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2.5rem;
    }

    .qr-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .qr-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .qr-manage-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.875rem 1.75rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .qr-manage-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .qr-display {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .qr-card {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
        max-width: 300px;
        width: 100%;
    }

    .qr-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        border-color: #667eea;
    }

    .qr-image-wrapper {
        width: 100%;
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        overflow: hidden;
        cursor: pointer;
        position: relative;
    }

    .qr-image-wrapper::after {
        content: '🔍 Click to zoom';
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .qr-image-wrapper:hover::after {
        opacity: 1;
    }

    .qr-image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .qr-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .qr-info {
        padding: 1.5rem;
    }

    .qr-label {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        text-align: center;
        font-size: 1.1rem;
    }

    .qr-date {
        font-size: 0.875rem;
        color: #6b7280;
        text-align: center;
    }

    /* Modal Styles for QR Zoom */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s;
    }

    .modal-overlay.active {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        animation: zoomIn 0.3s;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-content img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    .modal-close {
        position: absolute;
        top: -50px;
        right: 0;
        background: white;
        color: #1f2937;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .modal-close:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }

    .modal-label {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        color: #1f2937;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Donations Table */
    .donations-table-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-container {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead {
        background: #f9fafb;
    }

    .modern-table th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 700;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        color: #1f2937;
    }

    .modern-table tbody tr {
        transition: all 0.2s;
    }

    .modern-table tbody tr:hover {
        background: #f9fafb;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .donor-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .donor-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .donor-details {
        flex: 1;
        min-width: 0;
    }

    .donor-name {
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .donor-email {
        color: #6b7280;
        font-size: 0.875rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .amount-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
    }

    .payment-method-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
    }

    .payment-cash {
        background: #fef3c7;
        color: #92400e;
    }

    .payment-card {
        background: #dbeafe;
        color: #1e40af;
    }

    .payment-bank {
        background: #e0e7ff;
        color: #3730a3;
    }

    .payment-online {
        background: #fce7f3;
        color: #831843;
    }

    .payment-qr-code {
        background: #f3e8ff;
        color: #6b21a8;
    }

    .donation-note {
        color: #6b7280;
        font-size: 0.875rem;
        font-style: italic;
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .donation-date {
        color: #6b7280;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .evidence-btn, .delete-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s;
        border: 1px solid;
        cursor: pointer;
    }

    .evidence-btn {
        background: #f3f4f6;
        color: #374151;
        border-color: #e5e7eb;
    }

    .evidence-btn:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .delete-btn {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .delete-btn:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: #6b7280;
        font-size: 1.05rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1.5rem;
        }

        .page-header-content {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .report-btn {
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .qr-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .qr-manage-btn {
            width: 100%;
            justify-content: center;
        }

        .table-container {
            overflow-x: scroll;
        }

        .modern-table {
            min-width: 1000px;
        }
    }
</style>

<div class="donation-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h1 class="page-title">
                    <span>💰</span>
                    Donations Management
                </h1>
                <p class="page-subtitle">Track and manage all donations received</p>
            </div>
            <a href="{{ route('admin.donation.report') }}" class="report-btn">
                <i class="fas fa-chart-bar"></i>
                Generate Report
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total-amount">
            <div class="stat-header">
                <div class="stat-icon">💵</div>
                <div class="stat-content">
                    <h3>RM {{ number_format($totalAmount, 2) }}</h3>
                    <p>Total Donations</p>
                </div>
            </div>
        </div>

        <div class="stat-card total-donors">
            <div class="stat-header">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h3>{{ $totalDonors }}</h3>
                    <p>Total Donors</p>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Codes Section -->
    <div class="qr-section">
        <div class="qr-header">
            <h2 class="qr-title">
                <span>📱</span>
                Donation QR Code
            </h2>
            <a href="{{ route('admin.donation.manage-qr') }}" class="qr-manage-btn">
                <i class="fas fa-cog"></i>
                Manage QR Code
            </a>
        </div>

        @if($qrCodes && $qrCodes->isNotEmpty())
            <div class="qr-display">
                @foreach($qrCodes as $qr)
                    <div class="qr-card">
                        <div class="qr-image-wrapper" onclick="openQRModal('{{ $qr->qr_code_url }}', '{{ $qr->qr_code_label }}')">
                            @if($qr->qr_code_url)
                                <img src="{{ $qr->qr_code_url }}" alt="{{ $qr->qr_code_label }}" loading="lazy">
                            @else
                                <div style="text-align: center; color: #999;">
                                    <p>Image not found</p>
                                </div>
                            @endif
                        </div>
                        <div class="qr-info">
                            <div class="qr-label">{{ $qr->qr_code_label }}</div>
                            <div class="qr-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $qr->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">📱</div>
                <h3 class="empty-title">No QR Code Uploaded</h3>
                <p class="empty-text">Click "Manage QR Code" above to upload your donation QR code</p>
            </div>
        @endif
    </div>

    <!-- Donations Table -->
    <div class="donations-table-card">
        <div class="table-header">
            <h2 class="table-title">
                <span>📋</span>
                Recent Donations
            </h2>
        </div>

        <div class="table-container">
            @if($donations->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">💚</div>
                    <h3 class="empty-title">No Donations Yet</h3>
                    <p class="empty-text">Donations will appear here once they are recorded</p>
                </div>
            @else
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Note</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                            @if($donation->isDonation())
                            <tr>
                                <td>
                                    <div class="donor-info">
                                        <div class="donor-avatar">
                                            {{ strtoupper(substr($donation->donor_name, 0, 1)) }}
                                        </div>
                                        <div class="donor-details">
                                            <p class="donor-name">{{ $donation->donor_name }}</p>
                                            <p class="donor-email">{{ $donation->email ?? 'No email provided' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="amount-badge">
                                        <span>RM</span>
                                        <span>{{ number_format($donation->amount, 2) }}</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="payment-method-badge payment-{{ strtolower(str_replace(' ', '-', $donation->payment_method)) }}">
                                        {{ $donation->payment_method }}
                                    </span>
                                </td>
                                <td>
                                    <span class="donation-note" title="{{ $donation->note }}">
                                        {{ $donation->note ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="donation-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ $donation->created_at->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($donation->evidence_path)
                                            <a href="{{ route('admin.donation.view-evidence', $donation->id) }}" 
                                               target="_blank" 
                                               class="evidence-btn">
                                                <i class="fas fa-file-image"></i>
                                                Evidence
                                            </a>
                                        @endif
                                        
                                        <form action="{{ route('admin.donation.delete', $donation->id) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this donation? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal-overlay" id="qrModal" onclick="closeQRModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeQRModal()">×</button>
        <img id="qrModalImage" src="" alt="QR Code">
        <div class="modal-label" id="qrModalLabel"></div>
    </div>
</div>

<script>
function openQRModal(imageUrl, label) {
    const modal = document.getElementById('qrModal');
    const modalImage = document.getElementById('qrModalImage');
    const modalLabel = document.getElementById('qrModalLabel');
    
    modalImage.src = imageUrl;
    modalLabel.textContent = label;
    modal.classList.add('active');
    
    document.body.style.overflow = 'hidden';
}

function closeQRModal() {
    const modal = document.getElementById('qrModal');
    modal.classList.remove('active');
    
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQRModal();
    }
});
</script>
@endsection