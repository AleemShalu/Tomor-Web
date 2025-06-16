<x-app-layout>
    <div class=" px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Welcome banner -->
        {{--        <x-dashboard.welcome-banner/>--}}

        <!-- Cards -->
        <div>
            <!-- Filter -->
            {{--            <div class="bg-white py-5 mb-5 rounded-md  shadow-md">--}}

            {{--                <div class="flex justify-between items-center">--}}
            {{--                    <div class="flex font-bold text-2xl ml-4">--}}
            {{--                        <div>--}}
            {{--                            <i data-lucide="layout-dashboard" class="mt-3"></i>--}}
            {{--                        </div>--}}
            {{--                        <div class="mt-2 ml-3">--}}
            {{--                            {{__('locale.dashboard.dashboard')}}--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class="grid grid-cols-4 gap-x-3 font-inter">
                <!-- Card 1 -->
                <div class="card-block">
                    <div
                            class="bg-white shadow-md row align-items-center rounded-md hover:bg-gray-100 transition duration-300 ease-in-out">
                        <div class="px-4 pt-3">
                            <div class="col-8">
                             <span class="flex text-xl">
                                    <h4 class="font-bold startCounting" data-start-number="{{$earningsData}}">0</h4>&nbsp;SAR
                             </span>
                                <h6 class="text-muted m-b-0">{{__('locale.dashboard.all_earnings')}}</h6>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-chart-bar fa-2x text-yellow-300"></i>
                            </div>
                        </div>
                        <div class="bg-yellow-300 mt-2 pt-2 w-full">

                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card-block">
                    <div
                            class="bg-white shadow-md row align-items-center rounded-md hover:bg-gray-100 transition duration-300 ease-in-out">

                        <div class="px-4 pt-3">
                            <div class="col-8">
                             <span class="flex text-xl">
                            <h4 class="font-bold startCounting" data-start-number="{{$visitorsCount}}">0</h4>
                              </span>
                                <h6 class="text-muted m-b-0">{{__('locale.dashboard.visitors')}}</h6>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-users fa-2x text-green-500"></i>
                            </div>
                        </div>
                        <div class="bg-green-500 mt-2 pt-2 w-full">

                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card-block">
                    <div
                            class="bg-white shadow-md row align-items-center rounded-md hover:bg-gray-100 transition duration-300 ease-in-out">

                        <div class="px-4 pt-3">
                            <div class="col-8">
                             <span class="flex text-xl">
                            <h4 class="font-bold startCounting" data-start-number="{{$ordersCompletedData}}">0</h4>
                        </span>
                                <h6 class="text-muted m-b-0">{{__('locale.dashboard.orders_completed')}}</h6>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-check-circle fa-2x text-blue-500"></i>
                            </div>
                        </div>
                        <div class="bg-blue-500 mt-2 pt-2 w-full">

                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card-block">
                    <div
                            class="bg-white shadow-md row align-items-center rounded-md hover:bg-gray-100 transition duration-300 ease-in-out">

                        <div class="px-4 pt-3">
                            <div class="col-8">
                             <span class="flex text-xl">
                            <h4 class="font-bold startCounting">{{$ratingAvgData}}</h4>
                        </span>
                                <h6 class="text-muted m-b-0">{{__('locale.dashboard.rating')}}</h6>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fa-solid fa-star fa-2x text-red-500"></i>
                            </div>
                        </div>
                        <div class="bg-red-500 mt-2 pt-2 w-full">

                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 mt-5 rounded-md mt-5  gap-x-3">
                <div id="chart-1" class="p-3 bg-white shadow-md  rounded-md">
                    <div class="font-medium text-xl">
                        {{__('locale.dashboard.total_grand_amount')}}
                    </div>
                    <canvas id="chart_1"></canvas>
                </div>
                <div id="chart-2" class="p-3 bg-white shadow-md  rounded-md">
                    <div class="font-medium text-xl">
                        {{__('locale.dashboard.orders')}}
                    </div>
                    <canvas id="chart_2"></canvas>
                </div>
            </div>

            <div name="rating" class="grid grid-cols-3 mt-5 gap-x-3">
                <div class="p-3 bg-white shadow-md rounded-md">
                    <div class="font-medium text-xl">
                        {{ __('locale.dashboard.rating') }}
                    </div>
                    <canvas id="chart_3"></canvas>
                </div>
                <div class="p-3 bg-white shadow-md rounded-md">
                    <div class="font-medium text-xl">
                        {{ __('locale.dashboard.satisfaction_metric') }}
                    </div>
                    <div class=" flex justify-center items-center">
                        <canvas id="chart_4" class="max-h-72"></canvas>
                    </div>
                    <hr class="mt-3">
                    <div class="gap-x-5 px-5 py-3 mb-4 justify-center">
                        <div class="ml-5 float-left">{{ __('locale.dashboard.satisfied') }}: <span
                                    id="satisfiedCount">0</span></div>
                        <div class="mr-5 float-right">{{ __('locale.dashboard.not_satisfied') }}: <span
                                    id="notSatisfiedCount">0</span></div>
                    </div>
                </div>
                <div class="p-3 bg-white shadow-md rounded-md">
                    <div class="font-medium text-xl">
                        {{ __('locale.dashboard.employee_statistics') }}
                    </div>
                    <div>
                        <canvas id="chart_5"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    // JavaScript function to start counting from the data-start-number attribute
    function startCounting() {
        const numberElements = document.querySelectorAll('[data-start-number]');

        numberElements.forEach((element) => {
            const startNumber = parseInt(element.getAttribute('data-start-number'), 10) || 0;
            let currentNumber = 0;
            const increment = Math.ceil(startNumber / 100); // Change the increment speed as needed

            function updateNumber() {
                if (currentNumber < startNumber) {
                    currentNumber += increment;
                    element.textContent = currentNumber;
                    requestAnimationFrame(updateNumber);
                } else {
                    element.textContent = startNumber;
                }
            }

            updateNumber();
        });
    }

    // Call the function to start counting for all elements with the data-start-number attribute
    startCounting();
</script>
<script>
    var chart1Data = @json($chart1Data);
    var chart2Data = @json($chart2Data);

    // Extract month labels and total amounts for chart_1
    var monthLabels1 = chart1Data.map(order => order.month);
    var grandTotalAmounts1 = chart1Data.map(order => order.total);

    // Extract month labels and order counts for chart_2
    var monthLabels2 = chart2Data.map(order => order.month);
    var orderCounts = chart2Data.map(order => order.order_count);

    var chart1Code = document.getElementById('chart_1').getContext('2d');
    var chart1 = new Chart(chart1Code, {
        type: 'line',
        data: {
            labels: monthLabels1,
            datasets: [{
                label: 'Total Grand Amount',
                data: grandTotalAmounts1,
                backgroundColor: 'rgb(198,253,13)',
                borderColor: 'rgb(22,26,124)',
                borderWidth: 3
            }]
        },
        options: {
            // Customize chart options for chart_1 here
        }
    });

    // JavaScript code to create chart_2 (bar chart)
    var chart2Code = document.getElementById('chart_2').getContext('2d');
    var chart2 = new Chart(chart2Code, {
        type: 'bar',
        data: {
            labels: monthLabels2,
            datasets: [{
                label: 'Order Count',
                data: orderCounts,
                backgroundColor: 'rgb(107,110,170)',
                borderColor: 'rgba(22,26,124,0.7)',
                borderWidth: 2
            }]
        },
        options: {
            // Customize chart options for chart_2 here
        }
    });
</script>
<script>
    var chart3Data = @json($chart3Data);

    // Initialize an array to store rating counts
    var ratingCounts = [0, 0, 0, 0, 0]; // Assuming ratings can be from 1 to 5

    // Count the occurrences of each rating
    chart3Data.forEach(function (data) {
        var rating = data.rating;
        if (rating >= 1 && rating <= 5) {
            ratingCounts[rating - 1]++;
        }
    });

    var ctx = document.getElementById('chart_3').getContext('2d');
    var chart_3 = new Chart(ctx, {
        type: 'bar', // You can choose a different chart type if needed
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Rating Counts',
                data: ratingCounts,
                backgroundColor: 'rgba(22,26,124,0.61)',
                borderColor: 'rgb(2,3,30)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1 // Step size for the y-axis
                }
            }
        }
    });
