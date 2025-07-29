<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings - Taxi Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h2 class="text-xl font-bold">Taxi Admin</h2>
            </div>
            <nav class="mt-8">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.users') ? 'bg-blue-700' : '' }}">Users</a>
                <a href="{{ route('admin.riders') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.riders') ? 'bg-blue-700' : '' }}">Riders</a>
                <a href="{{ route('admin.drivers') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.drivers') ? 'bg-blue-700' : '' }}">Drivers</a>
                <a href="{{ route('admin.rides') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.rides') ? 'bg-blue-700' : '' }}">Rides</a>
                <a href="{{ route('admin.earnings') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.earnings') ? 'bg-blue-700' : '' }}">Earnings</a>
                <a href="{{ route('admin.settings') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.settings') ? 'bg-blue-700' : '' }}">Settings</a>
                <a href="{{ route('admin.logout') }}" class="block py-2 px-4 hover:bg-blue-700 mt-8">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Earnings Dashboard</h1>
                <p class="text-gray-600">Track platform earnings and revenue</p>
            </div>

            <!-- Earnings Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                            <p class="text-2xl font-semibold text-gray-900">${{ number_format($earningsData['total'], 2) }}</p>
                            <p class="text-xs text-gray-500">All time revenue</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Today's Earnings</p>
                            <p class="text-2xl font-semibold text-gray-900">${{ number_format($earningsData['today'], 2) }}</p>
                            <p class="text-xs text-gray-500">{{ now()->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">This Month</p>
                            <p class="text-2xl font-semibold text-gray-900">${{ number_format($earningsData['month'], 2) }}</p>
                            <p class="text-xs text-gray-500">{{ now()->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h3>
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>

                <!-- Earnings Distribution -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Earnings Distribution</h3>
                    <canvas id="distributionChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Commission Breakdown -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Commission Breakdown</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">${{ number_format($earningsData['total'] * 0.20, 2) }}</div>
                            <div class="text-sm text-gray-600">Platform Commission (20%)</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">${{ number_format($earningsData['total'] * 0.75, 2) }}</div>
                            <div class="text-sm text-gray-600">Driver Earnings (75%)</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">${{ number_format($earningsData['total'] * 0.03, 2) }}</div>
                            <div class="text-sm text-gray-600">Payment Processing (3%)</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">${{ number_format($earningsData['total'] * 0.02, 2) }}</div>
                            <div class="text-sm text-gray-600">Platform Fees (2%)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ride ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fare</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample data - in real app, this would come from controller -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">RIDE001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Driver One</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rider One</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$25.50</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$5.10</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ now()->subDays(1)->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">RIDE002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Driver Two</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rider Two</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$18.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$3.60</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ now()->subDays(2)->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">RIDE003</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Driver Three</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rider Three</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$32.75</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">$6.55</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ now()->subDays(3)->format('M d, Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [1200, 1900, 3000, 2500, 3200, 3800],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        // Distribution Chart
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Driver Earnings', 'Platform Commission', 'Payment Processing', 'Platform Fees'],
                datasets: [{
                    data: [75, 20, 3, 2],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(168, 85, 247)',
                        'rgb(251, 191, 36)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
