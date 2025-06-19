@extends('layouts.app')

@section('headerTitle', 'Manufacturing Dashboard')

@section('content')
<div class="py-12" x-data="{ currentTab: 'liveProduction' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Manufacturing Control Center</h2>
                </div>

                <!-- Production Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Live Production Rate</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ $liveProductionRate ?? '0' }}<span class="text-xl">/hr</span></p>
                            <p class="text-xs text-green-500">+{{ number_format($liveProductionRate - 100, 1) }}% vs target</p>
                        </div>
                        <div class="text-blue-500 text-3xl">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Overall Equipment Efficiency</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($oee ?? 0, 1) }}%</p>
                            <p class="text-xs text-green-500">World Class: >85%</p>
                        </div>
                        <div class="text-green-500 text-3xl">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h.01M17 7h.01M17 11h.01M17 15h.01M8 7h.01M8 11h.01M8 15h.01M12 7h.01M12 11h.01M12 15h.01M7 2L17 2C18.6569 2 20 3.34315 20 5V19C20 20.6569 18.6569 22 17 22H7C5.34315 22 4 20.6569 4 19V5C4 3.34315 5.34315 2 7 2Z"></path></svg>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Downtime Today</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ $downtimeToday ?? '0' }}<span class="text-xl">hr</span></p>
                            <p class="text-xs text-orange-500">Planned: 1.5hr</p>
                        </div>
                        <div class="text-orange-500 text-3xl">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Quality Defects</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($qualityDefects ?? 0, 1) }}%</p>
                            <p class="text-xs text-red-500">Target: <1%</p>
                        </div>
                        <div class="text-red-500 text-3xl">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex border-b border-gray-200 mb-6">
                    <button @click="currentTab = 'liveProduction'" :class="{ 'border-b-2 border-blue-500 text-blue-600': currentTab === 'liveProduction', 'text-gray-600 hover:text-gray-800': currentTab !== 'liveProduction' }" class="py-2 px-4 text-sm font-medium focus:outline-none">
                        Live Production
                    </button>
                    <button @click="currentTab = 'qualityControl'" :class="{ 'border-b-2 border-blue-500 text-blue-600': currentTab === 'qualityControl', 'text-gray-600 hover:text-gray-800': currentTab !== 'qualityControl' }" class="py-2 px-4 text-sm font-medium focus:outline-none">
                        Quality Control
                    </button>
                    <button @click="currentTab = 'machineHealth'" :class="{ 'border-b-2 border-blue-500 text-blue-600': currentTab === 'machineHealth', 'text-gray-600 hover:text-gray-800': currentTab !== 'machineHealth' }" class="py-2 px-4 text-sm font-medium focus:outline-none">
                        Machine Health
                    </button>
                    <button @click="currentTab = 'maintenance'" :class="{ 'border-b-2 border-blue-500 text-blue-600': currentTab === 'maintenance', 'text-gray-600 hover:text-gray-800': currentTab !== 'maintenance' }" class="py-2 px-4 text-sm font-medium focus:outline-none">
                        Maintenance
                    </button>
                </div>

                <!-- Tabs Content -->
                <div x-show="currentTab === 'liveProduction'">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Production Lines - Real Time -->
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Production Lines - Real Time</h3>
                            <div class="space-y-6">
                                @forelse($productionLines as $line)
                                <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $line['name'] }} - {{ $line['product'] }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $line['status'] === 'running' ? 'bg-green-100 text-green-800' : ($line['status'] === 'maintenance' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($line['status']) }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $line['temperature'] }}°C</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-500 mb-1">
                                        <span>Target: {{ $line['target_hourly'] }}/hour</span>
                                        <span>Hourly Progress: {{ $line['hourly_progress'] }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ ($line['hourly_progress'] / $line['target_hourly']) * 100 }}%"></div>
                                    </div>
                                    <p class="text-sm text-gray-500">Efficiency: {{ $line['efficiency'] }}%</p>
                                </div>
                                @empty
                                <p class="text-gray-600">No production lines active.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Production Alerts -->
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Production Alerts</h3>
                            <div class="space-y-4">
                                @forelse($productionAlerts as $alert)
                                <div class="p-4 rounded-md {{ $alert['status'] === 'urgent' ? 'bg-red-50 border border-red-200 text-red-800' : ($alert['status'] === 'warning' ? 'bg-yellow-50 border border-yellow-200 text-yellow-800' : 'bg-blue-50 border border-blue-200 text-blue-800') }}">
                                    <h4 class="font-medium mb-1">{{ $alert['message'] }}</h4>
                                    <p class="text-sm">{{ $alert['description'] }}</p>
                                </div>
                                @empty
                                <p class="text-gray-600">No production alerts.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'qualityControl'" style="display: none;">
                    <!-- Quality Control Metrics -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quality Control Metrics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($qualityControlMetrics as $metric)
                            <div class="p-4 border rounded-md">
                                <h4 class="font-medium text-gray-900 mb-2">{{ $metric['name'] }}</h4>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-2xl font-bold text-gray-900">{{ number_format($metric['current'], 1) }}%</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $metric['status'] === 'excellent' ? 'bg-green-100 text-green-800' : ($metric['status'] === 'good' ? 'bg-blue-100 text-blue-800' : ($metric['status'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($metric['status']) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-2">Threshold: {{ $metric['threshold'] }}%</p>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $metric['current'] >= $metric['threshold'] ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ $metric['current'] }}%"></div>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-600">No quality control metrics available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'machineHealth'" style="display: none;">
                    <!-- Machine Health Monitoring -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Machine Health Monitoring</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($machineHealth as $machine)
                            <div class="p-4 border rounded-md">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $machine['name'] }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $machine['status'] === 'healthy' ? 'bg-green-100 text-green-800' : ($machine['status'] === 'attention' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($machine['status']) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-2">Next service: {{ $machine['next_service'] }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-blue-500" style="width: {{ $machine['health_score'] }}%"></div>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Health Score: {{ $machine['health_score'] }}%</p>
                            </div>
                            @empty
                            <p class="text-gray-600">No machine health data available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'maintenance'" style="display: none;">
                    <!-- Maintenance Schedule -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Schedule</h3>
                        <div class="space-y-4">
                            @forelse($maintenanceSchedule as $maintenance)
                            <div class="p-4 rounded-md border {{ $maintenance['status'] === 'urgent' ? 'border-red-200 bg-red-50 text-red-800' : ($maintenance['status'] === 'warning' ? 'border-yellow-200 bg-yellow-50 text-yellow-800' : 'border-blue-200 bg-blue-50 text-blue-800') }}">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="font-medium">{{ $maintenance['type'] }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $maintenance['status'] === 'urgent' ? 'bg-red-100 text-red-800' : ($maintenance['status'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($maintenance['time_due']) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $maintenance['description'] }}</p>
                            </div>
                            @empty
                            <p class="text-gray-600">No maintenance scheduled.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 