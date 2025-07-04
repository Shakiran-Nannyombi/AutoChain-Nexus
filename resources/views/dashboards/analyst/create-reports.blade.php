@extends('layouts.dashboard')
@section('title', 'Generate Report')
@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 class="page-header"><i class="fas fa-plus"></i> Generate New Report</h2>

    <form action="{{ route('analyst.reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Report Title:</label>
        <input type="text" name="title" class="form-control" required>

        <label>Report Type:</label>
        <select name="type" class="form-control" required>
            <option value="sales">Sales</option>
            <option value="inventory">Inventory</option>
            <option value="performance">Performance</option>
        </select>

        <label>Target Role:</label>
        <select name="target_role" class="form-control" required>
            <option value="retailer">Retailer</option>
            <option value="supplier">Supplier</option>
            <option value="vendor">Vendor</option>
            <option value="manufacturer">Manufacturer</option>
        </select>

        <label>Report Summary:</label>
        <textarea name="summary" class="form-control" required></textarea>

        <label>Upload File (optional):</label>
        <input type="file" name="report_file" class="form-control">

        <br>
        <button class="btn btn-primary">Generate Report</button>
    </form>
</div>
@endsection
