@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem; font-weight:bold;"><i class="fas fa-cubes"></i> Inventory Status</h2>
        <!-- Inventory Summary -->
        <div style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <div style="flex:1; min-width:220px; background: linear-gradient(135deg, var(--primary), #0d3a07); color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.1rem; opacity: 0.9;">Total Raw Materials (Supplier Stock)</div>
                <div style="font-size: 2.2rem; font-weight: bold; margin-top: 0.5rem;">{{ $totalSupplierStock }}</div>
            </div>
            <div style="flex:1; min-width:220px; background: linear-gradient(135deg, var(--accent), #b35400); color: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 1.5rem; text-align: center;">
                <div style="font-size: 1.1rem; opacity: 0.9;">Total Finished Goods (Retailer Stock)</div>
                <div style="font-size: 2.2rem; font-weight: bold; margin-top: 0.5rem;">{{ $totalRetailerStock }}</div>
            </div>
        </div>
        <!-- Supplier Stock Table -->
        <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem; margin-bottom: 2rem;">
            <h3 style="color: var(--secondery); font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Raw Materials (Supplier Stock)</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead style="background: #f8f8f8;">
                        <tr>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Supplier ID</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Material Name</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Quantity</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--primary); font-size:0.98rem;">Colour</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplierStocks as $stock)
                            <tr style="transition: background 0.2s;" onmouseover="this.style.background='#f3f7fa'" onmouseout="this.style.background='#fff'">
                                <td style="padding: 0.7rem; font-weight: 500; color: #222;">{{ $stock->supplier_id }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ $stock->material_name }}</td>
                                <td style="padding: 0.7rem; color: {{ $stock->quantity < 10 ? '#b71c1c' : '#16610e' }}; font-weight: 600;">{{ $stock->quantity }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ $stock->colour }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 1.2rem; color: #888; text-align:center;">No supplier stock data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Retailer Stock Table -->
        <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 2rem;">
            <h3 style="color: var(--primary); font-size: 1.2rem; font-weight: 600; margin-bottom: 1.2rem;">Finished Goods (Retailer Stock)</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead style="background: #f8f8f8;">
                        <tr>
                            <th style="padding: 0.7rem; text-align:left; color: var(--secondery); font-size:0.98rem;">Retailer ID</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--secondery); font-size:0.98rem;">Car Model</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--secondery); font-size:0.98rem;">Quantity Received</th>
                            <th style="padding: 0.7rem; text-align:left; color: var(--secondery); font-size:0.98rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($retailerStocks as $stock)
                            <tr style="transition: background 0.2s;" onmouseover="this.style.background='#f3f7fa'" onmouseout="this.style.background='#fff'">
                                <td style="padding: 0.7rem; font-weight: 500; color: #222;">{{ $stock->retailer_id }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ $stock->car_model }}</td>
                                <td style="padding: 0.7rem; color: #16610e; font-weight: 600;">{{ $stock->quantity_received }}</td>
                                <td style="padding: 0.7rem; color: #555;">{{ $stock->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 1.2rem; color: #888; text-align:center;">No retailer stock data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection