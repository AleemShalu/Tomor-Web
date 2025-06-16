<x-app-layout>

    @php
        $positiveCount = 0;
        $negativeCount = 0;
        $totalRating = 0;
    @endphp

    @foreach($ratings as $rating)
        @if ($rating->rating >= 3)
            @php
                $positiveCount++;
            @endphp
        @else
            @php
                $negativeCount++;
            @endphp
        @endif

        @php
            $totalRating += $rating->rating;
        @endphp
    @endforeach

    @php
        $averageRating = count($ratings) > 0 ? $totalRating / count($ratings) : 0;
    @endphp

    <div class="bg-white max-w-7xl mx-auto mt-5 p-5 rounded-md">
        <div for="title_page" class="pl-4 flex gap-x-3">
            <div class="text-2xl font-bold">
                {{ __('locale.rating.title') }}
            </div>
            <div>
                <i class="fa-solid fa-star fa-2xl"></i>
            </div>
        </div>
        <div for="content_page">
            <div class="bg-white mt-4 py-6 px-4 w-full overflow-x-auto">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                        <li class="mr-2">
                            <a href="{{route('rating.index')}}"
                               class="inline-flex items-center justify-center p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group"
                               aria-current="page">
                                <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-500" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                    <path
                                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                </svg>
                                {{ __('locale.nav_rating.summary') }}
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="{{route('feedback.index')}}"
                               class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                <svg
                                        class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 20 20">
                                    <path
                                            d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z"/>
                                </svg>
                                {{ __('locale.nav_rating.feedback') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="p-3">
                    <!-- Your dashboard content goes here -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Card: Total Feedbacks -->
                        <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                            <h2 class="text-lg font-semibold mb-4">{{ __('locale.rating.total_feedbacks') }}</h2>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500 mr-2" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                </svg>
                                <span class="text-2xl font-semibold">{{ $totalRating}}</span>
                            </div>
                        </div>
                        <!-- Card: Average Rating -->
                        <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                            <h2 class="text-lg font-semibold mb-4">{{ __('locale.rating.average_rating') }}</h2>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-2" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path
                                            d="M12 2c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9Zm0 16.2c-3.94 0-7.2-3.26-7.2-7.2 0-3.94 3.26-7.2 7.2-7.2 3.94 0 7.2 3.26 7.2 7.2 0 3.94-3.26 7.2-7.2 7.2Zm.45-11.77a1.75 1.75 0 1 1 0 3.5 1.75 1.75 0 0 1 0-3.5Zm-.45 1.75a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"></path>
                                </svg>
                                <span class="text-2xl font-semibold">{{ number_format($averageRating, 1) }}</span>
                            </div>
                        </div>

                        <!-- Card: Positive Feedbacks -->
                        <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                            <h2 class="text-lg font-semibold mb-4">{{ __('locale.rating.positive_feedbacks') }}</h2>
                            <div class="flex items-center">
                                <i class="fa-regular fa-face-smile fa-lg pr-4"></i>
                                <span class="text-2xl font-semibold">{{ $positiveCount }}</span>
                            </div>
                        </div>

                        <!-- Card: Negative Feedbacks -->
                        <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                            <h2 class="text-lg font-semibold mb-4">{{ __('locale.rating.negative_feedbacks') }}</h2>
                            <div class="flex items-center">
                                <i class="fa-regular fa-face-frown fa-lg pr-4"></i>
                                <span class="text-2xl font-semibold">{{ $negativeCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded shadow mt-6">
                    <h2 class="text-lg font-semibold mb-4">{{ __('locale.rating.feedback_trends') }}</h2>
                    <canvas id="feedbackTrendsChart" width="400" height="200"></canvas>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
                    <div class="flex bg-white p-4 rounded shadow">
                        <h2 class="text-lg font-semibold mb-4">{{ __('locale.rating.feedback_types') }}</h2>
                        <div class="chart flex justify-center mt-5 items-center" width="100" style="width: 50%">
                            <canvas id="feedbackTypesChart"></canvas>
                        </div>

                    </div>
                    <div class="flex bg-white p-4 rounded shadow">
                        <h2 class="text-lg font-semibold mb-4">{{ __('locale.rating.user_satisfaction') }}</h2>
                        <div class="chart flex justify-center mt-5 items-center" width="100" style="width: 50%">
                            <canvas id="userSatisfactionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</x-app-layout>


<script>
    // Get the ratings data from your PHP code
    const ratingsData = @json($ratings);
    // Initialize an object to store data for the pie chart
    const data = {};

    // Loop through the ratings data to extract feedback types and counts
    ratingsData.forEach((rating) => {
        const feedbackType = rating.order_rating_type.en_name; // Change to your desired feedback type field
        if (!data[feedbackType]) {
            data[feedbackType] = 1;
        } else {
            data[feedbackType]++;
        }
    });

    // Create an array of labels and an array of data values
    const labels = Object.keys(data);
    const dataArray = Object.values(data);

    // Create the pie chart using Chart.js
    const ctx = document.getElementById('feedbackTypesChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: dataArray,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)', // Customize the colors as needed
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    // Add more colors if you have more feedback types
                ],
                borderColor: 'white',
                borderWidth: 2,
            }],
        },
        options: {
            responsive: true,
        },
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the ratings data from your PHP code
        const ratingsData = @json($ratingsTrendsChart);

        // Extract labels (months) and data (average ratings) from the PHP data
        const labels = ratingsData.map(item => item.month);
        const data = ratingsData.map(item => item.average_rating);

        // Create the "Feedback Trends" chart using Chart.js
        const feedbackTrendsCtx = document.getElementById("feedbackTrendsChart").getContext("2d");
        new Chart(feedbackTrendsCtx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Average Rating",
                    data: data,
                    borderColor: "rgb(54, 162, 235)",
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    tension: 0.4,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                        },
                    },
                },
            },
        });
    });
</script>
<script>
    // Get the positive and negative counts from your PHP code
    const positiveCount = {{ $positiveCount }};
    const negativeCount = {{ $negativeCount }};

    // Create the "User Satisfaction" pie chart using Chart.js
    const ctxUserSatisfaction = document.getElementById('userSatisfactionChart').getContext('2d');
    new Chart(ctxUserSatisfaction, {
        type: 'pie',
        data: {
            labels: ['Positive', 'Negative'],
            datasets: [{
                data: [positiveCount, negativeCount],
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)",
                        },
                    },
                },
            },
        },
    });
</script>


<script>
    // Data based on your provided PHP code
    var positiveCount = {{ $positiveCount }};
    var negativeCount = {{ $negativeCount }};
    var averageRating = {{ $averageRating }};

    // Create a chart using Chart.js
    var ctx = document.getElementById('userSatisfactionChart').getContext('2d');

    var userSatisfactionChart = new Chart(ctx, {
        type: 'doughnut', // Use a doughnut chart for user satisfaction
        data: {
            labels: ['Positive', 'Negative'],
            datasets: [{
                data: [positiveCount, negativeCount],
                backgroundColor: ['#36A2EB', '#FF6384'], // Colors for positive and negative sections
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            legend: {
                display: true,
            },
            title: {
                display: true,
                text: 'User Satisfaction',
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label = data.labels[tooltipItem.index] || '';
                        if (label) {
                            label += ': ';
                        }
                        label += data.datasets[0].data[tooltipItem.index];
                        return label;
                    },
                },
            },
        },
    });
</script>


