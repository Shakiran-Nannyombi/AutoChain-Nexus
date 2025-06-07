<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-foreground dark:text-gray-100">Total Orders</h3>
                        <p class="text-3xl font-bold text-foreground dark:text-gray-100">1,234</p>
                        <p class="text-sm text-green-500">+12% from last month</p>
                    </div>
                    <div class="text-muted-foreground dark:text-gray-600">
                         {{-- Icon Placeholder - Using a simple box icon for now --}}
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                </div>
                <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-foreground dark:text-gray-100">Active Shipments</h3>
                        <p class="text-3xl font-bold text-foreground dark:text-gray-100">89</p>
                        <p class="text-sm text-green-500">+5% from last week</p>
                    </div>
                    <div class="text-muted-foreground dark:text-gray-600">
                         {{-- Icon Placeholder - Using a simple truck icon for now --}}
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 13h-1a2 2 0 00-2-2H8a2 2 0 00-2 2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-7m-1-7a2 2 0 00-2-2H9a2 2 0 00-2 2m7 7a2 2 0 01-4 0"></path></svg>
                    </div>
                </div>
                <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-foreground dark:text-gray-100">Production Units</h3>
                        <p class="text-3xl font-bold text-foreground dark:text-gray-100">456</p>
                        <p class="text-sm text-green-500">+8% from last month</p>
                    </div>
                    <div class="text-muted-foreground dark:text-gray-600">
                         {{-- Icon Placeholder - Using a simple factory icon for now --}}
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                 <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-foreground dark:text-gray-100">Revenue</h3>
                        <p class="text-3xl font-bold text-foreground dark:text-gray-100">$2.4M</p>
                        <p class="text-sm text-green-500">+15% from last month</p>
                    </div>
                    <div class="text-muted-foreground dark:text-gray-600">
                         {{-- Icon Placeholder - Using a simple dollar icon for now --}}
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 6v2m0 6v2m-6-3a9 9 0 1118 0H6z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Supply Chain Workflow --}}
                <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-foreground dark:text-gray-100 mb-4">Supply Chain Workflow</h3>
                    <div class="space-y-4">
                        {{-- Workflow steps - Static placeholders --}}
                         <div class="flex items-center justify-between">
                            <span>Raw Materials</span>
                             <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-700 dark:text-green-200">completed</span>
                        </div>
                         <div class="flex items-center justify-between">
                            <span>Manufacturing</span>
                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-blue-700 dark:text-blue-200">in progress</span>
                        </div>
                         <div class="flex items-center justify-between">
                            <span>Quality Check</span>
                             <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-yellow-700 dark:text-yellow-200">pending</span>
                        </div>
                         <div class="flex items-center justify-between">
                            <span>Warehousing</span>
                             <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-yellow-700 dark:text-yellow-200">pending</span>
                        </div>
                         <div class="flex items-center justify-between">
                            <span>Distribution</span>
                             <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-yellow-700 dark:text-yellow-200">pending</span>
                        </div>
                         <div class="flex items-center justify-between">
                            <span>Retail</span>
                             <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-yellow-700 dark:text-yellow-200">pending</span>
                        </div>
                         {{-- Placeholder for the workflow chart/progress bars --}}
                         <div class="mt-4 space-y-2">
                            <div class="flex items-center">
                                <span class="w-12 text-sm">100%</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                     <div class="bg-blue-600 h-2.5 rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">→</span>
                            </div>
                             <div class="flex items-center">
                                <span class="w-12 text-sm">75%</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                     <div class="bg-blue-600 h-2.5 rounded-full" style="width: 75%"></div>
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">→</span>
                            </div>
                             <div class="flex items-center">
                                <span class="w-12 text-sm">0%</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                     <div class="bg-gray-400 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">→</span>
                            </div>
                             <div class="flex items-center">
                                <span class="w-12 text-sm">0%</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                     <div class="bg-gray-400 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">→</span>
                            </div>
                             <div class="flex items-center">
                                <span class="w-12 text-sm">0%</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                     <div class="bg-gray-400 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">→</span>
                            </div>
                             <div class="flex items-center">
                                <span class="w-12 text-sm">0%</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                     <div class="bg-gray-400 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                                <span class="ml-2 text-sm text-muted-foreground">→</span>
                            </div>
                         </div>
                    </div>
                </div>
                {{-- ML Insights --}}
                <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                     <h3 class="text-lg font-semibold text-foreground dark:text-gray-100 mb-4">ML Insights</h3>
                     <div class="space-y-4">
                         {{-- ML Insight items - Static placeholders --}}
                         <div class="flex items-start space-x-2">
                             {{-- Icon Placeholder --}}
                             <div class="text-muted-foreground dark:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                             <div>
                                 <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Demand Forecast</h4>
                                 <p class="text-green-500 text-sm">15% increase</p>
                                 <p class="text-muted-foreground dark:text-gray-400 text-sm">Expected demand for SUVs next quarter</p>
                             </div>
                         </div>
                          <div class="flex items-start space-x-2">
                              {{-- Icon Placeholder --}}
                              <div class="text-muted-foreground dark:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v6m-3-3h6m-9-11V7l-3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zm-9 3V7l-3 3m6-3V7l-3 3"></path></svg></div>
                             <div>
                                 <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Customer Segmentation</h4>
                                 <p class="text-foreground dark:text-gray-100 text-sm">3 segments</p>
                                 <p class="text-muted-foreground dark:text-gray-400 text-sm">Premium, Fleet, and Economic buyers identified</p>
                             </div>
                         </div>
                          <div class="flex items-start space-x-2">
                              {{-- Icon Placeholder --}}
                              <div class="text-muted-foreground dark:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.86 4.297A9 9 0 0118 10a9.003 9.003 0 01-6 6m-9-6a9 9 0 019-9m-6.003 18A9 9 0 0112 15h.01"></path></svg></div>
                             <div>
                                 <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Production Optimization</h4>
                                 <p class="text-green-500 text-sm">8% efficiency</p>
                                 <p class="text-muted-foreground dark:text-gray-400 text-sm">Recommended production line improvements</p>
                             </div>
                         </div>
                     </div>
                </div>
            </div>

            {{-- Secondary Grid --}}
             <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Recent Orders --}}
                <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-foreground dark:text-gray-100 mb-4">Recent Orders</h3>
                    <ul class="space-y-4">
                        {{-- Recent Order items - Static placeholders --}}
                        <li class="flex justify-between items-center">
                            <div>
                                <p class="text-foreground dark:text-gray-100">ORD-2024-001</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">Premium Motors Ltd</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">2024-06-01</p>
                            </div>
                            <div class="text-right">
                                <p class="text-foreground dark:text-gray-100">$45,000</p>
                                <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-700 dark:text-green-200">shipped</span>
                            </div>
                        </li>
                         <li class="flex justify-between items-center">
                            <div>
                                <p class="text-foreground dark:text-gray-100">ORD-2024-002</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">City Car Dealers</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">2024-06-01</p>
                            </div>
                            <div class="text-right">
                                <p class="text-foreground dark:text-gray-100">$32,500</p>
                                <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-yellow-700 dark:text-yellow-200">processing</span>
                            </div>
                        </li>
                         <li class="flex justify-between items-center">
                            <div>
                                <p class="text-foreground dark:text-gray-100">ORD-2024-003</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">AutoMax Showroom</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">2024-05-31</p>
                            </div>
                            <div class="text-right">
                                <p class="text-foreground dark:text-gray-100">$78,900</p>
                                <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-700 dark:text-green-200">delivered</span>
                            </div>
                        </li>
                         <li class="flex justify-between items-center">
                            <div>
                                <p class="text-foreground dark:text-gray-100">ORD-2024-004</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">Fleet Solutions Inc</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">2024-05-31</p>
                            </div>
                            <div class="text-right">
                                <p class="text-foreground dark:text-gray-100">$125,000</p>
                                <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-yellow-700 dark:text-yellow-200">pending</span>
                            </div>
                        </li>
                         <li class="flex justify-between items-center">
                            <div>
                                <p class="text-foreground dark:text-gray-100">ORD-2024-005</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">Metro Car Sales</p>
                                <p class="text-muted-foreground dark:text-gray-400 text-sm">2024-05-30</p>
                            </div>
                            <div class="text-right">
                                <p class="text-foreground dark:text-gray-100">$54,300</p>
                                <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-700 dark:text-green-200">shipped</span>
                            </div>
                        </li>
                    </ul>
                </div>
                 {{-- Inventory Status --}}
                <div class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-foreground dark:text-gray-100 mb-4">Inventory Status</h3>
                    <div class="space-y-4">
                         {{-- Inventory items - Static placeholders --}}
                         <div>
                            <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Engine Components</h4>
                            <div class="w-full bg-muted rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 49%"></div>
                            </div>
                            <p class="text-sm text-muted-foreground dark:text-gray-400 text-right">245 / 500 units</p>
                        </div>
                         <div>
                            <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Body Frames</h4>
                            <div class="w-full bg-muted rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 44%"></div>
                            </div>
                            <p class="text-sm text-muted-foreground dark:text-gray-400 text-right">89 / 200 units</p>
                        </div>
                         <div>
                            <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Electronics</h4>
                            <div class="w-full bg-muted rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 52%"></div>
                            </div>
                            <p class="text-sm text-muted-foreground dark:text-gray-400 text-right">156 / 300 sets</p>
                        </div>
                         <div>
                            <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Interior Parts</h4>
                            <div class="w-full bg-muted rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                            <p class="text-sm text-muted-foreground dark:text-gray-400 text-right">67 / 150 sets</p>
                        </div>
                         <div>
                            <h4 class="text-md font-semibold text-foreground dark:text-gray-100">Tires</h4>
                            <div class="w-full bg-muted rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 89%"></div>
                            </div>
                            <p class="text-sm text-muted-foreground dark:text-gray-400 text-right">890 / 1000 pieces</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</x-app-layout>
