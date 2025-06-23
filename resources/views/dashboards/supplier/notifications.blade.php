@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<h1 class="page-title">Notifications</h1>

<div class="notifications-container">
    <!-- Notification Stats -->
    <div class="notification-stats">
        <div class="stat-card">
            <div class="stat-icon unread">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">8</div>
                <div class="stat-label">Unread</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon today">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">12</div>
                <div class="stat-label">Today</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon urgent">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">3</div>
                <div class="stat-label">Urgent</div>
            </div>
        </div>
    </div>

    <!-- Notification Filters -->
    <div class="notification-filters">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="checklist">Checklists</button>
        <button class="filter-btn" data-filter="stock">Stock Updates</button>
        <button class="filter-btn" data-filter="chat">Chat Messages</button>
        <button class="filter-btn" data-filter="urgent">Urgent</button>
        <button class="btn-secondary">Mark All as Read</button>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        <div class="notification-item unread urgent" data-type="checklist">
            <div class="notification-icon urgent">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4>New Checklist Received</h4>
                    <span class="notification-time">2 minutes ago</span>
                </div>
                <p>ABC Manufacturing has sent a new checklist requiring immediate attention. Materials needed: Steel Sheets (500kg), Aluminum Alloy (200kg).</p>
                <div class="notification-actions">
                    <button class="btn-action">View Checklist</button>
                    <button class="btn-action">Mark as Read</button>
                </div>
            </div>
        </div>

        <div class="notification-item unread" data-type="stock">
            <div class="notification-icon stock">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4>Low Stock Alert</h4>
                    <span class="notification-time">15 minutes ago</span>
                </div>
                <p>Aluminum Alloy stock is running low (50kg remaining). Consider restocking to avoid supply delays.</p>
                <div class="notification-actions">
                    <button class="btn-action">View Stock</button>
                    <button class="btn-action">Mark as Read</button>
                </div>
            </div>
        </div>

        <div class="notification-item unread" data-type="chat">
            <div class="notification-icon chat">
                <i class="fas fa-comments"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4>New Message from XYZ Industries</h4>
                    <span class="notification-time">1 hour ago</span>
                </div>
                <p>XYZ Industries: "Need urgent supplies for tomorrow's production. Can you confirm availability?"</p>
                <div class="notification-actions">
                    <button class="btn-action">Reply</button>
                    <button class="btn-action">Mark as Read</button>
                </div>
            </div>
        </div>

        <div class="notification-item" data-type="checklist">
            <div class="notification-icon completed">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4>Checklist Approved</h4>
                    <span class="notification-time">3 hours ago</span>
                </div>
                <p>Your checklist for TechCorp Ltd has been approved. Delivery scheduled for Friday, June 25th.</p>
                <div class="notification-actions">
                    <button class="btn-action">View Details</button>
                </div>
            </div>
        </div>

        <div class="notification-item" data-type="stock">
            <div class="notification-icon stock">
                <i class="fas fa-truck"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4>Stock Restocked</h4>
                    <span class="notification-time">5 hours ago</span>
                </div>
                <p>Steel Sheets inventory has been restocked. New quantity: 800kg available for orders.</p>
                <div class="notification-actions">
                    <button class="btn-action">View Inventory</button>
                </div>
            </div>
        </div>

        <div class="notification-item" data-type="chat">
            <div class="notification-icon chat">
                <i class="fas fa-comments"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4>Message from ABC Manufacturing</h4>
                    <span class="notification-time">1 day ago</span>
                </div>
                <p>ABC Manufacturing: "Thank you for the quick delivery. Quality was excellent as always."</p>
                <div class="notification-actions">
                    <button class="btn-action">Reply</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .notifications-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .notification-stats {
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

    .stat-icon.unread {
        background: #dc3545;
    }

    .stat-icon.today {
        background: #17a2b8;
    }

    .stat-icon.urgent {
        background: #ffc107;
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

    .notification-filters {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        background: white;
        color: #666;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .filter-btn:hover {
        background: #f8f9fa;
        border-color: #007bff;
        color: #007bff;
    }

    .filter-btn.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    .btn-secondary {
        padding: 0.5rem 1rem;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        margin-left: auto;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-item {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        gap: 1rem;
        transition: all 0.2s;
        border-left: 4px solid transparent;
    }

    .notification-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .notification-item.unread {
        border-left-color: #007bff;
        background: #f8f9ff;
    }

    .notification-item.urgent {
        border-left-color: #dc3545;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
    }

    .notification-icon.urgent {
        background: #dc3545;
    }

    .notification-icon.stock {
        background: #28a745;
    }

    .notification-icon.chat {
        background: #17a2b8;
    }

    .notification-icon.completed {
        background: #6f42c1;
    }

    .notification-content {
        flex: 1;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .notification-header h4 {
        margin: 0;
        color: #333;
        font-size: 1.1rem;
    }

    .notification-time {
        font-size: 0.85rem;
        color: #666;
    }

    .notification-content p {
        margin: 0 0 1rem 0;
        color: #555;
        line-height: 1.5;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border: 1px solid #007bff;
        background: white;
        color: #007bff;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-action:hover {
        background: #007bff;
        color: white;
    }
</style>
@endpush 