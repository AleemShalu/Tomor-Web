<x-app-admin-layout>
    <div class="rounded-t bg-white mb-0 px-6 py-6">
        <div class="font-bold text-xl">
            <i class="fa-solid fa-shop mr-2"></i>
            {{__('admin.store_management.title')}}
        </div>
        <div class="pt-3">
            {{__('admin.store_management.description')}}
        </div>
        <div class="bg-gray-100 rounded-l-md">

            <div>
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded relative" role="alert">
                        <strong class="font-bold">Success:</strong> {{ session('success') }}
                    </div>
                @endif

                <div class="w-full overflow-x-auto p-5 rounded mt-5 ">
                    <!-- Add the "Create New Store" button here -->
                    <div class="py-2 flex justify-between">
                        <a href="{{ route('admin.store.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('admin.store_management.create_new_store') }}
                        </a>
                        <div class="flex">
                            <input type="text" id="searchField" placeholder="{{ __('admin.common.search') }}" class="p-2 rounded-l border-t mr-0 border-b border-l text-gray-800 border-gray-200 bg-white">
                            <button onclick="onFilterTextBoxChanged()" class="px-5 rounded-r bg-blue-500 text-white border border-l-0 border-t border-b border-blue-500 hover:bg-blue-700">
                                {{ __('admin.common.search') }}
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300 text-center">
                            <thead>
                            <tr>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.no') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.store_name') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.email') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.owner_name') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.commercial_name') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.short_name') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.contact_no') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.tax_id') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.status') }}</th>
                                <th class="px-4 py-2 bg-gray-200">{{ __('admin.store_management.column_headers.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="storeTableBody">
                            @forelse ($stores as $store)
                                <tr>
                                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="border px-4 py-2">{{ $store['commercial_name_en'] }}</td>
                                    <td class="border px-4 py-2">{{ $store['email'] }}</td>
                                    <td class="border px-4 py-2">{{ $store['owner']['name'] ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">{{ $store['commercial_name_en'] }}</td>
                                    <td class="border px-4 py-2">{{ $store['short_name_en'] }}</td>
                                    <td class="border px-4 py-2">{{ $store['contact_no'] }}</td>
                                    <td class="border px-4 py-2">{{ $store['tax_id_number'] }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($store['status'] === 1)
                                            <i class="fas fa-check text-green-500"></i>
                                        @else
                                            <i class="fas fa-times text-red-500"></i>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <div class="space-x-2">
                                            <a href="{{ route('admin.store.show', ['id' => $store['id']]) }}" class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-md">{{ __('admin.common.view') }}</a>
                                            <a href="{{ route('admin.store.edit', ['id' => $store['id']]) }}" class="px-3 py-1 text-sm font-medium text-green-600 bg-green-100 rounded-md">{{ __('admin.common.edit') }}</a>
                                            @if ($store['status'] === 1)
                                                <button onclick="updateStoreStatus({{ $store['id'] }}, 0)" class="px-3 py-1 text-sm font-medium text-red-600 bg-red-100 rounded-md">{{ __('admin.common.disabled') }}</button>
                                            @else
                                                <button onclick="updateStoreStatus({{ $store['id'] }}, 1)" class="px-3 py-1 text-sm font-medium text-green-600 bg-green-100 rounded-md">{{ __('admin.common.activation') }}</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="border px-4 py-2 text-center">{{ __('No stores found.') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div id="emptyMessage" class="text-center py-4" style="display: none;">
                            {{ __('No stores found.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-admin-layout>

<script>
    // Search functionality
    function onFilterTextBoxChanged() {
        const searchText = document.getElementById('searchField').value.toLowerCase();
        const rows = document.querySelectorAll('#storeTableBody tr');
        let visibleRows = 0;

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const matches = Array.from(cells).some(cell => cell.innerText.toLowerCase().includes(searchText));
            row.style.display = matches ? '' : 'none';
            if (matches) visibleRows++;
        });

        document.getElementById('emptyMessage').style.display = visibleRows > 0 ? 'none' : '';
    }

    function updateStoreStatus(id, status) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/store/update_status/${id}`;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;

        form.appendChild(csrfInput);
        form.appendChild(statusInput);

        document.body.appendChild(form);
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Show or hide the "No stores found" message based on table content
        const rows = document.querySelectorAll('#storeTableBody tr');
        const emptyMessage = document.getElementById('emptyMessage');
        emptyMessage.style.display = rows.length === 0 ? '' : 'none';
    });
</script>
