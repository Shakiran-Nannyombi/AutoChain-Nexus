@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<div class="content-card dashboard-page">
    <h2 style="color: var(--primary) !important; font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-tachometer-alt"></i> Overview</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Quick Stats -->
            <div style="background: linear-gradient(135deg, #2563eb, #60a5fa); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(37,99,235,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $pendingUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Pending Approvals</div>
                    </div>
                <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #f59e42, #b35400); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(245,158,66,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $totalUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Total Users</div>
                    </div>
                <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #22c55e, #388e3c); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(34,197,94,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $activeUsers ?? 0 }}</div>
                        <div style="opacity: 0.9;">Active Users</div>
                    </div>
                <i class="fas fa-user-check" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="/admin/user-management" style="display: block; padding: 1rem; background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%); border-radius: 8px; text-decoration: none; color: #fff; text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-user-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div>Review Pending Users</div>
                </a>
                <a href="/admin/user-management" style="display: block; padding: 1rem; background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%); border-radius: 8px; text-decoration: none; color: #fff; text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-user-cog" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div>Manage Users</div>
                </a>
                <a href="/admin/settings" style="display: block; padding: 1rem; background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%); border-radius: 8px; text-decoration: none; color: #fff; text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-cog" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div>System Settings</div>
                </a>
                <a href="/admin/reports" style="display: block; padding: 1rem; background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%); border-radius: 8px; text-decoration: none; color: #fff; text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(37,99,235,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-chart-bar" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div>View Reports</div>
                </a>
            </div>
        </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <!-- Recent Activity -->
        <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow);">
            <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-history"></i> Recent Activity
            </h3>
            @if(isset(
$recentActivities) && $recentActivities->count())
                @foreach($recentActivities as $activity)
                    <div style="border-bottom: 1px solid #eee; padding: 0.75rem 0;">
                        <div style="font-weight: 600; color: #0F2C67;">{{ ucfirst($activity->action) }}</div>
                        <div style="color: #555; font-size: 0.97em;">{{ $activity->details }}</div>
                        <div style="color: #888; font-size: 0.9em;">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; color: #6c757d; padding: 2rem 0;">
                    No recent activity to display.
                </div>
            @endif
        </div>
                
        <!-- User Distribution by Role -->
        <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow);">
            <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.3rem;">
                User Distribution by Role
            </h3>
                    <div>
                @if (isset($roleCounts) && !empty($roleCounts))
                    @foreach ($roleCounts as $role => $count)
                        <div class="role-item" style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; @if (!$loop->last) border-bottom: 1px solid #e0e0e0; @endif">
                            <span>{{ $role }}</span>
                            <span style="font-weight: 600;">{{ $count }}</span>
                    </div>
                    @endforeach
                @else
                    <div style="text-align: center; color: #6c757d; padding: 2rem 0;">
                        No user data available.
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $segmentNames = [
            1 => 'At Risk',
            2 => 'High Value Customers',
            3 => 'At Risk Customers',
        ];
    @endphp
    <!-- Customer Segment Analytics Section -->
    <div style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: var(--shadow); margin-top: 2rem;">
        <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
            <i class="fas fa-users"></i> Customer Segment Distribution
        </h3>
        <div>
            @if(isset($customerSegmentCounts) && count($customerSegmentCounts) > 0)
                <ul style="list-style: none; padding: 0;">
                    @foreach($customerSegmentCounts as $seg)
                        <li style="margin-bottom: 0.5rem;">
                            <strong>{{ $segmentNames[$seg->segment] ?? 'Unsegmented' }}:</strong> {{ $seg->count }} customers
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No segmentation data available.</p>
            @endif
        </div>
        @if(isset($segmentSummaries) && count($segmentSummaries) > 0)
        <div style="margin-top: 2rem;">
            <h4 style="color: var(--deep-purple);">Segment Summary Table</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="padding: 0.5rem; text-align: left;">Segment</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Total Spent</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Purchases</th>
                        <th style="padding: 0.5rem; text-align: left;">Avg. Recency (days)</th>
                        <th style="padding: 0.5rem; text-align: left;"># Customers</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($segmentSummaries as $summary)
                    <tr>
                        <td style="padding: 0.5rem;">{{ $segmentNames[$summary->segment] ?? 'Unsegmented' }}</td>
                        <td style="padding: 0.5rem;">${{ number_format($summary->avg_total_spent, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ number_format($summary->avg_purchases, 2) }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->avg_recency !== null ? number_format($summary->avg_recency, 1) : 'N/A' }}</td>
                        <td style="padding: 0.5rem;">{{ $summary->count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        </div>
    </div>
@endsection 