<x-app-layout>
    <x-branch-header :branch="$branch"></x-branch-header>

    <div class="max-w-6xl p-4 bg-white m-4 mx-auto">
        <div class="m-3">
            <div class="p-3">

                <div class="text-2xl font-bold">
                    @if(app()->getLocale() == 'ar')
                        {{ $branch->name_ar }}
                    @else
                        {{ $branch->name_en }}
                    @endif
                </div>
                <div class="text-gray-500">
                    #{{ $branch->commercial_registration_no }}

                </div>

            </div>
            <div class="grid grid-cols-5 gap-x-3">

                <div class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded border border-gray-200 hover:shadow-xl flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i class="fas fa-dollar-sign fa-xl"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('locale.branch_dashboard.earnings') }}
                    </div>
                    <div id="earningsDisplay" class="text-xl font-bold">

                    </div>
                </div>

                <!-- First Grid Item: Count Orders -->
                <div class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded border border-gray-200 hover:shadow-xl flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i class="fas fa-list fa-xl"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('locale.branch_dashboard.order_count') }}
                    </div>
                    <div id="orderCountDisplay" class="text-xl font-bold">
                    </div>

                </div>

                <!-- Second Grid Item: Visitors -->
                <div class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded border border-gray-200 hover:shadow-xl flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i class="fas fa-users fa-xl"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('locale.branch_dashboard.visitors') }}
                    </div>
                    <div id="visitorCountDisplay" class="text-xl font-bold">
                    </div>
                </div>

                <!-- Third Grid Item: Time Hours -->
                <div class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded border border-gray-200 hover:shadow-xl flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i class="fas fa-clock fa-xl"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('locale.branch_dashboard.time_hours') }}
                    </div>
                    <div id="workingTimeDisplay" class="text-xl font-bold">
                        // {{ __('locale.branch_dashboard.working_hours') }}
                    </div>
                    <div id="totalHoursDisplay">
                        // {{ __('locale.branch_dashboard.total_hours') }}
                    </div>
                </div>

                <!-- Fourth Grid Item: Rating Avg -->
                <div class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded border border-gray-200 hover:shadow-xl flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i class="fas fa-star fa-xl"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('locale.branch_dashboard.rating_avg') }}
                    </div>
                    <div id="avgRatingDisplay" class="text-xl font-bold">
                        // {{ __('locale.branch_dashboard.average_rating') }}
                    </div>
                    <div id="countRatingDisplay">

                    </div>
                </div>

            </div>
            <div class="grid grid-cols-2 mt-4 gap-x-3">
                <!-- Chart 1 -->
                <div style="height: 400px" class="bg-gray-100 rounded-md border border-gray-200 p-4">
                    <div class="text-2xl font-bold">
                        {{ __('locale.branch_dashboard.orders') }}
                    </div>
                    <canvas id="chart1"></canvas>
                </div>

                <!-- Chart 2 -->
                <div style="height: 400px" class="bg-gray-100 rounded-md border border-gray-200 p-4">
                    <div class="text-2xl font-bold">
                        {{ __('locale.branch_dashboard.visitors') }}
                    </div>
                    <canvas id="chart2"></canvas>
                </div>
            </div>

            <div>
                <div class="bg-gray-100 rounded-md border border-gray-200 p-4 mt-4">
                    <div class="text-2xl font-bold">
                        {{ __('locale.branch_dashboard.monthly_earnings') }}
                    </div>
                    <canvas id="chart3"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">

                <!-- Pie Chart: Customer Satisfaction -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-200">
                    <div class="text-2xl font-bold mb-4">
                        {{ __('locale.branch_dashboard.customer_satisfaction') }}
                    </div>
                    <canvas id="satisfactionChart"></canvas>
                </div>

                <!-- ag-Grid Table: Employee Order Completion -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-200">
                    <div class="text-2xl font-bold mb-4">
                        {{ __('locale.branch_dashboard.orders_by_employee') }}
                    </div>
                    <div id="employeeGrid" class="ag-theme-alpine w-full"></div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    var branchData = @json($branch);

    var earnings = branchData.orders.reduce(function (sum, order) {
        return sum + order.grand_total;
    }, 0);

    var orderCount = branchData.orders.length;
    var visitorCount = branchData.branch_visitors.length;

    // Calculate total work hours
    var workStatus = branchData.work_statuses[0]; // Assuming only one work status for now
    var startTime = new Date("1970-01-01T" + workStatus.start_time + "Z");
    var endTime = new Date("1970-01-01T" + workStatus.end_time + "Z");
    var totalHours = (endTime - startTime) / 3600000; // Convert milliseconds to hours

    var totalRating = 0;
    var totalOrdersWithRatings = 0;

    var satisfiedCount = 0;
    var notSatisfiedCount = 0;

    branchData.orders.forEach(function (order) {
        if (order.order_ratings && order.order_ratings.length > 0) {
            totalRating += order.order_ratings[0].rating;  // Assuming each order has only one rating for simplicity
            totalOrdersWithRatings++;
        }
    });

    var avgRating = totalOrdersWithRatings > 0 ? totalRating / totalOrdersWithRatings : 0;


    // Set the computed earnings to the div
    document.getElementById('earningsDisplay').textContent = earnings.toFixed(2);
    document.getElementById('orderCountDisplay').textContent = orderCount;
    document.getElementById('visitorCountDisplay').textContent = visitorCount;

    // Set the working hours and total hours to the divs
    document.getElementById('workingTimeDisplay').textContent = workStatus.start_time.slice(0, -3) + " - " + workStatus.end_time.slice(0, -3);  // Display in HH:MM format
    var hours = Math.floor(totalHours);
    var minutes = Math.round((totalHours - hours) * 60); // Convert decimal to minutes: roughly 59 minutes

    // Now concatenate the string and display it
    document.getElementById('totalHoursDisplay').textContent = hours + " {{ __('locale.branch_dashboard.hours') }} " + minutes + " {{ __('locale.branch_dashboard.minutes') }}";
    document.getElementById('avgRatingDisplay').textContent = avgRating.toFixed(1);  // Display the rating with one decimal point
    document.getElementById('countRatingDisplay').textContent = totalOrdersWithRatings.toFixed(0) + " {{ __('locale.branch_dashboard.of_rating') }}";  // Display the rating with one decimal point


