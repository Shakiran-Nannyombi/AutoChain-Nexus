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

    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
        <input type="text" id="supplier-search" placeholder="Search by manufacturer..." style="padding: 0.6rem 1rem; border: 1px solid #b3b3b3; border-radius: 7px; font-size: 1rem;">
        <select id="status-filter" style="padding: 0.6rem 1rem; border: 1px solid #b3b3b3; border-radius: 7px; font-size: 1rem;">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="fulfilled">Fulfilled</option>
            <option value="declined">Declined</option>
        </select>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;" id="checklist-grid">
        @forelse($checklists as $checklist)
            <div class="checklist-card" data-status="{{ $checklist->status }}" data-manufacturer="{{ $checklist->manufacturer_id }}" style="background: white; border-left: 5px solid var(--orange); padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div class="manufacturer-label" style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.5rem;">
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('supplier-search');
        const statusFilter = document.getElementById('status-filter');
        const cards = document.querySelectorAll('.checklist-card');
        function filterCards() {
            const search = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            cards.forEach(card => {
                const manufacturer = card.querySelector('.manufacturer-label').textContent.toLowerCase();
                const cardStatus = card.getAttribute('data-status');
                let show = true;
                if (search && !manufacturer.includes(search)) show = false;
                if (status && cardStatus !== status) show = false;
                card.style.display = show ? '' : 'none';
            });
        }
        searchInput.addEventListener('input', filterCards);
        statusFilter.addEventListener('change', filterCards);
    });
</script>
@endpush
@endsection
