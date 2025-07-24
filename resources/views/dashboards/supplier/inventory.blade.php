@extends('layouts.dashboard')

@section('title', 'Check Inventory')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 1rem;">
            <i class="fas fa-warehouse"></i> Inventory Management
        </h2>
        
        <!-- Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #27ae60, #229954); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $stocks->count() }}</div>
                        <div style="opacity: 0.9;">Total Items</div>
                    </div>
                    <i class="fas fa-boxes" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $stocks->where('quantity', '>', 0)->count() }}</div>
                        <div style="opacity: 0.9;">In Stock</div>
                    </div>
                    <i class="fas fa-check-circle" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 1.5rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold;">{{ $stocks->where('quantity', '<=', 10)->count() }}</div>
                        <div style="opacity: 0.9;">Low Stock</div>
                    </div>
                    <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
        </div>
        
        <!-- Inventory Table -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Material</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Quantity</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Color</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #333;">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem; font-weight: 600;">{{ $stock->material_name }}</td>
                        <td style="padding: 1rem;">{{ $stock->quantity }}</td>
                        <td style="padding: 1rem;">
                            @if($stock->colour)
                                <span style="background: #f8f9fa; color: #495057; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">
                                    {{ $stock->colour }}
                                </span>
                            @else
                                <span style="color: #6c757d;">N/A</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($stock->quantity > 10)
                                <span style="background: #d4edda; color: #155724; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">In Stock</span>
                            @elseif($stock->quantity > 0)
                                <span style="background: #fff3cd; color: #856404; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Low Stock</span>
                            @else
                                <span style="background: #f8d7da; color: #721c24; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600;">Out of Stock</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #666;">{{ $stock->updated_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #666;">
                            <i class="fas fa-warehouse" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                            <div>No inventory items found</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection