@extends('layouts.dashboard')

@section('title', 'Analytics')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
  <div class="content-card analytics-page" style="background: var(--background); box-shadow: var(--shadow); border-radius: 18px; padding: 2.5rem 2rem;">
    <h2 style="color: var(--primary); font-size: 2rem; margin-bottom: 2.5rem; font-weight: 700;"><i class="fas fa-chart-line"></i> Analytics</h2>
    <!-- Modern Stat Cards Grid -->
    <div class="modern-analytics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
      <!-- Stat Card: Total Users -->
      <div style="background: var(--primary); border-radius: 14px; box-shadow: var(--shadow); padding: 2rem; display: flex; flex-direction: column; align-items: flex-start; color: #fff;">
        <div style="font-size: 1.1rem; color: #fff; font-weight: 600; margin-bottom: 0.5rem;">Total Users</div>
        <div style="font-size: 2.5rem; font-weight: bold; color: #fff;">{{ $totalUsers }}</div>
      </div>
      <!-- Stat Card: Pending Approvals -->
      <div style="background: var(--secondary); border-radius: 14px; box-shadow: var(--shadow); padding: 2rem; display: flex; flex-direction: column; align-items: flex-start; color: #fff;">
        <div style="font-size: 1.1rem; color: #fff; font-weight: 600; margin-bottom: 0.5rem;">Pending Approvals</div>
        <div style="font-size: 2.5rem; font-weight: bold; color: #fff;">{{ $pendingUsers }}</div>
      </div>
      <!-- Stat Card: Active Users -->
      <div style="background: var(--success); border-radius: 14px; box-shadow: var(--shadow); padding: 2rem; display: flex; flex-direction: column; align-items: flex-start; color: #fff;">
        <div style="font-size: 1.1rem; color: #fff; font-weight: 600; margin-bottom: 0.5rem;">Active Users</div>
        <div style="font-size: 2.5rem; font-weight: bold; color: #fff;">{{ $approvedUsers }}</div>
      </div>
    </div>

    <!-- Modern Chart Cards Grid -->
    <div class="modern-analytics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 2.5rem; margin-bottom: 2.5rem;">
      <!-- User Roles Donut/Progress Circles Row -->
      <div style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 2rem; width: 100%; margin-bottom: 2.5rem;">
        <h3 style="margin-bottom: 2rem; font-size: 1.8rem; color: black; font-weight: 600; text-align: center;">User Roles Overview</h3>
        <div style="display: flex; flex-direction: row; justify-content: center; align-items: flex-start; gap: 2.5rem;">
          <div style="display: flex; flex-direction: row; gap: 2.5rem;">
            <div style="display: flex; flex-direction: column; align-items: center;">
              <div id="roleCircleAnalyst" style="width: 110px; height: 110px;"></div>
              <div style="margin-top: 0.5rem; font-weight: 600; color: #3490dc;">Analyst</div>
              <div style="font-size: 0.95rem; color: #666;">
                <span style="color: #38c172; font-weight: 600;">8</span> Active
                <span style="color: #e3342f; font-weight: 600; margin-left: 0.7rem;">2</span> Pending
              </div>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center;">
              <div id="roleCircleManufacturer" style="width: 110px; height: 110px;"></div>
              <div style="margin-top: 0.5rem; font-weight: 600; color: #f6993f;">Manufacturer</div>
              <div style="font-size: 0.95rem; color: #666;">
                <span style="color: #38c172; font-weight: 600;">10</span> Active
                <span style="color: #e3342f; font-weight: 600; margin-left: 0.7rem;">3</span> Pending
        </div>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center;">
              <div id="roleCircleRetailer" style="width: 110px; height: 110px;"></div>
              <div style="margin-top: 0.5rem; font-weight: 600; color: #38c172;">Retailer</div>
              <div style="font-size: 0.95rem; color: #666;">
                <span style="color: #38c172; font-weight: 600;">7</span> Active
                <span style="color: #e3342f; font-weight: 600; margin-left: 0.7rem;">1</span> Pending
        </div>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center;">
              <div id="roleCircleSupplier" style="width: 110px; height: 110px;"></div>
              <div style="margin-top: 0.5rem; font-weight: 600; color: #e3342f;">Supplier</div>
              <div style="font-size: 0.95rem; color: #666;">
                <span style="color: #38c172; font-weight: 600;">6</span> Active
                <span style="color: #e3342f; font-weight: 600; margin-left: 0.7rem;">2</span> Pending
        </div>
    </div>
            <div style="display: flex; flex-direction: column; align-items: center;">
              <div id="roleCircleVendor" style="width: 110px; height: 110px;"></div>
              <div style="margin-top: 0.5rem; font-weight: 600; color: #6574cd;">Vendor</div>
              <div style="font-size: 0.95rem; color: #666;">
                <span style="color: #38c172; font-weight: 600;">9</span> Active
                <span style="color: #e3342f; font-weight: 600; margin-left: 0.7rem;">1</span> Pending
        </div>
        </div>
    </div>
        </div>
        </div>
    </div>
    <!-- New Users (Last 7 Days) ECharts Bar Chart Card -->
    <div style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 2rem; width: 100%; margin-bottom: 2.5rem;">
      <h3 style="margin-bottom: 1.2rem; font-size: 1.8rem; color: var(--secondary); font-weight: 600; text-align: left;">New Users (Last 7 Days)</h3>
      <div id="newUsersBarChart" style="width: 100%; min-width: 320px; height: 320px;"></div>
                </div>
    <!-- User Sessions Per Month (Last 12 Months) Line Chart Card -->
    <div style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 2rem; width: 100%; margin-bottom: 2.5rem;">
      <h3 style="margin-bottom: 1.2rem; font-size: 1.8rem; color: #F97A00; font-weight: 600; text-align: left;">User Sessions Per Month (Last 12 Months)</h3>
      <div id="userSessionsLineChart" style="width: 100%; min-width: 320px; height: 320px; border: 1px solid #eee;"></div>
            </div>
    <!-- Login Activity Area Chart Card -->
    <div style="background: #fff; border-radius: 14px; box-shadow: var(--shadow); padding: 2rem; width: 100%; margin-bottom: 2.5rem;">
      <h3 style="margin-bottom: 1.2rem; font-size: 1.8rem; color: #482607; font-weight: 600; text-align: left;">Login activity</h3>
      <div id="loginActivityChart" style="width: 100%; min-width: 320px; height: 320px; border: 1px solid #eee;"></div>
    </div>
    <!-- The rest of your analytics page content (session charts, etc.) can follow here -->
  </div>
