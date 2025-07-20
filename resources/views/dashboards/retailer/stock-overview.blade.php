@extends('layouts.dashboard')
@section('title', 'Retailer Dashboard')
@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection
@section('content')
    <h1 class="page-header">Stock Overview</h1>
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-boxes"></i> Retailer Stock Overview
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: var(--deep-purple); color: white;">
                    <tr>
                        <th style="padding: 0.75rem; text-align: left;">Car Model</th>
                        <th style="padding: 0.75rem; text-align: left;">Vendor</th>
                        <th style="padding: 0.75rem; text-align: left;">Quantity Received</th>
                        <th style="padding: 0.75rem; text-align: left;">Status</th>
                        <th style="padding: 0.75rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 0.75rem;">{{ $stock->car_model }}</td>
                            <td style="padding: 0.75rem;">{{ $stock->vendor->name ?? 'N/A' }}</td>
                            <td style="padding: 0.75rem;">{{ $stock->quantity_received }}</td>
                            <td style="padding: 0.75rem;">{{ ucfirst($stock->status) }}</td>
                            <td style="padding: 0.75rem; text-align: center;">
    @if($stock->status === 'pending')
        <form action="{{ route('retailer.stock.accept', $stock->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Accept</button>
        </form>
        <form action="{{ route('retailer.stock.reject', $stock->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
        </form>
    @else
        <span style="color: gray; font-size: 0.9rem;">No actions</span>
    @endif
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 1rem; text-align: center; color: #888;">No stock available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection 