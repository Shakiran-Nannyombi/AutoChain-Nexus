@extends('layouts.dashboard')

@section('title', 'Send Checklist to Supplier')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem; font-size: 1.8rem;font-weight:bold;">
        <i class="fas fa-list-alt"></i> Send Checklist to Supplier
    </h2>
    <form method="POST" action="{{ route('manufacturer.checklists.send') }}" id="checklist-form">
        @csrf
        <div style="margin-bottom: 1rem;">
            <label for="supplier_id" style="font-weight: 600;">Select Supplier:</label>
            <select name="supplier_id" id="supplier_id" required style="width: 100%; padding: 0.5rem; border-radius: 6px; border: 1px solid #ccc;">
                <option value="">-- Choose Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->email }})</option>
                @endforeach
            </select>
        </div>
        <div id="materials-section" style="margin-bottom: 1rem;">
            <label style="font-weight: 600;">Materials & Quantities:</label>
            <div id="materials-list">
                <div class="material-row" style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                    <input type="text" name="materials[]" placeholder="Material Name" required style="flex:2; padding: 0.4rem; border-radius: 6px; border: 1px solid #ccc;">
                    <input type="number" name="quantities[]" placeholder="Quantity" min="1" required style="flex:1; padding: 0.4rem; border-radius: 6px; border: 1px solid #ccc;">
                    <button type="button" class="remove-material" style="background: #e74c3c; color: white; border: none; border-radius: 4px; padding: 0.3rem 0.7rem;">&times;</button>
                </div>
            </div>
            <button type="button" id="add-material" style="margin-top: 0.5rem; background: var(--primary); color: white; border: none; border-radius: 6px; padding: 0.4rem 1rem;">+ Add Material</button>
        </div>
        <button type="submit" style="background: var(--primary); color: white; border: none; border-radius: 6px; padding: 0.7rem 2rem; font-weight: 600;">Send Checklist</button>
    </form>

    <hr style="margin: 2rem 0;">
    <h3 style="color: var(--secondary); font-size: 1.5rem; margin-bottom: 1rem; font-weight:bold;">Sent Checklists</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 0.5rem;">Supplier</th>
                    <th style="padding: 0.5rem;">Materials</th>
                    <th style="padding: 0.5rem;">Status</th>
                    <th style="padding: 0.5rem;">Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sentChecklists as $checklist)
                    <tr>
                        <td style="padding: 0.5rem;">{{ $checklist->supplier->name ?? 'Supplier #' . $checklist->supplier_id }}</td>
                        <td style="padding: 0.5rem;">
                            @foreach($checklist->materials_requested as $mat => $qty)
                                <span>{{ $mat }}: <strong>{{ $qty }}</strong></span>@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td style="padding: 0.5rem;">
                            @php $status = $checklist->status ?? 'unknown'; @endphp
                            <span class="status-badge status-{{ $status }}">
                                {{ $status ? ucfirst($status) : 'Unknown' }}
                            </span>
                        </td>
                        <td style="padding: 0.5rem;">{{ $checklist->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No checklists sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('add-material').addEventListener('click', function() {
    const row = document.createElement('div');
    row.className = 'material-row';
    row.style.display = 'flex';
    row.style.gap = '1rem';
    row.style.marginBottom = '0.5rem';
    row.innerHTML = `<input type="text" name="materials[]" placeholder="Material Name" required style="flex:2; padding: 0.4rem; border-radius: 6px; border: 1px solid #ccc;">
        <input type="number" name="quantities[]" placeholder="Quantity" min="1" required style="flex:1; padding: 0.4rem; border-radius: 6px; border: 1px solid #ccc;">
        <button type="button" class="remove-material" style="background: #e74c3c; color: white; border: none; border-radius: 4px; padding: 0.3rem 0.7rem;">&times;</button>`;
    document.getElementById('materials-list').appendChild(row);
});
document.getElementById('materials-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-material')) {
        e.target.parentElement.remove();
    }
});
</script>
@endsection 