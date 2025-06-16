<x-app-admin-layout>
    @php
        $tabs = [
            ['route' => route('admin.feedback-and-complaint.index'), 'routeName' => 'admin.feedback-and-complaint.index', 'iconPath' => 'M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z', 'label' => 'admin.support.feedback_and_complaint.tabs.summary'],
            ['route' => route('admin.complaint.index'), 'routeName' => 'admin.complaint.index', 'iconPath' => 'M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z', 'label' => 'admin.support.feedback_and_complaint.tabs.complaints'],
            ['route' => route('admin.feedback.index'), 'routeName' => 'admin.feedback.index', 'iconPath' => 'M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z', 'label' => 'admin.support.feedback_and_complaint.tabs.feedback-orders'],
            ['route' => route('admin.feedback.users.index'), 'routeName' => 'admin.feedback.users.index', 'iconPath' => 'M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z', 'label' => 'admin.support.feedback_and_complaint.tabs.feedback']
        ];
        $currentRoute = Route::currentRouteName();
    @endphp


    <div class="w-1/2 lg:w-8/12 px-7 mx-auto mt-6">
        <div class="bg-white py-6 px-4 font-bold text-xl rounded">
            <div>
                <i class="fa-solid fa-list-check fa-xl pr-4"></i>
                {{__('admin.support.feedback_and_complaint.title')}}
            </div>
            <p class="text-gray-600 mt-4 font-light text-sm">
                {{__('admin.support.feedback_and_complaint.description')}}

            </p>

        </div>
        <div class="bg-white mt-4 py-6 px-4 w-full overflow-x-auto">


            <x-tab-complaint-navigation :currentRoute="$currentRoute" :tabs="$tabs"/>

            <div class="p-3">
                <h2 class="text-lg font-semibold mb-4">
                    {{__('admin.support.feedback_and_complaint.tabs.feedback')}}
                </h2>
                <div class="overflow-x-auto p-4">

                    <!-- ag-Grid container -->
                    <div id="myGrid" class="w-full ag-theme-alpine"></div>

                    <div class="mt-4">
                        {{ $complaints->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-admin-layout>

<script>
    var complaintsData = @json($complaints->items());

    var columnDefs = [
        {headerName: "ID", field: "id", max: 50, maxWidth: 85},
        {headerName: "Subject", field: "report_title", flex: 1},
        {
            headerName: "Customer Name",
            flex: 1,
            valueGetter: params => `${params.data.first_name} ${params.data.last_name}`
        },
        {headerName: "Email", field: "email", flex: 1},
        {headerName: "Title", field: "report_title", flex: 1},
        {headerName: "Message", field: "body_message", flex: 1},

        {
            headerName: "Created At",
            field: "created_at",
            flex: 1,
            filter: "agDateColumnFilter",
            cellRenderer: (params) => {
                // Assuming the date is in ISO format and you want to format it to 'MM/DD/YYYY, h:mm:ss a'
                if (params.value) {
                    const date = new Date(params.value);
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    };
                    return date.toLocaleDateString('en-US', options) + ', ' + date.toLocaleTimeString('en-US', options);
                }
                return null;
            }
        },
    ];
    var gridOptions = {
        flex: 1,
        columnDefs: columnDefs,
        domLayout: 'autoHeight',
        pagination: true,
        paginationPageSize: 10,
        rowData: complaintsData
    };

    var eGridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(eGridDiv, gridOptions);
</script>
