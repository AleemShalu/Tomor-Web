<!-- resources/views/admin/store/view.blade.php -->

<x-app-admin-layout>
    <div class="rounded-t bg-white mb-0 px-6 py-6 ">
        <div class="flex mt-4">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.store.index') }}" class="text-blue-500 underline">
                    {{ __('admin.common.back') }}
                </a>
            </div>
        </div>
        <div class="font-bold text-xl">
            {{__('admin.store_management.store_details')}}
        </div>
        <div class="pt-3">
            <div class="pt-3">
                <!-- Introduction -->
                <!-- Store details -->
                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h2 class="text-lg font-bold mb-4">
                        <i class="fas fa-store mr-2"></i>
                        {{__('admin.store_management.store_info')}}
                    </h2>
                    <div class="px-6 py-4 bg-gray-200 my-4">
                        <p class="text-gray-700">
                            {{__('admin.store_management.store_details_description')}}
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.store_management.column_headers.no')}}
                            </p>
                            <p>{{ $store->id }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.store.business_type')}}
                            </p>
                            <p>{{ $businessType->name_en }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.store.commercial_name_en')}}
                            </p>
                            <p>{{ $store->commercial_name_en }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.store.commercial_name_ar')}}
                            </p>
                            <p>{{ $store->commercial_name_ar }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.store.short_name_en')}}
                            </p>
                            <p>{{ $store->short_name_en }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.store.short_name_ar')}}
                            </p>
                            <p>{{ $store->short_name_ar }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.tax.tax_id_number')}}
                            </p>
                            <p>{{ $store->tax_id_number }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.commercial.municipal_license_number')}}
                            </p>
                            <p>{{ $store->municipal_license_no }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.store.website')}}
                            </p>
                            <p>{{ $store->website ?: 'Not provided' }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600 pb-2">
                                {{__('locale.store.store_logo')}}
                            </p>
                            <img width="30%" src="{{'/storage/'.$store->logo}}">
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class=" font-semibold text-gray-600">
                                {{__('locale.store.store_status')}}

                                @if ($store->status == 1)
                                    <span class="my-4 px-2 py-1 rounded bg-green-500 text-white">
                                        {{__('locale.store.active')}}
                                    </span>
                                @else
                                    <span class="my-4 px-2 py-1 rounded bg-red-500 text-white">
                                        {{__('locale.store.not_active')}}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('locale.store.created_at')}}
                            </p>
                            <p class="pb-2">{{ $store->created_at }}</p>
                            <hr>
                            <p class="font-semibold text-gray-600 pt-2">
                                {{__('locale.store.updated_at')}}
                            </p>
                            <p class="">{{ $store->updated_at }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h2 class="text-lg font-bold mb-4">
                        <i class="fas fa-user mr-2"></i>
                        {{__('admin.user_management.users.user_details')}}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.user_management.users.user_name')}}
                            </p>
                            <p>{{ $store->owner->name }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.user_management.users.user_email')}}
                            </p>
                            <p>{{ $store->owner->email }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.user_management.users.user_email_verified_at')}}
                            </p>
                            <p>{{ $store->owner->email_verified_at ?: 'Not verified' }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.user_management.users.user_contact_no')}}
                            </p>
                            <p dir="ltr">+{{ $store->owner->dial_code }} {{ $store->owner->contact_no }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.user_management.users.user_contact_no_verified_at')}}
                            </p>
                            <p>{{ $store->owner->phone_verified_at ?: 'Not verified' }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.user_management.users.user_created_at')}}
                            </p>
                            <p>{{ $store->owner->created_at }}</p>
                        </div>
                        <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                            <p class="font-semibold text-gray-600">
                                {{__('admin.user_management.users.user_last_seen')}}
                            </p>
                            <p>{{ $store->owner->last_seen }}</p>
                        </div>
                    </div>
                </div>

                <!-- Branch details -->
                <!-- Attachments -->
                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h2 class="text-lg font-bold mb-4">
                        <i class="fas fa-code-branch mr-2"></i>
                        {{__('admin.store_management.branch.info')}}
                    </h2>
                    <div class="py-2 flex justify-between">
                        <input type="text" id="searchInput" placeholder="Search for a branch..."
                               class="p-2 border rounded border-gray-400">
                    </div>
                    @if(count($store->branches) > 0)
                        <div class="text-center"> <!-- Wrap the table inside a div with "text-center" class -->
                            <table class="w-full bg-white border-collapse table-auto mb-4 rounded-lg shadow-md">
                                <thead>
                                <tr class="bg-blue-color-1-lighter">
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.branch.id')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.branch.name')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.branch.email')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.branch.contact_number')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.branch.default_branch')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.branch.created_at')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.branch.updated_at')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">-</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($store->branches as $branch)
                                    <tr class="hover:bg-gray-200">
                                        <td class="border-b-2 pb-2 p-3">{{ $branch->id }}</td>
                                        <td class="border-b-2 pb-2 p-3">{{ $branch->name }}</td>
                                        <td class="border-b-2 pb-2 p-3">{{ $branch->email }}</td>
                                        <td class="border-b-2 pb-2 p-3">{{ $branch->dial_code }} {{ $branch->contact_no }}</td>
                                        <td class="border-b-2 pb-2 p-3">{{ $branch->default_branch ? 'Yes' : 'No' }}</td>

                                        <td class="border-b-2 pb-2 p-3">{{ $branch->created_at }}</td>
                                        <td class="border-b-2 pb-2 p-3">{{ $branch->updated_at }}</td>
                                        <td class="border-b-2 pb-2 p-3">
                                            <a class="text-blue-600"
                                               href="{{route('admin.branch.show',['storeId'=>$branch->store_id,'branchId'=>$branch->id])}}">
                                                {{__('admin.store_management.branch.details')}}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mt-3">
                            {{__('admin.store_management.branch.no_branches_available')}}
                        </p>
                    @endif
                </div>

                <!-- Attachments -->
                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h2 class="text-lg font-bold mb-4">
                        <i class="fas fa-code-branch mr-2"></i>
                        {{__('admin.store_management.attachments.title')}}
                    </h2>
                    <!-- TAX ID Attachment -->
                    <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                        <p class="font-semibold text-gray-600">
                            {{__('admin.store_management.attachments.tax_id_attachment')}}
                        </p>
                        <a class="text-blue-500 font-bold" href="{{ asset('/storage/'.$store->tax_id_attachment) }}">Here</a>
                    </div>

                    <!-- Commercial Registration Attachment -->
                    <div class="border-b-2 pb-2 bg-white p-3 hover:bg-gray-200">
                        <p class="font-semibold text-gray-600">
                            {{__('locale.branch.commercial_registration_number')}}
                        </p>
                        <a class="text-blue-500 font-bold"
                           href="{{ asset('/storage/'.$store->commercial_registration_attachment) }}">Here</a>
                    </div>

                </div>

                <!-- Bank Accounts -->
                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h2 class="text-lg font-bold mb-4">
                        <i class="fas fa-code-branch mr-2"></i>
                        {{__('admin.store_management.branch.bank_accounts_for_store')}}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-4">
                        @foreach($store->bank_accounts as $bankAccount)
                            <div class="col-span-full bg-white p-3 mb-4 border rounded-lg shadow-md hover:shadow-lg">
                                <div class="mb-2">
                                    <p class="font-semibold">Account #{{ $loop->iteration }}</p>
                                </div>
                                <div class="border-b-2 pb-2 mb-2">
                                    <p class="font-semibold">
                                        {{__('locale.bank.account_holder_name')}}
                                    </p>
                                    <p>{{ $bankAccount->account_holder_name }}</p>
                                </div>
                                <div class="border-b-2 pb-2 mb-2">
                                    <p class="font-semibold">
                                        {{__('locale.bank.iban_number')}}
                                    </p>
                                    <p>{{ $bankAccount->iban_number }}</p>
                                </div>
                                <div class="border-b-2 pb-2 mb-2">
                                    <p class="font-semibold">
                                        {{__('locale.bank.name_bank')}}
                                    </p>
                                    <p>{{ $bankAccount->bank_name ?: 'Not provided' }}</p>
                                </div>
                                <div class="border-b-2 pb-2 mb-2">
                                    <p class="font-semibold">
                                        {{__('admin.store_management.branch.created_at')}}
                                    </p>
                                    <p>{{ $bankAccount->created_at }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        {{__('admin.store_management.branch.updated_at')}}
                                    </p>
                                    <p>{{ $bankAccount->updated_at }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Employees -->
                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h2 class="text-lg font-bold mb-4">
                        <i class="fas fa-users mr-2"></i>
                        {{__('admin.store_management.employee.title')}}
                    </h2>
                    
                    @if($employees->count() > 0)
                        <div class=""> <!-- Wrap the table inside a div with "text-center" class -->
                            <div class="mb-4">
                                <input type="text" id="employeeSearchInput" placeholder="Search employees..."
                                       class="p-2 border rounded">
                            </div>

                            <table class="w-full bg-white border-collapse table-auto mb-4 rounded-lg shadow-md">
                                <thead>
                                <tr class="bg-blue-color-1-lighter">
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.employee.id')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.employee.name')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.employee.email')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.employee.contact_number')}}
                                    </th>
                                    <th class="p-3 font-semibold text-gray-600">
                                        {{__('admin.store_management.employee.employee_positions')}}
                                    </th>
                                    <!-- Add more table headers as needed -->
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($employees as $employee)
                                    <tr class="hover:bg-gray-200">
                                        <td class="border-b-2 pb-2 p-3 text-center">{{ $employee->id }}</td>
                                        <td class="border-b-2 pb-2 p-3 text-center">{{ $employee->name }}</td>
                                        <td class="border-b-2 pb-2 p-3 text-center">{{ $employee->email }}</td>
                                        <td class="border-b-2 pb-2 p-3 text-center">{{ $employee->dial_code }} {{ $employee->contact_no }}</td>
                                        <td class="border-b-2 pb-2 p-3 text-center">
                                            @foreach($employee->employee_roles as $role)
                                                {{ $role->name_en }} ({{ $role->name_ar }})<br>
                                            @endforeach
                                        </td>
                                        <!-- Add more table data cells as needed -->
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- Pagination links -->
                            {{ $employees->links() }}
                        </div>
                    @else
                        <p class="mt-3">
                            {{__('admin.store_management.employee.no_employees_available')}}
                        </p>
                    @endif
                </div>

                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h2 class="text-lg font-bold mb-4">
                        <i class="fas fa-users mr-2"></i>
                        {{__('admin.store_management.product.title')}}

                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @if(count($products) == 0)
                            <p class="my-4">
                                {{__('admin.store_management.product.no_products_available')}}
                            </p>
                        @else
                            <p>
                                {{__('admin.store_management.product.view_all_products')}}
                                <a class="text-blue-500"
                                   href="{{route('admin.product.index',['id'=>$store->id])}}">here</a>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Print button -->
                <div class="text-right">
                    <a href="{{route('export.store-details-pdf',['storeId'=> $store->id])}}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{__('locale.common.print')}}
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-admin-layout>


<script>
    function toggleBranchStatus(element, branchId) {
        const formData = new FormData();
        formData.append('branchId', branchId);

        fetch('/update-branch-status', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 1) {
                    element.querySelector('.status-span').innerText = 'Active';
                    element.querySelector('.status-span').classList.remove('bg-red-500');
                    element.querySelector('.status-span').classList.add('bg-green-500');
                } else {
                    element.querySelector('.status-span').innerText = 'Inactive';
                    element.querySelector('.status-span').classList.remove('bg-green-500');
                    element.querySelector('.status-span').classList.add('bg-red-500');
                }
            })
            .catch(error => {
                console.error('Error updating branch status:', error);
            });
    }

    document.getElementById('searchInput').addEventListener('keyup', function () {
        // Get the search query
        let query = this.value.toLowerCase();

        // Get all the table rows
        let rows = document.querySelectorAll('tbody tr');

        // Loop through the rows and hide those that don't match the query
        rows.forEach(row => {
            let cells = Array.from(row.children);
            if (cells.some(cell => cell.textContent.toLowerCase().includes(query))) {
                row.style.display = ''; // show
            } else {
                row.style.display = 'none'; // hide
            }
        });
    });

    document.getElementById('employeeSearchInput').addEventListener('keyup', function () {
        let query = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let cells = Array.from(row.children);
            if (cells.some(cell => cell.textContent.toLowerCase().includes(query))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<style>
    /* Add the hover effect for the status span */
    .status-span:hover {
        background-color: #48bb78; /* Change this color to the desired hover color (green) */
        cursor: pointer;
    }
</style>
