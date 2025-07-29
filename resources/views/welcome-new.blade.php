<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚕 Taxi Booking - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-gray-800 mb-4">🚕 Taxi Booking</h1>
            <p class="text-xl text-gray-600 mb-8">Your ride is just a tap away!</p>
            <div class="space-y-4">
                <a href="/admin/login" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    Admin Dashboard
                </a>
            </div>
            <div class="mt-12 text-gray-500">
                <p>API Documentation</p>
                <div class="mt-4 space-x-4">
                    <a href="/api/test" class="text-blue-600 hover:underline">Test API</a>
                    <span>•</span>
                    <a href="/api/auth/register" class="text-blue-600 hover:underline">Register API</a>
                    <span>•</span>
                    <a href="/api/rider" class="text-blue-600 hover:underline">Rider API</a>
                    <span>•</span>
                    <a href="/api/driver" class="text-blue-600 hover:underline">Driver API</a>
                </div>
            </div>
            <div class="mt-8 p-6 bg-white rounded-lg shadow-md max-w-lg mx-auto">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">🚀 Quick Start</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Welcome to the Taxi Booking System! This is a comprehensive ride-hailing platform.
                </p>
                <div class="space-y-2 text-sm text-left">
                    <p><strong>✨ Features:</strong></p>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li>📱 Rider mobile app integration</li>
                        <li>🚗 Driver mobile app integration</li>
                        <li>⚡ Admin dashboard for management</li>
                        <li>📍 Real-time tracking</li>
                        <li>💳 Payment processing</li>
                        <li>⭐ Rating and review system</li>
                    </ul>
                </div>
                <div class="mt-4 p-3 bg-green-50 rounded border-l-4 border-green-400">
                    <p class="text-sm text-green-700">
                        <strong>✅ Status:</strong> Server is running successfully!
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
