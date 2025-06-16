@php use Carbon\Carbon; @endphp
<x-app-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

        <!-- Add New Usher Button -->
        <div class="flex justify-end">
            <a href="{{ route('admin.usher.create') }}"
               class="flex items-center bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded">
               <span class="mr-2">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                       <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4"></path>
                   </svg>
               </span>
                {{__('admin.user_management.usher_list.add_new_usher')}}
            </a>
        </div>

        <!-- Ushers Section -->
        <section class="bg-white p-6 shadow rounded-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">
                    {{__('admin.user_management.usher_list.usher')}}
                </h3>
                <span class="text-blue-600 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{__('admin.user_management.usher_list.total')}}: {{ count($ushers) }}
                </span>
            </div>
            <!-- Search Box -->
            <div class="mb-4">
                <input type="text" oninput="onSearchTextChange('usherTable', this.value)" placeholder="Search..."
                       id="usherSearchInput"
                       class="border p-2 w-full">
            </div>
            <div id="usherTableContainer">
                <table id="usherTable" class="min-w-full bg-white border border-gray-300 text-center">
                    <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_name') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_email') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_contact_no') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.usher_code') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_status') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($ushers as $usher)
                        <tr>
                            <td class="border px-4 py-2">{{ $usher['name'] }}</td>
                            <td class="border px-4 py-2">{{ $usher['email'] }}</td>
                            <td class="border px-4 py-2">{{ $usher['phone_number'] }}</td>
                            <td class="border px-4 py-2">{{ $usher['code_usher'] }}</td>
                            <td class="border px-4 py-2">
                                @if ($usher['states'] === 1)
                                    <i data-lucide="check" style="color: green"></i>
                                @else
                                    <i data-lucide="x" style="color: red"></i>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                <a href="/admin/user/ushers/{{ $usher['id'] }}/edit"
                                   class="inline-block hover:text-blue-600 mr-2" title="Edit">
                                    <i data-lucide="pencil"></i>
                                </a>
                                <a href="/admin/user/ushers/{{ $usher['id'] }}"
                                   class="inline-block hover:text-green-600 mr-2" title="View">
                                    <i data-lucide="eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border px-4 py-2 text-center">{{ __('No ushers found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Store/Owners Section -->
        <section class="bg-white p-6 shadow rounded-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">
                    {{__('admin.user_management.usher_list.owners_who_deal_usher')}}
                </h3>
                <span class="text-blue-600 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{__('admin.user_management.usher_list.total')}}: {{ count($storeOwners) }}
                </span>
            </div>
            <!-- Search Box -->
            <div class="mb-4">
                <input type="text" oninput="onSearchTextChange('ownerTable', this.value)" placeholder="Search..."
                       id="ownerSearchInput"
                       class="border p-2 w-full">
            </div>
            <div id="ownerTableContainer">
                <table id="ownerTable" class="min-w-full bg-white border border-gray-300 text-center">
                    <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_id') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_name') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_email') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_contact_no') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_status') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_created_at') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($storeOwners as $owner)
                        <tr>
                            <td class="border px-4 py-2">{{ $owner['id'] }}</td>
                            <td class="border px-4 py-2">{{ $owner['name'] }}</td>
                            <td class="border px-4 py-2">{{ $owner['email'] }}</td>
                            <td class="border px-4 py-2">{{ $owner['dial_code'] ? $owner['dial_code'] . ' ' . $owner['contact_no'] : $owner['contact_no'] }}</td>
                            <td class="border px-4 py-2">
                                @if ($owner['status'] === 1)
                                    <i data-lucide="check" style="color: green"></i>
                                @else
                                    <i data-lucide="x" style="color: red"></i>
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ Carbon::parse($owner['created_at'])->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border px-4 py-2 text-center">{{ __('No store owners found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        // Search functionality for tables
        function onSearchTextChange(tableId, searchText) {
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll('tbody tr');
            const lowerCaseSearchText = searchText.toLowerCase();

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell => cell.innerText.toLowerCase().includes(lowerCaseSearchText));
                row.style.display = match ? '' : 'none';
            });

            // Show a message if no results are found
            const noResultsMessage = table.querySelector('tbody tr.no-results');
            if (Array.from(rows).every(row => row.style.display === 'none')) {
                if (!noResultsMessage) {
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.classList.add('no-results');
                    noResultsRow.innerHTML = `<td colspan="${cells.length}" class="border px-4 py-2 text-center">No results found.</td>`;
                    table.querySelector('tbody').appendChild(noResultsRow);
                }
            } else {
                if (noResultsMessage) {
                    noResultsMessage.remove();
                }
            }
        }
    </script>
</x-app-admin-layout>
