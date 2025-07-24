@extends('layouts.dashboard')

@section('title', 'Delivery Details')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <div style="display: flex; align-items: center; margin-bottom: 2rem;">
            <a href="{{ route('supplier.shipments') }}" style="background: #6c757d; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 6px; margin-right: 1rem;">
               </i> Back to Shipments
            </a>
            <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin: 0;">
                Delivery Details - #SHP-{{ str_pad($delivery->id, 3, '0', STR_PAD_LEFT) }}
            </h2>
        </div>
        
        <!-- Status Timeline -->
        <div style="background: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 1.5rem; color: #333;">Delivery Status</h3>
            <div style="display: flex; align-items: center; gap: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background: #28a745;"></div>
                    <span style="font-weight: 600;">Confirmed</span>
                </div>
                <div style="height: 2px; flex: 1; background: {{ in_array($delivery->status, ['in_transit', 'delivered', 'completed']) ? '#28a745' : '#ddd' }};"></div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background: {{ in_array($delivery->status, ['in_transit', 'delivered', 'completed']) ? '#28a745' : '#ddd' }};"></div>
                    <span style="font-weight: 600; color: {{ in_array($delivery->status, ['in_transit', 'delivered', 'completed']) ? '#333' : '#999' }};">In Transit</span>
                </div>
                <div style="height: 2px; flex: 1; background: {{ in_array($delivery->status, ['delivered', 'completed']) ? '#28a745' : '#ddd' }};"></div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background: {{ in_array($delivery->status, ['delivered', 'completed']) ? '#28a745' : '#ddd' }};"></div>
                    <span style="font-weight: 600; color: {{ in_array($delivery->status, ['delivered', 'completed']) ? '#333' : '#999' }};">Delivered</span>
                </div>
                <div style="height: 2px; flex: 1; background: {{ $delivery->status === 'completed' ? '#28a745' : '#ddd' }};"></div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 20px; height: 20px; border-radius: 50%; background: {{ $delivery->status === 'completed' ? '#28a745' : '#ddd' }};"></div>
                    <span style="font-weight: 600; color: {{ $delivery->status === 'completed' ? '#333' : '#999' }};">Complete</span>
                </div>
            </div>
        </div>
        
        <!-- Live Tracking Map -->
        @if($delivery->status === 'in_transit')
        <div style="background: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 1.5rem; color: #333;">Live Tracking</h3>
            <div id="map" style="height: 400px; border-radius: 8px; background: #f0f0f0; position: relative; overflow: hidden;">
                <!-- Simulated Map -->
                <div style="position: absolute; top: 50%; left: {{ $delivery->progress }}%; transform: translate(-50%, -50%); z-index: 10;">
                    <div style="background: #007bff; color: white; padding: 0.5rem; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
                <!-- Route Line -->
                <div style="position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background: #ddd; transform: translateY(-50%);">
                    <div style="height: 100%; width: {{ $delivery->progress }}%; background: #007bff; transition: width 0.5s;"></div>
                </div>
                <!-- Start Point -->
                <div style="position: absolute; top: 50%; left: 5%; transform: translate(-50%, -50%);">
                    <div style="background: #28a745; color: white; padding: 0.3rem; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-play" style="font-size: 0.7rem;"></i>
                    </div>
                    <div style="position: absolute; top: 30px; left: 50%; transform: translateX(-50%); font-size: 0.8rem; white-space: nowrap;">Start</div>
                </div>
                <!-- End Point -->
                <div style="position: absolute; top: 50%; right: 5%; transform: translate(50%, -50%);">
                    <div style="background: #dc3545; color: white; padding: 0.3rem; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-flag" style="font-size: 0.7rem;"></i>
                    </div>
                    <div style="position: absolute; top: 30px; left: 50%; transform: translateX(-50%); font-size: 0.8rem; white-space: nowrap;">{{ $delivery->destination }}</div>
                </div>
            </div>
            <div style="margin-top: 1rem; text-align: center; color: #666;">
                <i class="fas fa-info-circle"></i> Live tracking updates every 30 seconds
            </div>
        </div>
        @endif
        
        <!-- Delivery Information -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Delivery Information</h3>
                <div style="margin-bottom: 1rem;">
                    <strong>Driver:</strong> {{ $delivery->driver }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Destination:</strong> {{ $delivery->destination }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Status:</strong> 
                    @if($delivery->status === 'in_transit')
                        <span style="background: #007bff; color: white; padding: 0.3rem 0.6rem; border-radius: 4px;">In Transit</span>
                    @elseif($delivery->status === 'delivered')
                        <span style="background: #17a2b8; color: white; padding: 0.3rem 0.6rem; border-radius: 4px;">Awaiting Confirmation</span>
                    @elseif($delivery->status === 'completed')
                        <span style="background: #28a745; color: white; padding: 0.3rem 0.6rem; border-radius: 4px;">Complete</span>
                    @endif
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Progress:</strong> {{ $delivery->progress }}%
                    <div style="background: #f0f0f0; border-radius: 10px; height: 10px; margin-top: 0.5rem;">
                        <div style="background: #007bff; height: 100%; width: {{ $delivery->progress }}%; border-radius: 10px;"></div>
                    </div>
                </div>
                <div>
                    <strong>ETA:</strong> {{ $delivery->eta ? \Carbon\Carbon::parse($delivery->eta)->format('M d, Y') : 'N/A' }}
                </div>
            </div>
            
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Materials</h3>
                @if($delivery->materials_delivered)
                    @foreach($delivery->materials_delivered as $material => $quantity)
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                            <span>{{ $material }}</span>
                            <span style="font-weight: 600;">{{ $quantity }}</span>
                        </div>
                    @endforeach
                @else
                    <p style="color: #666;">No materials specified</p>
                @endif
            </div>
        </div>
    </div>

<script>
@if($delivery->status === 'in_transit')
// Live tracking updates
let currentProgress = {{ $delivery->progress }};
const maxProgress = 95; // Don't go to 100% until delivered

function updateTracking() {
    if (currentProgress < maxProgress) {
        // Simulate progress increase
        currentProgress += Math.random() * 2;
        if (currentProgress > maxProgress) currentProgress = maxProgress;
        
        // Update truck position
        const truck = document.querySelector('#map > div:first-child');
        if (truck) {
            truck.style.left = currentProgress + '%';
        }
        
        // Update progress bar
        const progressBar = document.querySelector('#map .route-line > div');
        if (progressBar) {
            progressBar.style.width = currentProgress + '%';
        }
        
        // Update progress text
        const progressText = document.querySelector('.progress-text');
        if (progressText) {
            progressText.textContent = Math.round(currentProgress) + '%';
        }
    }
}

// Update every 30 seconds
setInterval(updateTracking, 30000);

// Initial update after 5 seconds
setTimeout(updateTracking, 5000);
@endif
</script>

@endsection