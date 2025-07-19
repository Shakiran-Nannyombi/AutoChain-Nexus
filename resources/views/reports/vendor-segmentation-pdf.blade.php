<!DOCTYPE html>
<html>
<head>
    <title>Vendor Segmentation Analytics</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.6; }
        h1 { color: #1a7f37; font-size: 2rem; margin-bottom: 1.5rem; }
        .summary { margin-bottom: 2rem; }
        .segment-bar { display: flex; height: 18px; border-radius: 9px; overflow: hidden; margin-bottom: 1rem; }
        .segment-bar-segment { height: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th, td { padding: 0.6rem; border: 1px solid #bbb; font-size: 0.98rem; }
        th { background: #f1f8e9; color: #1a7f37; }
        td { color: #333; }
    </style>
</head>
<body>
    <h1>Vendor Segmentation Analytics</h1>
    <div class="summary">
        <div style="font-size: 1.1rem; margin-bottom: 0.7rem;">This report provides an overview of vendor segmentation, performance, and top products.</div>
        <div class="segment-bar">
            @php
                $totalVendors = array_sum(array_map(function($d) { return isset($d['count']) ? $d['count'] : 0; }, (array)$segmentAnalytics));
            @endphp
            @foreach($segmentAnalytics as $segment => $data)
                @php $count = isset($data['count']) ? $data['count'] : 0; @endphp
                <div class="segment-bar-segment" style="width: {{ $totalVendors > 0 ? ($count / $totalVendors * 100) : 0 }}%; background: {{ $segmentColors[$segment] ?? '#aaa' }};" title="{{ $segmentLabels[$segment] ?? 'Unsegmented' }} ({{ $count }} vendors)"></div>
            @endforeach
        </div>
        <div style="font-size: 0.98rem; color: #888; margin-bottom: 1.2rem;">Proportion of vendors in each segment</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Vendor</th>
                <th>Segment</th>
                <th>Total Orders</th>
                <th>Most Ordered Product</th>
                <th>Fulfillment Rate</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendorAnalytics as $vendor)
                <tr>
                    <td>{{ $vendor['name'] }}</td>
                    <td>{{ $vendor['segment'] }}</td>
                    <td>{{ $vendor['total_orders'] }}</td>
                    <td>{{ $vendor['most_ordered_product'] }}</td>
                    <td>{{ $vendor['fulfillment_rate'] }}%</td>
                    <td>shs {{ is_numeric(preg_replace('/[^\d.]/', '', $vendor['total_value'])) ? number_format((float)preg_replace('/[^\d.]/', '', $vendor['total_value']), 2) : $vendor['total_value'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2 style="margin-top: 2.5rem; color: #1a7f37; font-size: 1.3rem;">Top 5 Products (All Vendors)</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Order Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $product => $count)
                <tr>
                    <td>{{ $product }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 