<x-app-layout>
    <div class="w-full max-w-8xl mx-auto  px-4 sm:px-6 lg:px-8 py-8 w-full mx-auto">
        <!-- Top Header -->
        <div class="flex justify-between items-center bg-white px-2 py-3 rounded border border-gray-200"
             dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
            <div class="flex items-center">
                <i data-lucide="store" class="mt-1 mx-2"></i>
                <h1 class="text-2xl font-bold">{{ __('locale.store.my_stores') }}</h1>
            </div>
            <div class="flex items-center">
                <a href="{{ route('store.create') }}"
                   class="button bg-blue-color-1 text-white p-2 rounded hover:bg-blue-color-1-light mx-2">
                    <i data-lucide="house-plus" class="inline"></i>

                    {{ __('locale.store.create_new_store') }}
                </a>
                @if ($stores->count() != 0)
                    <!-- Export Excel Button -->
                    <form action="{{ route('store.export-excel') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ auth()->user()->id }}">
                        <button type="submit"
                                class="button bg-green-500 text-white p-2 rounded hover:bg-green-600 mx-2">
                            <i data-lucide="sheet" class="inline"></i>
                            {{ __('locale.common.export_excel') }}
                        </button>
                    </form>
                    <!-- Export PDF Button -->
                    <form action="{{ route('store.export-pdf') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ auth()->user()->id }}">
                        <button type="submit" class="button bg-red-500 text-white p-2 rounded hover:bg-red-600 mx-2">
                            <i data-lucide="file-text" class="inline"></i>
                            {{ __('locale.common.export_pdf') }}
                        </button>
                    </form>
                @endif

            </div>
        </div>


        <div class="rounded bg-white mt-6 border border-gray-200">
            <!-- Table -->
            @if ($stores->count() == 0)
                <div class="container mx-auto px-4 py-8 text-center">
                    <p class="text-lg font-bold mb-6">{{ __('locale.store.no_stores_found') }}</p>
                </div>
            @else
                <div class="container mx-auto py-4 px-4">
                    <div class="mb-4 flex items-center">
                        <!-- Search input -->
                        <div class="flex-grow">
                            <input type="text" placeholder="Search..." id="searchInput"
                                   class="border border-gray-400 rounded w-full py-2">
                        </div>
                    </div>

                    <!-- Display warning if any of the stores has a status of 0 -->
                    @if ($stores->contains('status', 0))
                        <p class="flex items-center bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md shadow-md">
                            <span class="mr-2">⚠️</span>
                            <span>{{ __('locale.store_activation_warning') }}</span>
                        </p>
                    @endif

                    <!-- Blade Table -->
                    <table class="min-w-full bg-white mt-4 border border-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200">{{ __('#') }}</th>
                            <th class="py-2 px-4 border-b border-gray-200">{{ __('locale.store.commercial_name_en') }}</th>
                            <th class="py-2 px-4 border-b border-gray-200">{{ __('locale.store.business_type') }}</th>
                            <th class="py-2 px-4 border-b border-gray-200">{{ __('locale.commercial.commercial_registration_number') }}</th>
                            <th class="py-2 px-4 border-b border-gray-200">{{ __('locale.store.state') }}</th>
                            <th class="py-2 px-4 border-b border-gray-200">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($stores as $store)
                            <tr class="text-center">
                                <td class="py-2 px-4 border-b border-gray-200">{{ $store->id }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $store->commercial_name_en }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $store->business_type->{"name_" . (app()->getLocale() === 'ar' ? 'ar' : 'en')} }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $store->commercial_registration_no }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 text-center">
                                    <span class="inline-block px-2 py-1 rounded-md font-semibold {{ $store->status == 0 ? 'bg-red-300 text-red-800' : 'bg-green-300 text-green-800' }}">
                                        {{ $store->status == 0 ? __('locale.store.not_active') : __('locale.store.active') }}
                                    </span>
                                </td>

                                <td class="py-2 px-4 border-b border-gray-200 text-center">
                                    <a href="{{ route('store.manage', $store->id) }}"
                                       class="inline-block text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 font-medium rounded-md text-sm px-4 py-2 transition-all duration-150 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                        {{ __('locale.store.manage_store') }}
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    {{--                    <div class="mt-4">--}}
                    {{--                        {{ $stores->links() }}--}}
                    {{--                    </div>--}}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
    // JavaScript to handle search input filtering
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('#searchInput');
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
</script>
