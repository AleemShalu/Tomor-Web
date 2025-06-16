<x-app-layout>
    <div class="container mx-auto py-8 px-4 rounded">
        <button onclick="goBack()" type="button"
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
            Go Back
        </button>
        <div class="px-6 py-8 bg-white shadow-lg rounded-lg">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-semibold text-gray-800">Employee Details</h2>
                <div class="bg-blue-500 text-white px-3 py-1 rounded-lg">
                    <span class="text-sm">Active</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-1">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">Employee Name:</p>
                        <p class="text-lg font-medium text-gray-800">{{ $employee->name }}</p>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">Email:</p>
                        <p class="text-lg font-medium text-gray-800">{{ $employee->email }}</p>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">Contact Number:</p>
                        <p class="text-lg font-medium text-gray-800">{{ $employee->contact_no }}</p>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">Position:</p>
                        <p class="text-lg font-medium text-gray-800">{{ $employee->employee_roles[0]->name_en }}</p>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">Branch:</p>
                        <p class="text-lg font-medium text-gray-800">{{ $employee->employee_branches[0]->name_en }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                {{--                <div class="bg-gray-100 rounded-lg p-4">--}}
                {{--                    <p class="text-sm text-gray-600 mb-2">Performance Work:</p>--}}
                {{--                    <p class="text-lg font-medium text-gray-800">Insert performance work data here</p>--}}
                {{--                </div>--}}
                <div class="bg-gray-100 rounded-lg p-4 mt-4">
                    <p class="text-sm text-gray-600 mb-2">Order Count:</p>
                    <p class="text-lg font-medium text-gray-800">{{ $employee->employee_orders()->count()}}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 mt-4">
                    <p class="text-sm text-gray-600 mb-2">Time Worked:</p>
                    <p class="text-lg font-medium text-gray-800">{{ $employee->get_total_hours_last_three_months()}}</p>
                </div>
            </div>
        </div>


    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function toggleRegisterButton() {
            var selectElement = document.getElementById('store_branch_id');
            var registerButton = document.getElementById('registerButton');

            if (selectElement.value === '') {
                registerButton.disabled = true;
            } else {
                registerButton.disabled = false;
            }
        }
    </script>
</x-app-layout>
