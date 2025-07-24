@extends('layouts.dashboard')

@section('title', 'Delivery History')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin: 0;">
                <i class="fas fa-history"></i> Completed Deliveries
            </h2>
            <div style="background: #28a745; color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-check-circle"></i> {{ $completedDeliveries->count() }} Completed
            </div>
        </div>

        <!-- Summary Stats -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1.5rem; border-radius: 12px; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $completedDeliveries->count() }}</div>
                <div style="opacity: 0.9;">Total Completed</div>
            </div>
            <div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 1.5rem; border-radius: 12px; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $thisMonthCount }}</div>
                <div style="opacity: 0.9;">This Month</div>
            </div>
            <div style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; padding: 1.5rem; border-radius: 12px; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $totalMaterials }}</div>
                <div style="opacity: 0.9;">Materials Delivered</div>
            </div>
        </div>

        <!-- Deliveries Timeline -->
        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 1.5rem; color: #333; display: flex; align-items: center;">
                <i class="fas fa-timeline" style="margin-right: 0.5rem;"></i> Delivery Timeline
            </h3>
            
            @forelse($completedDeliveries as $delivery)
            <div style="display: flex; align-items: start; margin-bottom: 2rem; position: relative;">
                <!-- Timeline Line -->
                @if(!$loop->last)
                <div style="position: absolute; left: 20px; top: 40px; width: 2px; height: calc(100% + 1rem); background: #e9ecef;"></div>
                @endif
                
                <!-- Timeline Icon -->
                <div style="background: #28a745; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3); z-index: 1; position: relative;">
                    <i class="fas fa-check"></i>
                </div>
                
                <!-- Delivery Card -->
                <div style="flex: 1; background: #f8f9fa; border-radius: 12px; padding: 1.5rem; border-left: 4px solid #28a745;">
                    <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h4 style="margin: 0 0 0.5rem 0; color: #333; font-size: 1.2rem;">
                                Delivery #{{ str_pad($delivery->id, 3, '0', STR_PAD_LEFT) }}
                            </h4>
                            <div style="color: #666; font-size: 0.9rem;">
                                <i class="fas fa-building"></i> {{ $delivery->manufacturer->name ?? 'Manufacturer #'.$delivery->manufacturer_id }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="background: #28a745; color: white; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-check-double"></i> Completed
                            </div>
                            <div style="color: #666; font-size: 0.85rem;">
                                {{ \Carbon\Carbon::parse($delivery->updated_at)->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivery Details -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <div style="font-size: 0.8rem; color: #666; margin-bottom: 0.3rem;">Driver</div>
                            <div style="font-weight: 600; display: flex; align-items: center;">
                                <i class="fas fa-user" style="margin-right: 0.5rem; color: #007bff;"></i>
                                {{ $delivery->driver }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 0.8rem; color: #666; margin-bottom: 0.3rem;">Destination</div>
                            <div style="font-weight: 600; display: flex; align-items: center;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; color: #dc3545;"></i>
                                {{ $delivery->destination }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 0.8rem; color: #666; margin-bottom: 0.3rem;">Delivery Time</div>
                            <div style="font-weight: 600; display: flex; align-items: center;">
                                <i class="fas fa-clock" style="margin-right: 0.5rem; color: #ffc107;"></i>
                                {{ $delivery->eta ? \Carbon\Carbon::parse($delivery->eta)->diffForHumans() : 'On time' }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Materials -->
                    <div>
                        <div style="font-size: 0.8rem; color: #666; margin-bottom: 0.5rem;">Materials Delivered:</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            @if($delivery->materials_delivered)
                                @foreach($delivery->materials_delivered as $material => $quantity)
                                    <span style="background: #e3f2fd; color: #1976d2; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                                        {{ $material }}: {{ $quantity }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 3rem; color: #666;">
                <i class="fas fa-history" style="font-size: 4rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                <div style="font-size: 1.3rem; margin-bottom: 0.5rem;">No completed deliveries yet</div>
                <div>Deliveries will appear here once confirmed by manufacturers.</div>
            </div>
            @endforelse
        </div>
    </div>
@endsection