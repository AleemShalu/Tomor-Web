<x-app-admin-layout>

    <div class="container mx-auto mt-5 px-4">
        <h2 class="text-2xl font-semibold mb-5">
            {{ __('admin.user_management.users.create_new') }}

        </h2>

        <form action="{{ route('admin.usher.store') }}" method="post" class="bg-white p-5 rounded shadow-md">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('admin.user_management.users.user_name')}}
                </label>
                <input type="text" class="form-input w-full p-2 border rounded" id="name" name="name" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('admin.user_management.users.user_email')}}
                </label>
                <input type="email" class="form-input w-full p-2 border rounded" id="email" name="email" required>
            </div>

            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('admin.user_management.users.user_contact_no') }}
                </label>
                <input type="text" class="form-input w-full p-2 border rounded" id="phone_number" name="phone_number"
                       required>
            </div>

            <div class="mb-4">
                <label for="code_usher" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('admin.user_management.users.code_usher') }}
                </label>
                <input type="text" class="form-input w-full p-2 border rounded" id="code_usher" name="code_usher"
                       required>
            </div>

            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue">
                {{ __('admin.user_management.users.submit') }}
            </button>
        </form>
    </div>

</x-app-admin-layout>
