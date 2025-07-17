@php
$createdAt = now()->format('d/m/Y H:i');
$periodStart = now()->subDays(7)->format('d/m/Y');
$periodEnd = now()->format('d/m/Y');
// Demo data - replace with real data as needed
$summary = [
    'activeUsers' => 12,
    'inactiveUsers' => 5,
    'activeVehicles' => 8,
    'inactiveVehicles' => 2,
    'totalLogins' => 34,
    'totalWebsiteLogins' => 20,
    'totalMobileLogins' => 14,
    'totalWebsiteReports' => 7,
    'totalMobileReports' => 3,
    'totalScheduledReports' => 4,
];
$users = [
    ['email' => 'john@example.com', 'role' => 'Admin', 'mobile_reports' => 1, 'scheduled_reports' => 2, 'website_reports' => 3, 'alerts' => 0, 'mobile_logins' => 2, 'website_logins' => 4],
    ['email' => 'mark@example.com', 'role' => 'Vendor', 'mobile_reports' => 0, 'scheduled_reports' => 1, 'website_reports' => 1, 'alerts' => 1, 'mobile_logins' => 1, 'website_logins' => 2],
    ['email' => 'nicole@example.com', 'role' => 'Retailer', 'mobile_reports' => 2, 'scheduled_reports' => 0, 'website_reports' => 2, 'alerts' => 0, 'mobile_logins' => 3, 'website_logins' => 1],
    ['email' => 'steve@example.com', 'role' => 'Supplier', 'mobile_reports' => 0, 'scheduled_reports' => 1, 'website_reports' => 0, 'alerts' => 0, 'mobile_logins' => 0, 'website_logins' => 1],
];
// Chart data (demo)
$chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
$chartData = [5, 7, 6, 8, 9, 4, 10];
@endphp
<style>
.report-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.7rem;
    margin-bottom: 1.2rem;
}
.report-logo {
    display: flex;
    align-items: center;
    gap: 1.1rem;
}
.report-logo img {
    height: 48px;
    width: auto;
    border-radius: 8px;
    background: #f3f4f6;
    padding: 0.2rem;
}
.report-title {
    font-size: 2.1rem;
    font-weight: 800;
    color: var(--primary, #2563eb);
}
.report-meta {
    color: #64748b;
    font-size: 1.08rem;
    text-align: right;
}
.report-period {
    background: #222;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 6px;
    padding: 0.5rem 1.2rem;
    display: inline-block;
    margin-bottom: 1.2rem;
}
.report-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 2.5rem;
    margin-bottom: 2rem;
}
.report-summary-block {
    flex: 1 1 180px;
    min-width: 160px;
    background: #f3f4f6;
    border-radius: 10px;
    padding: 1.2rem 1rem;
    color: #2563eb;
    font-size: 1.08rem;
    box-shadow: 0 2px 8px rgba(37,99,235,0.06);
}
.report-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2rem;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(37,99,235,0.06);
}
.report-table th, .report-table td {
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    font-size: 1.05rem;
}
.report-table th {
    background: #f3f4f6;
    color: #2563eb;
    font-weight: 700;
}
.report-table tr:last-child td {
    border-bottom: none;
}
.report-table .total-row {
    background: #e6f9ed;
    font-weight: 700;
    color: #16610E;
}
.print-btn {
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1.3rem;
    font-size: 1.08rem;
    font-weight: 600;
    cursor: pointer;
    margin-left: 1.2rem;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.print-btn:hover {
    background: #1741a0;
}
.chart-container {
    width: 100%;
    max-width: 600px;
    margin: 2rem auto 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(37,99,235,0.06);
    padding: 1.5rem 1.2rem 1.2rem 1.2rem;
}
@media (max-width: 700px) {
    .report-summary { flex-direction: column; gap: 1rem; }
    .report-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    .chart-container { max-width: 100%; }
}
</style>
<div class="content-card" style="max-width: 900px; margin: 2rem auto; padding: 2.5rem 2rem; background: #fff; border-radius: 18px; box-shadow: var(--shadow);">
    <div class="report-header">
        <div class="report-logo">
            <img src="/images/admin.png" alt="System Logo">
            <span class="report-title">Autochain Nexus<br><span style="font-size:1.1rem; font-weight:400; color:#64748b;">User Activity Report</span></span>
        </div>
        <div class="report-meta">
            Created: {{ $createdAt }}<br>
            <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print / Export</button>
        </div>
    </div>
    <div class="report-period">Report Totals for: {{ $periodStart }} - {{ $periodEnd }}</div>
    <div class="report-summary">
        <div class="report-summary-block">
            <div><strong>Active Users:</strong> {{ $summary['activeUsers'] }}</div>
            <div><strong>Inactive Users:</strong> {{ $summary['inactiveUsers'] }}</div>
            <div><strong>Total Logins:</strong> {{ $summary['totalLogins'] }}</div>
        </div>
        <div class="report-summary-block">
            <div><strong>Active Vehicles:</strong> {{ $summary['activeVehicles'] }}</div>
            <div><strong>Inactive Vehicles:</strong> {{ $summary['inactiveVehicles'] }}</div>
            <div><strong>Total Website Logins:</strong> {{ $summary['totalWebsiteLogins'] }}</div>
            <div><strong>Total Mobile Logins:</strong> {{ $summary['totalMobileLogins'] }}</div>
        </div>
        <div class="report-summary-block">
            <div><strong>Total Website Reports Run:</strong> {{ $summary['totalWebsiteReports'] }}</div>
            <div><strong>Total Mobile Reports Run:</strong> {{ $summary['totalMobileReports'] }}</div>
            <div><strong>Total Scheduled Reports Run:</strong> {{ $summary['totalScheduledReports'] }}</div>
        </div>
    </div>
    <div class="chart-container">
        <div style="font-size: 1.1rem; color: #2563eb; font-weight: 700; margin-bottom: 0.7rem;">User Activity (Last 7 Days)</div>
        <canvas id="userActivityChart" width="540" height="220"></canvas>
    </div>

    <!-- Inventory Summary Section -->
    <div class="content-card" style="margin-top:2.5rem; margin-bottom:2.5rem; background:#f8fafc; border-radius:14px; box-shadow:var(--shadow); padding:2rem;">
        <div style="display:flex; align-items:center; gap:0.7rem; margin-bottom:1.2rem;">
            <i class="fas fa-boxes" style="color:#2563eb; font-size:1.5rem;"></i>
            <span style="font-size:1.25rem; font-weight:700; color:#2563eb;">Inventory Summary</span>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:2rem;">
            <div class="report-summary-block" style="color:#16610E;">
                <div><strong>Total Products:</strong> 320</div>
            </div>
            <div class="report-summary-block" style="color:#2563eb;">
                <div><strong>In Stock:</strong> 270</div>
            </div>
            <div class="report-summary-block" style="color:#eab308;">
                <div><strong>Low Stock Items:</strong> 18</div>
            </div>
            <div class="report-summary-block" style="color:#dc2626;">
                <div><strong>Out of Stock:</strong> 32</div>
            </div>
            <div class="report-summary-block" style="color:#0e7490;">
                <div><strong>Total Inventory Value:</strong> $45,200</div>
            </div>
        </div>
    </div>

    <!-- Validation Outcomes Section -->
    <div class="content-card" style="margin-bottom:2.5rem; background:#f8fafc; border-radius:14px; box-shadow:var(--shadow); padding:2rem;">
        <div style="display:flex; align-items:center; gap:0.7rem; margin-bottom:1.2rem;">
            <i class="fas fa-clipboard-check" style="color:#2563eb; font-size:1.5rem;"></i>
            <span style="font-size:1.25rem; font-weight:700; color:#2563eb;">Validation Outcomes</span>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:2rem; margin-bottom:1.5rem;">
            <div class="report-summary-block" style="color:#16610E;">
                <div><strong>Total Validations:</strong> 150</div>
            </div>
            <div class="report-summary-block" style="color:#2563eb;">
                <div><strong>Passed:</strong> 132</div>
            </div>
            <div class="report-summary-block" style="color:#dc2626;">
                <div><strong>Failed:</strong> 12</div>
            </div>
            <div class="report-summary-block" style="color:#eab308;">
                <div><strong>Pending:</strong> 6</div>
            </div>
        </div>
        <div style="margin-top:1.2rem;">
            <div style="font-weight:600; color:#2563eb; margin-bottom:0.5rem;">Most Common Issues</div>
            <ul style="margin-left:1.2rem; color:#dc2626;">
                <li>Missing product documentation (5)</li>
                <li>Invalid batch numbers (3)</li>
                <li>Unverified supplier info (2)</li>
                <li>Other (2)</li>
            </ul>
        </div>
        <div style="margin-top:1.2rem;">
            <div style="font-weight:600; color:#2563eb; margin-bottom:0.5rem;">Recent Validation Activity</div>
            <table class="report-table" style="margin-top:0;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Validator</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>2025-07-12</td><td>Product A</td><td style="color:#16610E;">Passed</td><td>Jane Doe</td></tr>
                    <tr><td>2025-07-12</td><td>Product B</td><td style="color:#dc2626;">Failed</td><td>John Smith</td></tr>
                    <tr><td>2025-07-11</td><td>Product C</td><td style="color:#eab308;">Pending</td><td>Jane Doe</td></tr>
                    <tr><td>2025-07-10</td><td>Product D</td><td style="color:#16610E;">Passed</td><td>Emily Lee</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="font-size: 1.2rem; color: #2563eb; font-weight: 700; margin-bottom: 0.7rem; margin-top:2.2rem;">Entire Fleet</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>USER NAME</th>
                <th>ROLE</th>
                <th>MOBILE REPORTS RUN</th>
                <th>SCHEDULED REPORTS RUN</th>
                <th>WEBSITE REPORTS RUN</th>
                <th>ALERTS FIRED</th>
                <th>MOBILE LOGINS</th>
                <th>WEBSITE LOGINS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user['email'] }}</td>
                <td>{{ $user['role'] }}</td>
                <td>{{ $user['mobile_reports'] }}</td>
                <td>{{ $user['scheduled_reports'] }}</td>
                <td>{{ $user['website_reports'] }}</td>
                <td>{{ $user['alerts'] }}</td>
                <td>{{ $user['mobile_logins'] }}</td>
                <td>{{ $user['website_logins'] }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>Entire Fleet</td>
                <td>Total</td>
                <td>{{ collect($users)->sum('mobile_reports') }}</td>
                <td>{{ collect($users)->sum('scheduled_reports') }}</td>
                <td>{{ collect($users)->sum('website_reports') }}</td>
                <td>{{ collect($users)->sum('alerts') }}</td>
                <td>{{ collect($users)->sum('mobile_logins') }}</td>
                <td>{{ collect($users)->sum('website_logins') }}</td>
            </tr>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('userActivityChart').getContext('2d');
const userActivityChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Logins',
            data: @json($chartData),
            backgroundColor: '#2563eb',
            borderRadius: 6
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 2 }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script> 