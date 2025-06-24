@extends('layouts.dashboard')

@section('title', 'Retailer Dashboard')

@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection

@section('content')
    <h1 class="page-header">Retailer Dashboard</h1>

    <div class="dashboard-grid">
        <!-- Stock Overview -->
        <div class="analyst-content">
            <div class="analyst-section">
                <h2><i class="fas fa-warehouse"></i> Stock Overview</h2>
                <table class="analyst-table">
                    <thead>
                        <tr>
                            <th>Car Model</th>
                            <th>Vendor</th>
                            <th>Received</th>
                            <th>In Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Toyota Corolla</td>
                            <td>Vendor A</td>
                            <td>10</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td>Honda Civic</td>
                            <td>Vendor B</td>
                            <td>8</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Ford Focus</td>
                            <td>Vendor C</td>
                            <td>12</td>
                            <td>12</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Update & Order Placement (side by side on large screens) -->
        <div class="dashboard-flex-row">
            <div class="analyst-content" style="flex:1; min-width:300px; margin-right:1rem;">
                <div class="analyst-section">
                    <h2><i class="fas fa-edit"></i> Sales Update</h2>
                    <form class="analyst-form" method="POST" action="#">
                        <div class="analyst-form-group">
                            <label class="analyst-form-label" for="car_model">Car Model</label>
                            <select class="analyst-form-input" id="car_model" name="car_model">
                                <option>Toyota Corolla</option>
                                <option>Honda Civic</option>
                                <option>Ford Focus</option>
                            </select>
                        </div>
                        <div class="analyst-form-group">
                            <label class="analyst-form-label" for="quantity">Quantity Sold</label>
                            <input class="analyst-form-input" type="number" id="quantity" name="quantity" min="1" required>
                        </div>
                        <button class="analyst-btn" type="submit">Update Stock</button>
                    </form>
                </div>
            </div>
            <div class="analyst-content" style="flex:1; min-width:300px;">
                <div class="analyst-section">
                    <h2><i class="fas fa-shopping-cart"></i> Order Placement</h2>
                    <form class="analyst-form" method="POST" action="#">
                        <div class="analyst-form-group">
                            <label class="analyst-form-label" for="customer_name">Customer Name</label>
                            <input class="analyst-form-input" type="text" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="analyst-form-group">
                            <label class="analyst-form-label" for="car_model_order">Car Model</label>
                            <select class="analyst-form-input" id="car_model_order" name="car_model_order">
                                <option>Toyota Corolla</option>
                                <option>Honda Civic</option>
                                <option>Ford Focus</option>
                            </select>
                        </div>
                        <div class="analyst-form-group">
                            <label class="analyst-form-label" for="order_quantity">Quantity</label>
                            <input class="analyst-form-input" type="number" id="order_quantity" name="order_quantity" min="1" required>
                        </div>
                        <button class="analyst-btn" type="submit">Place Order</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Chat & Notifications (side by side on large screens) -->
        <div class="dashboard-flex-row">
            <div class="analyst-content" style="flex:1; min-width:300px; margin-right:1rem;">
                <div class="analyst-section">
                    <h2><i class="fas fa-comments"></i> Chat with Vendors</h2>
                    <a href="{{ route('retailer.chat') }}" class="analyst-btn analyst-btn-secondary">Open Chat</a>
                </div>
            </div>
            <div class="analyst-content" style="flex:1; min-width:300px;">
                <div class="analyst-section">
                    <h2><i class="fas fa-bell"></i> Notifications</h2>
                    <div class="analyst-alert analyst-alert-info">
                        <i class="fas fa-truck"></i> New delivery received from Vendor A.
                    </div>
                    <div class="analyst-alert analyst-alert-success">
                        <i class="fas fa-check"></i> Sale completed: 2 Honda Civics sold.
                    </div>
                    <div class="analyst-alert analyst-alert-warning">
                        <i class="fas fa-comments"></i> New message from Vendor B.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .dashboard-grid {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .dashboard-flex-row {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    @media (max-width: 900px) {
        .dashboard-flex-row {
            flex-direction: column;
            gap: 1rem;
        }
    }
    </style>
@endsection 