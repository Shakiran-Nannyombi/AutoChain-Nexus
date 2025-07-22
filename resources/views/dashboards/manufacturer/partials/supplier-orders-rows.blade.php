@forelse($supplierOrders as $order)
<tr>
    <td style="padding: 0.7rem;">
        @foreach($order->materials_requested as $mat => $qty)
            <span>{{ $mat }}: <strong>{{ $qty }}</strong></span>@if(!$loop->last), @endif
        @endforeach
    </td>
    <td style="padding: 0.7rem;">
        @if($order->supplier)
            {{ $order->supplier->name }}<br>
            <span style="color:#888; font-size:0.95em;">{{ $order->supplier->email }}</span>
        @else
            Supplier #{{ $order->supplier_id }}
        @endif
    </td>
    <td style="padding: 0.7rem;">{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '' }}</td>
    <td style="padding: 0.7rem;">
        @php $status = $order->status ?: 'pending'; @endphp
        @if($status === 'pending')
            <span style="background: #e67e22; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Pending</span>
        @elseif($status === 'fulfilled')
            <span style="background: #27ae60; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Fulfilled</span>
        @elseif($status === 'cancelled')
            <span style="background: #c0392b; color: white; padding: 0.3rem 0.8rem; border-radius: 5px; font-size: 0.95rem;">Cancelled</span>
        @endif
    </td>
    <td style="padding: 0.7rem;">{{ count($order->materials_requested) }}</td>
    <td style="padding: 0.7rem;">
        <form method="POST" action="{{ route('manufacturer.remake.order', $order->id) }}" style="display:inline;">
            @csrf
            <button type="submit" style="background: var(--primary); color: white; border: none; border-radius: 6px; padding: 0.4rem 1.2rem; font-size: 0.95rem;">
                <i class="fas fa-redo"></i> Remake Order
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" style="text-align:center; color:#888; padding:2rem;">No orders found.</td>
</tr>
@endforelse 