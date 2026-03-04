@extends('layouts.user')

@section('content')
<style>
    .directory-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .header-left h1 {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-left p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    .total-badge {
        display: inline-block;
        color: #6b7280;
        padding: 0.5rem 0;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .total-badge strong {
        color: #374151;
        font-weight: 700;
    }

    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }

    .results-count {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Filter Section */
    .filter-section {
        padding: 1.25rem 2rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto;
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .filter-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.125rem;
    }

    .filter-input, .filter-select {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
        color: #1f2937;
    }

    .filter-input::placeholder {
        color: #9ca3af;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .members-table {
        width: 100%;
        border-collapse: collapse;
    }

    .members-table thead {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border-bottom: 2px solid #e5e7eb;
    }

    .members-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 700;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .members-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        color: #4b5563;
    }

    .members-table tbody tr {
        transition: all 0.2s;
        cursor: pointer;
    }

    .members-table tbody tr:hover {
        background: #f9fafb;
        transform: scale(1.01);
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .member-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
    }

    .member-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-male {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-female {
        background: #fce7f3;
        color: #9f1239;
    }

    .badge-fellowship {
        background: #f0fdf4;
        color: #15803d;
    }

    .badge-yes {
        background: #dcfce7;
        color: #166534;
    }

    .badge-no {
        background: #fee2e2;
        color: #991b1b;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
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
        display: flex;
        justify-content: center;
        border-top: 1px solid #f3f4f6;
    }

    @media (max-width: 1024px) {
        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filter-actions {
            grid-column: 1 / -1;
            justify-content: flex-end;
        }
    }

    @media (max-width: 768px) {
        .directory-container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-actions {
            grid-column: 1;
            justify-content: flex-end;
        }

        .members-table th,
        .members-table td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>

<div class="directory-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <h1>
                <span>👥</span> Member Directory
            </h1>
            <p>Browse and connect with church members</p>
        </div>
    </div>

    <!-- Members Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Members List</h3>
            <span class="results-count">
                Showing {{ $members->firstItem() ?? 0 }} - {{ $members->lastItem() ?? 0 }} of {{ $members->total() }} results
            </span>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('user.member.index') }}" id="filterForm">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            class="filter-input" 
                            placeholder="Name or fellowship..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Gender</label>
                        <select name="gender" class="filter-select">
                            <option value="">All Genders</option>
                            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Fellowship</label>
                        <select name="fellowship" class="filter-select">
                            <option value="">All Fellowships</option>
                            @foreach($fellowships as $fellowship)
                                <option value="{{ $fellowship }}" {{ request('fellowship') == $fellowship ? 'selected' : '' }}>
                                    {{ $fellowship }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Baptism Status</label>
                        <select name="baptism" class="filter-select">
                            <option value="">All Status</option>
                            <option value="Yes" {{ request('baptism') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ request('baptism') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <a href="{{ route('user.member.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                            Reset
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($members->count() > 0)
            <div class="table-responsive">
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Gender</th>
                            <th>Fellowship</th>
                            <th>Baptism</th>
                            <th>Member Since</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                            <tr onclick="window.location='{{ route('user.member.show', $member->id) }}'">
                                <td>
                                    <div class="member-info">
                                        <div class="member-avatar">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <span class="member-name">{{ $member->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $member->gender == 'Male' ? 'badge-male' : 'badge-female' }}">
                                        {{ $member->gender == 'Male' ? '♂' : '♀' }}
                                        {{ $member->gender }}
                                    </span>
                                </td>
                                <td>
                                    @if($member->fellowship)
                                        <span class="badge badge-fellowship">{{ $member->fellowship }}</span>
                                    @else
                                        <span style="color: #cbd5e0; font-style: italic;">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $member->baptism == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                                        {{ $member->baptism == 'Yes' ? '✓' : '✗' }}
                                        {{ $member->baptism }}
                                    </span>
                                </td>
                                <td>
                                    {{ $member->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($members->hasPages())
                <div class="pagination-wrapper">
                    {{ $members->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3 class="empty-title">No Members Found</h3>
                <p class="empty-text">
                    @if(request()->hasAny(['search', 'gender', 'fellowship', 'baptism']))
                        No members match your search criteria. Try adjusting your filters.
                    @else
                        No members available at the moment.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection