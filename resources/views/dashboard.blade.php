<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Autochain Nexus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .welcome {
            font-size: 24px;
            color: #333;
        }
        .logout {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
        }
        .logout:hover {
            background: #c82333;
        }
        .content {
            padding: 20px 0;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .info-card h3 {
            margin-top: 0;
            color: #007bff;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
        }
        .role-manufacturer { background: #28a745; }
        .role-supplier { background: #17a2b8; }
        .role-vendor { background: #ffc107; color: #212529; }
        .role-retailer { background: #6f42c1; }
        .role-analyst { background: #fd7e14; }
        .role-admin { background: #dc3545; }
        .features {
            background: #e9ecef;
            padding: 20px;
            border-radius: 8px;
        }
        .features h3 {
            margin-top: 0;
            color: #495057;
        }
        .features ul {
            margin: 0;
            padding-left: 20px;
        }
        .features li {
            margin-bottom: 8px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome">Welcome, {{ Auth::user()->name }}!</div>
            <a href="{{ route('login') }}" class="logout">Logout</a>
        </div>
        
        <div class="content">
            <div class="success-message">
                <h2>🎉 Login Successful!</h2>
                <p>You have successfully logged into your Autochain Nexus dashboard.</p>
            </div>
            
            <div class="user-info">
                <div class="info-card">
                    <h3>Account Information</h3>
                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Company:</strong> {{ Auth::user()->company ?? 'N/A' }}</p>
                    <p><strong>Role:</strong> 
                        <span class="role-badge role-{{ Auth::user()->getTable() }}">
                            {{ ucfirst(Auth::user()->getTable()) }}
                        </span>
                    </p>
                </div>
                
                <div class="info-card">
                    <h3>Contact Information</h3>
                    <p><strong>Phone:</strong> {{ Auth::user()->phone ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ Auth::user()->address ?? 'N/A' }}</p>
                    <p><strong>Member Since:</strong> {{ Auth::user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="features">
                <h3>🚀 Dashboard Features Coming Soon</h3>
                <p>This is a placeholder dashboard. The full dashboard with role-specific features will be implemented next:</p>
                <ul>
                    @if(Auth::user()->getTable() === 'admins')
                        <li>User Management & Approval</li>
                        <li>System Administration</li>
                        <li>Role Management</li>
                        <li>System Reports & Analytics</li>
                        <li>Database Management</li>
                        <li>Security Settings</li>
                    @elseif(Auth::user()->getTable() === 'manufacturers')
                        <li>Production Management</li>
                        <li>Quality Control Dashboard</li>
                        <li>Inventory Tracking</li>
                        <li>Order Management</li>
                    @elseif(Auth::user()->getTable() === 'suppliers')
                        <li>Supply Chain Management</li>
                        <li>Delivery Tracking</li>
                        <li>Inventory Management</li>
                        <li>Order Fulfillment</li>
                    @elseif(Auth::user()->getTable() === 'vendors')
                        <li>Product Catalog Management</li>
                        <li>Service Area Management</li>
                        <li>Contract Management</li>
                        <li>Performance Analytics</li>
                    @elseif(Auth::user()->getTable() === 'retailers')
                        <li>Store Management</li>
                        <li>Sales Analytics</li>
                        <li>Inventory Management</li>
                        <li>Customer Management</li>
                    @elseif(Auth::user()->getTable() === 'analysts')
                        <li>Data Analytics Dashboard</li>
                        <li>Market Research Tools</li>
                        <li>Report Generation</li>
                        <li>Trend Analysis</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 