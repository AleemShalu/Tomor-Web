<x-app-layout>
    <div class="max-w-7xl mx-auto pt-6">
        <x-store-main :store-id="$store->id">
            <x-slot name="main">
                <div class="w-full max-w-6xl mx-auto rounded">
                    <div>
                        <div class="w-full max-w-6xl mx-auto rounded mt-2">
                            <div>
                                <div class="flex justify-between items-center">
                                    <div class="mb-4">
                                        <input type="text" id="employeeSearch"
                                               class="border border-gray-400 rounded w-60 px-2 py-1 focus:outline-none"
                                               placeholder="{{ __('locale.store_manage_employees.search_employees') }}">
                                    </div>
                                    <a href="{{route('employee.create',[$store->id])}}">
                                        <button type="button"
                                                class="button bg-blue-color-1-dark text-white p-3 rounded font-light">
                                            {{ __('locale.store_manage_employees.create_new_employee') }}
                                        </button>
                                    </a>
                                </div>
                                <div id="agGrid" class="ag-theme-alpine" style="height: 500px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-store-main>
    </div>
</x-app-layout>
<!------------------------- JS ------------------------------->

<script src="{{asset('js/owner/store/store.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the JSON data from Blade variable
        var employeeData = @json($employees);

        // Define the ag-Grid columns
        var columnDefs = [
            {
                headerName: "#",
                width: 80,
                maxWidth: 85,
                sortable: true,
                flex: 1,
                cellRenderer: function (params) {
                    // Add 1 to the row index to start from 1
                    return params.node.rowIndex + 1;
                }
            },
            {
                headerName: "{{ __('locale.store_manage_employees.employee.name') }}",
                field: "name",
                resizable: true,
                width: 220,
                sortable: true,
                flex: 1
            },
            {
                headerName: "{{ __('locale.store_manage_employees.employee.position') }}",
                field: "employee_roles",
                resizable: true,
                width: 220,
                sortable: true,
                cellRenderer: function (params) {
                    return params.data.employee_roles.map(function (role) {
                        return role.name_{{ app()->getLocale() }}; // Use app()->getLocale() to get the current locale
                    }).join(', ');
                },
                flex: 1
            },
            {
                headerName: "{{ __('locale.store_manage_employees.employee.status') }}",
                field: "status",
                width: 150,
                cellRenderer: function (params) {
                    var statusValue = params.value;
                    var isOnline = statusValue === 1;
                    var statusText = isOnline ? '{{ __('locale.store_manage_employees.employee.active') }}' : '{{ __('locale.store_manage_employees.employee.not_active') }}';
                    var statusColor = isOnline ? 'bg-green-500' : 'bg-red-800';
                    return '<div class="flex items-center">' +
                        '<div class="h-2.5 w-2.5 rounded-full ' + statusColor + ' mr-2"></div>' +
                        statusText +
                        '</div>';
                },
                flex: 1
            },
            {
                headerName: "{{ __('locale.store_manage_employees.employee.created_at') }}",
                field: "created_at",
                width: 200,
                // flex: 1,
                cellRenderer: function (params) {
                    if (params.value) {
                        const date = new Date(params.value);
                        return date.toISOString().replace('T', ' ').replace(/\.\d+Z$/, '');
                    }
                    return '';
                }
            },
            {
                headerName: "{{ __('locale.store_manage_employees.employee.action') }}",
                width: 250,
                cellRenderer: function (params) {
                    var employeeId = params.data.id;
                    var storeId = @json($store->id); // Assuming you have $store available in the view

                    var deleteFormId = 'delete-form-' + employeeId;
                    var deleteAction = '{{ route("employee.delete", ":employeeId") }}';
                    deleteAction = deleteAction.replace(':employeeId', employeeId);

                    var editUrl = '{{ route("employee.edit", ["storeId" => $store->id, "employeeId" => ":employeeId"]) }}';
                    editUrl = editUrl.replace(':employeeId', employeeId);

                    return '<form id="' + deleteFormId + '" action="' + deleteAction + '" method="POST" style="display: inline;">' +
                        '@csrf' +
                        '@method("DELETE")' +
                        '<input type="hidden" name="store_id" value="' + storeId + '">' +
                        '</form>' +
                    '<a href="' + editUrl + '" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm p-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">{{__('locale.store_manage_employees.employee.edit')}}</a>';
                },
                flex: 1
            },
        ];

        var gridOptions = {
            columnDefs: columnDefs,
            rowData: employeeData,
            pagination: true,
            paginationPageSize: 10,
            enableFilter: true,
        };

        // Create the ag-Grid instance
        var gridDiv = document.querySelector('#agGrid');
        var grid = new agGrid.Grid(gridDiv, gridOptions);

        // Implement custom search
        var employeeSearchInput = document.querySelector('#employeeSearch');
        employeeSearchInput.addEventListener('input', function () {
            var searchValue = employeeSearchInput.value.toLowerCase();
            gridOptions.api.setQuickFilter(searchValue);
        });
    });
</script>
<!------------------------- CSS ------------------------------->


<!---------------------- Custom CSS --------------------------->




