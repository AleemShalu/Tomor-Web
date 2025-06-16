<x-app-admin-layout>
    <div class="w-1/2 lg:w-8/12 px-7 mx-auto mt-6">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-gray-50 border-0">
            <div class="rounded-t bg-white mb-0 px-6 py-6">
                <div class="text-center flex justify-between">
                    <h6 class="text-blueGray-700 text-xl font-bold">
                        {{ __('admin.user_management.users.user_edit')}}
                    </h6>
                </div>
            </div>
            <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                <form action="{{ route('admin.users.update', $user->id) }}" method="post">
                    @csrf
                    @method('POST')

                    <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                        {{ __('admin.user_management.users.personal_info')}}
                    </h6>
                    <div class="flex flex-wrap">
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="name">
                                    {{ __('admin.user_management.users.user_name')}}
                                </label>
                                <input type="text" name="name" id="name" value="{{ $user->name }}"
                                       class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150">
                            </div>
                        </div>
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="email">
                                    {{ __('admin.user_management.users.user_email')}}
                                </label>
                                <input type="email" name="email" id="email" value="{{ $user->email }}"
                                       class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150">
                            </div>
                        </div>
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="password">
                                    {{ __('admin.user_management.users.user_password')}}
                                </label>
                                <input type="password" name="password" id="password"
                                       class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                       placeholder="Enter password">
                            </div>
                        </div>
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                       for="contact_us_phone">
                                    {{ __('admin.user_management.users.user_contact_no')}}
                                </label>
                                <div class="flex">
                                    <input type="text" name="dial_code" id="dial_code" value="966"
                                           class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded-l text-sm shadow focus:outline-none focus:ring w-1/4 ease-linear transition-all duration-150"
                                           readonly>
                                    <input type="text" name="contact_no" id="contact_no" value="{{ $user->contact_no }}"
                                           class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded-r text-sm shadow focus:outline-none focus:ring w-3/4 ease-linear transition-all duration-150">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-6 border-b-1 border-blueGray-300">

                    <button type="submit"
                            class="inline-block px-6 py-3 mt-4 text-sm font-medium leading-5 text-white uppercase transition bg-blue-500 rounded-xl shadow ripple hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue focus:ring focus:ring-blue-300 active:bg-blue-700">
                        {{ __('admin.common.save')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-admin-layout>
