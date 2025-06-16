<x-app-layout>
    <x-branch-header :branch="$branch"></x-branch-header>


    <div class="bg-white p-4 m-4 rounded-xl">
        <div>
            <div class="text-xl font-bold">{{ __('locale.branch_employees.title') }}</div>
            <div class="text-gray-500">
                {{ __('locale.branch_employees.description') }}
            </div>

        </div>
        <div class="mt-4">
            <div id="agGrid" style="height: 500px; width: 100%;" class="ag-theme-alpine"></div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the JSON data from Blade variable
        var employeeData = @json($employees);

        // Define the ag-Grid columns
        var columnDefs = [
            {headerName: "ID", field: "id", width: 80, maxWidth: 150, sortable: true, flex: 1},
            {headerName: "Full Name", field: "name", width: 150, sortable: true, flex: 1},
            {
                headerName: "States",
                field: "is_working_now",
                width: 150,
                sortable: true,
                flex: 1,
                cellRenderer: params => {
                    return params.value ? 'Active' : 'Inactive';
                },
                cellStyle: params => {
                    return {
                        color: params.value ? 'green' : 'red',
                        fontWeight: params.value ? 'bold' : 'normal'
                    };
                }
            },

            {
                headerName: "Positions",
                field: "employee_roles",
                width: 150,
                sortable: true,
                flex: 1,
                cellRenderer: function (params) {
                    if (Array.isArray(params.value) && params.value.length > 0) {
                        return params.value[0].name_en;
                    }
                    return '';
                },
            },
            {
                headerName: "Count Orders",
                field: "count_orders",
                width: 150,
                sortable: true,
                flex: 1
            },
            {headerName: "Work Time / W", field: "total_hours_this_week", width: 150, sortable: true, flex: 1},
            {
                headerName: "Manage",
                width: 200,
                flex: 1,
                cellRenderer: function (params) {
                    var employeeId = params.data.id;

                    var storeId = @json($id);

                    var editUrl = '{{ route("employee.edit", ["storeId" => ":storeId", "employeeId" => ":employeeId"]) }}';
                    editUrl = editUrl.replace(':storeId', storeId).replace(':employeeId', employeeId);

                    var printUrl = '{{ route("export.employee", ["storeId" => ":storeId", "userId" => ":employeeId"]) }}';
                    printUrl = printUrl.replace(':storeId', storeId).replace(':employeeId', employeeId);

                    var previewUrl = '{{ route("employee.preview", ["storeId" => ":storeId", "employeeId" => ":employeeId"]) }}';
                    previewUrl = previewUrl.replace(':storeId', storeId).replace(':employeeId', employeeId);

                    return '<a href="' + editUrl + '" class="text-blue-500 hover:text-blue-600 mr-2"><i class="fas fa-edit"></i></a>' +
                        '<a href="' + printUrl + '" class="text-black hover:text-gray-600 mr-2"><i class="fas fa-print"></i></a>' +
                        '<a href="' + previewUrl + '" class="text-green-500 hover:text-green-600"><i class="fas fa-eye"></i></a>';
                },
            },
        ];

        // Set up ag-Grid
        var gridOptions = {
            columnDefs: columnDefs,
            rowData: employeeData,
            pagination: true,
            paginationPageSize: 10,
            enableFilter: true, // Enable quick filter for all columns
        };

        // Create the ag-Grid instance
        var gridDiv = document.querySelector('#agGrid');
        new agGrid.Grid(gridDiv, gridOptions);
    });
</script>
