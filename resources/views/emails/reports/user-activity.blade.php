<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header h1 {
            color: #0d2a4e;
        }
        .content {
            padding: 20px 0;
        }
        .stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            text-align: center;
        }
        .stat-item {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
        }
        .stat-item .value {
            font-size: 2rem;
            font-weight: bold;
            color: #2a6eea;
        }
        .stat-item .label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.8rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Activity Report</h1>
            <p>Here is the summary for the period of {{ $data['period'] }}.</p>
        </div>
        <div class="content">
            <div class="stat-grid">
                <div class="stat-item">
                    <div class="value">{{ $data['new_users'] }}</div>
                    <div class="label">New Users</div>
                </div>
                <div class="stat-item">
                    <div class="value">{{ $data['approved_users'] }}</div>
                    <div class="label">Approved Applications</div>
                </div>
                <div class="stat-item">
                    <div class="value">{{ $data['pending_users'] }}</div>
                    <div class="label">Pending Applications</div>
                </div>
                <div class="stat-item">
                    <div class="value">{{ $data['total_users'] }}</div>
                    <div class="label">Total Active Users</div>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated report from Autochain Nexus.</p>
        </div>
    </div>
</body>
</html> 