</script>
<script>
    var chart3Data = @json($chart3Data);

    // Initialize variables to count satisfaction and dissatisfaction
    var satisfactionCount = 0;
    var dissatisfactionCount = 0;

    // Count the occurrences of satisfaction and dissatisfaction
    chart3Data.forEach(function (data) {
        var rating = data.rating;
        if (rating > 3) {
            satisfactionCount++;
        } else {
            dissatisfactionCount++;
        }
    });

    var ctx = document.getElementById('chart_4').getContext('2d');
    var chart_4 = new Chart(ctx, {
        type: 'pie', // Pie chart
        data: {
            labels: ['Satisfied', 'Not Satisfied'],
            datasets: [{
                data: [satisfactionCount, dissatisfactionCount],
                backgroundColor: [
                    'rgba(198,253,13,0.7)', // Border color for satisfied
                    'rgba(22,26,124,0.7)'  // Border color for not satisfied
                ],
                borderColor: [
                    'rgb(169,213,14)', // Border color for satisfied
                    'rgba(22,26,124,0.7)'  // Border color for not satisfied
                ],
                borderWidth: 1
            }]
        },
        options: {
            // Customize chart options here if needed
        }
    });

    // Update the count values
    document.getElementById('satisfiedCount').textContent = satisfactionCount;
    document.getElementById('notSatisfiedCount').textContent = dissatisfactionCount;
</script>

<script>
    var employeesData = @json($chart5Data);

    // Extract employee roles and their counts
    var roles = [];
    var roleCounts = [];

    employeesData.forEach(function (employee) {
        employee.owner_stores.forEach(function (store) {
            store.employees.forEach(function (employee) {
                employee.employee_roles.forEach(function (role) {
                    var roleName = role.name;
                    if (!roles.includes(roleName)) {
                        roles.push(roleName);
                        roleCounts.push(1);
                    } else {
                        var index = roles.indexOf(roleName);
                        roleCounts[index]++;
                    }
                });
            });
        });
    });

    var ctx = document.getElementById('chart_5').getContext('2d');
    var chart_5 = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [roles[0], roles[1]],
            datasets: [{
                // label: roles,
                data: roleCounts,
                backgroundColor: [
                    'rgba(198,253,13,0.7)',
                    'rgba(22,26,124,0.7)'
                ],
                borderColor: [
                    'rgb(169,213,14)',
                    'rgba(22,26,124,0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            // maintainAspectRatio: false,
            // scales: {
            //     x: {
            //         beginAtZero: true,
            //     },
            //     y: {
            //         beginAtZero: true,
            //     },
            // },
        }
    });


</script>
<script>
    $(document).ready(function () {
        $("input[name='datetimes']").daterangepicker(
            {},
            function (start, end, label) {
                let startDate = start.format("YYYY-MM-DD").toString();
                let endDate = end.format("YYYY-MM-DD").toString();

                document.getElementById("startDate").innerHTML =
                    "Start date: " + startDate;
                document.getElementById("endDate").innerHTML = "End date: " + endDate;

            }
        );
    });
</script>
