<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Event Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-black/20 backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('events.index') }}"
                    class="flex items-center text-white hover:text-purple-300 transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Back to Events</span>
                </a>
                <div class="flex items-center space-x-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30' }}">
                        <i class="fas fa-circle mr-1 text-xs"></i>
                        {{ $event->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-16">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/20 to-secondary/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Event Image -->
                <div class="order-2 lg:order-1">
                    <div class="relative">
                        <div
                            class="w-full h-96 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-calendar-alt text-white text-8xl"></i>
                        </div>
                        <div
                            class="absolute -bottom-6 -right-6 w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center shadow-xl">
                            <i class="fas fa-ticket-alt text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Event Info -->
                <div class="order-1 lg:order-2 space-y-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                            {{ $event->title }}
                        </h1>
                        <div class="flex items-center text-gray-300 mb-6">
                            <i class="fas fa-user-circle text-gray-400 mr-3"></i>
                            <span class="text-lg">Organized by {{ $event->user->name }}</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                        <p class="text-gray-300 leading-relaxed">
                            {{ $event->description ?: 'Join us for an amazing event that will inspire and connect you with like-minded individuals. This is a great opportunity to network, learn, and have fun!' }}
                        </p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20 text-center">
                            <i class="fas fa-users text-purple-400 text-2xl mb-2"></i>
                            <p class="text-white font-semibold text-lg">{{ $event->total_attendees }}</p>
                            <p class="text-gray-400 text-sm">Attendees</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20 text-center">
                            <i class="fas fa-check-circle text-green-400 text-2xl mb-2"></i>
                            <p class="text-white font-semibold text-lg">{{ $event->checked_in_count }}</p>
                            <p class="text-gray-400 text-sm">Checked In</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Details -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Event Details Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-6">Event Details</h2>

                        <div class="space-y-6">
                            <!-- Date & Time -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-start space-x-4">
                                    <div class="bg-blue-500/20 p-3 rounded-xl">
                                        <i class="fas fa-calendar text-blue-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white mb-1">Start Date</h3>
                                        <p class="text-gray-300 text-lg">{{ $event->start_date->format('l, F j, Y') }}
                                        </p>
                                        <p class="text-gray-400">{{ $event->start_date->format('g:i A') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div class="bg-purple-500/20 p-3 rounded-xl">
                                        <i class="fas fa-clock text-purple-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white mb-1">End Date</h3>
                                        <p class="text-gray-300 text-lg">{{ $event->end_date->format('l, F j, Y') }}
                                        </p>
                                        <p class="text-gray-400">{{ $event->end_date->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Location -->
                            @if ($event->location)
                                <div class="flex items-start space-x-4">
                                    <div class="bg-red-500/20 p-3 rounded-xl">
                                        <i class="fas fa-map-marker-alt text-red-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white mb-1">Location</h3>
                                        <p class="text-gray-300 text-lg">{{ $event->location }}</p>
                                        <button
                                            class="mt-2 text-blue-400 hover:text-blue-300 transition-colors duration-300">
                                            <i class="fas fa-directions mr-1"></i>
                                            Get Directions
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Duration -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-500/20 p-3 rounded-xl">
                                    <i class="fas fa-hourglass-half text-green-400 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white mb-1">Duration</h3>
                                    <p class="text-gray-300 text-lg">
                                        {{ $event->start_date->diffForHumans($event->end_date, true) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- About Section -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-6">About This Event</h2>
                        <div class="prose prose-invert max-w-none">
                            <p class="text-gray-300 leading-relaxed mb-4">
                                {{ $event->description ?: 'This is an exciting event that brings together professionals, enthusiasts, and curious minds from various backgrounds. Whether you\'re looking to network, learn new skills, or simply have a great time, this event has something for everyone.' }}
                            </p>
                            <p class="text-gray-300 leading-relaxed">
                                Don't miss out on this incredible opportunity to connect with like-minded individuals
                                and expand your horizons. We look forward to seeing you there!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Register Card -->
                    <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl p-8 text-center">
                        <h3 class="text-2xl font-bold text-white mb-4">Ready to Join?</h3>
                        <p class="text-purple-100 mb-6">
                            Register now to secure your spot at this amazing event!
                        </p>
                        <button
                            class="w-full bg-white text-purple-600 font-bold py-4 px-6 rounded-xl hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            Register Now
                        </button>
                        <p class="text-purple-200 text-sm mt-4">
                            Free registration • QR code provided
                        </p>
                    </div>

                    <!-- Event Info -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                        <h3 class="text-lg font-semibold text-white mb-4">Event Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Event ID</span>
                                <span class="text-white font-mono text-sm">{{ $event->id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Status</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300' }}">
                                    {{ $event->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Created</span>
                                <span class="text-white text-sm">{{ $event->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                        <h3 class="text-lg font-semibold text-white mb-4">Share Event</h3>
                        <div class="flex space-x-3">
                            <button
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-lg transition-colors duration-300">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button
                                class="flex-1 bg-blue-800 hover:bg-blue-900 text-white py-2 px-3 rounded-lg transition-colors duration-300">
                                <i class="fab fa-facebook"></i>
                            </button>
                            <button
                                class="flex-1 bg-pink-600 hover:bg-pink-700 text-white py-2 px-3 rounded-lg transition-colors duration-300">
                                <i class="fab fa-instagram"></i>
                            </button>
                            <button
                                class="flex-1 bg-blue-700 hover:bg-blue-800 text-white py-2 px-3 rounded-lg transition-colors duration-300">
                                <i class="fab fa-linkedin"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black/20 backdrop-blur-lg border-t border-white/10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-gray-400">© 2024 Events Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