</script>
<script>
    var branchData = @json($branch);

    // An array to keep count of orders for each month
    var monthlyOrders = new Array(12).fill(0);

    // An array to keep count of earnings for each month
    var monthlyEarnings = new Array(12).fill(0);

    branchData.orders.forEach(function (order) {
        var orderDate = new Date(order.order_date);
        var month = orderDate.getMonth();  // This will return a number between 0 and 11 (January is 0, December is 11)
        monthlyOrders[month]++;
        monthlyEarnings[month] += order.grand_total;
    });

    // An array to keep count of visitors for each month
    var monthlyVisitors = new Array(12).fill(0);

    // Assuming each visitor has a 'created_at' property indicating the date of the visit
    branchData.branch_visitors.forEach(function (visitor) {
        var visitDate = new Date(visitor.created_at);
        var month = visitDate.getMonth();  // This will return a number between 0 and 11 (January is 0, December is 11)
        monthlyVisitors[month]++;
    });


    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('chart1').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: '# of Orders',
                    data: monthlyOrders,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctx2 = document.getElementById('chart2').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: '# of Visitors',
                    data: monthlyVisitors,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });


    var ctx3 = document.getElementById('chart3').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',  // Using line chart for earnings to show the trend
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Earnings',
                data: monthlyEarnings,
                backgroundColor: 'rgba(54,162,235,0.4)',
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Include a currency sign in the y-axis ticks
                        callback: function (value, index, values) {
                            return '$' + value;
                        }
                    }
                }
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem) {
                        return '$' + Number(tooltipItem.yLabel).toFixed(2);
                    }
                }
            }
        }
    });


    branchData.orders.forEach(function (order) {
        if (order.order_ratings && order.order_ratings.length > 0) {
            var rating = order.order_ratings[0].rating;

            if (rating > 3) {
                satisfiedCount++;
            } else {
                notSatisfiedCount++;
            }
        } else {
            // Handle orders without ratings if necessary
            // For now, we're ignoring them, but you can choose to count them in notSatisfiedCount or any other logic.
        }
    });

    var ctxSatisfaction = document.getElementById('satisfactionChart').getContext('2d');
    new Chart(ctxSatisfaction, {
        type: 'pie',
        data: {
            labels: ['Satisfied', 'Not Satisfied'],
            datasets: [{
                data: [satisfiedCount, notSatisfiedCount],
                backgroundColor: [
                    'rgba(54,162,235,0.5)', // Color for Satisfied
                    'rgba(255,99,132,0.5)' // Color for Not Satisfied
                ],
                borderColor: [
                    'rgb(54, 162, 235)', // Color for Satisfied
                    'rgba(255, 99, 132, 1)'  // Color for Not Satisfied
                ],
                borderWidth: 1
            }]
        }
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var gridOptions = {
            columnDefs: [
                {headerName: 'Employee Name', field: 'name', flex: 1},
                {headerName: 'Orders Completed', field: 'orderCount', flex: 1},
                // Add more fields if necessary
            ],

            rowData: @json($topEmployees->map(function ($employee) {
            return [
                'name' => $employee->name,
                'orderCount' => count($employee->employee_orders)
                // Add more data if necessary
            ];
        })),
            domLayout: 'autoHeight'
        };

        // Set up the ag-grid
        var eGridDiv = document.querySelector('#employeeGrid');
        new agGrid.Grid(eGridDiv, gridOptions);
    });
</script>
