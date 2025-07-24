@extends('layouts.dashboard')

@section('title', 'Live Tracking')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); margin-bottom: 1rem; font-weight:bold; font-size: 2rem;">
            Live Order Tracking
        </h2>
        
        <!-- Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $inTransitDeliveries->count() }}</div>
                        <div style="opacity: 0.9;">In Transit</div>
                    </div>
                    <i class="fas fa-truck" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #28a745, #1e7e34); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $deliveredToday }}</div>
                        <div style="opacity: 0.9;">Delivered Today</div>
                    </div>
                    <i class="fas fa-check-circle" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #ffc107, #e0a800); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $avgProgress }}%</div>
                        <div style="opacity: 0.9;">Avg Progress</div>
                    </div>
                    <i class="fas fa-chart-line" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
        </div>

        <!-- Live Tracking Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
            @forelse($inTransitDeliveries as $delivery)
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #007bff;">
                <h3 style="margin: 0; color: #333;">Delivery #{{ str_pad($delivery->id, 3, '0', STR_PAD_LEFT) }}</h3> <br>
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1rem;">
                    <span style="background: #430c60; color: white; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem;">
                        <i class="fas fa-truck"></i> In Transit
                    </span>
                </div>
                
                <!-- Driver & Destination Info -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <div style="font-size: 0.9rem; color: #666; margin-bottom: 0.3rem;">Driver</div>
                        <div style="font-weight: 600; display: flex; align-items: center;">
                            <i class="fas fa-user" style="margin-right: 0.5rem; color: #007bff;"></i>
                            {{ $delivery->driver }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; color: #666; margin-bottom: 0.3rem;">Destination</div>
                        <div style="font-weight: 600; display: flex; align-items: center;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; color: #dc3545;"></i>
                            {{ $delivery->destination }}
                        </div>
                    </div>
                </div>
                
                <!-- Live Map -->
                <div style="height: 120px; background: #f8f9fa; border-radius: 8px; position: relative; margin-bottom: 1rem; overflow: hidden;">
                    <!-- Truck Icon -->
                    <div class="truck-icon" data-delivery="{{ $delivery->id }}" style="position: absolute; top: 50%; left: {{ $delivery->progress }}%; transform: translate(-50%, -50%); z-index: 10; transition: left 0.5s ease;">
                        <div style="background: #197307; color: white; padding: 0.4rem; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                            <i class="fas fa-truck" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    
                    <!-- Route -->
                    <div style="position: absolute; top: 50%; left: 0; width: 100%; height: 3px; background: #dee2e6; transform: translateY(-50%);">
                        <div class="progress-bar" data-delivery="{{ $delivery->id }}" style="height: 100%; width: {{ $delivery->progress }}%; background: #007bff; transition: width 0.5s ease;"></div>
                    </div>
                    
                    <!-- Start & End Points -->
                    <div style="position: absolute; top: 50%; left: 8px; transform: translateY(-50%);">
                        <div style="background: #28a745; width: 12px; height: 12px; border-radius: 50%;"></div>
                    </div>
                    <div style="position: absolute; top: 50%; right: 8px; transform: translateY(-50%);">
                        <div style="background: #dc3545; width: 12px; height: 12px; border-radius: 50%;"></div>
                    </div>
                </div>
                
                <!-- Progress Info -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <span style="font-size: 0.9rem; color: #666;">Progress: </span>
                        <span class="progress-text" data-delivery="{{ $delivery->id }}" style="font-weight: 600; color: #007bff;">{{ $delivery->progress }}%</span>
                    </div>
                    <div>
                        <span style="font-size: 0.9rem; color: #666;">ETA: </span>
                        <span style="font-weight: 600;">{{ $delivery->eta ? \Carbon\Carbon::parse($delivery->eta)->format('M d, H:i') : 'N/A' }}</span>
                    </div>
                </div>
                
                <!-- Materials -->
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">Materials:</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @if($delivery->materials_delivered)
                            @foreach($delivery->materials_delivered as $material => $quantity)
                                <span style="background: #e3f2fd; color: #1976d2; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem;">
                                    {{ $material }}: {{ $quantity }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                <!-- Action Button -->
                <a href="{{ route('supplier.delivery.details', $delivery->id) }}" style="display: block; text-align: center; background: var(--primary); color: var(--text); padding: 0.6rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-eye"></i> View Full Details
                </a>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #666;">
                <i class="fas fa-truck" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                <div style="font-size: 1.2rem; margin-bottom: 0.5rem;">No deliveries in transit</div>
                <div>All your deliveries have been completed or are pending shipment.</div>
            </div>
            @endforelse
        </div>
    </div>

<script>
// Live tracking updates
function updateLiveTracking() {
    document.querySelectorAll('.truck-icon').forEach(truck => {
        const deliveryId = truck.dataset.delivery;
        const progressBar = document.querySelector(`.progress-bar[data-delivery="${deliveryId}"]`);
        const progressText = document.querySelector(`.progress-text[data-delivery="${deliveryId}"]`);
        
        // Get current progress
        let currentProgress = parseFloat(progressBar.style.width) || {{ $inTransitDeliveries->first()->progress ?? 0 }};
        
        // Simulate progress (increase by 0.5-2%)
        if (currentProgress < 95) {
            currentProgress += Math.random() * 1.5 + 0.5;
            if (currentProgress > 95) currentProgress = 95;
            
            // Update visuals
            truck.style.left = currentProgress + '%';
            progressBar.style.width = currentProgress + '%';
            progressText.textContent = Math.round(currentProgress) + '%';
        }
    });
}

// Update every 30 seconds
setInterval(updateLiveTracking, 30000);

// Initial update after 10 seconds
setTimeout(updateLiveTracking, 10000);

// Add pulse animation to truck icons
setInterval(() => {
    document.querySelectorAll('.truck-icon > div').forEach(truck => {
        truck.style.transform = 'scale(1.1)';
        setTimeout(() => {
            truck.style.transform = 'scale(1)';
        }, 200);
    });
}, 3000);
</script>

@endsection