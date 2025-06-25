@extends('layouts.dashboard')

@section('title', 'Delivery History')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
        <i class="fas fa-truck-loading"></i> Delivery History
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        @forelse($deliveries as $delivery)
            <div style="background: white; border-left: 5px solid var(--deep-purple); padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-industry"></i> To Manufacturer #{{ $delivery->manufacturer_id }}
                </div>
                <div style="font-size: 0.9rem; color: #555;">Delivered Materials:</div>
                <ul style="margin-left: 1rem; margin-top: 0.5rem;">
                    @foreach($delivery->materials_delivered as $material => $qty)
                        <li>{{ $material }}: <strong>{{ $qty }}</strong></li>
                    @endforeach
                </ul>
                <div style="margin-top: 0.75rem; font-size: 0.8rem; color: #888;">
                    <i class="fas fa-calendar-alt"></i> {{ $delivery->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        @empty
            <div style="background: var(--light-cyan); padding: 2rem; text-align: center; border-radius: 10px; color: #555;">
                <i class="fas fa-shipping-fast" style="font-size: 2rem; color: var(--deep-purple);"></i>
                <p style="margin-top: 0.5rem;">No deliveries made yet. Once you fulfill checklist requests, they’ll appear here.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
