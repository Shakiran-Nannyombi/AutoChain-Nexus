@extends('layouts.dashboard')

@section('title', 'Delivery History')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<h1 class="page-title">Delivery History</h1>

<div class="delivery-container">
    <!-- Delivery Stats -->
    <div class="delivery-stats">
        <div class="stat-card">
            <div class="stat-icon completed">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">156</div>
                <div class="stat-label">Total Deliveries</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon ontime">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">142</div>
                <div class="stat-label">On Time</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon delayed">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">14</div>
                <div class="stat-label">Delayed</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">$45,230</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
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
            <label for="status-filter">Status:</label>
            <select id="status-filter">
                <option value="">All Status</option>
                <option value="completed">Completed</option>
                <option value="delayed">Delayed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="date-from">From:</label>
            <input type="date" id="date-from">
        </div>
        <div class="filter-group">
            <label for="date-to">To:</label>
            <input type="date" id="date-to">
        </div>
        <button class="btn-primary">Apply Filters</button>
    </div>

    <!-- Delivery History Table -->
    <div class="delivery-table-container">
        <h2><i class="fas fa-history"></i> Past Deliveries</h2>
        <div class="table-container">
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Delivery ID</th>
                        <th>Date</th>
                        <th>Manufacturer</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#DL-001</td>
                        <td>2025-06-20</td>
                        <td>ABC Manufacturing</td>
                        <td>Steel Sheets, Aluminum</td>
                        <td>500 kg, 200 kg</td>
                        <td>$2,450</td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="Download Invoice"><i class="fas fa-download"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#DL-002</td>
                        <td>2025-06-18</td>
                        <td>XYZ Industries</td>
                        <td>Plastic Components</td>
                        <td>150 pieces</td>
                        <td>$1,275</td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="Download Invoice"><i class="fas fa-download"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#DL-003</td>
                        <td>2025-06-15</td>
                        <td>TechCorp Ltd</td>
                        <td>Electronic Components</td>
                        <td>75 units</td>
                        <td>$3,750</td>
                        <td><span class="status-badge delayed">Delayed</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="Download Invoice"><i class="fas fa-download"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>#DL-004</td>
                        <td>2025-06-12</td>
                        <td>ABC Manufacturing</td>
                        <td>Chemical Supplies</td>
                        <td>100 liters</td>
                        <td>$850</td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="btn-action" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action" title="Download Invoice"><i class="fas fa-download"></i></button>
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
    .delivery-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .delivery-stats {
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

    .stat-icon.completed {
        background: #28a745;
    }

    .stat-icon.ontime {
        background: #17a2b8;
    }

    .stat-icon.delayed {
        background: #ffc107;
    }

    .stat-icon.revenue {
        background: #6f42c1;
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

    .delivery-table-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .delivery-table-container h2 {
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-container {
        overflow-x: auto;
    }

    .delivery-table {
        width: 100%;
        border-collapse: collapse;
    }

    .delivery-table th, .delivery-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .delivery-table th {
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

    .status-badge.completed {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.delayed {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.cancelled {
        background: #f8d7da;
        color: #721c24;
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