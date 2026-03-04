@extends('layouts.admin')

@section('content')
<style>
    .report-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 20px;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 40px rgba(59, 130, 246, 0.3);
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
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
    }

    .filter-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .filter-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-select {
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        background: white;
    }

    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-generate {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-card.total-amount {
        border-left-color: #10b981;
    }

    .stat-card.total-donations {
        border-left-color: #3b82f6;
    }

    .stat-card.unique-donors {
        border-left-color: #f59e0b;
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .payment-breakdown {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .payment-item {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
    }

    .payment-method {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }

    .payment-stats {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .payment-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .payment-stat-label {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .payment-stat-value {
        font-weight: 600;
        color: #1f2937;
    }

    .donations-table-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .table-header {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .modern-table tbody tr:hover {
        background: #f9fafb;
    }

    .donor-name {
        font-weight: 600;
        color: #1f2937;
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
    }

    .btn-print {
        padding: 0.75rem 1.5rem;
        background: #10b981;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-print:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
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

    @media print {
        .filter-card,
        .btn-print,
        .page-header {
            display: none;
        }
        
        .report-container {
            padding: 0;
        }
    }

    @media (max-width: 768px) {
        .filter-form {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .modern-table {
            min-width: 800px;
        }
    }
</style>

<div class="report-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <span>📊</span>
                Donation Report
            </h1>
            <p class="page-subtitle">Generate and view detailed donation reports by month</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <h2 class="filter-title">
            <i class="fas fa-filter"></i>
            Select Report Period
        </h2>
        
        <form action="{{ route('admin.donation.report') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label class="form-label" for="month">Month</label>
                <select name="month" id="month" class="form-select" required>
                    <option value="1" {{ $month == 1 ? 'selected' : '' }}>January</option>
                    <option value="2" {{ $month == 2 ? 'selected' : '' }}>February</option>
                    <option value="3" {{ $month == 3 ? 'selected' : '' }}>March</option>
                    <option value="4" {{ $month == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $month == 5 ? 'selected' : '' }}>May</option>
                    <option value="6" {{ $month == 6 ? 'selected' : '' }}>June</option>
                    <option value="7" {{ $month == 7 ? 'selected' : '' }}>July</option>
                    <option value="8" {{ $month == 8 ? 'selected' : '' }}>August</option>
                    <option value="9" {{ $month == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $month == 10 ? 'selected' : '' }}>October</option>
                    <option value="11" {{ $month == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $month == 12 ? 'selected' : '' }}>December</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="year">Year</label>
                <select name="year" id="year" class="form-select" required>
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="btn-generate">
                <i class="fas fa-search"></i>
                Generate Report
            </button>
        </form>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card total-amount">
            <div class="stat-icon">💵</div>
            <div class="stat-value">RM {{ number_format($totalAmount, 2) }}</div>
            <div class="stat-label">Total Amount</div>
        </div>

        <div class="stat-card total-donations">
            <div class="stat-icon">📝</div>
            <div class="stat-value">{{ $totalDonations }}</div>
            <div class="stat-label">Total Donations</div>
        </div>

        <div class="stat-card unique-donors">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ $uniqueDonors }}</div>
            <div class="stat-label">Unique Donors</div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    @if($byPaymentMethod->isNotEmpty())
    <div class="payment-breakdown">
        <h2 class="section-title">
            <span>💳</span>
            Payment Method Breakdown
        </h2>

        <div class="payment-grid">
            @foreach($byPaymentMethod as $method => $data)
                <div class="payment-item">
                    <div class="payment-method">{{ $method }}</div>
                    <div class="payment-stats">
                        <div class="payment-stat">
                            <span class="payment-stat-label">Count:</span>
                            <span class="payment-stat-value">{{ $data['count'] }}</span>
                        </div>
                        <div class="payment-stat">
                            <span class="payment-stat-label">Total:</span>
                            <span class="payment-stat-value">RM {{ number_format($data['total'], 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Donations Table -->
    <div class="donations-table-card">
        <div class="table-header">
            <h2 class="section-title" style="margin: 0;">
                <span>📋</span>
                Donations for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
            </h2>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i>
                Print Report
            </button>
        </div>

        <div class="table-container">
            @if($donations->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">📊</div>
                    <h3>No Donations Found</h3>
                    <p>No donations were received during this period</p>
                </div>
            @else
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Donor Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $index => $donation)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $donation->created_at->format('d M Y') }}</td>
                                <td><span class="donor-name">{{ $donation->donor_name }}</span></td>
                                <td>{{ $donation->email ?? '-' }}</td>
                                <td>
                                    <span class="amount-badge">
                                        RM {{ number_format($donation->amount, 2) }}
                                    </span>
                                </td>
                                <td>{{ $donation->payment_method }}</td>
                                <td>{{ $donation->note ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection