<x-app-layout>
    <x-branch-header :branch="$branch"></x-branch-header>

    <div class="bg-white p-4 m-4 rounded-xl border border-gray-200">
        <div>
            <div>
                <div class="text-xl font-bold">{{ __('locale.branch_orders.title') }}</div>
                <div class="text-gray-500">{{ __('locale.branch_orders.description') }}</div>
            </div>
        </div>
        <div class="mt-4 flex flex-col md:flex-row">
            <div class="bg-gray-100 p-4 rounded-xl w-full h-1/3 md:w-1/3 md:mt-0 border border-gray-200">
                <div class="font-bold mb-2">{{ __('locale.branch_orders.filter_title') }}</div>
                <form action="{{ route('branch.orders', ['branchId' => $branch->id]) }}" method="GET">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('locale.branch_orders.order_number_label') }}</label>
                            <input type="text" name="order_number" id="order_number"
                                   class="mt-1 p-2 border rounded-md w-full" value="{{ old('order_number') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('locale.branch_orders.status_label') }}</label>
                            <select name="status" class="mt-1 p-2 border rounded-md w-full">
                                <option value="">{{ __('locale.status_order.all') }}</option>
                                <option value="delivered">{{ __('locale.status_order.delivered') }}</option>
                                <option value="finished">{{ __('locale.status_order.finished') }}</option>
                                <option value="canceled">{{ __('locale.status_order.canceled') }}</option>
                                <option value="unknown">{{ __('locale.status_order.unknown') }}</option>
                                <!-- Add more status options here -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('locale.branch_orders.branch_order_number_label') }}</label>
                            <input type="text" name="branch_order_number" id="branch_order_number"
                                   class="mt-1 p-2 border rounded-md w-full" value="{{ old('branch_order_number') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('locale.branch_orders.branch_queue_number_label') }}</label>
                            <input type="text" name="branch_queue_number" id="branch_queue_number"
                                   class="mt-1 p-2 border rounded-md w-full" value="{{ old('branch_queue_number') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('locale.branch_orders.start_date_label') }}</label>
                            <input type="date" name="start_date" id="start_date"
                                   class="mt-1 p-2 border rounded-md w-full" value="{{ old('start_date') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('locale.branch_orders.end_date_label') }}</label>
                            <input type="date" name="end_date" id="end_date" class="mt-1 p-2 border rounded-md w-full"
                                   value="{{ old('end_date') }}">
                        </div>
                    </div>
                    <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}">
                    <div class="mt-4">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                            {{ __('locale.branch_orders.apply_filters_button') }}
                        </button>
                    </div>
                </form>
            </div>
            <div name="table" class="px-4 w-full md:w-2/3">
                <div class="overflow-x-auto h-full border border-gray-200 p-2 bg-gray-100 rounded">
                    <div class="mb-4 flex space-x-4">
                        <input type="text" id="searchInput"
                               placeholder="{{ __('locale.branch_orders.search_placeholder') }}"
                               class="px-2 py-1 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                        <button id="btExportCSV"
                                class="px-2 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-300">
                            {{ __('locale.branch_orders.export_csv_button') }}
                        </button>
                    </div>
                    <div id="agGrid" class="ag-theme-alpine" style="height: 600px;"></div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

@php
    $statusTranslations = [
        'received' => __('order-status.received'),
        'processing' => __('order-status.processing'),
        'delivering' => __('order-status.delivering'),
        'delivered' => __('order-status.delivered'),
        'canceled' => __('order-status.canceled'),
        'unknown' => __('order-status.unknown')
    ];
@endphp

<script>
    var statusTranslations = @json($statusTranslations);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the JSON data from Blade variable for orders
        var rowData = @json($orders);

        // Custom sorting function to toggle between ascending and descending order
        function customComparator(valueA, valueB, isInverted) {
            var compareValue = valueA.localeCompare(valueB);
            return isInverted ? compareValue * -1 : compareValue;
        }

        // Define the ag-Grid columns for orders
        var columnDefs = [
            {headerName: "#", field: "id", maxWidth: 60, sortable: true, comparator: customComparator},
            {
                headerName: "{{__('locale.branch_orders.order_number_label')}}",
                field: 'order_number',
                maxWidth: 100,
                sortable: true,
                comparator: customComparator
            },
            {
                headerName: "{{__('locale.branch_orders.status_label')}}",
                field: 'status',
                flex: 1,
                cellClass: getStatusCellClass,
                sortable: true,
                comparator: customComparator
            },
            {
                headerName: "{{__('locale.branch_orders.branch_order_number_label')}}",
                field: 'branch_order_number',
                flex: 1,
                sortable: true,
                comparator: customComparator
            },
            {
                headerName: "{{__('locale.branch_orders.branch_queue_number_label')}}",
                field: 'branch_queue_number',
                flex: 1,
                sortable: true,
                comparator: customComparator
            },
            {
                headerName: "{{__('locale.branch_orders.branch_orders_date')}}",
                field: 'order_date',
                width: 150,
                sortable: true,
                comparator: customComparator,
                valueFormatter: function (params) {
                    const date = new Date(params.value); // Creating a date object from the string
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based, adding 1 and ensure two digits
                    const day = String(date.getDate()).padStart(2, '0'); // Ensure two digits

                    return `${year}-${month}-${day}`; // Returning formatted date
                }
            },
            {
                headerName: "{{__('locale.order.customer_name')}}",
                field: 'customer_name',
                flex: 1,
                sortable: true,
                comparator: customComparator
            },
            {
                headerName: "{{__('locale.common.actions')}}",
                flex: 1,
                cellRenderer: function (params) {
                    var branchId = params.data.branch_id;  // Assuming 'branch_id' is the name of the property on the data
                    var orderId = params.data.id;

                    // Construct the URL dynamically using JavaScript
                    var url = '{{ route("preview-order", [":branchId", ":orderId"]) }}';
                    url = url.replace(':branchId', branchId).replace(':orderId', orderId);

                    return `
            <a href="${url}"
               class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm p-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                {{__('locale.branch_orders.order_details')}}
                    </a>`;
                },
            },
        ];

        // Function to determine cell class for 'Status' column
        function getStatusCellClass(params) {
            var status = params.value;

            switch (status) {
                case 'received':
                    return "{{__('order-status.received')}}";
                case 'processing':
                    return "{{__('order-status.processing')}}";
                case 'delivering':
                    return "{{__('order-status.delivering')}}";
                case 'delivered':
                    return "{{__('order-status.delivered')}}";
                case 'canceled':
                    return "{{__('order-status.canceled')}}";
                default:
                    return "{{__('order-status.unknown')}}";
            }
        }

        // Set up ag-Grid
        var gridOptions = {
            columnDefs: columnDefs,
            rowData: rowData,
            pagination: true,
            paginationPageSize: 12,
            enableRangeSelection: true,
        };

        // Create the ag-Grid instance
        var gridDiv = document.querySelector('#agGrid');
        var grid = new agGrid.Grid(gridDiv, gridOptions);

        // Add an input field for searching
        var searchInput = document.querySelector('#searchInput');
        searchInput.addEventListener('input', function () {
            gridOptions.api.setQuickFilter(searchInput.value);
        });


        document.querySelector('#btExportCSV').addEventListener('click', function () {
            gridOptions.api.exportDataAsCsv();
        });

    });
</script>
