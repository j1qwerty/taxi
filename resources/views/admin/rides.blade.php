<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rides Management - Taxi Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
                <h1 class="text-3xl font-bold text-gray-900">Rides Management</h1>
                <p class="text-gray-600">Monitor and manage all ride bookings</p>
            </div>

            <!-- Rides Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Rides</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $rides->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $rides->where('status', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $rides->whereIn('status', ['searching', 'accepted', 'arrived', 'started'])->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Cancelled</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $rides->where('status', 'cancelled')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Options -->
            <div class="bg-white rounded-lg shadow mb-6 p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="searching">Searching</option>
                            <option value="accepted">Accepted</option>
                            <option value="arrived">Arrived</option>
                            <option value="started">Started</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Methods</option>
                            <option value="cash">Cash</option>
                            <option value="wallet">Wallet</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <input type="date" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mt-6">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Apply Filters</button>
                    </div>
                </div>
            </div>

            <!-- Rides Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">All Rides</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ride Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fare</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($rides as $ride)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $ride->ride_id }}</div>
                                        <div class="text-sm text-gray-500">{{ $ride->created_at->format('M d, Y H:i') }}</div>
                                        @if($ride->distance)
                                            <div class="text-xs text-gray-400">{{ number_format($ride->distance, 1) }} km</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                            {{ substr($ride->rider->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $ride->rider->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $ride->rider->user->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ride->driver)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center text-white text-sm font-medium">
                                                {{ substr($ride->driver->user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $ride->driver->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $ride->driver->vehicle_number ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No driver assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <div class="truncate max-w-48" title="{{ $ride->pickup_address }}">
                                            <span class="text-green-600">●</span> {{ \Str::limit($ride->pickup_address, 30) }}
                                        </div>
                                        <div class="truncate max-w-48" title="{{ $ride->drop_address }}">
                                            <span class="text-red-600">●</span> {{ \Str::limit($ride->drop_address, 30) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($ride->actual_fare)
                                            <div class="font-medium">${{ number_format($ride->actual_fare, 2) }}</div>
                                            <div class="text-xs text-gray-500">Final</div>
                                        @else
                                            <div class="font-medium">${{ number_format($ride->estimated_fare, 2) }}</div>
                                            <div class="text-xs text-gray-500">Estimated</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($ride->status)
                                        @case('searching')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Searching</span>
                                            @break
                                        @case('accepted')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Accepted</span>
                                            @break
                                        @case('arrived')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Arrived</span>
                                            @break
                                        @case('started')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">Started</span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($ride->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @switch($ride->payment_method)
                                            @case('cash')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    Cash
                                                </span>
                                                @break
                                            @case('wallet')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                    Wallet
                                                </span>
                                                @break
                                            @case('online')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                    </svg>
                                                    Online
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewRideDetails('{{ $ride->ride_id }}')" class="text-blue-600 hover:text-blue-900">View</button>
                                        @if($ride->status !== 'completed' && $ride->status !== 'cancelled')
                                            <button onclick="updateRideStatus('{{ $ride->ride_id }}')" class="text-yellow-600 hover:text-yellow-900">Update</button>
                                        @endif
                                        @if($ride->status === 'searching')
                                            <button onclick="cancelRide('{{ $ride->ride_id }}')" class="text-red-600 hover:text-red-900">Cancel</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No rides found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($rides->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $rides->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ride Details Modal -->
    <div id="rideModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Ride Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="p-6">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewRideDetails(rideId) {
            document.getElementById('rideModal').classList.remove('hidden');
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ride ID</label>
                            <p class="text-sm text-gray-900">${rideId}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="text-sm text-gray-900">In Progress</p>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <p class="text-gray-500">Loading ride details...</p>
                    </div>
                </div>
            `;
        }

        function updateRideStatus(rideId) {
            alert('Update ride status for: ' + rideId);
        }

        function cancelRide(rideId) {
            if (confirm('Are you sure you want to cancel this ride?')) {
                alert('Ride cancelled: ' + rideId);
            }
        }

        function closeModal() {
            document.getElementById('rideModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rideModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
