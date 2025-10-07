<x-filament-panels::page>
    {{-- Scanner Button Section --}}
    <div class="mb-6">
        <div class="fi-sc-component">

            <a href="{{ route('filament.dashboard.pages.q-r-scanner') }}"
                class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors duration-200 shadow-md hover:shadow-lg">
                <x-filament::card>
                    <h3 class="text-xl font-bold mb-2">Open Scanner</h3>
                    <svg height="65" width="65" class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>

                </x-filament::card>
            </a>




        </div>
    </div>

    {{-- Widgets will be rendered here by Filament --}}
    @if ($this->getHeaderWidgets())
        <x-filament-widgets::widgets :widgets="$this->getHeaderWidgets()" :columns="2" />
    @endif

    @if ($this->getFooterWidgets())
        <x-filament-widgets::widgets :widgets="$this->getFooterWidgets()" :columns="2" />
    @endif
</x-filament-panels::page>
