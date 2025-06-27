@extends('layouts.dashboard')

@section('title', 'Customer Dashboard')

@section('sidebar-content')
    @include('dashboards.customer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--deep-purple); margin-bottom: 1rem;">
        <i class="fas fa-user"></i> Welcome to the Customer Dashboard
    </h2>

    <p style="font-size: 1.1rem;">Explore available car models at various retailers.</p>

    <div style="margin-top: 2rem;">
        <h3 style="color: var(--maroon); font-size: 1.4rem;">
            <i class="fas fa-store"></i> Retailer Stock Overview
        </h3>

        @forelse ($retailers as $retailer)
            <div style="margin-top: 1.5rem; padding: 1rem; border: 1px solid #ccc; border-radius: 10px;">
                <h4 style="color: var(--deep-purple); font-weight: bold;">
                    <i class="fas fa-user-tag"></i> {{ $retailer->name }}
                </h4>

                @php
                    $inventory = $retailer->product_inventory ?? [];
                @endphp

                @if (empty($inventory))
                    <p style="color: gray;">No stock available at the moment.</p>
                @else
                    <table style="width: 100%; margin-top: 1rem; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f0f0f0;">
                                <th style="padding: 0.5rem; text-align: left;">Car Model</th>
                                <th style="padding: 0.5rem; text-align: left;">In Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventory as $item)
                                <tr>
                                    <td style="padding: 0.5rem;">{{ $item['car_model'] ?? '-' }}</td>
                                    <td style="padding: 0.5rem;">{{ $item['in_stock'] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @empty
            <p>No retailers found.</p>
        @endforelse
    </div>
</div>
@endsection
