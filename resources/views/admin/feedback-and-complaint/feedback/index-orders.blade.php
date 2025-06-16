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
                    {{__('admin.support.feedback_and_complaint.tabs.feedback-orders')}}
                </h2>
                <div class="overflow-x-auto p-4">
                    <form action="{{ route('admin.feedback.index') }}" method="GET"
                          class="flex items-center space-x-4">

                        <label for="status" class="font-semibold">Status:</label>
                        <select name="platform" id="platform" class="border border-gray-300 p-2 rounded">
                            <option value="">All</option>
                            <option value="web">Web</option>
                            <option value="app">App</option>
                        </select>


                        <label for="date_from" class="font-semibold">Date From:</label>
                        <input type="date" name="date_from" id="date_from" class="border border-gray-300 p-2 rounded"
                               value="{{ request()->get('date_from') }}">

                        <label for="date_to" class="font-semibold">Date To:</label>
                        <input type="date" name="date_to" id="date_to" class="border border-gray-300 p-2 rounded"
                               value="{{ request()->get('date_to') }}">

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded">Filter
                        </button>
                    </form>

                    <!-- ag-Grid container -->
                    <div id="myGrid" class="mt-4 w-full ag-theme-alpine"></div>

                    <div class="mt-4">
                        {{ $feedbacks->links() }}
                    </div>
                </div>
                <div class="hidden mt-4 flex justify-end space-x-2">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Export as PDF</button>
                    <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Export as Excel
                    </button>
                </div>
            </div>


        </div>
    </div>
    </div>
</x-app-admin-layout>
<script>
    var feedbacksData = @json($feedbacks->items());

    var columnDefs = [
        {headerName: "ID", field: "id", maxWidth: 80},
        {headerName: "Platform", field: "platform", maxWidth: 120},
        {headerName: "Body Massage", field: "body_massage"},
        {headerName: "Rating", field: "rating", maxWidth: 100},
        {headerName: "Created At", field: "created_at"},
        {headerName: "Email", valueGetter: params => `${params.data.user.email}`},
        {headerName: "User Status", valueGetter: params => params.data.user.status === 1 ? "Active" : "Inactive"},
        {
            headerName: "Profile Photo", maxWidth: 100,
            cellRenderer: function (params) {
                return `<img src="${params.data.user.profile_photo_url}" alt="${params.data.user.name}" width="50">`;
            }
        },
        {
            headerName: "Actions",
            cellRenderer: function (params) {
                return `
                <a href="/path/to/replay/endpoint/${params.data.id}" class="text-blue-600 hover:text-blue-800">Replay</a> |
                <a href="/path/to/view/endpoint/${params.data.id}" class="text-green-600 hover:text-green-800">View</a>
            `;
            }
        }
    ];

    var gridOptions = {
        flex: 1,
        columnDefs: columnDefs,
        domLayout: 'autoHeight',
        rowData: feedbacksData
    };

    var eGridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(eGridDiv, gridOptions);
</script>