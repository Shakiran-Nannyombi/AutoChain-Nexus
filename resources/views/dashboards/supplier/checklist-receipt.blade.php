@extends('layouts.dashboard')

@section('title', 'Checklist Receipt')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<h1 class="page-title">Checklist Receipt</h1>

<div class="checklist-container">
    <!-- Checklist Stats -->
    <div class="checklist-stats">
        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">12</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">45</div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon completed">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">38</div>
                <div class="stat-label">Delivered</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">3</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filter-group">
            <label for="status-filter">Status:</label>
            <select id="status-filter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="completed">Completed</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="manufacturer-filter">Manufacturer:</label>
            <select id="manufacturer-filter">
                <option value="">All Manufacturers</option>
                <option value="manufacturer1">ABC Manufacturing</option>
                <option value="manufacturer2">XYZ Industries</option>
                <option value="manufacturer3">TechCorp Ltd</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="date-filter">Date Range:</label>
            <input type="date" id="date-filter">
        </div>
        <button class="btn-primary">Apply Filters</button>
    </div>

    <!-- Checklists Table -->
    <div class="checklists-table-container">
        <h2><i class="fas fa-clipboard-list"></i> Received Checklists</h2>
        <div class="table-container">
            <table class="checklists-table">
                <thead>
                    <tr>
                        <th>Checklist ID</th>
                        <th>Manufacturer</th>
                        <th>Materials Required</th>
                        <th>Quantity</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#CL-001</td>
                        <td>ABC Manufacturing</td>
                        <td>Steel Sheets, Aluminum Alloy</td>
                        <td>500 kg, 200 kg</td>
                        <td>2025-06-25</td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td><span class="priority-badge high">High</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="Approve"><i class="fas fa-check"></i></button>
                            <button class="btn-action" title="Reject"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#CL-002</td>
                        <td>XYZ Industries</td>
                        <td>Plastic Components</td>
                        <td>150 pieces</td>
                        <td>2025-06-28</td>
                        <td><span class="status-badge approved">Approved</span></td>
                        <td><span class="priority-badge medium">Medium</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="Update Status"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#CL-003</td>
                        <td>TechCorp Ltd</td>
                        <td>Electronic Components</td>
                        <td>75 units</td>
                        <td>2025-06-22</td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td><span class="priority-badge low">Low</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="View Invoice"><i class="fas fa-file-invoice"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#CL-004</td>
                        <td>ABC Manufacturing</td>
                        <td>Chemical Supplies</td>
                        <td>100 liters</td>
                        <td>2025-06-20</td>
                        <td><span class="status-badge rejected">Rejected</span></td>
                        <td><span class="priority-badge high">High</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="Resubmit"><i class="fas fa-redo"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .checklist-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .checklist-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.pending {
        background: #ffc107;
    }

    .stat-icon.approved {
        background: #28a745;
    }

    .stat-icon.completed {
        background: #17a2b8;
    }

    .stat-icon.rejected {
        background: #dc3545;
    }

    .stat-info {
        flex: 1;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
    }

    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        gap: 1rem;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-weight: 500;
        color: #333;
        font-size: 0.9rem;
    }

    .filter-group select, .filter-group input {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .btn-primary {
        padding: 0.5rem 1rem;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .checklists-table-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .checklists-table-container h2 {
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-container {
        overflow-x: auto;
    }

    .checklists-table {
        width: 100%;
        border-collapse: collapse;
    }

    .checklists-table th, .checklists-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .checklists-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.approved {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.completed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .priority-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .priority-badge.high {
        background: #f8d7da;
        color: #721c24;
    }

    .priority-badge.medium {
        background: #fff3cd;
        color: #856404;
    }

    .priority-badge.low {
        background: #d4edda;
        color: #155724;
    }

    .btn-action {
        padding: 0.25rem 0.5rem;
        margin: 0 0.25rem;
        border: none;
        background: none;
        cursor: pointer;
        color: #666;
        transition: color 0.2s;
    }

    .btn-action:hover {
        color: #333;
    }
</style>
@endpush 