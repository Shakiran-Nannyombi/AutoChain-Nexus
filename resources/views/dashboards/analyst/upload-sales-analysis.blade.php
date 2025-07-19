@extends('layouts.dashboard')

@section('title', 'Upload Sales Analysis')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
<div class="content-card" style="max-width: 600px; margin: 2rem auto;">
    <h2 style="color: var(--primary); font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem;">
        <i class="fas fa-upload"></i> Upload Sales Analysis
    </h2>
    @if ($errors->any())
        <div style="background: #ffeaea; color: #b71c1c; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <ul style="margin: 0; padding-left: 1.2rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('analyst.upload-sales-analysis.submit') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.2rem;">
        @csrf
        <div>
            <label for="title" style="font-weight: 600;">Title</label>
            <input type="text" name="title" id="title" class="form-control" required style="width: 100%; padding: 0.7rem; border-radius: 6px; border: 1px solid #ccc;">
        </div>
        <div>
            <label for="summary" style="font-weight: 600;">Summary</label>
            <textarea name="summary" id="summary" class="form-control" required rows="3" style="width: 100%; padding: 0.7rem; border-radius: 6px; border: 1px solid #ccc;"></textarea>
        </div>
        <div>
            <label for="file" style="font-weight: 600;">Sales Analysis File (CSV or JSON)</label>
            <input type="file" name="file" id="file" class="form-control" required accept=".csv,.json,.txt" style="width: 100%;">
        </div>
        <button type="submit" style="background: var(--primary); color: #fff; border: none; border-radius: 6px; padding: 0.8rem 1.5rem; font-weight: 600; font-size: 1rem; margin-top: 0.5rem;">Upload</button>
    </form>
</div>
@endsection 