<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Taxi Booking</title>
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
                <h1 class="text-3xl font-bold text-gray-900">Platform Settings</h1>
                <p class="text-gray-600">Manage platform configuration and preferences</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- General Settings -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">General Settings</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Platform Name</label>
                                <input type="text" value="Taxi Booking Platform" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Support Email</label>
                                <input type="email" value="support@taxibooking.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Support Phone</label>
                                <input type="tel" value="+1-800-TAXI-NOW" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option>UTC</option>
                                    <option selected>America/New_York</option>
                                    <option>America/Los_Angeles</option>
                                    <option>Europe/London</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Settings -->
                    <div class="bg-white rounded-lg shadow mt-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Pricing Configuration</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Base Fare</label>
                                    <input type="number" value="3.50" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Per Mile Rate</label>
                                    <input type="number" value="1.25" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Per Minute Rate</label>
                                    <input type="number" value="0.35" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Fare</label>
                                    <input type="number" value="5.00" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Platform Commission (%)</label>
                                <input type="number" value="20" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Driver Settings -->
                    <div class="bg-white rounded-lg shadow mt-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Driver Management</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Search Radius (miles)</label>
                                    <input type="number" value="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Driver Timeout (minutes)</label>
                                    <input type="number" value="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="auto-assign" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="auto-assign" class="text-sm text-gray-700">Auto-assign rides to nearest driver</label>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="driver-verification" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="driver-verification" class="text-sm text-gray-700">Require driver verification</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Status -->
                <div class="lg:col-span-1">
                    <!-- System Status -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">System Status</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Platform Status</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Online</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Payment Gateway</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Connected</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">SMS Service</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Limited</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Map Service</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow mt-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Send System Notification
                            </button>
                            
                            <button class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                Export Data
                            </button>
                            
                            <button class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                                Maintenance Mode
                            </button>
                            
                            <button class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Emergency Stop
                            </button>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow mt-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm text-gray-900">Settings updated</p>
                                        <p class="text-xs text-gray-500">2 hours ago</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm text-gray-900">New driver approved</p>
                                        <p class="text-xs text-gray-500">5 hours ago</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm text-gray-900">Pricing updated</p>
                                        <p class="text-xs text-gray-500">1 day ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-8 flex justify-end">
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Save All Settings
                </button>
            </div>
        </div>
    </div>

    <script>
        // Add some interactivity for demo purposes
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-save simulation
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Simulate auto-save with visual feedback
                    const originalBg = this.style.backgroundColor;
                    this.style.backgroundColor = '#dcfce7'; // light green
                    setTimeout(() => {
                        this.style.backgroundColor = originalBg;
                    }, 1000);
                });
            });
        });
    </script>
</body>
</html>
