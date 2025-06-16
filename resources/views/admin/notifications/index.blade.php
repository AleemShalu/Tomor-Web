<x-app-admin-layout>
    <div class=" lg:w-8/12 px-7 mx-auto mt-6">
        <div class="bg-white py-6 px-4 font-bold text-xl rounded">
            <div>
                <i class="fa-solid fa-comments px-4"></i>
                {{__('admin.notification_management.title')}}
            </div>
        </div>
        <div class="bg-white mt-4 py-6 px-4 w-full overflow-x-auto">
            <!-- Button to create a new notification -->
            <a href="{{route('admin.notifications.create')}}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                {{__('admin.notification_management.create_new_notification')}}
            </a>


            <div class="hidden">
                <div class="mt-4 w-1/3">
                    <label class="block mb-1" for="notificationType">Select Notification Type:</label>
                    <select name="notificationType" id="notificationType" class="border p-2 rounded w-full mb-4">
                        <option value="offer">Special Offers</option>
                        <option value="discount">Discounts</option>
                        <option value="update">Updates</option>
                        <!-- Add more options here -->
                    </select>

                    <label class="block mb-1" for="notificationPlatform">Select Notification Platform:</label>
                    <select name="notificationPlatform" id="notificationPlatform"
                            class="border p-2 rounded w-full mb-4">
                        <option value="web">Web</option>
                        <option value="mobile">Mobile</option>
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                        <!-- Add more options here -->
                    </select>
                </div>

            </div>
            <!-- Display notifications in a table -->
            <table class="w-full text-center border-gray-300 mt-3">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        {{__('admin.notification_management.notification_type')}}
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        {{__('admin.notification_management.notification_platform')}}
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        {{__('admin.notification_management.target_audience')}}
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        {{__('admin.notification_management.notification_title')}}
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        {{__('admin.notification_management.date')}}
                    </th>
                </tr>
                </thead>
                <tbody>
                @if(count($notificationsGroup) > 0)
                    @foreach($notificationsGroup as $group)
                        <tr class="border-t border-gray-200">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{$group->notification_type}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{$group->platform_type}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{$group->users_type}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{$group->notification_title_ar}} | {{$group->notification_title_en}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{$group->created_at}}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm text-gray-700 text-center">
                            No notifications found.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-admin-layout>
