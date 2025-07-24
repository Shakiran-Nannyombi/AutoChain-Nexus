@extends('layouts.dashboard')

@section('title', 'Stock Management')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--text); margin-bottom: 1rem; font-weight: 800; font-size: 2rem;">
         Stock Management
    </h2>

    {{-- Add Stock Form --}}
    <div style="background: var(--primary); padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
        <h3 style="color: var(--text); font-size: 1.3rem; margin-bottom: 1rem;">
            <i class="fas fa-plus-circle"></i> Add New Stock
        </h3>

        <form method="POST" action="{{ route('supplier.stock.add') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            @csrf
            <input name="material_name" placeholder="Material Name" required class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #ccc;">
            <input type="number" name="quantity" placeholder="Quantity" required class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #ccc;">
            <input type="text" name="colour" placeholder="Colour" required class="form-control" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #ccc;"> <br>
            <button type="submit" style=" padding: 0.75rem; background:#b5f6aa; color: black; border: none; border-radius: 8px; font-weight: bold; transition: background 0.3s;">
                <i class="fas fa-save"></i> Save Stock
            </button>
        </form>
    </div>

    {{-- Stock List --}}
    <div>
        <h3 style="color: var(--text); margin-bottom: 1rem; font-weight: 800; font-size: 1.5rem;">
            <i class="fas fa-warehouse"></i> Your Current Stock
        </h3>

        @if($stocks->isEmpty())
            <p style="color: #777;">You have no stock records yet.</p>
        @else
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            @foreach($stocks as $stock)
                <div style="background: white; border-left: 5px solid var(--primary); padding: 1rem; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                    <div style="font-weight: bold; font-size: 1.1rem;">{{ $stock->material_name }}</div>
                    <div style="font-size: 0.9rem; color: #555;">Quantity: {{ $stock->quantity }}</div>
                    <div style="font-size: 0.9rem; color: #555;">Colour: <span style="font-weight: 600;">{{ $stock->colour }}</span></div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
