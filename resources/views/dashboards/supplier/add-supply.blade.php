@extends('layouts.dashboard')

@section('title', 'Add New Supply')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--text); font-size: 2rem; font-weight: bold; margin-bottom: 1rem;">
            <i class="fas fa-plus"></i> Add New Supply
        </h2>
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('supplier.stock.add') }}" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Material Name</label>
                    <input type="text" name="material_name" required 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"
                           placeholder="e.g., Steel, Aluminum, Plastic">
                    @error('material_name')
                        <div style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Quantity</label>
                    <input type="number" name="quantity" required min="1"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"
                           placeholder="Enter quantity">
                    @error('quantity')
                        <div style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Color (Optional)</label>
                <input type="text" name="colour"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"
                       placeholder="e.g., Red, Blue, Natural">
                @error('colour')
                    <div style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" 
                        style="background: var(--primary); color: white; padding: 0.75rem 2rem; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-plus"></i> Add Supply
                </button>
                <a href="{{ route('supplier.dashboard') }}" 
                   style="background: #6c757d; color: white; padding: 0.75rem 2rem; border-radius: 6px; text-decoration: none; font-size: 1rem; font-weight: 600;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection