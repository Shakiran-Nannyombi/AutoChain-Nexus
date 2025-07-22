<style>
.invoice-box {
    max-width: 800px;
    margin: auto;
    padding: 30px;
    border: 1px solid #eee;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    font-size: 16px;
    line-height: 24px;
    font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    color: #555;
    background: #fff;
}
.invoice-box table {
    width: 100%;
    line-height: inherit;
    text-align: left;
    border-collapse: collapse;
}
.invoice-box table td {
    padding: 5px;
    vertical-align: top;
}
.invoice-box table tr.top table td {
    padding-bottom: 20px;
}
.invoice-box table tr.information table td {
    padding-bottom: 20px;
}
.invoice-box table tr.heading td {
    background: #eee;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
}
.invoice-box table tr.details td {
    padding-bottom: 10px;
}
.invoice-box table tr.item td{
    border-bottom: 1px solid #eee;
}
.invoice-box table tr.item.last td {
    border-bottom: none;
}
.invoice-box table tr.total td:nth-child(4) {
    border-top: 2px solid #eee;
    font-weight: bold;
}
.invoice-title {
    font-size: 2.2rem;
    color: #444;
    font-weight: 700;
    text-align: right;
}
</style>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="4">
                <table>
                    <tr>
                        <td class="invoice-title" colspan="2">
                            Invoice
                        </td>
                        <td style="text-align:right;">
                            DATE: {{ $order->ordered_at ? $order->ordered_at->format('Y-m-d') : '' }}<br>
                            INVOICE #: {{ $order->id }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="information">
            <td colspan="4">
                <table>
                    <tr>
                        <td>
                            <b>FROM:</b><br>
                            {{ $manufacturer->name ?? 'Company Name' }}<br>
                            {{ $manufacturer->email ?? 'Email' }}<br>
                            {{ $manufacturer->address ?? 'Address 1' }}<br>
                        </td>
                        <td>
                            <b>TO:</b><br>
                            {{ $vendor->name ?? 'Client Name' }}<br>
                            {{ $vendor->email ?? 'Client Email' }}<br>
                            {{ $vendor->address ?? 'Client Address' }}<br>
                        </td>
                        <td>
                            <b>TERMS:</b> Net 30<br>
                            <b>DUE:</b> {{ $order->expected_delivery_date ?? 'Due Date' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="heading">
            <td>Item Description</td>
            <td>Quantity</td>
            <td>Price</td>
            <td>Amount</td>
        </tr>
        <tr class="item last">
            <td>{{ $order->product_name ?? $order->product }}</td>
            <td>{{ $order->quantity }}</td>
            <td>shs {{ number_format($order->unit_price, 2) }}</td>
            <td>shs {{ number_format($order->total_amount, 2) }}</td>
        </tr>
        <tr class="total">
            <td></td>
            <td></td>
            <td style="text-align:right;">Subtotal</td>
            <td>shs {{ number_format($order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align:right;">Tax</td>
            <td>shs 0.00</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align:right; font-size:1.1rem; font-weight:700;">BALANCE DUE</td>
            <td style="font-size:1.1rem; font-weight:700;">shs {{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>
    <div style="margin-top:2.5rem;">
        <b>Notes</b><br>
        <div style="border:1px solid #eee; border-radius:6px; padding:0.7rem 1rem; min-height:60px; margin-top:0.5rem;">
            @if(isset($delivery_date) && isset($delivery_address) && isset($driver_name))
                <b>Delivery Date:</b> {{ $delivery_date }}<br>
                <b>Delivery Address:</b> {{ $delivery_address }}<br>
                <b>Driver Name:</b> {{ $driver_name }}<br>
            @endif
            {{ $order->notes ?? '' }}
        </div>
    </div>
</div> 