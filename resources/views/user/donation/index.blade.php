@extends('layouts.user')

@section('content')
<style>
    .donation-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0.5rem 0 0 0;
    }
    
    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* QR Codes Section */
    .qr-section {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .donate-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .donate-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .qr-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .qr-card {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
        cursor: pointer;
    }

    .qr-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.2);
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
        position: relative;
        padding: 1rem;
    }

    .qr-image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .qr-card:hover .qr-image-wrapper img {
        transform: scale(1.05);
    }

    .qr-info {
        padding: 1.5rem;
    }

    .qr-label {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        word-break: break-word;
        font-size: 1.1rem;
    }

    .qr-date {
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .qr-hint {
        font-size: 0.875rem;
        color: #667eea;
        font-weight: 600;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* QR Modal */
    .qr-modal {
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

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .qr-modal-content {
        position: relative;
        margin: auto;
        padding: 2rem;
        width: 90%;
        max-width: 600px;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
    }

    .qr-modal img {
        width: 100%;
        max-width: 500px;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 10px 50px rgba(255, 255, 255, 0.1);
    }

    .qr-modal-label {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .qr-modal-instructions {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin-bottom: 2rem;
    }

    .qr-modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .qr-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .empty-qr-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    /* Donation History */
    .history-section {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .history-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .donations-table {
        width: 100%;
        border-collapse: collapse;
    }

    .donations-table thead {
        background: #f9fafb;
    }

    .donations-table th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 700;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
    }

    .donations-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        color: #1f2937;
    }

    .donations-table tbody tr {
        transition: all 0.2s;
    }

    .donations-table tbody tr:hover {
        background: #f9fafb;
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

    .payment-badge {
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

    .payment-qr {
        background: #f3e8ff;
        color: #6b21a8;
    }

    .donation-date {
        color: #6b7280;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .evidence-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #dbeafe;
        color: #1e40af;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .evidence-link:hover {
        background: #bfdbfe;
        transform: translateY(-2px);
    }

    .no-evidence {
        color: #9ca3af;
        font-size: 0.875rem;
        font-style: italic;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 1rem;
    }

    .empty-text {
        color: #6b7280;
        font-size: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination a,
    .pagination span {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        text-decoration: none;
        color: #667eea;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
    }

    .pagination span.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination span.disabled {
        color: #d1d5db;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }

        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .qr-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .donations-table {
            min-width: 800px;
        }
    }
</style>

<div class="donation-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">💝 Make a Donation</h1>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- QR Codes Section -->
    @if($qrCodes && $qrCodes->isNotEmpty())
    <div class="qr-section">
        <div class="section-header">
            <h2 class="section-title">
                <span>📱</span>
                Scan to Donate
            </h2>
        </div>

        <p style="color: #6b7280; margin-bottom: 2rem; font-size: 1rem;">
            Click on any QR code below to enlarge it, then scan using your phone to transfer a donation to the church. After completing your transfer, add your donation record.
        </p>

        <div class="qr-grid">
            @foreach($qrCodes as $qr)
                <div class="qr-card" onclick="openQRModal('{{ $qr->qr_code_url }}', '{{ $qr->qr_code_label }}')">
                    <div class="qr-image-wrapper">
                        <img src="{{ $qr->qr_code_url }}" alt="{{ $qr->qr_code_label }}">
                    </div>
                    <div class="qr-info">
                        <div class="qr-label">{{ $qr->qr_code_label }}</div>
                        <div class="qr-date">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $qr->created_at->format('d M Y') }}
                        </div>
                        <div class="qr-hint">
                            <i class="fas fa-search-plus"></i>
                            Click to enlarge & scan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- QR Modal -->
    <div id="qrModal" class="qr-modal" onclick="closeQRModal()">
        <div class="qr-modal-content" onclick="event.stopPropagation()">
            <span class="qr-modal-close" onclick="closeQRModal()">&times;</span>
            <img id="qrModalImage" src="" alt="QR Code">
            <div class="qr-modal-label" id="qrModalLabel"></div>
            <div class="qr-modal-instructions">
                📱 Scan this QR code with your banking app to make a donation
            </div>
        </div>
    </div>
    @else
    <div class="qr-section">
        <div class="empty-qr-state">
            <div class="empty-icon">📱</div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">No QR Codes Available</h3>
            <p style="margin-bottom: 1.5rem;">The church has not set up QR codes for donations yet. Please check back later or contact the church directly.</p>
        </div>
    </div>
    @endif

    <!-- Donation History -->
    <div class="history-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid #e5e7eb;">
            <h2 class="history-title">
                <span>📋</span>
                Your Donation History
            </h2>
            <a href="{{ route('user.donation.create') }}" class="donate-btn">
                <i class="fas fa-plus"></i>
                Add Donation
            </a>
        </div>

        @if($userDonations->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">💝</div>
                <h3 class="empty-title">No Donations Yet</h3>
                <p class="empty-text">You haven't recorded any donations yet. Scan a QR code or add a donation record.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="donations-table">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Note</th>
                            <th>Evidence</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userDonations as $donation)
                            <tr>
                                <td>
                                    <span class="amount-badge">
                                        <span>RM</span>
                                        <span>{{ number_format($donation->amount, 2) }}</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="payment-badge payment-{{ strtolower(str_replace(' ', '-', $donation->payment_method)) }}">
                                        {{ $donation->payment_method }}
                                    </span>
                                </td>
                                <td style="color: #6b7280; font-size: 0.875rem;">
                                    {{ $donation->note ?? '-' }}
                                </td>
                                <td>
                                    @if($donation->evidence_url)
                                        <a href="{{ $donation->evidence_url }}" target="_blank" class="evidence-link">
                                            <i class="fas fa-file-pdf"></i>
                                            View Evidence
                                        </a>
                                    @else
                                        <span class="no-evidence">No evidence uploaded</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="donation-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ $donation->created_at->format('d M Y') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($userDonations->hasPages())
                <div class="pagination">
                    {{ $userDonations->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function openQRModal(imageUrl, label) {
    const modal = document.getElementById('qrModal');
    const modalImage = document.getElementById('qrModalImage');
    const modalLabel = document.getElementById('qrModalLabel');
    
    modalImage.src = imageUrl;
    modalLabel.textContent = label;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeQRModal() {
    const modal = document.getElementById('qrModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeQRModal();
    }
});
</script>
@endsection