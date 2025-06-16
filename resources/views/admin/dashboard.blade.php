<x-app-admin-layout>

    <div class="border-b border-gray-200 dark:border-gray-700 bg-white">
        <div class="text-2xl font-bold py-3 px-3 ml-3">
            {{ __('admin.dashboard.admin_dashboard') }}
        </div>
        <div class="py-3 px-3 ml-3">
            <label for="timeFilter" class="mr-2 text-gray-600">{{ __('admin.dashboard.filter_by') }}</label>
            <select id="timeFilter" class="border rounded-md p-2">
                <option value="week">{{ __('admin.dashboard.one_week') }}</option>
                <option value="month">{{ __('admin.dashboard.one_month') }}</option>
                <option value="3months">{{ __('admin.dashboard.three_months') }}</option>
                <option value="6months">{{ __('admin.dashboard.six_months') }}</option>
                <option value="all" selected>{{ __('admin.dashboard.all_time') }}</option>
            </select>
        </div>

    </div>

    <div class="max-w-6xl p-4 bg-white m-4 mx-auto">
        <div class="m-3">
            <div class="p-3">

                <div class="text-2xl font-bold">
                    {{ __('admin.dashboard.overview') }}
                </div>
                <div class="text-gray-500">
                    {{ __('admin.dashboard.summary') }}
                </div>

            </div>
            <div class="grid grid-cols-4 gap-x-3">

                <!-- Users -->
                <div
                    class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded-md border border-gray-200 flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i data-lucide="users"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('admin.dashboard.total_users') }}
                    </div>
                    <div id="userCountDisplay" class="text-xl font-bold">
                        {{ $totalUsers }}
                    </div>

                    <!-- Checking the value of $userPercent -->
                    @if($userPercent > 0)
                        <div class="flex mt-4">
                            {{ $userPercent }}% {{ __('admin.dashboard.arrow_up') }}
                        </div>
                    @elseif($userPercent < 0)
                        <div class="flex mt-4 text-red-500">
                            {{ abs($userPercent) }}% {{ __('admin.dashboard.arrow_down') }}
                        </div>
                    @else
                        <div class="flex mt-4">
                            {{ __('admin.dashboard.no_change') }}
                        </div>
                    @endif
                </div>

                <!-- Stores -->
                <div
                    class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded-md border border-gray-200 flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i data-lucide="store"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('admin.dashboard.total_stores') }}
                    </div>
                    <div id="storeCountDisplay" class="text-xl font-bold">
                        {{ $totalStores }}
                    </div>
                </div>

                <!-- Service Cost -->
                <div
                    class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded-md border border-gray-200 flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i data-lucide="dollar-sign"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('admin.dashboard.service_cost') }}
                    </div>
                    <div id="serviceCostDisplay" class="text-xl font-bold">
                        {{ number_format($serviceCostThisWeek, 0, '.', '') }} SAR

                    </div>
                    <!-- Check the value of $earningsPercent -->
                    @if($earningsPercent > 0)
                        <div class="flex mt-4">
                            {{ __('admin.dashboard.increased_by') }} {{ $earningsPercent }}% <i
                                data-lucide="arrow-up"></i>
                        </div>
                    @elseif($earningsPercent < 0)
                        <div class="flex mt-4 text-red-500">
                            {{ __('admin.dashboard.decreased_by') }} {{ abs($earningsPercent) }}% <i
                                data-lucide="arrow-down" class="ml-2"></i>
                        </div>
                    @else
                        <div class="flex mt-4">
                            {{ __('admin.dashboard.no_change_in_earnings') }} <i data-lucide="minus"></i>
                        </div>
                    @endif
                </div>

                <!-- Satisfied Users -->
                <div
                    class="transition duration-300 ease-in-out transform hover:scale-105 bg-gray-100 p-4 rounded-md border border-gray-200 flex flex-col items-center">
                    <div class="w-8 h-8 mb-4 text-center">
                        <i data-lucide="smile"></i>
                    </div>
                    <div class="text-lg font-semibold text-gray-600">
                        {{ __('admin.dashboard.satisfied_users') }}
                    </div>
                    <div id="satisfiedUsersDisplay" class="text-xl font-bold">
                        {{ $satisfiedUsersPercentage }}%
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-2 mt-4 gap-x-3">
                <!-- Chart 1: User Growth -->
                <div style="height: 400px" class="bg-gray-100 rounded-md border border-gray-200 p-8">
                    <div class="text-2xl font-bold flex items-center">
                        <i data-lucide="trending-up" class="mr-2"></i> <!-- Icon for Growth -->
                        {{ __('admin.dashboard.user_growth') }}
                    </div>
                    <canvas id="chart1"></canvas>
                </div>

                <!-- Chart 2: Store Growth -->
                <div style="height: 400px" class="bg-gray-100 rounded-md border border-gray-200 p-8">
                    <div class="text-2xl font-bold flex items-center">
                        <i data-lucide="store" class="mr-2"></i> <!-- Icon for Store -->
                        {{ __('admin.dashboard.store_growth') }}
                    </div>
                    <canvas id="chart2"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <!-- Pie Chart: Service Cost Distribution -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-200">
                    <div class="text-2xl font-bold mb-4 flex items-center">
                        <i data-lucide="pie-chart" class="mr-2"></i> <!-- Icon for Pie Chart -->
                        {{ __('admin.dashboard.service_cost_distribution') }}
                    </div>
                    <div style="height:400px;">
                        <canvas id="serviceCostChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart: User Satisfaction -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-200">
                    <div class="text-2xl font-bold mb-4 flex items-center">
                        <i data-lucide="thumbs-up" class="mr-2"></i> <!-- Icon for Satisfaction -->
                        {{ __('admin.dashboard.user_satisfaction_rates') }}
                    </div>
                    <div style="height:400px;">
                        <canvas id="satisfactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-admin-layout>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        let userGrowthChart;
        let storeGrowthChart;
        let serviceCostChart;
        let satisfactionChart;

        let timeFilterElement = document.getElementById('timeFilter');

        function updateChartData(timeframe) {
            fetch(`/admin/dashboard/user-growth?timeframe=${timeframe}`)
                .then(response => response.json())
                .then(data => {
                    if (userGrowthChart) {
                        userGrowthChart.destroy();
                    }

                    const ctx = document.getElementById('chart1').getContext('2d');
                    userGrowthChart = new Chart(ctx, {
                        type: 'line',
                        data: data,
                        options: getChartOptions()
                    });
                })
                .catch(error => {
                    console.error('Error fetching user growth data:', error);
                });
        }

        function updateStoreChartData(timeframe) {
            fetch(`/admin/dashboard/store-growth?timeframe=${timeframe}`)
                .then(response => response.json())
                .then(data => {
                    if (storeGrowthChart) {
                        storeGrowthChart.destroy();
                    }

                    const ctx = document.getElementById('chart2').getContext('2d');
                    storeGrowthChart = new Chart(ctx, {
                        type: 'line',
                        data: data,
                        options: getChartOptions()
                    });
                })
                .catch(error => {
                    console.error('Error fetching store growth data:', error);
                });
        }

        function updateServiceCostChartData(timeframe) {
            fetch(`/admin/dashboard/service-cost-distribution?timeframe=${timeframe}`)
                .then(response => response.json())
                .then(data => {
                    if (serviceCostChart) {
                        serviceCostChart.destroy();
                    }

                    const ctx = document.getElementById('serviceCostChart').getContext('2d');
                    serviceCostChart = new Chart(ctx, {
                        type: 'bar',  // Change the chart type to 'bar'
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.datasets[0].data,
                                backgroundColor: [
                                    'rgba(255,99,132,0.7)',  // Red
                                    'rgba(54,162,235,0.7)', // Blue
                                    'rgba(255,206,86,0.7)',  // Yellow
                                    'rgba(75,192,192,0.7)',  // Green
                                    // Add more colors as needed
                                ],
                                borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    // Add corresponding border colors
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Service Cost Distribution'
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                })
                .catch(error => {
                    console.error('Error fetching service cost data:', error);
                });
        }


        function updateSatisfactionChartData(timeframe) {
            fetch(`/admin/dashboard/platform-rating-distribution?timeframe=${timeframe}`)
                .then(response => response.json())
                .then(data => {
                    if (satisfactionChart) {
                        satisfactionChart.destroy();
                    }

                    const ctx = document.getElementById('satisfactionChart').getContext('2d');
                    satisfactionChart = new Chart(ctx, {
                        type: 'line', // Change type to line
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true
                                },
                                title: {
                                    display: true,
                                    text: 'User Satisfaction Rates for Web and App'
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        min: 1, // set the minimum value
                                        max: 5, // set the maximum value
                                        stepSize: 1, // increment values by 1
                                        precision: 0 // no decimal places
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching platform rating distribution data:', error);
                });
        }

        function getChartOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            };
        }

        timeFilterElement.addEventListener('change', function () {
            const timeframe = timeFilterElement.value;
            updateChartData(timeframe);
            updateStoreChartData(timeframe);
            updateServiceCostChartData(timeframe);
            updateSatisfactionChartData(timeframe);
        });

        // Initial chart load with default value ('all')
        updateChartData('all');
        updateStoreChartData('all');
        updateServiceCostChartData('all');
        updateSatisfactionChartData('all');
    });
</script>
