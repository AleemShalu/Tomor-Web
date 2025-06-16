<x-app-admin-layout>
    <div class="container mx-auto py-4 max-w-md">

        <div class="flex items-center mb-4">
            <a href="{{ route('admin.usher') }}" class="text-blue-500 underline">
                {{ __('admin.common.back') }}
            </a>
        </div>

        <h1 class="text-xl font-bold mb-4">
            {{__('admin.user_management.users.usher_update')}}
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                 role="alert">
                {{--                <strong class="font-bold">{{__('admin.common.success')}}!</strong>--}}
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">{{__('admin.common.errors.error')}}!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('admin.usher.update', ['usher' => $usher->id]) }}"
              class="">
            @csrf
            @method('POST')

            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                        {{__('admin.user_management.users.user_name')}}

                    </label>
                    <input type="text" id="name" name="name" value="{{ $usher->name }}"
                           class="border rounded-md p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                        {{__('admin.user_management.users.user_email')}}
                    </label>
                    <input type="email" id="email" name="email" value="{{ $usher->email }}"
                           class="border rounded-md p-2 w-full bg-gray-200" disabled="disabled">
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">
                        {{__('admin.user_management.users.user_contact_no')}}
                    </label>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ $usher->phone_number }}"
                           class="border rounded-md p-2 w-full bg-gray-200" disabled="disabled">
                </div>

                <div class="mb-4">
                    <label for="code_usher" class="block text-gray-700 text-sm font-bold mb-2">
                        {{__('admin.user_management.users.usher_code')}}
                    </label>
                    <input type="text" id="code_usher" name="code_usher" value="{{ $usher->code_usher }}"
                           class="border rounded-md p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="states" class="block text-gray-700 text-sm font-bold mb-2">
                        {{__('admin.user_management.users.user_status')}}
                    </label>
                    <select id="states" name="states" class="border rounded-md p-2 w-full">
                        <option value="1" {{ $usher->states == 1 ? 'selected' : '' }}>
                            {{__('admin.user_management.users.active')}}
                        </option>
                        <option value="0" {{ $usher->states == 0 ? 'selected' : '' }}>
                            {{__('admin.user_management.users.inactive')}}
                        </option>
                    </select>
                </div>

                <input type="hidden" name="usher_id" id="usher_id" value="{{$usher->id}}">

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full">
                    {{__('admin.user_management.users.update')}}
                </button>
            </div>
        </form>
    </div>
</x-app-admin-layout>
