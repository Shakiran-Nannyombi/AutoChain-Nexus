<x-mail::message>
# Your Order Has Been Accepted!

Dear {{ $order->customer_name }},

Your order (ID: {{ $order->id }}) for <strong>{{ $order->product->name }}</strong> has been accepted by the retailer <strong>{{ $order->retailer->name }}</strong>.

**Order Details:**
- **Product:** {{ $order->product->name }}
- **Quantity:** {{ $order->quantity }}
- **Total Amount:** ${{ number_format($order->total_amount, 2) }}
- **Retailer:** {{ $order->retailer->name }}

We will notify you when your order is shipped or ready for pickup (if applicable).

<x-mail::button :url="''">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