@endsection

@push('scripts')
<!-- ECharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // User Roles Donut/Progress Circles (demo data)
        const roleCircles = [
          { id: 'roleCircleAnalyst', color: '#3490dc', active: 8, pending: 2 },
          { id: 'roleCircleManufacturer', color: '#f6993f', active: 10, pending: 3 },
          { id: 'roleCircleRetailer', color: '#38c172', active: 7, pending: 1 },
          { id: 'roleCircleSupplier', color: '#e3342f', active: 6, pending: 2 },
          { id: 'roleCircleVendor', color: '#6574cd', active: 9, pending: 1 }
        ];
        roleCircles.forEach(role => {
          const dom = document.getElementById(role.id);
          if (dom) {
            const total = role.active + role.pending;
            const chart = echarts.init(dom);
            chart.setOption({
              series: [{
            type: 'pie',
                radius: ['70%', '90%'],
                avoidLabelOverlap: false,
                silent: true,
                label: { show: false },
                data: [
                  { value: role.active, name: 'Active', itemStyle: { color: '#38c172' } },
                  { value: role.pending, name: 'Pending', itemStyle: { color: '#e3342f' } },
                  { value: Math.max(0, total - (role.active + role.pending)), name: '', itemStyle: { color: '#f3f4f6' }, tooltip: { show: false } }
                ]
              }],
              graphic: [{
                type: 'text',
                left: 'center',
                top: 'center',
                style: {
                  text: total + '',
                  fontSize: 22,
                  fontWeight: 700,
                  fill: role.color
                            }
              }]
            });
            }
        });

        // New Users (Last 7 Days) ECharts Bar Chart
        const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const approved = [12, 15, 10, 18, 14, 16, 13];
        const pending = [5, 7, 4, 6, 8, 5, 7];
        const barChartDom = document.getElementById('newUsersBarChart');
        if (barChartDom) {
          const barChart = echarts.init(barChartDom);
          barChart.setOption({
            tooltip: { trigger: 'axis' },
            legend: {
              data: ['Approved', 'Pending'],
              top: 0,
              right: 10,
              textStyle: { color: 'var(--text-dark)', fontWeight: 500 }
            },
            grid: { left: 40, right: 20, top: 40, bottom: 50 },
            xAxis: {
              type: 'category',
              data: days,
              axisLine: { lineStyle: { color: 'var(--text-light)' } },
              axisLabel: { color: 'var(--text-dark)' }
            },
            yAxis: {
              type: 'value',
              axisLine: { lineStyle: { color: 'var(--text-light)' } },
              axisLabel: { color: 'var(--text-dark)' },
              splitLine: { lineStyle: { color: '#eee' } }
                },
            series: [
              {
                name: 'Approved',
                type: 'bar',
                data: approved,
                itemStyle: { color: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#2563eb', borderRadius: [6, 6, 0, 0] },
                barWidth: 24
              },
              {
                name: 'Pending',
                type: 'bar',
                data: pending,
                itemStyle: { color: getComputedStyle(document.documentElement).getPropertyValue('--primary-light').trim() || '#a5b4fc', borderRadius: [6, 6, 0, 0] },
                barWidth: 24
              }
            ]
          });
        }

        // User Sessions Per Month (Last 12 Months) ECharts Line Chart
        const months = ['Jul 2023', 'Aug 2023', 'Sep 2023', 'Oct 2023', 'Nov 2023', 'Dec 2023', 'Jan 2024', 'Feb 2024', 'Mar 2024', 'Apr 2024', 'May 2024', 'Jun 2024'];
        const sessions = [120, 150, 180, 170, 200, 220, 210, 230, 250, 240, 260, 280];
        const userSessionsDom = document.getElementById('userSessionsLineChart');
        if (userSessionsDom) {
          const userSessionsChart = echarts.init(userSessionsDom);
          userSessionsChart.setOption({
            title: { text: '', left: 'center' },
            tooltip: { trigger: 'axis' },
            grid: { left: 40, right: 20, top: 30, bottom: 50 },
            xAxis: {
              type: 'category',
              data: months,
              axisLine: { lineStyle: { color: 'var(--text-light)' } },
              axisLabel: { color: 'var(--text-dark)', rotate: 30 }
            },
            yAxis: {
              type: 'value',
              axisLine: { lineStyle: { color: 'var(--text-light)' } },
              axisLabel: { color: 'var(--text-dark)' },
              splitLine: { lineStyle: { color: '#eee' } }
            },
            series: [{
              name: 'Sessions',
              data: sessions,
              type: 'line',
              smooth: true,
              symbol: 'circle',
              symbolSize: 8,
              lineStyle: { color: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#2563eb', width: 3 },
              itemStyle: { color: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#2563eb' },
              areaStyle: { color: getComputedStyle(document.documentElement).getPropertyValue('--primary-light').trim() || '#a5b4fc', opacity: 0.15 }
            }]
          });
        }

        // Login Activity ECharts Area Chart (real data)
        const loginActivityData = @json($loginActivityData);
        const loginDays = loginActivityData.days;
        const roleNames = loginActivityData.roles;
        const roleSeriesData = loginActivityData.data;
        // Assign a color for each role (fallback to palette if not enough CSS vars)
        const roleColors = [
          getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#2563eb',
          getComputedStyle(document.documentElement).getPropertyValue('--secondary').trim() || '#10b981',
          getComputedStyle(document.documentElement).getPropertyValue('--success').trim() || '#38c172',
          getComputedStyle(document.documentElement).getPropertyValue('--warning').trim() || '#f59e42',
          getComputedStyle(document.documentElement).getPropertyValue('--danger').trim() || '#e3342f',
          '#a5b4fc', '#f3f4f6', '#6574cd', '#f6993f', '#b91c1c'
        ];
        const loginActivityDom = document.getElementById('loginActivityChart');
        if (loginActivityDom) {
          const series = roleNames.map((role, idx) => ({
            name: role.charAt(0).toUpperCase() + role.slice(1),
            type: 'line',
            stack: 'total',
            smooth: true,
            symbol: 'none',
            data: roleSeriesData[idx],
            lineStyle: { color: roleColors[idx % roleColors.length], width: 2 },
            emphasis: { focus: 'series' },
            z: 2
          }));
          const loginChart = echarts.init(loginActivityDom);
          loginChart.setOption({
            tooltip: { trigger: 'axis' },
            legend: {
              data: roleNames.map(r => r.charAt(0).toUpperCase() + r.slice(1)),
              top: 10,
              right: 20,
              textStyle: { color: 'var(--text-dark)', fontWeight: 500 }
            },
            grid: { left: 40, right: 20, top: 40, bottom: 50 },
            xAxis: {
              type: 'category',
              data: loginDays,
              axisLine: { lineStyle: { color: 'var(--text-light)' } },
              axisLabel: { color: 'var(--text-dark)' }
                    },
            yAxis: {
              type: 'value',
              axisLine: { lineStyle: { color: 'var(--text-light)' } },
              axisLabel: { color: 'var(--text-dark)' },
              splitLine: { lineStyle: { color: '#eee' } }
            },
            series: series
        });
        }
    });
</script>
@endpush
