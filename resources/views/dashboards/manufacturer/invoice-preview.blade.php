@extends('layouts.dashboard')

@section('title', 'Invoice Preview')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card" style="max-width: 800px; margin: 2rem auto;">
    <h2 style="color: var(--primary); font-size: 2rem; font-weight: bold; margin-bottom: 1.2rem;">Invoice Preview</h2>
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 2rem; margin-bottom: 2rem;">
        @include('emails.reports.invoice', [
            'order' => $order,
            'product' => $product,
            'manufacturer' => $manufacturer,
            'vendor' => $vendor,
            // Optionally pass delivery details if needed
        ])
    </div>
    <div style="display: flex; gap: 1.2rem;">
        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Save as PDF</button>
        <button class="btn btn-secondary" disabled><i class="fas fa-edit"></i> Edit Invoice (coming soon)</button>
        <button class="btn btn-success" disabled><i class="fas fa-envelope"></i> Resend Email (coming soon)</button>
    </div>
    <div style="margin-top: 2rem;">
        <a href="{{ route('manufacturer.orders') }}" class="btn btn-outline-primary">Back to Orders</a>
    </div>
</div>
@endsection 