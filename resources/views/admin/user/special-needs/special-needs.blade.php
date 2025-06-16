<!-- resources/views/dashboard.blade.php -->

<x-app-admin-layout>
    <div class="px-4 sm:px-6 lg:px-8 w-full max-w-9xl mx-auto my-4">
        <div class="relative border border-gray-200 bg-white p-4 sm:p-6 rounded-sm overflow-hidden flex justify-start gap-3">
            <i data-lucide="accessibility"></i>
            <div class="text-xl font-bold font-inter">
                {{ __('admin.user_management.special_needs.title') }}
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 w-full max-w-9xl mx-auto">
        <div class="relative bg-white p-4 sm:p-6 rounded-sm overflow-hidden mb-8  border border-gray-200">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded relative"
                     role="alert">
                    <strong class="font-bold">Success:</strong> {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <input
                        type="text"
                        id="searchInput"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search..."
                        oninput="onSearchTextChange()"
                />
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 text-center">
                    <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_id') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_name') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_email') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_contact_no') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_special_needs_type') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.special_needs_qualified') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('admin.user_management.users.user_status') }}</th>
                        <th class="px-4 py-2 bg-gray-200">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody id="userTableBody">
                    @forelse ($users as $user)
                        <tr>
                            <td class="border px-4 py-2">{{ $user['id'] }}</td>
                            <td class="border px-4 py-2">{{ $user['name'] }}</td>
                            <td class="border px-4 py-2">{{ $user['email'] }}</td>
                            <td class="border px-4 py-2">{{ '+' . $user['dial_code'] . ' ' . $user['contact_no'] }}</td>
                            <td class="border px-4 py-2">
                                @if (isset($user['customer_with_special_needs']['special_needs_type']))
                                    @if (app()->getLocale() === 'ar')
                                        {{ $user['customer_with_special_needs']['special_needs_type']['disability_type_ar'] }}
                                    @else
                                        {{ $user['customer_with_special_needs']['special_needs_type']['disability_type_en'] }}
                                    @endif
                                @else
                                    {{ __('N/A') }}
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                {{ $user['customer_with_special_needs']['special_needs_qualified'] ?? 'N/A' }}
                            </td>
                            <td class="border px-4 py-2">{{ $user['customer_with_special_needs']['special_needs_status'] ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">
                                <div class="space-x-2">
                                    <button class="px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-md"
                                            onclick="viewUserSpecialNeeds({{ $user['id'] }})">{{ __('admin.user_management.special_needs.view') }}</button>
                                    <button class="px-3 py-1 text-sm font-medium text-green-600 bg-green-100 rounded-md"
                                            onclick="editUserSpecialNeeds({{ $user['id'] }})">{{ __('admin.user_management.special_needs.edit') }}</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border px-4 py-2 text-center">{{ __('No users found.') }}</td>
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
    // Action handlers
    function viewUserSpecialNeeds(userId) {
        const url = "{{ route('admin.users.special-needs.view', ['id' => ':userId']) }}".replace(':userId', userId);
        window.location.href = url;
    }

    function editUserSpecialNeeds(userId) {
        const url = "{{ route('admin.users.special-needs.edit', ['id' => ':userId']) }}".replace(':userId', userId);
        window.location.href = url;
    }

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

    // Initial check for empty table
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('#userTableBody tr');
        document.getElementById('emptyMessage').style.display = rows.length > 0 ? 'none' : '';
    });
</script>
