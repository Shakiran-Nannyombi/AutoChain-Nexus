@if(isset($delivery_date) && isset($delivery_address) && isset($driver_name))
    <h3>Delivery Details</h3>
    <p><strong>Delivery Date:</strong> {{ $delivery_date }}</p>
    <p><strong>Delivery Address:</strong> {{ $delivery_address }}</p>
    <p><strong>Driver Name:</strong> {{ $driver_name }}</p>
@endif 