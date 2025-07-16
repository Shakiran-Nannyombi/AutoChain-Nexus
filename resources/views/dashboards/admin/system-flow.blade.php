@extends('layouts.dashboard')

@section('title', 'System Flow Visualization')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    <div class="content-card">
    <!-- Welcome Message -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.1rem; font-weight: 800; color: var(--primary, #2563eb); margin-bottom: 0.3rem;">Welcome to the System Flow Dashboard</h1>
        <p style="font-size: 1.15rem; color: #64748b;">Monitor your system's health, user engagement, and workflow progress all in one place.</p>
    </div>
    <!-- Top Navigation Tabs -->
    <div class="dashboard-tabs" style="display: flex; gap: 2.5rem; border-bottom: 2px solid #e5e7eb; margin-bottom: 2.5rem; padding-top: 1.5rem;">
        <a href="#" class="tab-link active" data-tab="overview" style="color: var(--primary, #2563eb); font-weight: 700; padding-bottom: 0.7rem; border-bottom: 2px solid var(--primary, #2563eb);">System Overview</a>
        <a href="#" class="tab-link" data-tab="kpi" style="color: #64748b; font-weight: 500; padding-bottom: 0.7rem; border-bottom: 2px solid transparent;">System Flow KPI</a>
        <a href="#" class="tab-link" data-tab="user" style="color: #64748b; font-weight: 500; padding-bottom: 0.7rem; border-bottom: 2px solid transparent;">User KPIs</a>
        <a href="#" class="tab-link" data-tab="supplier" style="color: #64748b; font-weight: 500; padding-bottom: 0.7rem; border-bottom: 2px solid transparent;">Supplier KPIs</a>
    </div>
    <!-- Tab Content Areas -->
    <div id="tab-content-overview" class="tab-content" style="display: block;">
        <!-- Hero Section with Illustration -->
        <div style="display: flex; align-items: center; gap: 2.5rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
            <div style="flex: 2; min-width: 320px;">
                <h2 style="color: var(--secondary); font-size: 1.8rem; font-weight: 800; margin-bottom: 1rem;">System Overview</h2>
                <p style="color: #64748b; font-size: 1.15rem; margin-bottom: 1.2rem;">Get a high-level summary of your inventory management system, including user roles, modules, and workflow highlights. Use this section to understand the overall structure and flow of your platform.</p>
                <ul style="margin-top: 1rem; color: #16610E; font-size: 1.08rem;">
                    <li style="margin-bottom: 0.7rem;"><span style="font-size: 1.3rem; color: #22c55e; margin-right: 0.5rem;">✔️</span><strong>Multi-role access:</strong> <span style="color: #2563eb;">Admin, Vendor, Manufacturer, Retailer, Analyst, Supplier</span></li>
                    <li style="margin-bottom: 0.7rem;"><span style="font-size: 1.3rem; color: #22c55e; margin-right: 0.5rem;">✔️</span><strong>Real-time inventory tracking</strong> and reporting for all users</li>
                    <li style="margin-bottom: 0.7rem;"><span style="font-size: 1.3rem; color: #22c55e; margin-right: 0.5rem;">✔️</span><strong>Automated validation</strong> and approval workflows</li>
                    <li style="margin-bottom: 0.7rem;"><span style="font-size: 1.3rem; color: #22c55e; margin-right: 0.5rem;">✔️</span><strong>Integrated analytics</strong> and forecasting modules</li>
                </ul>
            </div>
            <div style="flex: 1; min-width: 260px; display: flex; justify-content: center; align-items: center;">
                <img src="/images/admin.png" alt="System Overview" style="max-width: 220px; width: 100%; border-radius: 12px; box-shadow: 0 2px 12px rgba(37,99,235,0.08); background: #f3f4f6; padding: 0.5rem;" />
            </div>
        </div>
        <!-- Instructions Section -->
        <div style="background: #f3f4f6; border-radius: 10px; padding: 1.5rem 2rem; margin-bottom: 2.5rem;">
            <h3 style="color: var(--primary, #2563eb); font-size: 1.25rem; font-weight: 700; margin-bottom: 0.7rem;">How to use this dashboard</h3>
            <ol style="color: #2563eb; font-size: 1.08rem; margin-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;"><strong>Navigate tabs:</strong> Switch between System Overview, KPIs, and Supplier metrics using the tabs above.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Monitor KPIs:</strong> Review key performance indicators to track system health and workflow progress.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Explore analytics:</strong> Use the analytics and reports sections for deeper insights.</li>
                <li><strong>Take action:</strong> Use the sidebar to manage users, schedule visits, and validate suppliers as needed.</li>
            </ol>
        </div>
        <!-- Feature Explanations -->
        <div style="margin-bottom: 1.5rem;">
            <h4 style="color: #16610E; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Feature Highlights</h4>
            <ul style="color: #64748b; font-size: 1.05rem;">
                <li><strong>Role-based Access:</strong> Each user type has tailored permissions and dashboards.</li>
                <li><strong>Validation Workflow:</strong> Automated checks and manual approvals for secure onboarding.</li>
                <li><strong>Real-time Data:</strong> Inventory, orders, and visits update instantly for all users.</li>
                <li><strong>Analytics & Forecasting:</strong> Built-in tools for demand prediction and performance tracking.</li>
            </ul>
        </div>
    </div>
    <div id="tab-content-kpi" class="tab-content" style="display: none;">
        <h2 style="color: var(--secondary); font-size: 1.8rem; font-weight: 700; margin-bottom: 1rem;">System Flow KPIs</h2>
        <p style="color: #64748b;">Monitor key performance indicators for your system’s workflow, including active users, connections, validations, visits, and vendor status.</p>
         <!-- Info/Alert Box -->
         <div class="dashboard-info-box" style="background: #fff; border-radius: 10px; padding: 1.2rem 1.5rem; color: #16610E; font-size: 1.08rem; margin-top: 2rem;">
            <i class="fas fa-info-circle" style="margin-right: 0.7rem; color: #2563eb;"></i>
            <strong>Admin Tip:</strong> Use this dashboard to monitor your system's health, user engagement, and workflow progress. For more details, visit the Analytics or User Management pages.
        </div> <br><br>
        <!-- KPI Cards Row with Live Data and Colored Backgrounds -->
        <div class="kpi-row" style="display: flex; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
            <div class="kpi-card" style="flex: 1 1 180px; min-width: 160px; background: #e6f9ed; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #16610E;">Active Users</span>
                <span style="font-size: 2rem; font-weight: bold; color: #22c55e;">{{ $stats['activeUsers'] ?? '--' }}</span>
            </div>
            <div class="kpi-card" style="flex: 1 1 180px; min-width: 160px; background: #fffbe6; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #b45309;">Active Connections</span>
                <span style="font-size: 2rem; font-weight: bold; color: #eab308;">{{ $stats['activeConnections'] ?? '--' }}</span>
        </div>
            <div class="kpi-card" style="flex: 1 1 180px; min-width: 160px; background: #ffeaea; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #b91c1c;">Pending Validations</span>
                <span style="font-size: 2rem; font-weight: bold; color: #f87171;">{{ $stats['pendingValidations'] ?? '--' }}</span>
            </div>
            <div class="kpi-card" style="flex: 1 1 180px; min-width: 160px; background: #e0f7fa; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #0369a1;">Visits Scheduled</span>
                <span style="font-size: 2rem; font-weight: bold; color: #38bdf8;">{{ $stats['visitsScheduled'] ?? '--' }}</span>
        </div>
            <div class="kpi-card" style="flex: 1 1 180px; min-width: 160px; background: #e6f9ed; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.2rem 1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #16610E;">Visits Completed</span>
                <span style="font-size: 2rem; font-weight: bold; color: #22c55e;">{{ $stats['visitsCompleted'] ?? '--' }}</span>
            </div>
        </div>      
        <!-- Real-Time Active User Connections -->
        <div style="background: #e7f7fb; border-radius: 10px; padding: 1.2rem 1.5rem; color: #16610E; font-size: 1.08rem; margin-bottom: 2rem; border-left: 5px solid var(--primary, #2563eb);">
            <i class="fas fa-link" style="margin-right: 0.7rem; color: #2563eb;"></i>
            <strong>Current Active User Connections:</strong>
            <ul style="margin-top: 0.7rem; margin-bottom: 0; color: #2563eb;">
                @forelse($activeConnectionsList as $conn)
                    <li>
                        <strong>{{ $conn->sender->name ?? 'Unknown' }}</strong> ({{ $conn->sender->role ?? 'N/A' }})
                        &rarr;
                        <strong>{{ $conn->receiver->name ?? 'Unknown' }}</strong> ({{ $conn->receiver->role ?? 'N/A' }})
                        <span style="color: #64748b; font-size: 0.97em;">({{ $conn->created_at ? $conn->created_at->diffForHumans() : '' }})</span>
                    </li>
                @empty
                    <li>No recent user-to-user connections found.</li>
                @endforelse
            </ul>
            <div style="margin-top: 0.7rem; color: #64748b; font-size: 0.98rem;">These are the most recent real-time communications or workflow connections between your platform's users.</div>
        </div>
        <!-- Active Connections: Who Can Communicate With Whom? -->
        <div style="background: #f3f4f6; border-radius: 10px; padding: 1.5rem 2rem; margin-bottom: 2rem;">
            <h3 style="color: var(--primary, #2563eb); font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem;">
                Active Connections: Who Can Communicate With Whom?
            </h3>
            <ul style="color: #2563eb; font-size: 1.08rem; margin-bottom: 1.2rem;">
                <li><strong>Vendors</strong>: Can communicate with <strong>Manufacturers, Retailers, and Admin</strong><br><span style="color: #64748b;">Vendors coordinate with manufacturers for supplies, retailers for sales, and analysts for demand forecasting.</span></li>
                <li><strong>Manufacturers</strong>: Can communicate with <strong>Suppliers, Vendors, Analysts, and Admin</strong><br><span style="color: #64748b;">Manufacturers source raw materials from suppliers, work with vendors for distribution, and consult analysts for production planning.</span></li>
                <li><strong>Suppliers</strong>: Can communicate with <strong>Manufacturers and Admin</strong><br><span style="color: #64748b;">Suppliers provide raw materials or products directly to manufacturers.</span></li>
                <li><strong>Retailers</strong>: Can communicate with <strong>Vendors, Customers, and Admin</strong><br><span style="color: #64748b;">Retailers order products from vendors and use analyst insights for inventory and sales strategies.</span></li>
                <li><strong>Analysts</strong>: Can communicate with <strong>All roles (Manufacturers, Admin)</strong><br><span style="color: #64748b;">Analysts provide insights, forecasts, and reports to all other roles to optimize operations and decision-making.</span></li>
                <li><strong>Admin</strong>: Can monitor and oversee all connections and communications in the system.</li>
            </ul>
            <div style="color: #64748b; font-size: 0.98rem;">This matrix shows which roles can directly communicate and collaborate within your platform.</div>
        </div>
    </div>
    <div id="tab-content-user" class="tab-content" style="display: none;">
        <h2 style="color: #ff8800; font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">User KPIs</h2>
        <p style="color: #64748b;">Track user-specific metrics such as registration trends, active/inactive ratios, and engagement rates. Use this data to optimize user onboarding and retention strategies.</p>
        <!-- KPI List and Pie Chart in a single row -->
        <div style="display: flex; flex-wrap: wrap; gap: 2.5rem; align-items: stretch; margin-top: 2rem;">
            <!-- KPI List -->
            <ul style="flex: 1 1 320px; list-style: none; padding: 0; margin: 0; font-size: 1.15rem; align-self: center;">
                <li style="margin-bottom: 1.3rem;"><span style="color: #2563eb;"><i class="fas fa-user"></i></span> <span style="color: #2563eb;">New registrations this month:</span> <strong>{{ $stats['newUsersThisMonth'] ?? '12' }}</strong></li>
                <li style="margin-bottom: 1.3rem;"><span style="color: #22c55e;"><i class="fas fa-circle"></i></span> <span style="color: #22c55e;">Active users:</span> <strong>{{ $stats['activeUsers'] ?? '--' }}</strong></li>
                <li style="margin-bottom: 1.3rem;"><span style="color: #f87171;"><i class="fas fa-circle"></i></span> <span style="color: #f87171;">Inactive users:</span> <strong>{{ $stats['inactiveUsers'] ?? '5' }}</strong></li>
                <li style="margin-bottom: 1.3rem;"><span style="color: #2563eb;"><i class="fas fa-chart-bar"></i></span> <span style="color: #2563eb;">Engagement rate:</span> <strong>{{ $stats['engagementRate'] ?? '78%' }}</strong></li>
            </ul>
            <!-- Pie Chart: Active vs Inactive Users -->
            <div style="flex: 1 1 380px; min-width: 320px; background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <span style="font-size: 1.1rem; font-weight:bold; color: var(--primary); margin-bottom: 1.3rem;">User Status Distribution</span>
                <canvas id="userStatusPie" width="200" height="200"></canvas>
            </div>
        </div>
        <!-- Engagement Bar Chart on its own line, but much wider -->
        <div style="width: 100%; margin-top: 2.5rem; display: flex; justify-content: center;">
            <div style="width: 100%; max-width: 1000px; background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 2rem 1.5rem 1.5rem 1.5rem; display: flex; flex-direction: column; align-items: center;">
                <span style="font-size: 1.1rem; font-weight:bold; color: var(--primary);">Engagement Rate</span>
                <div style="width: 100%; display: flex; justify-content: flex-end; align-items: center; margin-bottom: 1.2rem;">
                    <select id="engagementPeriod" style="padding: 0.3rem 1.7rem 0.3rem 0.5rem; border-radius: 6px; border: 1px solid #e5e7eb; font-size: 1rem; background: #fff url('data:image/svg+xml;utf8,<svg fill=\'%2364748b\' height=\'20\' viewBox=\'0 0 20 20\' width=\'20\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7.293 7.293a1 1 0 011.414 0L10 8.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z\'/></svg>') no-repeat right 0.7rem center/1.1em auto; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                        <option value="1">Last 1 month (weekly)</option>
                        <option value="2">Last 2 months (weekly)</option>
                        <option value="3">Last 3 months (weekly)</option>
                        <option value="4">Last 4 months (weekly)</option>
                        <option value="5">Last 5 months (monthly)</option>
                        <option value="6">Last 6 months (monthly)</option>
                        <option value="12" selected>Last 12 months (monthly)</option>
                    </select>
                </div>
                <canvas id="engagementBar" width="900" height="260" style="margin-top: 1.2rem;"></canvas>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        // Pie Chart: Active vs Inactive Users
        const userStatusPie = document.getElementById('userStatusPie').getContext('2d');
        new Chart(userStatusPie, {
            type: 'pie',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: [
                        {{ $stats['activeUsers'] ?? 100 }},
                        {{ $stats['inactiveUsers'] ?? 5 }}
                    ],
                    backgroundColor: ['#22c55e', '#f87171'],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { display: true, position: 'bottom' }
                }
            }
        });
        // Demo engagement data for different periods (all months start from January)
        const engagementData = {
            1: { labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], data: [75, 82, 77, 85] },
            2: { labels: ['W1-Jan', 'W2-Jan', 'W3-Jan', 'W4-Jan', 'W1-Feb', 'W2-Feb', 'W3-Feb', 'W4-Feb'], data: [72, 80, 78, 85, 77, 83, 81, 78] },
            3: { labels: ['W1-Jan', 'W2-Jan', 'W3-Jan', 'W4-Jan', 'W1-Feb', 'W2-Feb', 'W3-Feb', 'W4-Feb', 'W1-Mar', 'W2-Mar', 'W3-Mar', 'W4-Mar'], data: [70, 82, 74, 86, 75, 77, 88, 80, 79, 81, 82, 80] },
            4: { labels: Array.from({length: 16}, (_, i) => 'W' + (i+1) + '-' + ['Jan','Feb','Mar','Apr'][Math.floor(i/4)]), data: [68, 80, 72, 84, 73, 85, 77, 88, 76, 78, 80, 91, 79, 80, 82, 93] },
            5: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'], data: [75, 88, 80, 77, 89] },
            6: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], data: [72, 85, 78, 90, 77, 89] },
            12: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], data: [68, 80, 72, 84, 73, 85, 77, 88, 76, 78, 80, 91] }
        };
        // Initial chart
        let engagementBarChart;
        function renderEngagementChart(period) {
            const ctx = document.getElementById('engagementBar').getContext('2d');
            if (engagementBarChart) engagementBarChart.destroy();
            engagementBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: engagementData[period].labels,
                    datasets: [{
                        label: 'Engagement %',
                        data: engagementData[period].data,
                        backgroundColor: '#2563eb',
                        borderRadius: 6
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { stepSize: 10 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
        // Initial render (12 months)
        renderEngagementChart(12);
        document.getElementById('engagementPeriod').addEventListener('change', function() {
            renderEngagementChart(this.value);
        });
        </script>
    </div>
    <div id="tab-content-supplier" class="tab-content" style="display: none;">
        <h2 style="color: #ff8800; font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Supplier KPIs</h2>
        <p style="color: #64748b;">Analyze supplier performance, delivery reliability, and compliance with validation requirements. This helps ensure a robust and efficient supply chain.</p>
        <!-- Supplier KPI Cards Row -->
        <div class="kpi-row" style="display: flex; gap: 1.2rem; margin-top: 2.2rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
            <div class="kpi-card" style="flex: 1 1 220px; min-width: 180px; background: #e0f7fa; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.3rem 1.1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #0369a1; display: flex; align-items: center;"><span style='font-size:1.3rem; margin-right:0.5rem;'>🚚</span>On-time deliveries</span>
                <span style="font-size: 2rem; font-weight: bold; color: #38bdf8;">{{ $stats['onTimeDeliveries'] ?? '92%' }}</span>
            </div>
            <div class="kpi-card" style="flex: 1 1 220px; min-width: 180px; background: #fffbe6; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.3rem 1.1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #b45309; display: flex; align-items: center;"><span style='font-size:1.3rem; margin-right:0.5rem;'>📦</span>Total suppliers</span>
                <span style="font-size: 2rem; font-weight: bold; color: #eab308;">{{ $stats['totalSuppliers'] ?? '18' }}</span>
            </div>
            <div class="kpi-card" style="flex: 1 1 220px; min-width: 180px; background: #e6f9ed; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.3rem 1.1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #16610E; display: flex; align-items: center;"><span style='font-size:1.3rem; margin-right:0.5rem;'>✅</span>Validation compliance</span>
                <span style="font-size: 2rem; font-weight: bold; color: #22c55e;">{{ $stats['supplierValidationCompliance'] ?? '95%' }}</span>
            </div>
            <div class="kpi-card" style="flex: 1 1 220px; min-width: 180px; background: #ffeaea; border-radius: 14px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 1.3rem 1.1rem; display: flex; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 1.1rem; color: #b91c1c; display: flex; align-items: center;"><span style='font-size:1.3rem; margin-right:0.5rem;'>⚠️</span>Issues reported this month</span>
                <span style="font-size: 2rem; font-weight: bold; color: #f87171;">{{ $stats['supplierIssues'] ?? '2' }}</span>
                </div>
                    </div>
        <!-- Supplier KPI Analysis Section -->
        <div style="background: #f3f4f6; border-radius: 12px; box-shadow: 0 2px 8px rgba(37,99,235,0.06); padding: 2rem 2.2rem; margin-bottom: 2.5rem;">
            <h3 style="color: #16610E; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center;"><span style='font-size:1.5rem; margin-right:0.6rem;'>📊</span>Supplier Performance Analysis</h3>
            <ul style="color: #2563eb; font-size: 1.08rem; margin-left: 1.2rem; margin-bottom: 0.7rem;">
                <li style="margin-bottom: 0.6rem;"><strong>On-time deliveries</strong> are at <span style='color:#38bdf8;'>{{ $stats['onTimeDeliveries'] ?? '92%' }}</span>. <span style='color:#16610E;'>This indicates strong supplier reliability.</span> <span style='color:#64748b;'>Keep monitoring for seasonal dips.</span></li>
                <li style="margin-bottom: 0.6rem;"><strong>Total suppliers</strong> is <span style='color:#eab308;'>{{ $stats['totalSuppliers'] ?? '18' }}</span>. <span style='color:#16610E;'>A diverse supplier base reduces risk.</span> <span style='color:#64748b;'>Consider onboarding more for redundancy.</span></li>
                <li style="margin-bottom: 0.6rem;"><strong>Validation compliance</strong> is <span style='color:#22c55e;'>{{ $stats['supplierValidationCompliance'] ?? '95%' }}</span>. <span style='color:#16610E;'>Excellent compliance rate.</span> <span style='color:#64748b;'>Maintain regular audits to sustain this.</span></li>
                <li style="margin-bottom: 0.6rem;"><strong>Issues reported</strong> this month: <span style='color:#f87171;'>{{ $stats['supplierIssues'] ?? '2' }}</span>. <span style='color:#b91c1c;'>Investigate recurring issues promptly.</span> <span style='color:#64748b;'>Proactive communication can reduce future incidents.</span></li>
            </ul>
            <div style="margin-top: 1.2rem; color: #16610E; font-size: 1.08rem; background: #e6f9ed; border-radius: 8px; padding: 1rem 1.2rem;">
                <strong>Recommendation:</strong> <span style='color:#2563eb;'>Continue to nurture high-performing suppliers and address issues early. Consider supplier training or incentives for further improvement.</span>
            </div>
        </div>
    </div>
    <script>
    document.querySelectorAll('.tab-link').forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            // Remove active class and reset styles from all tabs
            document.querySelectorAll('.tab-link').forEach(function(t) {
                t.classList.remove('active');
                t.style.color = '#64748b';
                t.style.fontWeight = '500';
                t.style.borderBottom = '2px solid transparent';
            });
            // Add active class and styles to clicked tab
            this.classList.add('active');
            this.style.color = 'var(--primary, #2563eb)';
            this.style.fontWeight = '700';
            this.style.borderBottom = '2px solid var(--primary, #2563eb)';
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function(content) {
                content.style.display = 'none';
            });
            // Show selected tab content
            var tabName = this.getAttribute('data-tab');
            document.getElementById('tab-content-' + tabName).style.display = 'block';
        });
    });
    </script>
</div>
@endsection
