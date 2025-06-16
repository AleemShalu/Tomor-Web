<x-app-admin-layout>
    <div class="p-8" id="printContent">

        <div class="flex">
            <h2 class="text-2xl font-bold mb-4">
                {{__('admin.store_management.branch.info')}}
            </h2>
            <!-- Print Button -->
            {{--            <div class="mb-4 flex float-right">--}}
            {{--                <button onclick="printContent()"--}}
            {{--                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow">--}}
            {{--                    <i class="bi bi-printer"></i>--}}
            {{--                    Print Content--}}
            {{--                </button>--}}
            {{--            </div>--}}
        </div>

        <div class="border p-4 rounded-lg mb-4 bg-white">
            <div class="pb-2">
                <p class="font-bold">
                    {{__('admin.store_management.branch.name')}}
                </p>
                <p>{{ $branch->name }}</p>
            </div>
            <div class="pb-2">
                <p class="font-bold">
                    {{__('locale.branch.commercial_registration_number')}}
                </p>
                <p>{{ $branch->commercial_registration_no }}</p>
            </div>
            <div class="pb-2">
                <p class="font-bold">
                    {{__('locale.branch.commercial_registration_expiry')}}
                </p>
                <p>{{ $branch->commercial_registration_expiry }}</p>
            </div>

            @if($branch->commercial_registration_attachment)
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $branch->commercial_registration_attachment) }}"
                       target="_blank"
                       class="text-blue-600 hover:underline text-sm">{{ __('locale.store.view_attachment') }}</a>
                </div>
            @endif
        </div>

        <h2 class="text-2xl font-bold mb-4">
            {{__('locale.bank_account.title')}}
        </h2>

        <div class="border p-4 rounded-lg mb-4 bg-white">
            <div class="pb-2">
                <p class="font-bold">
                    {{__('locale.bank_account.account_holder_name')}}
                </p>
                <p>{{ $branch->bank_account->account_holder_name }}</p>
            </div>
            <div class="pb-2">
                <p class="font-bold">
                    {{__('locale.bank_account.iban_number')}}
                </p>
                <p>{{ $branch->bank_account->iban_number }}</p>
            </div>
        </div>

        <!-- Add other bank account details you want to display -->

        <h2 class="text-2xl font-bold mb-4">
            {{__('locale.branch_settings.location.title')}}

        </h2>

        <div class="border p-4 rounded-lg mb-4 bg-white">
            <div class="pb-2">
                <p class="font-bold">
                    {{__('locale.branch_settings.location.description')}}
                </p>
                <p>{{ $branch->location->location_description }}</p>
            </div>
            <div class="pb-2">
                <p class="font-bold">
                    {{__('locale.branch_settings.location.title')}}
                </p>
                <a href="{{$branch->location->google_maps_url }}">{{ $branch->location->google_maps_url }}</a>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-4">
            {{__('admin.store_management.work_statuses.title')}}
        </h2>

        @if(count($branch->work_statuses) > 0)
            <table class="w-full border bg-white">
                <thead>
                <tr>
                    <th class="p-2 border font-bold">
                        {{__('admin.store_management.work_statuses.status')}}
                    </th>
                    <th class="p-2 border font-bold">
                        {{__('admin.store_management.work_statuses.start_time')}}
                    </th>
                    <th class="p-2 border font-bold">
                        {{__('admin.store_management.work_statuses.end_time')}}
                    </th>
                    <th class="p-2 border font-bold">
                        {{__('admin.store_management.work_statuses.created_at')}}
                    </th>
                    <th class="p-2 border font-bold">
                        {{__('admin.store_management.work_statuses.updated_at')}}
                    </th>
                    <!-- Add more work status details columns here -->
                </tr>
                </thead>
                <tbody>
                @foreach ($branch->work_statuses as $workStatus)
                    <tr>
                        <td class="p-2 border text-center">
                            @if ($workStatus->status == 'Active')
                                <span class="my-4 px-2 py-1 rounded bg-green-500 text-white">Active</span>
                            @else
                                <span class="my-4 px-2 py-1 rounded bg-red-500 text-white">Inactive</span>
                            @endif
                        </td>
                        <td class="p-2 border">{{ $workStatus->start_time }}</td>
                        <td class="p-2 border">{{ $workStatus->end_time }}</td>
                        <td class="p-2 border">{{ $workStatus->created_at }}</td>
                        <td class="p-2 border">{{ $workStatus->updated_at }}</td>
                        <!-- Add more work status details cells here -->
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="font-bold">
                {{__('admin.store_management.employee.no_work_statuses_found')}}
            </p>
        @endif

        <h2 class="text-2xl font-bold mb-4 mt-3">
            {{__('admin.store_management.employee.title')}}
        </h2>
        <div class="mb-4 justify-between" style="height: 760px">
            <!-- Search Bar -->
            <div class="mb-2 flex justify-between items-center">
                <input type="text" id="quickFilterInput" placeholder="Search..." class="border p-2 rounded mr-2">

                <button id="exportToCsv"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow">
                    <i class="bi bi-download"></i>
                    Export to CSV
                </button>
            </div>

            <div id="grid_employees" class="ag-theme-alpine" style="width: 100%; height: 100px;"></div>
        </div>
        <div>

        </div>

    </div>
