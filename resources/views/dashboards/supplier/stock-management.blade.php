@extends('layouts.dashboard')

@section('title', 'Stock Management')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<h1 class="page-title">Stock Management</h1>

<div class="stock-management-container">
    <!-- Current Stock Overview -->
    <div class="stock-overview-card">
        <h2><i class="fas fa-boxes"></i> Current Stock Overview</h2>
        <div class="stock-stats">
            <div class="stat-item">
                <span class="stat-label">Total Stock</span>
                <span class="stat-value">1,250 kg</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Available</span>
                <span class="stat-value">850 kg</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Reserved</span>
                <span class="stat-value">400 kg</span>
            </div>
        </div>
    </div>

    <!-- Add New Stock Form -->
    <div class="add-stock-card">
        <h2><i class="fas fa-plus-circle"></i> Add New Stock</h2>
        <form class="stock-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="material_name">Material Name</label>
                    <input type="text" id="material_name" name="material_name" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="unit">Unit</label>
                    <select id="unit" name="unit" required>
                        <option value="kg">Kilograms (kg)</option>
                        <option value="tons">Tons</option>
                        <option value="pieces">Pieces</option>
                        <option value="liters">Liters</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="raw_materials">Raw Materials</option>
                        <option value="components">Components</option>
                        <option value="packaging">Packaging</option>
                        <option value="chemicals">Chemicals</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="supplier_cost">Supplier Cost</label>
                    <input type="number" id="supplier_cost" name="supplier_cost" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" id="expiry_date" name="expiry_date">
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Add Stock</button>
                <button type="reset" class="btn-secondary">Clear Form</button>
            </div>
        </form>
    </div>

    <!-- Current Inventory Table -->
    <div class="inventory-table-card">
        <h2><i class="fas fa-list"></i> Current Inventory</h2>
        <div class="table-container">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Steel Sheets</td>
                        <td>Raw Materials</td>
                        <td>500</td>
                        <td>kg</td>
                        <td>$2.50/kg</td>
                        <td><span class="status-badge available">Available</span></td>
                        <td>
                            <button class="btn-action" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn-action" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Aluminum Alloy</td>
                        <td>Raw Materials</td>
                        <td>300</td>
                        <td>kg</td>
                        <td>$3.20/kg</td>
                        <td><span class="status-badge low">Low Stock</span></td>
                        <td>
                            <button class="btn-action" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn-action" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Plastic Components</td>
                        <td>Components</td>
                        <td>200</td>
                        <td>pieces</td>
                        <td>$0.85/piece</td>
                        <td><span class="status-badge reserved">Reserved</span></td>
                        <td>
                            <button class="btn-action" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn-action" title="Delete"><i class="fas fa-trash"></i></button>
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
    .stock-management-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .stock-overview-card, .add-stock-card, .inventory-table-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stock-overview-card h2, .add-stock-card h2, .inventory-table-card h2 {
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stock-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .stat-label {
        display: block;
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #333;
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-primary, .btn-secondary {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    .table-container {
        overflow-x: auto;
    }

    .inventory-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .inventory-table th, .inventory-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .inventory-table th {
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

    .status-badge.available {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.low {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.reserved {
        background: #d1ecf1;
        color: #0c5460;
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