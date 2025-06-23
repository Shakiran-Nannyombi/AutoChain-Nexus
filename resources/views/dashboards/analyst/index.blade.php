@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/analyst.css') }}">
@endpush

@section('title', 'Analyst Dashboard')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection
@section('content')
    @php
        $title = 'Analyst Dashboard';
    @endphp

    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-chart-line"></i> Analytics Dashboard
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Quick Stats -->
            <div style="background: linear-gradient(135deg, var(--deep-purple), var(--orange)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $totalReports ?? 0 }}</div>
                        <div style="opacity: 0.9;">Generated Reports</div>
                    </div>
                    <i class="fas fa-chart-bar" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--maroon), var(--orange)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $dataPoints ?? 0 }}</div>
                        <div style="opacity: 0.9;">Data Points Analyzed</div>
                    </div>
                    <i class="fas fa-database" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--blue), var(--light-cyan)); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $trends ?? 0 }}</div>
                        <div style="opacity: 0.9;">Trends Identified</div>
                    </div>
                    <i class="fas fa-trending-up" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $accuracy ?? '95%' }}</div>
                        <div style="opacity: 0.9;">Prediction Accuracy</div>
                    </div>
                    <i class="fas fa-bullseye" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="/analyst/reports/generate" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Generate Report</div>
                </a>
                
                <a href="/analyst/analytics" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-chart-line" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Run Analytics</div>
                </a>
                
                <a href="/analyst/trends" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-trending-up" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Analyze Trends</div>
                </a>
                
                <a href="/analyst/forecasting" style="display: block; padding: 1rem; background: var(--light-cyan); border-radius: 8px; text-decoration: none; color: var(--deep-purple); text-align: center; transition: transform 0.2s;">
                    <i class="fas fa-crystal-ball" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;">Forecasting</div>
                </a>
            </div>
        </div>

        <!-- Recent Reports -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-file-alt"></i> Recent Reports
            </h3>
            <div style="background: var(--gray); padding: 1rem; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-chart-bar" style="color: var(--blue);"></i>
                    <div>
                        <div style="font-weight: 600;">Sales Performance Report</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Monthly sales analysis for Q1 2024</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">2 hours ago</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <i class="fas fa-warehouse" style="color: var(--orange);"></i>
                    <div>
                        <div style="font-weight: 600;">Inventory Analysis Report</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Stock levels and turnover analysis</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">1 day ago</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 0;">
                    <i class="fas fa-trending-up" style="color: var(--maroon);"></i>
                    <div>
                        <div style="font-weight: 600;">Market Trends Report</div>
                        <div style="font-size: 0.9rem; opacity: 0.7;">Industry trends and predictions</div>
                    </div>
                    <div style="margin-left: auto; font-size: 0.9rem; opacity: 0.7;">3 days ago</div>
                </div>
            </div>
        </div>

        <!-- Key Insights -->
        <div>
            <h3 style="color: var(--deep-purple); margin-bottom: 1rem; font-size: 1.3rem;">
                <i class="fas fa-lightbulb"></i> Key Insights
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--orange);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Sales Trend</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">15% increase in Q1 sales</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Trend: Upward</div>
                        <div style="background: var(--orange); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Positive</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--blue);">
                    <div style="font-weight: 600; color: var(--deep-purple);">Inventory Efficiency</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">85% inventory turnover rate</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Status: Optimal</div>
                        <div style="background: var(--blue); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Good</div>
                    </div>
                </div>
                
                <div style="background: var(--white); padding: 1rem; border-radius: 8px; border-left: 4px solid #28a745;">
                    <div style="font-weight: 600; color: var(--deep-purple);">Customer Satisfaction</div>
                    <div style="font-size: 0.9rem; opacity: 0.7; margin-bottom: 0.5rem;">92% customer satisfaction rate</div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.9rem;">Rating: Excellent</div>
                        <div style="background: #28a745; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">High</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 