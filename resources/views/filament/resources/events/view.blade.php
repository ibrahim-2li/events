<x-filament-panels::page>
    <div class="flex justify-center py-8">
        <div class="w-full max-w-2xl">
            <div class="bg-gray-900 rounded-xl shadow-lg p-8 relative">
                <!-- Active Badge -->
                <div class="absolute top-6 right-6">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $record->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $record->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-white mb-4 pr-20">
                    {{ $record->title }}
                </h1>

                <!-- Organized by -->
                <div class="flex items-center text-gray-400 mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">Organized by {{ $record->user->name }}</span>
                </div>

                <!-- Description -->
                <div class="text-gray-300 mb-8 leading-relaxed">
                    {{ $record->description ?: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.' }}
                </div>

                <!-- Date and Location Info -->
                <div class="space-y-6">
                    <!-- Date Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-white text-sm mb-1">Start Date</h3>
                                <p class="text-gray-400 text-sm">{{ $record->start_date->format('M j, Y') }}</p>
                                <p class="text-gray-500 text-xs">{{ $record->start_date->format('g:i A') }}</p>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-white text-sm mb-1">End Date</h3>
                                <p class="text-gray-400 text-sm">{{ $record->end_date->format('M j, Y') }}</p>
                                <p class="text-gray-500 text-xs">{{ $record->end_date->format('g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    @if ($record->location)
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-white text-sm mb-1">Location</h3>
                                <p class="text-gray-400 text-sm">{{ $record->location }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- QR Token Info (for admin/debug purposes) -->
                @if (auth()->user() && auth()->user()->is_admin)
                    <div class="mt-8 pt-6 border-t border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                <span class="text-xs text-gray-500">QR Token:</span>
                                <code
                                    class="text-xs text-gray-400 bg-gray-800 px-2 py-1 rounded">{{ $record->qr_token }}</code>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
