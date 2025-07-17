<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendor Analytics Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; }
        h1, h2, h3 { color: #2d6a4f; margin-bottom: 0.5em; }
        .section { margin-bottom: 2em; }
        .chart-img { display: block; margin: 0 auto 1em auto; max-width: 400px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5em; font-size: 0.97em; }
        th, td { border: 1px solid #bbb; padding: 0.5em 0.7em; text-align: left; }
        th { background: #e9f5ee; }
        .segment-label { font-weight: bold; padding: 0.2em 0.7em; border-radius: 5px; color: #fff; }
        .gold { background: #4CAF50; }
        .silver { background: #2196F3; }
        .bronze { background: #FFC107; color: #222; }
        .unsegmented { background: #BDBDBD; }
    </style>
</head>
<body>
    <h1>Vendor Analytics Report</h1>
    <p style="font-size:1.1em; margin-bottom:2em;">Comprehensive analytics for all vendors, including segmentation, order statistics, and product trends.</p>

    <div class="section">
        <h2>Summary</h2>
        <table>
            <tr>
                <th>Segment</th>
                <th>Vendors</th>
                <th>Total Orders</th>
                <th>Total Value</th>
            </tr>
            @foreach($segmentAnalytics as $seg => $data)
                <tr>
                    <td>
                        <span class="segment-label {{ strtolower($segmentLabels[$seg] ?? 'unsegmented') }}">
                            {{ $segmentLabels[$seg] ?? 'Unsegmented' }}
                        </span>
                    </td>
                    <td>{{ $data['count'] }}</td>
                    <td>{{ $data['total_orders'] }}</td>
                    <td>₦{{ number_format($data['total_value'], 2) }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <h2>Charts</h2>
        <div style="display: flex; gap: 2em; align-items: flex-start;">
            <div style="flex:1;">
                <h3>Segment Distribution</h3>
                <img class="chart-img" src="data:image/png;base64,{{ $segmentChartImg }}" alt="Segment Distribution Chart">
            </div>
            <div style="flex:1;">
                <h3>Top 5 Products</h3>
                <img class="chart-img" src="data:image/png;base64,{{ $topProductsChartImg }}" alt="Top Products Chart">
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Top 5 Products (All Vendors)</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>Order Count</th>
            </tr>
            @foreach($topProducts as $product => $count)
                <tr>
                    <td>{{ $product }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <h2>Vendor Analytics</h2>
        <table>
            <tr>
                <th>Vendor</th>
                <th>Segment</th>
                <th>Total Orders</th>
                <th>Most Ordered Product</th>
                <th>Top 3 Products</th>
                <th>Total Value</th>
            </tr>
            @foreach($vendorAnalytics as $vendor)
                <tr>
                    <td>{{ $vendor['name'] }}</td>
                    <td>{{ $vendor['segment'] }}</td>
                    <td>{{ $vendor['total_orders'] }}</td>
                    <td>{{ $vendor['most_ordered_product'] }}</td>
                    <td>{{ implode(', ', $vendor['top_3_products']) }}</td>
                    <td>₦{{ number_format($vendor['total_value'], 2) }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <p style="font-size:0.95em; color:#888;">Generated on {{ date('Y-m-d H:i') }}</p>
</body>
</html> 