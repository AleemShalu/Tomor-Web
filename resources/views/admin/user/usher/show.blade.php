<x-app-admin-layout>
    <div class="container mx-auto py-4">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.usher') }}" class="text-blue-500 underline">
                {{ __('admin.common.back') }}
            </a>
        </div>

        <h1 class="text-xl font-bold mb-4">
            {{__('admin.user_management.usher_list.usher_details')}}
        </h1>

        <div class="border p-4 rounded-md bg-white">
            <h2 class="text-lg font-semibold">{{__('admin.user_management.users.user_name')}}:</h2>
            <p>{{ $usher->name }}</p>

            <h2 class="text-lg font-semibold mt-4">{{__('admin.user_management.users.user_email')}}:</h2>
            <p>{{ $usher->email }}</p>

            <h2 class="text-lg font-semibold mt-4">{{__('admin.user_management.users.user_contact_no')}}:</h2>
            <p>{{ $usher->phone_number }}</p>

            <h2 class="text-lg font-semibold mt-4">
                {{__('admin.user_management.users.usher_code')}}:
            </h2>
            <p>{{ $usher->code_usher }}</p>

            <h2 class="text-lg font-semibold mt-4">
                {{__('admin.user_management.usher_list.usher_clients_count')}}:</h2>
            <p>{{ $usher->clients->count() }}</p>


            <h2 class="text-lg font-semibold mt-4">
                {{__('admin.user_management.users.user_created_at')}}:
            </h2>
            <p>{{ $usher->created_at }}</p>

        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-lg font-semibold mt-4">
                {{__('admin.user_management.usher_list.usher_clients')}}
            </h2>

            @if($usher->clients->isEmpty())
                <!-- Display message when there are no clients -->
                <p class="text-gray-500">{{ __('admin.user_management.usher_list.no_clients_message') }}</p>
            @else
                <!-- Display the table if there are clients -->
                <table class="min-w-full bg-white border divide-y divide-gray-300 overflow-hidden">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Phone</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                    @foreach($usher->clients as $client)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap">{{ $client->user->id }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap">{{ $client->user->name }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap">{{ $client->user->email }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap">{{ $client->user->contact_no }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>


    </div>
</x-app-admin-layout>
