@extends('layouts.app')

@section('content')
    <!-- Tab Navigation -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="dashboardTabs" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="overview-tab" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="user-management-tab" type="button" role="tab" aria-controls="user-management" aria-selected="false">User Management</button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div id="dashboardTabContent">
        <!-- Overview Tab Content -->
        <div class="p-4 rounded-lg bg-white" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12">
                <div class="bg-blue-100 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-white-500">Total Users</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                    <p class="text-sm text-gray-600">Active accounts</p>
                </div>
                <div class="bg-blue-100 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-white-500">Active Users</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeUsers }}</p>
                    <p class="text-sm text-gray-600">Currently active users</p>
                </div>
                <div class="bg-blue-100 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-white-500">Pending Users</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingUsers }}</p>
                    <p class="text-sm text-gray-600">Users awaiting approval</p>
                </div>
                <div class="bg-blue-100 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-white-500">System Health</h3>
                    <div class="mt-2 flex items-center">
                        <span class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                        <p class="text-lg font-semibold text-gray-900">{{ $systemHealth }}</p>
                    </div>
                </div>
            </div>

            <!-- User Distribution by Role -->
            <div class="mb-6">
                <h3 class="text-xl font-medium font-semibold text-gray-900 mb-4">User Distribution by Role</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($formattedUserDistribution as $roleName => $count)
                        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-600">{{ $roleName }}</p>
                            <p class="text-xl font-semibold text-gray-900">{{ $count }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity Log -->
            <div class="mb-6">
                <h3 class="text-xl font-medium font-semibold text-gray-900 mb-4">Recent System Activity</h3>
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary">
                                        <span class="text-sm font-medium leading-none text-white">
                                            {{ substr($activity->user->name ?? 'System', 0, 2) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No recent activities</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- User Management Tab Content -->
        <div class="hidden p-4 rounded-lg bg-white" id="user-management" role="tabpanel" aria-labelledby="user-management-tab">
            <!-- User Management Table -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 border-b-2 border-primary pb-2 mb-4">User Management</h3>
                    <div class="flex space-x-2">
                        <button id="addUserButton" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                            Add User
                        </button>
                        <a href="{{ route('activity-logs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                            View Logs
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary">
                                                <span class="text-sm font-medium leading-none text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->email_verified_at ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-primary hover:text-primary-dark mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No other users found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

<x-add-user-modal/>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing modal script...');

        // Tab Switching Logic
        const tabs = document.querySelectorAll('#dashboardTabs button');
        const tabContents = document.querySelectorAll('#dashboardTabContent > div');

        function activateTab(tabId) {
            tabs.forEach(tab => {
                if (tab.id === tabId) {
                    tab.classList.add('border-primary', 'text-primary');
                    tab.classList.remove('border-transparent', 'text-gray-500');
                    tab.setAttribute('aria-selected', 'true');
                } else {
                    tab.classList.remove('border-primary', 'text-primary');
                    tab.classList.add('border-transparent', 'text-gray-500');
                    tab.setAttribute('aria-selected', 'false');
                }
            });

            tabContents.forEach(content => {
                if (content.id === tabId.replace('-tab', '')) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        }

        // Set initial active tab
        activateTab('overview-tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                activateTab(this.id);
            });
        });

        // Add User Modal Logic
        const addUserButton = document.getElementById('addUserButton');
        const addUserModal = document.getElementById('addUserModal');
        const closeAddUserModalButton = document.getElementById('closeAddUserModal');

        console.log('Add User Button (inside DOMContentLoaded):', addUserButton);
        console.log('Add User Modal (inside DOMContentLoaded):', addUserModal);
        console.log('Close Modal Button (inside DOMContentLoaded):', closeAddUserModalButton);

        if (addUserButton && addUserModal && closeAddUserModalButton) {
            addUserButton.onclick = function(e) {
                e.preventDefault();
                console.log('Add User button clicked (inside DOMContentLoaded)');
                addUserModal.classList.remove('hidden');
            };

            closeAddUserModalButton.onclick = function(e) {
                e.preventDefault();
                console.log('Close modal button clicked (inside DOMContentLoaded)');
                addUserModal.classList.add('hidden');
            };

            addUserModal.addEventListener('click', function(event) {
                if (event.target === addUserModal) {
                    addUserModal.classList.add('hidden');
                }
            });

        } else {
            console.error('Could not find one or more elements for the Add User modal (inside DOMContentLoaded).');
        }
    });
</script>