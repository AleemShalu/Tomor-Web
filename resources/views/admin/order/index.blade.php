<x-app-admin-layout>

    <div class="p-4">
        <div class="bg-white rounded-md p-4">
            <div class="flex">
                <div class="mx-2">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 21 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.5 3h9.563M9.5 9h9.563M9.5 15h9.563M1.5 13a2 2 0 1 1 3.321 1.5L1.5 17h5m-5-15 2-1v6m-2 0h4"/>
                    </svg>
                </div>
                <h1 class="font-bold text-xl">
                    {{__('admin.order_management.title')}}
                </h1>
            </div>
            <div class="text-gray-500">
                {{__('admin.order_management.description')}}
            </div>
        </div>
        <div class="bg-white ">
            <div class="flex space-x-4 px-4 pt-2 rounded-md mt-4 hidden">
                <!-- Buttons for Reports -->
                <div class="py-3">
                    <a href="{{ route('admin.order.reports.excel') }}"
                       class="px-4 py-2 bg-blue-500 text-white rounded-md">Export
                        Excel</a>
                    <a href="{{ route('admin.order.reports.csv') }}"
                       class="px-4 py-2 bg-blue-500 text-white rounded-md">Export
                        CSV</a>
                    <a href="{{ route('admin.order.reports.pdf') }}"
                       class="px-4 py-2 bg-blue-500 text-white rounded-md">Export
                        PDF</a>
                </div>
            </div>
            <div class="relative overflow-x-auto p-4">

                <input type="text" id="filter-text-box" class="mb-2 rounded-md border-gray-400"
                       placeholder="Search..."/>
                <div class="relative overflow-x-auto">
                    <div id="myGrid" style="height: 600px; width: 100%;" class="ag-theme-alpine"></div>
                </div>

            </div>
        </div>
    </div>

    </div>
</x-app-admin-layout>
@php
    // Generate a map of status keys to localized strings
    $statusTranslations = [
        'received' => __('order-status.received'),
        'processing' => __('order-status.processing'),
        'finished' => __('order-status.finished'),
        'delivering' => __('order-status.delivering'),
        'delivered' => __('order-status.delivered'),
        'canceled' => __('order-status.canceled'),
        'pending_payment' => __('order-status.pending_payment')
    ];
@endphp

<script>
    var statusTranslations = @json($statusTranslations);
</script>

<script>
    var statusColors = @json($statusColors);
    var orders = @json($orders);
    var columnDefs = [
            // {headerCheckboxSelection: true, checkboxSelection: true, maxWidth: 60}, // Checkbox for row selection
            {
                headerName: "{{__('admin.order_management.id')}}",
                field: "id", flex: 1, sortable: true, filter: true, maxWidth: 70, resizable: true
            },
            {
                headerName: "{{__('admin.order_management.order_number')}}",
                field: "order_number",
                flex: 1,
                sortable: true,
                filter: true,
                maxWidth: 160,
                resizable: true
            },
            {
                headerName: "{{ __('admin.order_management.status') }}",
                field: "status",
                flex: 1,
                sortable: true,
                filter: true,
                cellRenderer: function (params) {
                    // Using the statusTranslations map to find the localized label
                    return statusTranslations[params.value.toLowerCase()] || params.value;
                },
                cellStyle: function (params) {
                    return {
                        backgroundColor: getStatusColor(params.value)
                    };
                },
                resizable: true
            },
            {
                headerName: "{{__('admin.order_management.customer_name')}}",
                field:
                    "customer_name", flex:
                    1, sortable:
                    true, filter:
                    true, resizable:
                    true
            }
            ,
            {
                headerName: "{{__('admin.order_management.customer_email')}}",
                field:
                    "customer_email",
                flex:
                    1,
                sortable:
                    true,
                filter:
                    true,
                maxWidth:
                    200,
                resizable:
                    true
            }
            ,
            {
                headerName: "{{__('admin.order_management.customer_contact_no')}}",
                field:
                    "customer_contact_no",
                flex:
                    1,
                sortable:
                    true,
                filter:
                    true,
                maxWidth:
                    160,
                resizable:
                    true
            }
            ,
            {
                headerName: "{{__('admin.order_management.checkout_method')}}",
                field:
                    "checkout_method",
                flex:
                    1,
                sortable:
                    true,
                filter:
                    true,
                resizable:
                    true
            }
            ,
            {
                headerName: "{{__('admin.order_management.grand_total')}}",
                field:
                    "grand_total", flex:
                    1, sortable:
                    true, filter:
                    true, resizable:
                    true
            }
            ,
            {
                headerName: "{{__('admin.order_management.tax_total')}}",
                field:
                    "tax_total", flex:
                    1, sortable:
                    true, filter:
                    true, resizable:
                    true
            }
            ,
            {
                headerName: "{{__('admin.order_management.commercial_name_en')}}",
                field:
                    "store.commercial_name_en",
                flex:
                    1,
                sortable:
                    true,
                filter:
                    true,
                resizable:
                    true
            }
            ,
            {
                headerName: "{{__('admin.order_management.action')}}",
                flex:
                    1,
                cellRenderer:
                    params => {
                        // Use the user's ID from the current row data to generate the URL.
                        const url = getViewUrl(params.data.id);
                        return `<a href="${url}" class="btn btn-primary">{{__('admin.common.view')}}</a>`;
                    },
                resizable:
                    true
            }
        ]
    ;
    var gridOptions = {
        columnDefs: columnDefs,
        rowData: orders,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        rowSelection: 'multiple',
        enableRangeSelection: true,
        enableCellTextSelection: true,  // Enable text selection for copying
        clipboardDeliminator: '\t',  // Delimiter when copying multiple values. Using tab for this example.
        getRowStyle: function (params) {
            if (params.node.data.status === 'Pending') {
                return {background: 'yellow'};
            }
        },

        getContextMenuItems: function (params) {
            return [
                {
                    name: 'Copy Cell',
                    action: function () {
                        var selectedNodes = params.api.getSelectedNodes();
                        if (selectedNodes.length > 0) {
                            var copyText = selectedNodes[0].data[params.column.colId];
                            var textArea = document.createElement('textarea');
                            textArea.value = copyText;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textArea);
                        }
                    }
                },
                {
                    name: 'Copy Row',
                    action: function () {
                        params.api.copySelectedRowsToClipboard(true); // true to include headers
                    }
                },
            ];
        }
    };

    function getStatusColor(status) {
        switch (status.toLowerCase()) {
            case 'received':
                return '#ADD8E6';  // Light Blue
            case 'processing':
                return '#FFEB3B';  // Bright Yellow
            case 'finished':
                return '#4CAF50';  // Medium Green
            case 'delivering':
                return '#2E7D32';  // Medium Green
            case 'delivered':
                return '#2E7D32';  // Dark Green
            case 'canceled':
                return '#FF5733';  // Crimson Red
            case 'pending_payment':
                return '#00ffc2';  // Crimson Red
            case 'unknown':
            default:
                return '#9E9E9E';  // Gray
        }
    }


    function getViewUrl(orderId) {
        // If you're using named routes, you might generate URLs differently.
        // This is a simple example.
        return `../admin/order/${orderId}`;
    }

    // Get a reference to the input element
    var filterText = document.getElementById('filter-text-box');

    // Add an event listener for input changes
    filterText.addEventListener('input', function () {
        gridOptions.api.setQuickFilter(filterText.value);
    });

    document.addEventListener("DOMContentLoaded", function () {
        var gridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(gridDiv, gridOptions);

        document.getElementById('exportData').addEventListener('click', function () {
            gridOptions.api.exportDataAsCsv();
        });

    });


</script>

