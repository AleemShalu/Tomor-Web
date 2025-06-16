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
                <!-- Your dashboard content goes here -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card: Total Feedbacks -->
                    <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.total_feedbacks')}}
                        </h2>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-500 mr-2" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                            </svg>
                            <span class="text-2xl font-semibold">{{ $feedbacksCounts + $feedbacks->count() }}</span>
                        </div>
                    </div>
                    <!-- Card: Open Complaints -->
                    <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.complaints')}}
                        </h2>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-500 mr-2" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                            </svg>
                            <span class="text-2xl font-semibold">{{$complaintsCounts}}</span>
                        </div>
                    </div>
                    <!-- Card: Average Rating -->
                    <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.average_rating')}}
                        </h2>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-2" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M12 2c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9Zm0 16.2c-3.94 0-7.2-3.26-7.2-7.2 0-3.94 3.26-7.2 7.2-7.2 3.94 0 7.2 3.26 7.2 7.2 0 3.94-3.26 7.2-7.2 7.2Zm.45-11.77a1.75 1.75 0 1 1 0 3.5 1.75 1.75 0 0 1 0-3.5Zm-.45 1.75a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"></path>
                            </svg>
                            <span class="text-2xl font-semibold">{{ number_format($feedbacks->avg('rating'), 1) }}</span>
                        </div>
                    </div>

                    <!-- Card: Positive Feedbacks -->
                    <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.positive_feedbacks')}}
                        </h2>
                        <div class="flex items-center">
                            <i class="fa-regular fa-face-smile fa-lg pr-4"></i>
                            <span class="text-2xl font-semibold">{{ $feedbacks->where('rating', '>', 3)->where('rating', '<=', 5)->count() }}</span>
                        </div>
                    </div>
                    <!-- Card: Negative Feedbacks -->
                    <div class="bg-gray-50 p-4 rounded shadow hover:shadow-md hover:bg-gray-100">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.negative_feedbacks')}}
                        </h2>
                        <div class="flex items-center">
                            <i class="fa-regular fa-face-frown fa-lg pr-4"></i>
                            <span class="text-2xl font-semibold">{{ $feedbacks->where('rating', '>=', 0)->where('rating', '<', 3)->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="hidden bg-white p-4 rounded shadow mt-6">
                    <h2 class="text-lg font-semibold mb-4">
                        {{__('admin.support.feedback_and_complaint.feedback_trends')}}
                    </h2>
                    <canvas id="feedbackTrendsChart" width="400" height="200"></canvas>
                </div>

                <div class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Chart 1: Feedback and Complaints Chart -->
                    <div class="bg-white p-4 rounded shadow">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.feedback_and_complaints')}}
                        </h2>
                        <canvas id="feedbackComplaintsChart" width="200"></canvas>
                    </div>


                    <!-- Chart 4: Resolution Time Chart -->
                    <div class="bg-white p-4 rounded shadow">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.resolution_time')}}
                        </h2>
                        <canvas id="resolutionTimeChart" width="200"></canvas>
                    </div>

                    <!-- Chart 3: Feedback Types Chart -->
                    <div class="bg-white p-4 rounded shadow">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.feedback_types')}}
                        </h2>
                        <canvas id="feedbackTypesChart" width="200"></canvas>
                    </div>
                    <!-- Chart 2: User Satisfaction Chart -->
                    <div class="bg-white p-4 rounded shadow">
                        <h2 class="text-lg font-semibold mb-4">
                            {{__('admin.support.feedback_and_complaint.user_satisfaction')}}
                        </h2>
                        <canvas id="userSatisfactionChart" width="200"></canvas>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-admin-layout>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sample data for the chart (you can replace this with your actual data)
        const feedbackTrendsData = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [{
                label: "Feedbacks",
                data: [20, 25, 18, 30, 15, 22],
                borderColor: "rgb(54, 162, 235)",
                backgroundColor: "rgba(54, 162, 235, 0.2)",
                tension: 0.4,
            }],
        };

        // Configure and create the chart
        const feedbackTrendsCtx = document.getElementById("feedbackTrendsChart").getContext("2d");
        new Chart(feedbackTrendsCtx, {
            type: "line",
            data: feedbackTrendsData,
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
    // Chart.js code for Feedback and Complaints Chart
    var feedbackComplaintsChart = new Chart(document.getElementById('feedbackComplaintsChart'), {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Feedbacks',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Complaints',
                data: [5, 8, 1, 4, 7, 2],
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
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

    // Chart.js code for User Satisfaction Chart
    var userSatisfactionChart = new Chart(document.getElementById('userSatisfactionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Satisfied', 'Neutral', 'Dissatisfied'],
            datasets: [{
                data: [70, 20, 10],
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 206, 86, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        }
    });

    // Chart.js code for Feedback Types Chart
    var feedbackTypesChart = new Chart(document.getElementById('feedbackTypesChart'), {
        type: 'pie',
        data: {
            labels: ['Feature Request', 'Bug Report', 'General Feedback'],
            datasets: [{
                data: [40, 30, 30],
                backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                borderWidth: 1
            }]
        }
    });

    // Chart.js code for Resolution Time Chart
    var resolutionTimeChart = new Chart(document.getElementById('resolutionTimeChart'), {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Resolution Time',
                data: [10, 8, 12, 7, 9, 11],
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false
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
</script>
