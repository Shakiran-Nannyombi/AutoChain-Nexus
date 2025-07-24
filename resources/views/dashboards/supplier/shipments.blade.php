@extends('layouts.dashboard')

@section('title', 'Track Shipments')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 1rem;">
            Shipment Management
        </h2>
        
        <!-- Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #e67e22, #d35400); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $shipments->where('status', 'pending')->count() }}</div>
                        <div style="opacity: 0.9;">Pending</div>
                    </div>
                    <i class="fas fa-clock" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $shipments->where('status', 'in_transit')->count() }}</div>
                        <div style="opacity: 0.9;">In Transit</div>
                    </div>
                    <i class="fas fa-truck" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #9b59b6, #8e44ad); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $shipments->where('status', 'out_for_delivery')->count() }}</div>
                        <div style="opacity: 0.9;">Out for Delivery</div>
                    </div>
                    <i class="fas fa-shipping-fast" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #27ae60, #229954); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $shipments->where('status', 'delivered')->count() }}</div>
                        <div style="opacity: 0.9;">Delivered</div>
                    </div>
                    <i class="fas fa-check-circle" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
        </div>

        <!-- Shipments Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Shipment ID</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Destination</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Driver</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Materials</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Progress</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">ETA</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem; font-weight: 600;">#SHP-{{ str_pad($shipment->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 1rem;">{{ $shipment->destination }}</td>
                        <td style="padding: 1rem;">{{ $shipment->driver }}</td>
                        <td style="padding: 1rem;">
                            @if($shipment->materials_delivered)
                                @foreach($shipment->materials_delivered as $material => $quantity)
                                    <span style="background: #e3f2fd; color: #1976d2; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.85rem; margin-right: 0.5rem;">
                                        {{ $material }}: {{ $quantity }}
                                    </span>
                                @endforeach
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($shipment->status === 'pending')
                                <span style="background: #fff3cd; color: #856404; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Pending</span>
                            @elseif($shipment->status === 'in_transit')
                                <span style="background: #d1ecf1; color: #0c5460; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">In Transit</span>
                            @elseif($shipment->status === 'out_for_delivery')
                                <span style="background: #e2e3f1; color: #383d41; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Out for Delivery</span>
                            @elseif($shipment->status === 'delivered')
                                <span style="background: #d4edda; color: #155724; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Delivered</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <div style="background: #f0f0f0; border-radius: 10px; height: 8px; width: 100px; position: relative;">
                                <div style="background: #3498db; height: 100%; width: {{ $shipment->progress }}%; border-radius: 10px;"></div>
                            </div>
                            <span style="font-size: 0.85rem; color: #666;">{{ $shipment->progress }}%</span>
                        </td>
                        <td style="padding: 1rem; color: #666;">
                            {{ $shipment->eta ? \Carbon\Carbon::parse($shipment->eta)->format('M d, Y') : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 2rem; text-align: center; color: #666;">
                            <i class="fas fa-shipping-fast" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                            <div>No shipments found</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection