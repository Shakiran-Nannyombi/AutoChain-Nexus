@extends('layouts.dashboard')

@section('title', 'Checklist Receipt')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
        <i class="fas fa-list-alt"></i> Checklist Receipt
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        @forelse($checklists as $checklist)
            <div style="background: white; border-left: 5px solid var(--orange); padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-industry"></i> From Manufacturer #{{ $checklist->manufacturer_id }}
                </div>
                <div style="font-size: 0.9rem; color: #555;">Requested Materials:</div>
                <ul style="margin-left: 1rem; margin-top: 0.5rem;">
                    @foreach($checklist->materials_requested as $material => $qty)
                        <li>{{ $material }}: <strong>{{ $qty }}</strong></li>
                    @endforeach
                </ul>
                <div style="margin-top: 1rem;">
                    @if($checklist->status !== 'fulfilled')
                        <form method="POST" action="{{ route('supplier.checklist.fulfill', $checklist->id) }}">
                            @csrf
                            <button type="submit" style="padding: 0.5rem 1rem; background: var(--deep-purple); color: white; border: none; border-radius: 6px;">
                                <i class="fas fa-check-circle"></i> Fulfill Checklist
                            </button>
                        </form>
                    @else
                        <span style="display: inline-block; margin-top: 0.5rem; padding: 0.3rem 0.6rem; background: #28a745; color: white; font-size: 0.85rem; border-radius: 5px;">
                            <i class="fas fa-check"></i> Fulfilled
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div style="background: var(--light-cyan); padding: 2rem; text-align: center; border-radius: 10px; color: #555;">
                <i class="fas fa-inbox" style="font-size: 2rem; color: var(--deep-purple);"></i>
                <p style="margin-top: 0.5rem;">No checklist requests yet. They will appear here when sent by a manufacturer.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
