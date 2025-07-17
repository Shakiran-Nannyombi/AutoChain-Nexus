@extends('layouts.dashboard')
@section('content')
<div class="content-card">
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">&larr; Back to Applications</a>
    <div class="d-flex align-items-center mb-3">
        <img src="{{ asset($analyst->profile_photo ?? 'images/profile/analyst.jpeg') }}" alt="photo" width="64" class="rounded-circle me-3">
        <div>
            <h3>{{ $analyst->name }}</h3>
            <p class="mb-1">Company: {{ $analyst->company }}</p>
            <p class="mb-1">Email: {{ $analyst->email }}</p>
        </div>
    </div>
    <h5>Portfolio / Expertise</h5>
    <ul>
        <li>Certification: {{ $analyst->analyst_certification ?? 'N/A' }}</li>
        <li>Specializations: {{ $analyst->specialization_areas ?? 'N/A' }}</li>
        <li>Research Methods: {{ $analyst->research_methodologies ?? 'N/A' }}</li>
        <li>Reporting: {{ $analyst->reporting_capabilities ?? 'N/A' }}</li>
    </ul>
    {{-- Optionally, list recent reports or analytics here --}}
</div>
@endsection 