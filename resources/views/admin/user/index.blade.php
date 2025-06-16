<!-- resources/views/dashboard.blade.php -->

<x-app-admin-layout>
    @php
        $lang = app()->getLocale();
    @endphp

    <style>
        /* Add any additional styling here if necessary */
    </style>

    <div class="px-4 sm:px-6 lg:px-8 w-full max-w-9xl mx-auto my-4">
        <div class="relative bg-white p-4 sm:p-6 rounded-sm overflow-hidden  border border-gray-200 flex justify-start gap-3 font-bold text-base-blue">
            <i data-lucide="users"></i> {{__('admin.user_management.users_list.title')}}
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 w-full max-w-9xl mx-auto  grid lg:grid-cols-2 md:grid-cols-1 content-center items-center mb-4">
        <div>
            <label for="searchInput" class="sr-only">{{__('admin.user_management.users_list.search')}}</label>
            <div class="relative">
                <div class="absolute inset-y-0 {{$lang === 'en' ? 'left-0 pl-3' : 'right-0 pr-3'}} flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" id="searchInput"
                       class="block w-full p-4 {{$lang === 'en' ? 'pl-10' : 'pr-10'}} text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="{{__('admin.user_management.users_list.search')}}..."
                       oninput="onSearchTextChange()">
                <button onclick="onFilterTextBoxChanged()"
                        class="text-white absolute {{$lang === 'en' ? 'right-2.5' : 'left-2.5'}} bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">{{__('admin.user_management.users_list.search')}}</button>
            </div>
        </div>

        <div class="place-self-end flex">
            <a href="{{route('admin.users.register')}}"
               class="flex-none flex justify-center items-center gap-2 h-10 px-3 bg-base-blue m-2 rounded-md p-1 text-white">
                <i data-lucide="plus-square"
                   style="color: #ffffff;"></i> {{__('admin.user_management.users_list.new_user')}}
            </a>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8  w-full max-w-9xl mx-auto">
        <div class="relative bg-white p-4 sm:p-6  border border-gray-200 rounded-sm overflow-hidden mb-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 mb-4 pb-4 px-4 py-4 rounded relative"
                     role="alert">
                    <strong class="font-bold">Success:</strong> {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 text-center">
                    <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.number') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.name') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.email') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.phone') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.role') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.enroll_date') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.special_need') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.status') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users_list.action') }}</th>
                    </tr>
                    </thead>
                    <tbody id="userTableBody">
                    @forelse ($users as $user)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $user['name'] }}</td>
                            <td class="border px-4 py-2">{{ $user['email'] }}</td>
                            <td class="border px-4 py-2">
                                @if (empty($user['dial_code']) || empty($user['contact_no']))
                                    N/A
                                @else
                                    {{ '+' . $user['dial_code'] . ' ' . $user['contact_no'] }}
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $user['roles'][0]['name'] ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') }}</td>
                            <td class="border px-4 py-2">
                                @if ($user['customer_with_special_needs'])
                                    {{ __('admin.common.yes') }}
                                @else
                                    {{ __('admin.common.no') }}
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if ($user['status'] == 0)
                                    <a href="{{ route('admin.users.update_status', ['id' => $user['id'], 'status' => 1]) }}"
                                       class="text-white bg-red-500 p-2 rounded">
                                        {{ __('admin.common.inactive') }}
                                    </a>
                                @else
                                    <a href="{{ route('admin.users.update_status', ['id' => $user['id'], 'status' => 0]) }}"
                                       class="text-white bg-green-500 p-2 rounded">
                                        {{ __('admin.common.active') }}
                                    </a>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                <div class="space-x-2">
                                    <a href="{{ route('admin.user.view', ['id' => $user['id']]) }}"
                                       class="px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-md">
                                        {{ __('admin.common.view')}}
                                    </a>
                                    <a href="{{ route('admin.users.edit', ['id' => $user['id']]) }}"
                                       class="px-3 py-1 text-sm font-medium text-green-600 bg-green-100 rounded-md">
                                        {{ __('admin.common.edit')}}
                                    </a>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border px-4 py-2 text-center">{{ __('No users found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div id="emptyMessage" class="text-center py-4" style="display: none;">
                    {{ __('No users found.') }}
                </div>
            </div>
        </div>
    </div>
</x-app-admin-layout>

<script>
    // Search functionality
    function onSearchTextChange() {
        const searchText = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#userTableBody tr');
        let visibleRows = 0;

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const matches = Array.from(cells).some(cell => cell.innerText.toLowerCase().includes(searchText));
            row.style.display = matches ? '' : 'none';
            if (matches) visibleRows++;
        });

        document.getElementById('emptyMessage').style.display = visibleRows > 0 ? 'none' : '';
    }

    function onFilterTextBoxChanged() {
        onSearchTextChange();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('#userTableBody tr');
        const emptyMessage = document.getElementById('emptyMessage');
        if (rows.length === 0) {
            emptyMessage.style.display = '';
        } else {
            emptyMessage.style.display = 'none';
        }
    });

    function exportToPDF() {
        // Implement your PDF export logic here
    }

    function exportToExcel() {
        // Implement your Excel export logic here
    }
</script>
