<x-app-layout>
    <x-branch-header :branch="$branch"></x-branch-header>
    <div class="flex flex-col justify-center">
        <div class="bg-white m-4 p-4 rounded-md">
            <div class="flex items-center">
                <div>
                    <svg width="40px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <title>ic_fluent_live_24_regular</title>
                            <desc>Created with Sketch.</desc>
                            <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="ic_fluent_live_24_regular" fill="#212121" fill-rule="nonzero">
                                    <path d="M5.98959236,4.92893219 C6.28248558,5.22182541 6.28248558,5.69669914 5.98959236,5.98959236 C2.67013588,9.30904884 2.67013588,14.6909512 5.98959236,18.0104076 C6.28248558,18.3033009 6.28248558,18.7781746 5.98959236,19.0710678 C5.69669914,19.363961 5.22182541,19.363961 4.92893219,19.0710678 C1.02368927,15.1658249 1.02368927,8.83417511 4.92893219,4.92893219 C5.22182541,4.63603897 5.69669914,4.63603897 5.98959236,4.92893219 Z M19.0710678,4.92893219 C22.9763107,8.83417511 22.9763107,15.1658249 19.0710678,19.0710678 C18.7781746,19.363961 18.3033009,19.363961 18.0104076,19.0710678 C17.7175144,18.7781746 17.7175144,18.3033009 18.0104076,18.0104076 C21.3298641,14.6909512 21.3298641,9.30904884 18.0104076,5.98959236 C17.7175144,5.69669914 17.7175144,5.22182541 18.0104076,4.92893219 C18.3033009,4.63603897 18.7781746,4.63603897 19.0710678,4.92893219 Z M8.81801948,7.75735931 C9.1109127,8.05025253 9.1109127,8.52512627 8.81801948,8.81801948 C7.06066017,10.5753788 7.06066017,13.4246212 8.81801948,15.1819805 C9.1109127,15.4748737 9.1109127,15.9497475 8.81801948,16.2426407 C8.52512627,16.5355339 8.05025253,16.5355339 7.75735931,16.2426407 C5.41421356,13.8994949 5.41421356,10.1005051 7.75735931,7.75735931 C8.05025253,7.46446609 8.52512627,7.46446609 8.81801948,7.75735931 Z M16.2426407,7.75735931 C18.5857864,10.1005051 18.5857864,13.8994949 16.2426407,16.2426407 C15.9497475,16.5355339 15.4748737,16.5355339 15.1819805,16.2426407 C14.8890873,15.9497475 14.8890873,15.4748737 15.1819805,15.1819805 C16.9393398,13.4246212 16.9393398,10.5753788 15.1819805,8.81801948 C14.8890873,8.52512627 14.8890873,8.05025253 15.1819805,7.75735931 C15.4748737,7.46446609 15.9497475,7.46446609 16.2426407,7.75735931 Z M12,10.5 C12.8284271,10.5 13.5,11.1715729 13.5,12 C13.5,12.8284271 12.8284271,13.5 12,13.5 C11.1715729,13.5 10.5,12.8284271 10.5,12 C10.5,11.1715729 11.1715729,10.5 12,10.5 Z"
                                          id="🎨-Color"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="pt-1 pl-2 text-xl font-bold">
                    {{ __('locale.branch_orders.title') }}
                </div>
            </div>
            <div>
                <div class="flex items-center space-x-4 mb-4 mt-3">
                    {{--                    <label for="startDate" class="text-gray-600">{{ __('locale.branch_orders.start_date_label') }}--}}
                    {{--                        :</label>--}}
                    {{--                    <input type="date" id="startDate"--}}
                    {{--                           class="px-2 py-1 rounded-lg focus:outline-none focus:ring focus:border-blue-300">--}}

                    {{--                    <label for="endDate" class="text-gray-600">{{ __('locale.branch_orders.end_date_label') }}:</label>--}}
                    {{--                    <input type="date" id="endDate"--}}
                    {{--                           class="px-2 py-1 rounded-lg focus:outline-none focus:ring focus:border-blue-300">--}}

                    <!-- Status Filter Dropdown -->
                    <label for="statusFilter" class="text-gray-600">{{ __('locale.branch_orders.status_label') }}
                        :</label>
                    <select id="statusFilter"
                            class="px-2 py-1 rounded-lg focus:outline-none focus:ring focus:border-blue-300">
                        <option value="">{{__('locale.status_order.all')}}</option>
                        <option value="received">{{__('locale.status_order.received')}}</option>
                        <option value="processing">{{__('locale.status_order.processing')}}</option>
                        <option value="delivering">{{__('locale.status_order.delivering')}}</option>
                        <option value="delivered">{{__('locale.status_order.delivered')}}</option>
                        <!-- Add more status options as needed -->
                    </select>

                    <div class="relative flex-1">
                        <!-- Search Input -->
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M8.293 3.293a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414l-6-6a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M13 10a3 3 0 11-6 0 3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                        <input
                                type="text"
                                id="searchInput"
                                placeholder="{{ __('locale.branch_orders.search_placeholder') }}"
                                class="py-2 pl-10 pr-4 rounded-lg focus:outline-none focus:ring focus:border-blue-300 w-full"
                        />
                    </div>
                    <button
                            id="btExportCSV"
                            class="export-button bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg"
                    >
                        {{ __('locale.branch_orders.export_csv_button') }}
                    </button>
                </div>

                <div id="agGrid" class="ag-theme-alpine" style="height: 700px; width: 100%;"></div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    /* Define CSS classes for status background colors */
    .status-delivered {
        background-color: #4CAF50; /* Green */
    }

    .status-delivering {
        background-color: #08c710; /* Green */
    }

    .status-canceled {
        background-color: #b9553d; /* Red */
    }

    .status-received {
        background-color: #3498DB; /* Blue */
    }

    .status-processing {
        background-color: #F1C40F; /* Yellow */
    }

    .status-unknown {
        background-color: #D3D3D3; /* Gray */
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Grab the startDate and endDate input elements
        var startDateInput = document.getElementById('startDate');
        var endDateInput = document.getElementById('endDate');


        // Define the ag-Grid columns for orders
        var columnDefs = [
            {
                headerName: "{{__('locale.branch_orders.order_number_label')}}",
                field: "order_number", minWidth: 80, maxWidth: 90, sortable: true,
                flex: 1
            },
            {
                headerName: "{{__('locale.branch_orders.status_label')}}",
                field: 'status',
                cellClass: getStatusCellClass,
                sortable: true,
                filter: 'agTextColumnFilter',
                flex: 1,
                minWidth: 150,
            },
            {
                headerName: "{{__('locale.branch_orders.branch_order_number_label')}}",
                field: 'branch_order_number',
                sortable: true,
                flex: 1,
                minWidth: 150,

            },
            {
                headerName: "{{__('locale.branch_orders.branch_queue_number_label')}}",
                field: 'branch_queue_number',
                sortable: true,
                minWidth: 150,
                flex: 1
            },
            {
                headerName: "{{__('locale.branch_orders.branch_orders_date')}}",
                field: 'order_date',
                sortable: true,
                flex: 1,
                minWidth: 120,
                filter: 'agDateColumnFilter',  // Use date filter
                valueFormatter: function (params) {
                    // Format the date as 'yyyy/MM/dd'
                    return params.value ? formatDate(params.value) : null;
                },
            },
            {
                headerName: "{{__('locale.order.customer_name')}}",
                field: 'customer_name',
                minWidth: 180,
                sortable: true,
                flex: 1
            },
            {
                headerName: "{{__('locale.common.actions')}}",
                flex: 1,
                minWidth: 140,
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

        // Function to format the date as 'yyyy/MM/dd'
        function formatDate(date) {
            const options = {year: 'numeric', month: '2-digit', day: '2-digit'};
            return new Date(date).toLocaleDateString('en-US', options).replace(/\//g, '-');
        }

        // Function to determine cell class for 'Status' column
        function getStatusCellClass(params) {
            var status = params.value;

            switch (status) {
                case 'received':
                    return 'status-received';
                case 'processing':
                    return 'status-processing';
                case 'delivering':
                    return 'status-delivering';
                case 'delivered':
                    return 'status-delivered';
                case 'canceled':
                    return 'status-canceled';
                default:
                    return 'status-unknown';
            }
        }


        // Set up ag-Grid
        var gridOptions = {
            columnDefs: columnDefs,
            enableFilter: true, // Enable filtering
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


        // Add event listener for the status filter dropdown
        var statusFilter = document.querySelector('#statusFilter');
        statusFilter.addEventListener('change', function () {
            var selectedStatus = statusFilter.value;

            // Apply the filter to the ag-Grid
            if (selectedStatus === '') {
                // Clear any existing filter
                gridOptions.api.setFilterModel(null);
            } else {
                // Apply a text filter to the 'status' column
                gridOptions.api.setFilterModel({
                    status: {
                        type: 'equals',
                        filter: selectedStatus,
                    },
                });
            }
        });

        // Function to update the Ag-Grid data
        function updateGridData(updatedOrders) {
            gridOptions.api.setRowData(updatedOrders);
        }


        // Get the branchId from Blade template
        var branchId = {{ $branch->id }};

        // Function to fetch initial data and periodically update the grid data
        function fetchAndUpdateData(branchId) {
            // Construct the URL with the branchId parameter
            const url = `/branch-get-orders-live/${branchId}`;
            fetch(url, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    updateGridData(data.orders);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Initial fetch of data
        fetchAndUpdateData(branchId);

        // Periodically update the data every 1 min
        setInterval(function () {
            fetchAndUpdateData(branchId);
        }, 10000);
    });
</script>
