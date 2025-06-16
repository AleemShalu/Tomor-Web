@php
    $locale = app()->getLocale();
@endphp

<x-app-admin-layout>
    <div class=" px-7 mx-auto mt-6">
        <div class="bg-white py-6 px-4 font-bold text-xl rounded">
            <div class="flex">
                <i class="mx-3 mt-1" data-lucide="badge-dollar-sign"></i>
                {{__('admin.financial_management.financial_stores_statistics')}}
            </div>
        </div>
        <div class="bg-white py-6 px-4 mt-4 rounded">
            {{--            <input type="text" id="searchInput" placeholder="Search..." class="border px-3 py-2 rounded-md">--}}
            {{--            <select id="statusFilter" class="border px-3 py-2 rounded-md ml-2">--}}
            {{--                <option value="">All Status</option>--}}
            {{--                <option value="paid">Paid</option>--}}
            {{--                <option value="unpaid">Unpaid</option>--}}
            {{--                <!-- Add more status options as needed -->--}}
            {{--            </select>--}}
            {{--            <button id="filterButton" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Filter</button>--}}

            {{--            <a href="{{ route('admin.financial.invoices.export-excel') }}"--}}
            {{--               class="bg-green-500 text-white px-4 py-2 rounded-md ml-2 {{ $locale === 'ar' ? 'float-left' : 'float-right' }}">--}}
            {{--                Export All Invoices--}}
            {{--            </a>--}}

            <div id="agGrid" class="ag-theme-alpine mt-4" style="height: 70%;"></div>
            <div class="mt-2">
                <div id="pagination" class="mt-2"></div>
            </div>
        </div>

    </div>
</x-app-admin-layout>

<script>
    // Declare gridOptions in a broader scope
    var gridOptions;

    document.addEventListener('DOMContentLoaded', function () {

        var gridOptions = {
            columnDefs: [
                {headerName: '#', field: 'id', maxWidth: 80},
                {headerName: 'Commercial Name (EN)', field: 'commercial_name_en', maxWidth: 300},
                {headerName: 'Email', field: 'email', maxWidth: 200},
                {
                    headerName: 'Contact Number',
                    field: 'concatenated_contact',
                    maxWidth: 300,
                    valueGetter: function (params) {
                        console.log(params.data);

                        // Concatenate dial_code and contact_no
                        return '+' + params.data.dial_code + ' ' + params.data.contact_no;
                    }
                },
                {
                    headerName: 'Order Count',
                    valueGetter: function (params) {
                        return params.data.order_service && params.data.order_service.length > 0 ? params.data.order_service[0].order_count : null;
                    }, maxWidth: 150
                },
                {
                    headerName: 'Total Cost Service',
                    valueGetter: function (params) {
                        return params.data.order_service && params.data.order_service.length > 0 ? params.data.order_service[0].total_price + ' SAR' : null;
                    },
                    maxWidth: 150
                },
                {
                    headerName: 'Action',
                    cellRenderer: function (params) {
                        return '<a href="#" onclick="viewInvoice(' + params.data.id + ')" class="px-2 py-1 bg-blue-500 text-white rounded">View Invoices</a>';
                    },
                },

            ],
            defaultColDef: {
                flex: 1,
                minWidth: 150,
                filter: true,
                sortable: true,
            },
            groupDefaultExpanded: 1, // Expand the first group by default
            animateRows: true,
            domLayout: 'autoHeight'
        };
        var gridDiv = document.querySelector('#agGrid');
        new agGrid.Grid(gridDiv, gridOptions);
        //
        // var searchInput = document.querySelector('#searchInput');
        // var statusFilter = document.querySelector('#statusFilter');
        var paginationDiv = document.querySelector('#pagination');


        // Define loadGridData function in the global scope
        window.loadGridData = function (page) {
            $.ajax({
                url: '{{route('admin.financial.store-analysis.fetch')}}',
                type: 'GET',
                data: {
                    page: page,
                    // search: searchInput.value,
                    // status: statusFilter.value
                },
                success: function (response) {
                    var storesData = response.data;
                    gridOptions.api.setRowData(storesData);

                    // Update pagination controls
                    renderPagination(response.current_page, response.last_page);
                },
                error: function (error) {
                    console.error('Error loading grid data:', error);
                }
            });
        };

        // Initial load
        loadGridData(1);

        function renderPagination(currentPage, totalPages) {
            paginationDiv.innerHTML = `
                <span class="mr-4">Page ${currentPage} of ${totalPages}</span>
                <button onclick="loadGridData(${currentPage - 1})"
                    class="px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    ${currentPage === 1 ? 'disabled' : ''}
                >
                    <i class="icon-chevron-left"></i> Previous
                </button>
                <button onclick="loadGridData(${currentPage + 1})"
                    class="px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    ${currentPage === totalPages ? 'disabled' : ''}
                >
                    Next <i class="icon-chevron-right"></i>
                </button>
            `;
        }
    });

    function viewInvoice(id) {
        var url = "{{ route('admin.financial.invoices.store.index', ['id' => ':id']) }}";
        window.location.href = url.replace(':id', id);
    }
</script>