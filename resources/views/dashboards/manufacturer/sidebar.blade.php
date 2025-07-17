<a href="/manufacturer/dashboard" class="nav-item {{ request()->is('manufacturer/dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Control Center
</a>
<a href="/manufacturer/products" class="nav-item {{ request()->is('manufacturer/products*') ? 'active' : '' }}">
    <i class="fas fa-box"></i> Products
</a>
<a href="/manufacturer/checklists" class="nav-item {{ request()->is('manufacturer/checklists*') ? 'active' : '' }}">
    <i class="fas fa-list-alt"></i> Checklists
</a>
<a href="/manufacturer/orders" class="nav-item {{ request()->is('manufacturer/orders*') ? 'active' : '' }}">
    <i class="fas fa-shopping-cart"></i> Supplier Orders
</a>
<a href="/manufacturer/vendor-orders" class="nav-item {{ request()->is('manufacturer/vendor-orders*') ? 'active' : '' }}">
    <i class="fas fa-truck"></i> Vendor Orders
</a>
<a href="/manufacturer/material-receipt" class="nav-item {{ request()->is('manufacturer/material-receipt*') ? 'active' : '' }}">
    <i class="fas fa-truck-loading"></i> Material Receipt
</a>
<a href="/manufacturer/inventory-status" class="nav-item {{ request()->is('manufacturer/inventory-status*') ? 'active' : '' }}">
    <i class="fas fa-cubes"></i> Inventory Status
</a>
<a href="/manufacturer/quality-control" class="nav-item {{ request()->is('manufacturer/quality-control*') ? 'active' : '' }}">
    <i class="fas fa-shield-alt"></i> Quality Control
</a>
<a href="/manufacturer/production-analytics" class="nav-item {{ request()->is('manufacturer/production-analytics*') ? 'active' : '' }}">
    <i class="fas fa-chart-bar"></i> Production Analytics
</a>
<a href="/manufacturer/production-reports" class="nav-item {{ request()->is('manufacturer/production-reports*') ? 'active' : '' }}">
    <i class="fas fa-file-alt"></i> Production Reports
</a>
<a href="/manufacturer/demand-prediction" class="nav-item {{ request()->is('manufacturer/demand-prediction*') ? 'active' : '' }}">
    <i class="fas fa-chart-line"></i> Demand Prediction
</a>
<a href="/manufacturer/chat" class="nav-item {{ request()->is('manufacturer/chat*') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Chat
</a>
<a href="/manufacturer/settings" class="nav-item {{ request()->is('manufacturer/settings*') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>

