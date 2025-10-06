<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Discover Amazing Events</title>
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
    <!-- Header -->
    <header class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/20 to-secondary/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
                    <span class="bg-gradient-to-r from-white to-purple-200 bg-clip-text text-transparent">
                        Discover
                    </span>
                    <br>
                    <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                        Amazing Events
                    </span>
                </h1>
                <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
                    Join thousands of attendees at our curated selection of exciting events.
                    From tech conferences to networking meetups, find your next adventure.
                </p>
                <div class="flex justify-center space-x-4">
                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-calendar-check text-green-400 mr-2"></i>
                        <span>{{ $events->count() }} Active Events</span>
                    </div>
                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-users text-blue-400 mr-2"></i>
                        <span>Growing Community</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Events Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if ($events->count() > 0)
            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($events as $event)
                    <div
                        class="group relative bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 hover:border-white/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/25">
                        <!-- Event Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                                <i class="fas fa-circle text-green-400 mr-1 text-xs"></i>
                                Active
                            </span>
                        </div>

                        <!-- Event Image Placeholder -->
                        <div
                            class="w-full h-48 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl mb-6 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-4xl"></i>
                        </div>

                        <!-- Event Content -->
                        <div class="space-y-4">
                            <!-- Title -->
                            <h3
                                class="text-xl font-bold text-white group-hover:text-purple-300 transition-colors duration-300">
                                {{ $event->title }}
                            </h3>

                            <!-- Organizer -->
                            <div class="flex items-center text-gray-400">
                                <i class="fas fa-user-circle text-gray-500 mr-2"></i>
                                <span class="text-sm">Organized by {{ $event->user->name }}</span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-300 text-sm leading-relaxed line-clamp-3">
                                {{ Str::limit($event->description ?: 'Join us for an amazing event that will inspire and connect you with like-minded individuals.', 120) }}
                            </p>

                            <!-- Date & Time -->
                            <div class="space-y-3">
                                <div class="flex items-center text-gray-400">
                                    <i class="fas fa-calendar text-blue-400 mr-3 w-4"></i>
                                    <div>
                                        <p class="text-sm font-medium text-white">
                                            {{ $event->start_date->format('M j, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $event->start_date->format('g:i A') }} -
                                            {{ $event->end_date->format('g:i A') }}</p>
                                    </div>
                                </div>

                                @if ($event->location)
                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-map-marker-alt text-red-400 mr-3 w-4"></i>
                                        <p class="text-sm text-gray-300">{{ $event->location }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Attendees Count -->
                            <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                <div class="flex items-center text-gray-400">
                                    <i class="fas fa-users text-purple-400 mr-2"></i>
                                    <span class="text-sm">{{ $event->total_attendees }} attendees</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $event->start_date->diffForHumans() }}
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-4">
                                <a href="{{ route('events.show', $event) }}"
                                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25 flex items-center justify-center group">
                                    <span>View Details & Register</span>
                                    <i
                                        class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-16">
                <div class="bg-white/5 backdrop-blur-lg rounded-2xl p-8 border border-white/20">
                    <h2 class="text-3xl font-bold text-white mb-4">Don't Miss Out!</h2>
                    <p class="text-gray-300 mb-6 max-w-2xl mx-auto">
                        Stay updated with the latest events and never miss an opportunity to connect, learn, and grow.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-bell mr-2"></i>
                            Get Notifications
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 border border-white/20">
                            <i class="fas fa-share mr-2"></i>
                            Share Events
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- No Events State -->
            <div class="text-center py-16">
                <div class="bg-white/5 backdrop-blur-lg rounded-2xl p-12 border border-white/20 max-w-2xl mx-auto">
                    <i class="fas fa-calendar-times text-6xl text-gray-500 mb-6"></i>
                    <h2 class="text-3xl font-bold text-white mb-4">No Active Events</h2>
                    <p class="text-gray-300 mb-8">
                        We're currently preparing some amazing events for you. Check back soon for exciting
                        opportunities!
                    </p>
                    <button
                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300">
                        <i class="fas fa-bell mr-2"></i>
                        Notify Me
                    </button>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-black/20 backdrop-blur-lg border-t border-white/10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-white mb-4">Events Platform</h3>
                <p class="text-gray-400 mb-6">Connecting people through amazing experiences</p>
                <div class="flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                </div>
                <p class="text-gray-500 text-sm mt-6">© 2024 Events Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom Styles -->
    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
        }
    </style>
</body>

</html>
