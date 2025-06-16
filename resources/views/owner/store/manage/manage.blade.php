<x-app-layout>
    <div class="max-w-7xl mx-auto py-6">
        <x-store-main :store-id="$store->id">
            <x-slot name="main">

                <div>
                    <x-store-header
                            :store-header="$store->store_header"
                            :commercial-name-en="$store->commercial_name_en"
                            :description="$store->description"
                            :logo="$store->logo"
                            :short-name-en="$store->short_name_en"
                            :store-id="$store->id"
                    />
                </div>

                <div>
                    <x-store-information
                            :commercial-registration-no="$store->commercial_registration_no"
                            :contact-no="$store->contact_no"
                            :tax-id-number="$store->tax_id_number"
                            :commercial-registration-expiry="convertGregorianToHijri($store->commercial_registration_expiry)"
                            :municipal-license-no="$store->municipal_license_no"
                            :status="$store->status"
                    />
                </div>
                <div class="mt-5 w-full max-w-6xl mx-auto bg-white rounded">
                    <div>
                        <div class="bg-white rounded ">

                            <div class="hidden">
                                <div class="mb-4">
                                    <label for="timeRange" class="block text-gray-700">
                                        <i class="fas fa-clock"></i> {{__('locale.store_manage.select_time_range')}}
                                    </label>
                                    <select id="timeRange"
                                            class="px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
                                        <option value="oneWeek">{{ __('locale.store_manage.one_week') }}</option>
                                        <option value="oneMonth">{{ __('locale.store_manage.one_month') }}</option>
                                        <option value="threeMonths">{{ __('locale.store_manage.three_months') }}</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1 gap-4 my-4">
                                    <div class="chart bg-gray-100 p-4 rounded-md shadow-md">
                                        <div class="flex items-center">
                                            <i class="fas fa-chart-bar mr-2"></i>
                                            <div
                                                    class="font-bold text-xl px-2">{{ __('locale.store_manage.orders_sales_branches') }}</div>
                                        </div>
                                        <div width="200" height="400">
                                            <canvas id="chart1" width="200" height="400"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-100 p-4 border border-gray-200 rounded">
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2 px-2"></i>
                                        <div
                                                class="font-bold text-xl">{{ __('locale.store_manage.employees_branches') }}</div>
                                    </div>
                                    <div width="200" height="400">
                                        <canvas id="chart2" width="400" height="400"></canvas>
                                    </div>
                                </div>
                                <div class="bg-gray-100 p-4 border border-gray-200 rounded">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-friends mr-2"></i>
                                        <div
                                                class="font-bold text-xl px-2">{{ __('locale.store_manage.visitors_branches') }}</div>
                                    </div>
                                    <div width="200" height="400">
                                        <canvas id="chart3" width="400" height="400"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid mt-4">
                                    <div class="bg-gray-100 p-4 border border-gray-200 rounded">
                                        <div class="flex items-center">
                                            <i class="fas fa-star mr-2"></i>
                                            <div
                                                    class="font-bold text-xl px-2">{{ __('locale.store_manage.rating_branches') }}</div>
                                        </div>
                                        <div>
                                            <div width="200" height="200">
                                                <canvas id="chart4" width="400" height="400"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid mt-4">
                                    <div class="bg-gray-100 p-4 border border-gray-200 rounded">
                                        <div class="flex items-center">
                                            <i class="fas fa-university mr-2"></i>
                                            <div
                                                    class="font-bold text-xl px-2">{{ __('locale.store_manage.bank_accounts') }}</div>
                                        </div>
                                        <div id="agGrid2" style="height: 400px; width: 100%;"
                                             class="ag-theme-alpine"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </x-slot>

        </x-store-main>
    </div>
</x-app-layout>

