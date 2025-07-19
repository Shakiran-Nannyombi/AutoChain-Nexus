@extends('layouts.dashboard')

@section('title', 'Analyst Dashboard')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        <h2 style="color: var(--primary); font-size: 1.7rem; font-weight: bold; margin-bottom: 1.2rem;">
            <i class="fas fa-chart-line"></i> Analytics Dashboard
        </h2>
        <div style="background: linear-gradient(135deg, #28a745, #20c997); color: #fff; border-radius: 12px; padding: 1.2rem 2.2rem; min-width: 180px; text-align: center; font-size: 1.2rem; font-weight: 600;">
            95%<br><span style="font-size: 0.98rem; font-weight: 400;">Prediction Accuracy</span>
        </div>
    </div>
    <div style="display: flex; gap: 2.5rem; margin-bottom: 2.2rem; flex-wrap: wrap; align-items: center;">
        <div style="display: flex; gap: 1.5rem; flex: 2 1 400px;">
            <a href="{{ route('analyst.reports') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #102a9b; border-radius: 12px; padding: 1.3rem 2rem; text-decoration: none; color: #fff; font-weight: 600; min-width: 150px; box-shadow: 0 1px 8px rgba(33,150,243,0.04); transition: box-shadow 0.18s, transform 0.12s, background 0.18s; border: none;">
                <i class="fas fa-plus" style="font-size: 2rem; margin-bottom: 0.5rem; color: #fff;"></i>
                <span style="color: #fff;">Generate Report</span>
            </a>
            <a href="{{ route('analyst.analytics') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #98138b; border-radius: 12px; padding: 1.3rem 2rem; text-decoration: none; color: #fff; font-weight: 600; min-width: 150px; box-shadow: 0 1px 8px rgba(33,150,243,0.04); transition: box-shadow 0.18s, transform 0.12s, background 0.18s; border: none;">
                <i class="fas fa-chart-line" style="font-size: 2rem; margin-bottom: 0.5rem; color: #fff;"></i>
                <span style="color: #fff;">Run Analytics</span>
            </a>
            <a href="{{ route('analyst.trends') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #b15505; border-radius: 12px; padding: 1.3rem 2rem; text-decoration: none; color: #fff; font-weight: 600; min-width: 150px; box-shadow: 0 1px 8px rgba(33,150,243,0.04); transition: box-shadow 0.18s, transform 0.12s, background 0.18s; border: none;">
                <i class="fas fa-chart-area" style="font-size: 2rem; margin-bottom: 0.5rem; color: #fff;"></i>
                <span style="color: #fff;">Analyze Trends</span>
            </a>
            <a href="{{ route('analyst.forecasting') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #87193a; border-radius: 12px; padding: 1.3rem 2rem; text-decoration: none; color: #fff; font-weight: 600; min-width: 150px; box-shadow: 0 1px 8px rgba(33,150,243,0.04); transition: box-shadow 0.18s, transform 0.12s, background 0.18s; border: none;">
                <i class="fas fa-magic" style="font-size: 2rem; margin-bottom: 0.5rem; color: #fff;"></i>
                <span style="color: #fff;">Forecasting</span>
            </a>
        </div>
        <a href="{{ route('analyst.myApplications') }}" class="btn" style="background: #198754; color: #fff; font-weight: 600; border-radius: 6px; padding: 0.7rem 1.3rem;">My Applications</a>
    </div>
    <div style="margin-bottom: 2.2rem;">
        <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem;">
            <i class="fas fa-file-alt"></i> Recent Reports
        </h3>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="display: flex; align-items: center; gap: 1rem; padding: 0.7rem 0; border-bottom: 1px solid #eee;">
                <i class="fas fa-chart-bar" style="color: var(--primary);"></i>
                <div style="flex:1;">
                    <div style="font-weight: 600;">Sales Performance Report</div>
                    <div style="font-size: 0.95em; color: #666;">Monthly sales analysis for Q1 2024</div>
                </div>
                <span style="font-size: 0.93em; color: #888;">2 hours ago</span>
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; padding: 0.7rem 0; border-bottom: 1px solid #eee;">
                <i class="fas fa-warehouse" style="color: #ff9800;"></i>
                <div style="flex:1;">
                    <div style="font-weight: 600;">Inventory Analysis Report</div>
                    <div style="font-size: 0.95em; color: #666;">Stock levels and turnover analysis</div>
                </div>
                <span style="font-size: 0.93em; color: #888;">1 day ago</span>
            </li>
            <li style="display: flex; align-items: center; gap: 1rem; padding: 0.7rem 0;">
                <i class="fas fa-trending-up" style="color: #b71c1c;"></i>
                <div style="flex:1;">
                    <div style="font-weight: 600;">Market Trends Report</div>
                    <div style="font-size: 0.95em; color: #666;">Industry trends and predictions</div>
                </div>
                <span style="font-size: 0.93em; color: #888;">3 days ago</span>
            </li>
        </ul>
    </div>
    <div>
        <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.7rem;">
            <i class="fas fa-lightbulb"></i> Key Insights
        </h3>
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
            <div style="background: #fff; border-radius: 8px; border-left: 4px solid #ff9800; padding: 1rem 1.2rem; flex: 1 1 220px; min-width: 200px;">
                <div style="font-weight: 600; color: var(--primary);">Sales Trend</div>
                <div style="font-size: 0.95em; color: #666; margin-bottom: 0.5rem;">15% increase in Q1 sales</div>
                <div style="font-size: 0.93em;">Trend: Upward</div>
            </div>
            <div style="background: #fff; border-radius: 8px; border-left: 4px solid #2196f3; padding: 1rem 1.2rem; flex: 1 1 220px; min-width: 200px;">
                <div style="font-weight: 600; color: var(--primary);">Inventory Efficiency</div>
                <div style="font-size: 0.95em; color: #666; margin-bottom: 0.5rem;">85% inventory turnover rate</div>
                <div style="font-size: 0.93em;">Status: Optimal</div>
            </div>
            <div style="background: #fff; border-radius: 8px; border-left: 4px solid #28a745; padding: 1rem 1.2rem; flex: 1 1 220px; min-width: 200px;">
                <div style="font-weight: 600; color: var(--primary);">Customer Satisfaction</div>
                <div style="font-size: 0.95em; color: #666; margin-bottom: 0.5rem;">92% customer satisfaction rate</div>
                <div style="font-size: 0.93em;">Rating: Excellent</div>
            </div>
        </div>
    </div>
</div>
@endsection 