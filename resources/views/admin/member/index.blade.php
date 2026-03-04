@extends('layouts.admin')

@section('content')
<style>
    .members-container {
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

    .page-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left h2 {
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

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.875rem 1.75rem;
        background: white;
        color: #667eea;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        color: #667eea;
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
        animation: slideDown 0.3s ease;
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

    .members-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
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

    .table-wrapper {
        overflow-x: auto;
    }

    .members-table {
        width: 100%;
        border-collapse: collapse;
    }

    .members-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .members-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.8125rem;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
    }
    
    .members-table th:last-child {
        cursor: default;
    }

    .members-table th:hover:not(:last-child) {
        background: rgba(255, 255, 255, 0.1);
    }

    .sort-icon {
        margin-left: 0.375rem;
        font-size: 0.75rem;
        opacity: 0.7;
    }

    .members-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .members-table tbody tr:last-child {
        border-bottom: none;
    }

    .members-table tbody tr:hover {
        background: #f9fafb;
    }

    .members-table td {
        padding: 1.25rem 1.5rem;
        color: #374151;
        font-size: 0.9375rem;
        vertical-align: middle;
    }

    .member-name {
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        min-width: 150px;
    }

    .member-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .gender-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        background: #e0f2fe;
        color: #0369a1;
    }

    .gender-male {
        background: #dbeafe;
        color: #1e40af;
    }

    .gender-female {
        background: #fce7f3;
        color: #9f1239;
    }

    .contact-info {
        color: #6b7280;
        font-size: 0.9375rem;
    }

    .member-email {
        color: #6b7280;
        font-size: 0.9375rem;
        display: block;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .fellowship-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        background: #f0fdf4;
        color: #15803d;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .baptism-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .baptism-yes {
        background: #dcfce7;
        color: #166534;
    }

    .baptism-no {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 0.625rem;
        flex-wrap: wrap;
        min-width: 280px;
    }

    .btn-action {
        padding: 0.625rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .btn-view {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-view:hover {
        background: #bfdbfe;
        color: #1e3a8a;
    }

    .btn-edit {
        background: #ddd6fe;
        color: #6d28d9;
    }

    .btn-edit:hover {
        background: #c4b5fd;
        color: #5b21b6;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
        color: #7f1d1d;
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
        margin-bottom: 2rem;
    }

    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
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
        .page-header h2 {
            font-size: 1.75rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-actions {
            grid-column: 1;
            justify-content: flex-end;
        }

        .table-wrapper {
            overflow-x: scroll;
        }

        .members-table {
            min-width: 1200px;
        }
        
        .action-buttons {
            min-width: auto;
        }
    }
</style>

<div class="members-container">
    <div class="page-header">
        <div class="page-header-content">
            <div class="header-left">
                <h2>
                    <span class="header-icon">
                        <i class="fas fa-users"></i>
                    </span>
                    Members Management
                </h2>
                <p class="header-subtitle">Track and manage church members with ease</p>
            </div>
            <a href="{{ route('admin.member.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                <span>Add Member</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="success-alert">
            <span style="font-size: 1.5rem;">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="members-card">
        <div class="table-header">
            <h3 class="table-title">Members List</h3>
            <span class="results-count">
                Showing {{ $members->firstItem() ?? 0 }} - {{ $members->lastItem() ?? 0 }} of {{ $members->total() }} results
            </span>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.member.index') }}" id="filterForm">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            class="filter-input" 
                            placeholder="Name, email, or phone..."
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
                        <a href="{{ route('admin.member.index') }}" class="btn btn-secondary">
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

        <div class="table-wrapper">
            @if($members && count($members) > 0)
                <table class="members-table">
                    <thead>
                        <tr>
                            <th onclick="sortBy('name')">
                                Name
                                @if(request('sort') == 'name')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </th>
                            <th onclick="sortBy('gender')">
                                Gender
                                @if(request('sort') == 'gender')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </th>
                            <th>Contact</th>
                            <th onclick="sortBy('email')">
                                Email
                                @if(request('sort') == 'email')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </th>
                            <th onclick="sortBy('fellowship')">
                                Fellowship
                                @if(request('sort') == 'fellowship')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </th>
                            <th onclick="sortBy('baptism')">
                                Baptism
                                @if(request('sort') == 'baptism')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td>
                                    <div class="member-name">
                                        <div class="member-avatar">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $member->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($member->gender)
                                        <span class="gender-badge {{ $member->gender == 'Male' ? 'gender-male' : 'gender-female' }}">
                                            {{ $member->gender == 'Male' ? '♂' : '♀' }}
                                            {{ $member->gender }}
                                        </span>
                                    @else
                                        <span style="color: #9ca3af;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="contact-info">{{ $member->phone ?: 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="member-email" title="{{ $member->email }}">{{ $member->email }}</span>
                                </td>
                                <td>
                                    @if($member->fellowship)
                                        <span class="fellowship-badge">🤝 {{ $member->fellowship }}</span>
                                    @else
                                        <span style="color: #9ca3af;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->baptism)
                                        <span class="baptism-badge 
                                            @if($member->baptism == 'Yes') baptism-yes
                                            @else baptism-no
                                            @endif">
                                            @if($member->baptism == 'Yes') ✓
                                            @else ✗
                                            @endif
                                            {{ $member->baptism }}
                                        </span>
                                    @else
                                        <span style="color: #9ca3af;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.member.show', $member) }}" class="btn-action btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.member.edit', $member) }}" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.member.destroy', $member) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete {{ $member->name }}?')"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <h3 class="empty-title">No Members Found</h3>
                    <p class="empty-text">
                        @if(request()->hasAny(['search', 'gender', 'fellowship', 'baptism']))
                            No members match your search criteria. Try adjusting your filters.
                        @else
                            Start by adding your first member to the system.
                        @endif
                    </p>
                    @if(!request()->hasAny(['search', 'gender', 'fellowship', 'baptism']))
                        <a href="{{ route('admin.member.create') }}" class="btn-add">
                            <i class="fas fa-plus"></i>
                            <span>Add First Member</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($members instanceof \Illuminate\Pagination\LengthAwarePaginator && $members->hasPages())
        <div class="pagination-wrapper">
            {{ $members->links() }}
        </div>
    @endif
</div>

<script>
function sortBy(field) {
    const form = document.getElementById('filterForm');
    const url = new URL(window.location.href);
    
    // Toggle order if same field, otherwise default to asc
    if (url.searchParams.get('sort') === field) {
        const currentOrder = url.searchParams.get('order') || 'asc';
        url.searchParams.set('order', currentOrder === 'asc' ? 'desc' : 'asc');
    } else {
        url.searchParams.set('order', 'asc');
    }
    
    url.searchParams.set('sort', field);
    window.location.href = url.toString();
}
</script>
@endsection