@php
    $locale = app()->getLocale();
@endphp

<x-app-admin-layout>
    <div class="  px-7 mx-auto mt-6">
        <div class="bg-white py-6 px-4 font-bold text-xl rounded">
            <div class="flex">
                <i class="mx-3 mt-1" data-lucide="badge-dollar-sign"></i>
                {{__('admin.financial_management.financial_invoices')}}
            </div>
        </div>


        <div class="bg-white py-6 px-4 mt-4 rounded">
            <input type="text" id="searchInput" placeholder="Search..." class="border px-3 py-2 rounded-md">
            <select id="statusFilter" class="border px-3 py-2 rounded-md ml-2">
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
                <!-- Add more status options as needed -->
            </select>
            <button id="filterButton" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Filter</button>

            <a href="{{ route('admin.financial.store.invoices.export-excel',[$id]) }}"
               class="bg-green-500 text-white px-4 py-2 rounded-md ml-2 {{ $locale === 'ar' ? 'float-left' : 'float-right' }}">
                {{ __('admin.financial_management.export_all_invoices') }}
            </a>

            <div id="agGrid" class="ag-theme-alpine mt-4" style="height: 70%;"></div>
            <div class="mt-2">
                <div id="pagination" class="mt-2"></div>
            </div>
        </div>

        <script>
            // Declare gridOptions in a broader scope
            const store_id = '{{ $id }}';
            var gridOptions;

            document.addEventListener('DOMContentLoaded', function () {
                var gridOptions = {
                    columnDefs: [
                        {headerName: '#', field: 'id', maxWidth: 80, suppressMenu: true},
                        {headerName: 'Invoice Number', field: 'invoice_number'},
                        {headerName: 'Status', field: 'status'},
                        {headerName: 'Customer Name', field: 'customer_name'},
                        // {headerName: 'Business Name', field: 'business_name_ar'},
                        {headerName: 'Store Name', field: 'store_name_ar'},
                        {headerName: 'Store Invoice Number', field: 'store_invoice_number'},
                        {headerName: 'Store Branch Name', field: 'store_branch_name_ar'},
                        {headerName: 'Invoice Date', field: 'invoice_date'},
                        // {headerName: 'Supply Date', field: 'supply_date'},
                        {headerName: 'Order ID', field: 'order_id'},
                        // {headerName: 'Business Address', field: 'business_address'},
                        {headerName: 'Customer Email', field: 'customer_email'},
                        // {headerName: 'Business VAT Number', field: 'business_vat_number'},
                        // {headerName: 'Customer Dial Code', field: 'customer_dial_code'},
                        {headerName: 'Total VAT', field: 'total_vat'},
                        {headerName: 'Invoice Discount Percentage', field: 'invoice_discount_percentage'},
                        {headerName: 'Invoice Discount Amount', field: 'invoice_discount_amount'},
                        {headerName: 'Total Discount', field: 'total_discount'},
                        {headerName: 'Total Taxtable Amount', field: 'total_taxtable_amount'},
                        {headerName: 'Gross Total Including VAT', field: 'gross_total_including_vat'},
                        // {headerName: 'Exchange Rate', field: 'exchange_rate'},
                        {headerName: 'Conversion Time', field: 'conversion_time'},
                        {headerName: 'Total VAT in SAR', field: 'total_vat_in_sar'},
                        {
                            headerName: 'Action',
                            cellRenderer: function (params) {
                                return '<a href="#" onclick="viewInvoice(' + params.data.id + ')" class="px-2 py-1 bg-blue-500 text-white rounded">Download</a>';
                            },
                        },
                    ],
                    defaultColDef: {
                        flex: 1,
                        minWidth: 150,
                        filter: true,
                        sortable: true,
                    },
                    domLayout: 'autoHeight',
                };

                var gridDiv = document.querySelector('#agGrid');
                new agGrid.Grid(gridDiv, gridOptions);

                var searchInput = document.querySelector('#searchInput');
                var statusFilter = document.querySelector('#statusFilter');
                var paginationDiv = document.querySelector('#pagination');

                // Define loadGridData function in the global scope
                window.loadGridData = function (page) {
                    $.ajax({
                        url: '{{route('admin.financial.invoices.fetch')}}',
                        type: 'GET',
                        data: {
                            id: store_id,
                            page: page,
                            search: searchInput.value,
                            status: statusFilter.value
                        },
                        success: function (response) {
                            var invoicesData = response.data;
                            gridOptions.api.setRowData(invoicesData);

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

                // gridOptions.api.addEventListener('paginationChanged', function (event) {
                //     var currentPage = gridOptions.api.paginationGetCurrentPage() + 1;
                //     loadGridData(currentPage);
                // });

                // // Search functionality
                // searchInput.addEventListener('input', function () {
                //     loadGridData(1);
                // });
                //
                // // Status filter functionality
                // statusFilter.addEventListener('change', function () {
                //     loadGridData(1);
                // });

                var filterButton = document.querySelector('#filterButton');

                // Attach click event handler to the filter button
                filterButton.addEventListener('click', function () {
                    loadGridData(1);
                });

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


                // ... existing code
            });

            function viewInvoice(id) {
                var url = "{{ route('admin.financial.invoices.show', ['id' => ':id']) }}";
                window.location.href = url.replace(':id', id);
            }

            function exportInvoices(id) {
                $.ajax({
                    url: '/admin/financial/invoices/store/' + id + '/export-excel',
                    method: 'GET',
                    success: function (response) {
                        // Handle success, if needed
                        console.log('Export successful');
                        // You might want to redirect the user to the exported file or display a success message.
                    },
                    error: function (error) {
                        // Handle error, if needed
                        console.error('Error exporting invoices:', error);
                        // You might want to display an error message to the user.
                    }
                });
            }
        </script>
    </div>
</x-app-admin-layout>
