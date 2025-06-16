<x-guest-layout>
    @php
        $lang = app()->getLocale();
    @endphp
            <!-- Hire Us -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="max-w-xl mx-auto">
            <div class="text-center pb-5">
                <h1 class="text-3xl font-bold text-gray-800 sm:text-4xl dark:text-white">
                    {{ __('locale.feedback.title') }}
                </h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">
                    {{ __('locale.feedback.subtitle') }}
                </p>
            </div>

            @if (session('success'))
                <div class="mt-12">
                    <div id="alert-3"
                         class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                         role="alert">
                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div class="ml-3 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                        <button type="button"
                                class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                                data-dismiss-target="#alert-3" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Form -->
            @if ($errors->any())
                <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                     role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Danger</span>
                    <div>
                        <span class="font-medium">{{ __('locale.feedback.error_message') }}</span>
                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <form action="{{ route('support.feedback.store') }}" method="post">
                @csrf
                <div class="grid gap-4 lg:gap-6">
                    <!-- Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                        <div>
                            <label for="firstname"
                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.feedback.first_name') }}</label>
                            <input type="text" name="firstname" id="firstname"
                                   class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                   placeholder="{{ __('locale.feedback.enter_first_name') }}">
                        </div>

                        <div>
                            <label for="lastname"
                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.feedback.last_name') }}</label>
                            <input type="text" name="lastname" id="lastname"
                                   class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                   placeholder="{{ __('locale.feedback.enter_last_name') }}">
                        </div>
                    </div>
                    <!-- End Grid -->

                    <div>
                        <label for="email"
                               class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.feedback.email') }}</label>
                        <input type="email" name="email" id="email" autocomplete="email"
                               class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                               placeholder="{{ __('locale.feedback.enter_email') }}">
                    </div>

                    <!-- End Grid -->

                    <!-- Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                        <div>
                            <label for="report_subtype"
                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.feedback.topic') }}</label>
                            <select name="report_subtype" id="report_subtype"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400">
                                @foreach($options as $option)
                                    <option value="{{ $option->id }}">
                                        @if ($lang === 'ar')
                                            {{ $option->ar_name }}
                                        @else
                                            {{ $option->en_name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="report_title"
                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.feedback.subject') }}</label>
                            <input type="text" name="report_title" id="report_title"
                                   class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                   placeholder="{{ __('locale.feedback.enter_subject') }}">
                        </div>
                    </div>

                    <div>
                        <label for="body_message"
                               class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.feedback.details') }}</label>
                        <textarea id="body_message" name="body_message" rows="4"
                                  class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                  placeholder="{{ __('locale.feedback.enter_details') }}"></textarea>
                    </div>
                </div>
                <!-- End Grid -->

                <div class="mt-6 grid">
                    <button type="submit"
                            class="inline-flex justify-center items-center gap-x-3 text-center bg-blue-600 hover:bg-blue-700 border border-transparent text-sm lg:text-base text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-white transition py-3 px-4 dark:focus:ring-offset-gray-800">{{ __('locale.feedback.send_inquiry') }}</button>
                </div>
            </form>
            <!-- End Form -->
        </div>
    </div>
    <!-- End Hire Us -->
</x-guest-layout>