<script>
    const storeData = @json($store);
    const timeRangeSelect = document.getElementById('timeRange');
    const bankAccounts = @json($store->bank_accounts);


    function extractDataForChart1() {
        const branchNames = storeData.branches.map(branch => branch.name_en);
        const orderCounts = storeData.branches.map(branch => branch.orders.length);

        return {
            labels: branchNames,
            datasets: [{
                label: "Count of Orders",
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 2,
                data: orderCounts
            }]
        };
    }

    function extractDataForChart2() {
        const counts = storeData.employees.reduce((acc, employee) => {
            if (employee.employee_roles.some(role => role.name === 'worker')) acc.workerCount++;
            else if (employee.employee_roles.some(role => role.name === 'worker_supervisor')) acc.workerSupervisorCount++;
            else if (employee.employee_roles.some(role => role.name === 'supervisor')) acc.supervisorCount++;

            return acc;
        }, {workerCount: 0, workerSupervisorCount: 0, supervisorCount: 0});

        return {
            labels: ['Worker', 'Worker Supervisor', 'Supervisor'],
            datasets: [{
                data: [counts.workerCount, counts.workerSupervisorCount, counts.supervisorCount],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4,
                borderWidth: 1
            }]
        };
    }


    function calculateThresholdDate(selectedValue) {
        const thresholdDate = new Date();
        switch (selectedValue) {
            case 'oneWeek':
                thresholdDate.setDate(thresholdDate.getDate() - 7);
                break;
            case 'oneMonth':
                thresholdDate.setMonth(thresholdDate.getMonth() - 1);
                break;
            case 'threeMonths':
                thresholdDate.setMonth(thresholdDate.getMonth() - 3);
                break;
            default:
                thresholdDate.setDate(thresholdDate.getDate() - 7);
        }
        return thresholdDate;
    }

    function extractDataForChart3(selectedValue) {
        const thresholdDate = calculateThresholdDate(selectedValue);
        const visitorCounts = storeData.branches.map(branch => branch.branch_visitors.length);
        const branchNames = storeData.branches.map(branch => branch.name_en);

        return {
            labels: branchNames,
            datasets: [{
                label: 'Visitors Count',
                data: visitorCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 2,
            }]
        };

    }

    function extractDataForChart4(selectedValue) {
        const thresholdDate = calculateThresholdDate(selectedValue);

        const ratings = storeData.branches.map(branch => {
            const branchOrders = branch.orders.filter(order => new Date(order.order_date) >= thresholdDate);
            const totalRating = branchOrders.reduce((sum, order) => sum + order.order_ratings.reduce((ratingSum, rating) => ratingSum + rating.rating, 0), 0);
            return branchOrders.length ? totalRating / branchOrders.length : 0;
        });

        return {
            labels: storeData.branches.map(branch => branch.name_en),
            datasets: [{
                label: 'Average Ratings',
                data: ratings,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        };
    }

    timeRangeSelect.addEventListener('change', function () {
        const selectedValue = timeRangeSelect.value;

        chart.data.datasets[0].data = storeData.branches.map(branch => branch.orders.filter(order => new Date(order.order_date) >= calculateThresholdDate(selectedValue)).length);
        chart3.data = extractDataForChart3(selectedValue);
        chart4.data = extractDataForChart4(selectedValue);

        chart.update();
        chart3.update();
        chart4.update();
    });


    const options = {
        maintainAspectRatio: false,
        scales: {
            y: {stacked: true, grid: {display: true, color: "rgba(255,99,132,0.2)"}},
            x: {grid: {display: false}}
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const gridOptions = {
            columnDefs: [
                {headerName: "Account Holder", field: "account_holder_name", minWidth: 100, resizable: true},
                {headerName: "IBAN", field: "iban_number", minWidth: 150, resizable: true},
                {headerName: "Bank Name", field: "bank_name", minWidth: 100, resizable: true},
                {headerName: "Swift Code", field: "swift_code", minWidth: 100, resizable: true}
            ],
            rowData: bankAccounts,
            onFirstDataRendered: onFirstDataRendered,
            domLayout: 'autoHeight'
        };

        const eGridDiv = document.querySelector('#agGrid2');
        new agGrid.Grid(eGridDiv, gridOptions);

        function onFirstDataRendered(params) {
            params.api.sizeColumnsToFit();
        }

        window.addEventListener('resize', function () {
            setTimeout(function () {
                params.api.sizeColumnsToFit();
            })
        });
    });

    const chart1 = new Chart('chart1', {
        type: "bar",
        data: extractDataForChart1(),
        options: options
    });
    const chart2 = new Chart('chart2', {
        type: 'pie',
        data: extractDataForChart2(),
        options: {maintainAspectRatio: false}
    });
    const chart3 = new Chart('chart3', {type: 'bar', data: extractDataForChart3(timeRangeSelect.value), options});
    const chart4 = new Chart('chart4', {type: 'bar', data: extractDataForChart4(timeRangeSelect.value), options});

    // Assuming the initialization for chart4 elsewhere since the initial dataset was not given in the provided code.
</script>
