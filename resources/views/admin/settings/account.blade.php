<x-app-admin-layout>

    <div class="bg-gray-100 w-full h-full px-6  h-screen">
        <div>
            <div class="font-bold text-xl pt-4">
                {{__('admin.settings.account_settings.title')}}
            </div>
        </div>
        <div class="my-4">
            <hr/>
        </div>
        <div class="grid grid-cols-2">
            <div>
                <div class="text-lg font-bold">
                    {{__('admin.settings.account_settings.title_profile')}}
                </div>
                <div class="text-gray-400">
                    {{__('admin.settings.account_settings.description')}}
                </div>
            </div>
            <div class="grid bg-white p-4 rounded-md gap-y-3">
                <!-- Profile Update Form -->
                <form method="POST" action="{{ route('admin.settings.account.update') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Include the ID as a hidden input -->
                    <input type="hidden" name="id" value="{{ $admin->id }}">

                    <!-- Avatar Input -->
                    <div class="mb-4">
                        <label for="profile_photo_path"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('admin.settings.account_settings.profile_photo')}}

                        </label>
                        <div>
                            <img src="{{ $admin->profile_photo_url }}"
                                 style="object-fit: cover; height: 100px; width: 100px" class="rounded-full">
                        </div>
                        <div class="space-y-4">
                            <input
                                type="file"
                                id="profile_photo_path"
                                name="profile_photo_path"
                                accept="image/*"
                                class="hidden"
                                onchange="document.getElementById('fileName').textContent = this.files[0].name"
                            >
                            <label
                                for="profile_photo_path"
                                class="cursor-pointer bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 inline-block rounded"
                            >
                                {{__('admin.settings.account_settings.upload_profile_photo')}}
                            </label>
                            <span id="fileName" class="text-gray-500">
                                {{__('admin.settings.account_settings.no_file_selected')}}
                            </span>
                        </div>
                        @error('profile_photo_path')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('admin.settings.account_settings.full_name')}}
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}"
                               class="bg-white w-full border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">

                            {{__('admin.settings.account_settings.email')}}

                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}"
                               class="bg-white w-full border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required>
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Update Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-700 dark:border-blue-700">
                            {{__('admin.settings.account_settings.update_profile')}}

                        </button>
                    </div>
                </form>

                <!-- Success Message -->
                @if(session('profile_success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4"
                         role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('profile_success') }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="my-4">
            <hr/>
        </div>
        <form action="{{ route('admin.settings.account.update-password') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2">
                <div>
                    <div class="text-lg font-bold">
                        {{__('admin.settings.account_settings.title_password')}}
                    </div>
                    <div class="text-gray-400">
                        {{__('admin.settings.account_settings.description_password')}}
                    </div>
                </div>
                <div class="grid bg-white p-4 rounded-md gap-y-3">
                    <!-- Password Inputs -->
                    <div class="">
                        <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('admin.settings.account_settings.current_password')}}
                        </div>
                        <div>
                            <input type="password" name="current_password" id="current_password"
                                   class="bg-white w-full border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                    <div class="">
                        <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('admin.settings.account_settings.new_password')}}
                        </div>
                        <div>
                            <input type="password" name="new_password" id="new_password"
                                   class="bg-white w-full border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                    <div class="">
                        <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{__('admin.settings.account_settings.confirm_new_password')}}
                        </div>
                        <div>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                   class="bg-white w-full border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Display success message if available -->
                    @if(session('password_success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4"
                             role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('password_success') }}</span>
                        </div>
                    @endif
                    <!-- Update Button -->
                    <div class="flex justify-end pt-4">
                        <button
                            class="text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-700 dark:border-blue-700"
                            type="submit">
                            {{__('admin.settings.account_settings.update_password')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="my-4">
            <hr/>
        </div>


    </div>
</x-app-admin-layout>