</x-app-admin-layout>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var gridOptions = {
            defaultColDef: {
                // Enables filtering for all columns
                filter: true,

                flex: 1, // This makes all columns responsive and flexible

                // Enables sorting for all columns
                sortable: true,

                // Allows columns to be resized
                resizable: true,

                // Sets a default width for columns (in px)
                // width: 150,

                // Makes sure content does not get out of the cell
                wrapText: true,
                autoHeight: true,

                // Allows for columns to be rearranged
                enableRowGroup: true,
                enablePivot: true,
                enableValue: true,

                // Floating filter below the column header
                floatingFilter: true,

                // For suppress the missing filter icon in the column header
                suppressMenu: false,

                // For columns with more data you might not want to show tooltips, set to true to disable
                suppressToolPanel: false,

                // Set min and max width constraints
                // minWidth: 50,
                // maxWidth: 300,

                // Allows the user to pin columns to the left or right of a grid
                pin: 'right',

                // Sets an animation's duration when columns are resized in milliseconds
                resizeAnimationDuration: 300,

                // domLayout: 'autoHeight',            // Adjust grid height automatically based on number of rows
                // More properties can be added as needed...
            },
            columnDefs: [
                // Define your columns here, example:
                {headerName: 'Name', field: 'name'},
                {
                    headerName: 'Contact',
                    valueGetter: function (params) {
                        if (params.data.dial_code) {
                            return params.data.dial_code + ' ' + params.data.contact_no;
                        } else {
                            return params.data.contact_no;
                        }
                    }
                },
                {
                    headerName: 'Profile Photo',
                    field: 'profile_photo_url',
                    cellRenderer: function (params) {
                        return '<img src="' + params.value + '" style="width: 50px; height: 50px;" />';
                    }
                },
                {
                    headerName: 'Role',
                    valueGetter: function (params) {
                        return params.data.roles && params.data.roles[0] ? params.data.roles[0].name : '';
                    }
                },
                {headerName: 'Email', field: 'email'},
                {headerName: 'Created At', field: 'created_at', valueFormatter: dateFormatter},

                // Add more columns as needed...
            ],
            domLayout: 'autoHeight', // This ensures the grid fits its content
            pagination: true,        // This enables pagination
            paginationPageSize: 10, // This sets the number of rows per page. You can adjust this number as needed.

            rowData: @json($branch->employees), // This converts the PHP array into a JSON object for JavaScript
        };

        function dateFormatter(params) {
            return moment(params.value).format('MM/DD/YYYY, h:mm:ss a');
        }

        // Function to export grid data to CSV
        document.getElementById('exportToCsv').addEventListener('click', function () {
            gridOptions.api.exportDataAsCsv();
        });

        // Setup for the quick filter (search functionality)
        document.getElementById('quickFilterInput').addEventListener('input', function () {
            gridOptions.api.setQuickFilter(this.value);
        });

        var eGridDiv = document.querySelector('#grid_employees');
        new agGrid.Grid(eGridDiv, gridOptions);
    });

    function printContent() {
        let printContents = document.getElementById('printContent').innerHTML;
        let originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
<style>
    @media print {
        #exportToCsv {
            display: none;
        }

        /* Any other styles for print mode */
    }
</style>
