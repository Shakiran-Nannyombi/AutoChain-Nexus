@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-truck-loading"></i> Material Receipt</h2>
        @if($deliveries->isEmpty())
            <div style="text-align:center; color: var(--primary); font-size: 1.1rem; padding: 2rem 0;">
                No material deliveries have been received yet.
            </div>
        @else
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="padding: 0.5rem;">Supplier</th>
                        <th style="padding: 0.5rem;">Materials</th>
                        <th style="padding: 0.5rem;">Delivered At</th>
                        <th style="padding: 0.5rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                        <tr>
                            <td style="padding: 0.5rem;">
                                @if($delivery->supplier)
                                    {{ $delivery->supplier->name }} <span style="color:#888; font-size:0.95em;">({{ $delivery->supplier->email }})</span>
                                @else
                                    Supplier #{{ $delivery->supplier_id }}
                                @endif
                            </td>
                            <td style="padding: 0.5rem;">
                                @foreach($delivery->materials_delivered as $mat => $qty)
                                    <span>{{ $mat }}: <strong>{{ $qty }}</strong></span>@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td style="padding: 0.5rem;">{{ $delivery->created_at->format('Y-m-d H:i') }}</td>
                            <td style="padding: 0.5rem;">
                                <form method="POST" action="{{ route('manufacturer.order.delivered', $delivery->id) }}">
                                    @csrf
                                    <button type="submit" style="background: var(--primary); color: white; border: none; border-radius: 6px; padding: 0.4rem 1.2rem; font-size: 0.95rem;">
                                        <i class="fas fa-check"></i> Order Delivered
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @if(isset($confirmedOrders) && $confirmedOrders->count())
    <div class="content-card" style="margin-top:2.5rem;">
        <h3 style="color: var(--primary); margin-bottom: 1rem;">Confirmed Orders</h3>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 0.5rem;">Supplier</th>
                    <th style="padding: 0.5rem;">Materials</th>
                    <th style="padding: 0.5rem;">Fulfilled At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($confirmedOrders as $order)
                <tr>
                    <td style="padding: 0.5rem;">
                        @if($order->supplier)
                            {{ $order->supplier->name }} <span style="color:#888; font-size:0.95em;">({{ $order->supplier->email }})</span>
                        @else
                            Supplier #{{ $order->supplier_id }}
                        @endif
                    </td>
                    <td style="padding: 0.5rem;">
                        @foreach($order->materials_requested as $mat => $qty)
                            <span>{{ $mat }}: <strong>{{ $qty }}</strong></span>@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td style="padding: 0.5rem;">{{ $order->updated_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection
