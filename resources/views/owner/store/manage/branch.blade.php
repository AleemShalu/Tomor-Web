<x-app-layout>
    <div class="max-w-7xl mx-auto pt-6">
        <x-store-main :store-id="$store->id">
            <x-slot name="main">
                <div class=" w-full max-w-6xl mx-auto bg-white rounded">
                    <div>
                        <div class="p-4 w-full max-w-6xl mx-auto">
                            <a href="{{ route('branch.create',$store->id ) }}"
                               class="button bg-blue-color-1-dark text-white p-3 rounded font-light">
                                {{__('locale.store_branch_manage.create_new_branch')}}
                            </a>
                        </div>
                        <!-- Add your DataTables.js integration code here -->
                        <div id="agGrid" class="ag-theme-alpine items-center p-4" style=" height: 500px;"></div>
                    </div>

                </div>
            </x-slot>

        </x-store-main>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the JSON data from Blade variable
        var branchData = @json($branches);

        // Define the ag-Grid columns
        var columnDefs = [
            {
                headerName: "{{__('locale.store_branch_manage.table.no')}}",
                field: "id",
                width: 100,
                maxWidth: 150,
                sortable: true,
                resizable: true,
                flex: 1
            },
            {
                headerName: "{{__('locale.store_branch_manage.table.name')}}",
                field: "name_ar",
                resizable: true,
                width: 220,
                sortable: true,
                flex: 1
            },
            {
                headerName: "{{__('locale.store_branch_manage.table.city')}}",
                field: <?php echo app()->getLocale() === 'ar' ? '"city.ar_name"' : '"city.en_name"' ?>,
                resizable: true, maxWidth: 100, sortable: true, flex: 1
            },
            {
                headerName: "{{__('locale.commercial.commercial_registration_number')}}",
                field: "commercial_registration_no",
                resizable: true,
                maxWidth: 150,
                sortable: true,
                flex: 1
            },
            {
                headerName: "{{__('locale.store.branch_status')}}",
                field: "work_statuses.status",
                maxWidth: 120,
                cellRenderer: function (params) {
                    return params.data.work_statuses[0].status === "active"
                        ? '<p class="rounded-md bg-green-300 text-center">{{__('locale.store.active')}}</p>'
                        : '<p class="rounded-md bg-red-300 text-center">{{__('locale.store.not_active')}}</p>';
                },
                sortable: true,
                resizable: true,
                flex: 1
            },
            {
                headerName: "{{__('locale.store.store_working_hours')}}",
                field: "work_statuses",
                width: 250,
                cellRenderer: function (params) {
                    const startTime = formatTime(params.data.work_statuses[0].start_time);
                    const endTime = formatTime(params.data.work_statuses[0].end_time);
                    return `${startTime} - ${endTime}`;
                },
                sortable: true,
                resizable: true,
                flex: 1

            },

            {
                headerName: "{{__('locale.store_branch_manage.table.action')}}",
                width: 200,
                cellRenderer: function (params) {
                    var branchId = params.data.id;
                    var url = '{{ route("branch", ":branchId") }}';
                    url = url.replace(':branchId', branchId);
                    return '<a href="' + url + '" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm p-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">{{__('locale.store_branch_manage.button.branch_manage')}}</a>';
                },
                flex: 1
            },
        ];

        // Set up ag-Grid
        var gridOptions = {
            columnDefs: columnDefs,
            rowData: branchData,
            pagination: true,
            paginationPageSize: 10,
            // Enable quick filter for all columns
            enableFilter: true,
        };

        // Create the ag-Grid instance
        var gridDiv = document.querySelector('#agGrid');
        var grid = new agGrid.Grid(gridDiv, gridOptions);

        function formatTime(timeString) {
            const [hours, minutes] = timeString.split(':');
            const date = new Date(0, 0, 0, hours, minutes);
            const options = {hour: '2-digit', minute: '2-digit', hour12: true};
            return date.toLocaleString('en-US', options);
        }


        // Add an input field for searching
        var searchInput = document.querySelector('#searchInput');
        searchInput.addEventListener('input', function () {
            gridOptions.api.setQuickFilter(searchInput.value);
        });
    });
</script>
