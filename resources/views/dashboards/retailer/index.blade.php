@extends('layouts.dashboard')

@section('title', 'Retailer Dashboard')

@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection

@section('content')
    @php $title = 'Retailer Dashboard'; @endphp

    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-store"></i> Retailer Dashboard
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, var(--primary), #0d3a07); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $acceptedStock->count() }}</div>
                        <div style="opacity: 0.9;">Accepted Stock Items</div>
                    </div>
                    <i class="fas fa-box-open" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--primary-light), #388e3c); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $sales->count() }}</div>
                        <div style="opacity: 0.9;">Sales Made</div>
                    </div>
                    <i class="fas fa-cash-register" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--secondary), #b35400); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $orders->count() }}</div>
                        <div style="opacity: 0.9;">Customer Orders</div>
                    </div>
                    <i class="fas fa-shopping-bag" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>

        <h3 style="color: var(--deep-purple); font-size: 1.3rem; margin-bottom: 1rem;">
            <i class="fas fa-clock"></i> Recent Sales
        </h3>
        <div style="background: var(--gray); padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            @foreach($sales as $sale)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <div><strong>{{ $sale->car_model }}</strong> - {{ $sale->quantity_sold }} units</div>
                    <div style="opacity: 0.7; font-size: 0.9rem;">{{ $sale->created_at->diffForHumans() }}</div>
                </div>
            @endforeach
        </div>

        <h3 style="color: var(--deep-purple); font-size: 1.3rem; margin-bottom: 1rem;">
            <i class="fas fa-users"></i> Recent Customer Orders
        </h3>
        <div style="background: var(--gray); padding: 1rem; border-radius: 8px;">
            @foreach($orders as $order)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #ddd;">
                    <div><strong>{{ $order->customer_name }}</strong> ordered {{ $order->quantity }} {{ $order->car_model }}</div>
                    <div style="opacity: 0.7; font-size: 0.9rem;">{{ $order->created_at->diffForHumans() }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
