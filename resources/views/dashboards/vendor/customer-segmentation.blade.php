@extends('layouts.dashboard')

@section('title', 'Customer Segmentation')

@section('sidebar-content')
    @include('dashboards.vendor.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.8rem; color: var(--text); letter-spacing: 0.01em;">
       Retailer Segment Distribution
    </h2>
    <button onclick="runRetailerSegmentation()" class="btn btn-primary" style="margin-bottom:1.2rem;">Run Retailer Segmentation</button>
    <div style="margin-bottom:1.5rem;">
        <select id="segmentFilter" onchange="filterSegments()" style="margin-bottom:1rem;">
            <option value="">All Segments</option>
            <option value="High Value">High Value</option>
            <option value="Occasional">Occasional</option>
            <option value="At Risk">At Risk</option>
        </select>
        <canvas id="segmentChart" style="max-width: 600px; margin: 2rem auto 1rem auto; display: block;"></canvas>
        <table id="retailer-segments-table" class="table table-striped" style="width:100%; border-collapse:collapse;"></table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let allSegments = [];
let segmentChart;
async function runRetailerSegmentation() {
    const response = await fetch('http://localhost:8001/segment-retailers', { method: 'POST' });
    const data = await response.json();
    if (data.success) {
        alert('Retailer segmentation complete!');
        fetchRetailerSegments();
    } else {
        alert('Segmentation failed: ' + data.error);
    }
}
async function fetchRetailerSegments() {
    const response = await fetch('http://localhost:8001/retailer-segments');
    const data = await response.json();
    if (data.success) {
        renderSegmentsTable(data.segments);
    } else {
        document.getElementById('retailer-segments-table').innerHTML = '<tr><td colspan="6">Could not fetch segments: ' + data.error + '</td></tr>';
    }
}
function renderSegmentsTable(segments) {
    allSegments = segments; // Save for filtering
    const table = document.getElementById('retailer-segments-table');
    table.innerHTML = '';
    if (!segments.length) {
        table.innerHTML = '<tr><td colspan="6">No data</td></tr>';
        if (segmentChart) segmentChart.destroy();
        return;
    }
    table.innerHTML += `<tr>
        <th>Retailer</th>
        <th>Segment</th>
        <th>Total Orders</th>
        <th>Total Value</th>
        <th>Order Frequency</th>
        <th>Recency (days)</th>
    </tr>`;
    segments.forEach(seg => {
        table.innerHTML += `<tr>
            <td>${seg.name}</td>
            <td>${seg.segment_name}</td>
            <td>${seg.total_orders}</td>
            <td>${seg.total_value}</td>
            <td>${seg.order_frequency}</td>
            <td>${seg.recency_days}</td>
        </tr>`;
    });
    // Prepare data for chart
    const segmentCounts = {};
    segments.forEach(seg => {
        segmentCounts[seg.segment_name] = (segmentCounts[seg.segment_name] || 0) + 1;
    });
    const labels = Object.keys(segmentCounts);
    const data = Object.values(segmentCounts);
    // Render chart
    if (segmentChart) segmentChart.destroy();
    const ctx = document.getElementById('segmentChart').getContext('2d');
    segmentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Retailers per Segment',
                data,
                backgroundColor: ['#2563eb', '#38c172', '#e3342f', '#f59e42'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Retailer Segment Distribution' }
            }
        }
    });
}
function filterSegments() {
    const filter = document.getElementById('segmentFilter').value;
    if (!filter) {
        renderSegmentsTable(allSegments);
    } else {
        renderSegmentsTable(allSegments.filter(seg => seg.segment_name === filter));
    }
}
window.onload = fetchRetailerSegments;

// Fetch and render top products chart
async function fetchAndRenderTopProducts() {
    // Fetch all retailer orders (or CSV with car_model and quantity)
    // Example: fetch from a backend endpoint that returns all orders as JSON
    // Replace the URL with your actual endpoint or CSV fetch logic
    const response = await fetch('/api/retailer-orders'); // Or use CSV parsing if needed
    const orders = await response.json();
    const productCounts = {};
    orders.forEach(order => {
        if (!productCounts[order.car_model]) productCounts[order.car_model] = 0;
        productCounts[order.car_model] += order.quantity;
    });
    const topProducts = Object.entries(productCounts)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);
    const labels = topProducts.map(([product]) => product);
    const data = topProducts.map(([, qty]) => qty);
    const ctx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Top Best-Bought Products',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}
// Call on page load
fetchAndRenderTopProducts();
</script>
@endsection 