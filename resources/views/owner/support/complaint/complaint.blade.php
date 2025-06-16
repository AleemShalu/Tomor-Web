@php
    $lang = app()->getLocale();
@endphp
<x-guest-layout>
    <!-- Hire Us -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Grid -->
        <div class="grid md:grid-cols-2 items-center gap-12">

            <div class="px-5">
                <!-- Points of Contact -->
                <div class="pb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ __('locale.complaint.points_of_contact') }}</h2>
                    <ul class="mt-4 space-y-2">
                        {{--                        <li>--}}
                        {{--                            <div class="py-2"><strong>U.S. Flowbite</strong></div>--}}
                        {{--                            <div class="pl-4 ">11350 McCormick Rd, EP III, Suite 200,</div>--}}
                        {{--                            <div class="pl-4 ">Hunt Valley, MD 21031</div>--}}
                        {{--                        </li>--}}
                        <li>
                            <div class="py-2"><strong>Information & Sales</strong></div>
                            <div class="pl-4 ">sales@tomor-sa.com</div>
                        </li>
                        <li>
                            <div class="py-2"><strong>Support</strong></div>
                            <div class="pl-4 ">support@tomor-sa.com</div>
                        </li>
                        <li>
                            <div class="py-2"><strong>Verification of Employment</strong></div>
                            <div class="pl-4 ">hr@tomor-sa.com</div>
                        </li>
                        <li>
                            <div class="py-2"><strong>Our offices </strong></div>
                            <div class="pl-4 "><strong>Saudi Arabia</strong></div>
                            <div class="pl-8 ">23431, Abed bin Hussein, Al-Rawdah,
                            </div>
                            <div class="pl-8 ">Jeddah, Saudi Arabia</div>
                            {{--                            <div class="pl-4 "><strong>Germany</strong></div>--}}
                            {{--                            <div class="pl-8 ">Neue Schönhauser Str. 3-5,</div>--}}
                            {{--                            <div class="pl-8 ">10178 Berlin</div>--}}
                            {{--                            <div class="pl-4 "><strong>France</strong></div>--}}
                            {{--                            <div class="pl-8 ">266 Place Ernest Granier,</div>--}}
                            {{--                            <div class="pl-8 ">34000 Montpellier</div>--}}
                        </li>
                    </ul>
                </div>                <!-- End Points of Contact -->
            </div>

            <!-- End Col -->

            <div class="relative pt-5">
                <!-- Card -->
                <div class="flex flex-col border rounded-xl bg-gray-100 p-4 sm:p-6 lg:p-10 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ __('locale.complaint.compliant_page') }}</h2>

                    <p class="mt-4 text-gray-600 dark:text-gray-300">
                        {{ __('locale.complaint.welcome_message') }}
                    </p>
                    @if (session('success'))
                        <div class="mt-12">
                            <div id="alert-3"
                                 class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                                 role="alert">
                                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                </svg>
                                <span class="sr-only">Info</span>
                                <div class="ml-3 text-sm font-medium">
                                    {{ session('success') }}
                                    <br>
                                    {{ __('locale.complaint.ticket_number_message') }}<label
                                            class="font-bold">{{ Session::get('ticketNumber') }}</label> {{ __('locale.complaint.we_will_get_back') }}

                                </div>

                                <button type="button"
                                        class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                                        data-dismiss-target="#alert-3" aria-label="Close">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>
                            @endif

                            <!-- Form -->
                            @if ($errors->any())
                                <div
                                        class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Danger</span>
                                    <div>
                                        <span
                                                class="font-medium">{{ __('locale.complaint.error_message_title') }}</span>
                                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            <form action="{{ route('support.complaint') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mt-6 grid gap-4 lg:gap-6">
                                    <!-- Grid -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                        <div>
                                            <label for="firstname"
                                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.complaint.form_labels.first_name') }}</label>
                                            <input type="text" name="firstname" id="firstname"
                                                   class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                                   placeholder="{{ __('locale.complaint.placeholder_text.first_name') }}">
                                        </div>

                                        <div>
                                            <label for="lastname"
                                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.complaint.form_labels.last_name') }}</label>
                                            <input type="text" name="lastname" id="lastname"
                                                   class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                                   placeholder="{{ __('locale.complaint.placeholder_text.last_name') }}">
                                        </div>
                                    </div>

                                    <!-- End Grid -->

                                    <div>
                                        <label for="email"
                                               class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.complaint.form_labels.email') }}
                                        </label>
                                        <input type="email" name="email" id=email" autocomplete="email"
                                               class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                               placeholder="{{ __('locale.complaint.placeholder_text.email') }}">
                                    </div>


                                    <!-- Grid -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                                        <div>
                                            <label for="report_subtype"
                                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.complaint.form_labels.topic') }}</label>
                                            <select name="report_subtype" id="hs-company-hire-us-1"
                                                    class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400">
                                                @foreach($options as $option)
                                                    <option value="{{$option->id}}">
                                                        @if($lang == 'ar')
                                                            {{$option->ar_name}}
                                                        @else
                                                            {{$option->en_name}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="report_title"
                                                   class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.complaint.form_labels.subject') }}</label>
                                            <input type="text" name="report_title" id="report_title"
                                                   class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                                   placeholder="{{ __('locale.complaint.placeholder_text.subject') }}">
                                        </div>
                                    </div>
                                    <!-- End Grid -->
                                    <div>
                                        <label for="body_message"
                                               class="block text-sm text-gray-700 font-medium dark:text-white">{{ __('locale.complaint.form_labels.details') }}</label>
                                        <textarea id="body_message" name="body_message" rows="4"
                                                  class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400"
                                                  placeholder="{{ __('locale.complaint.placeholder_text.details') }}"></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 lg:gap-6">
                                        <div class="mb-3">
                                            <label for="formFileLg"
                                                   class="mb-2 inline-block text-neutral-700 dark:text-neutral-200">{{ __('locale.complaint.form_labels.attachment') }}</label>
                                            <input
                                                    class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] font-normal leading-[2.15] text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
                                                    id="formFileLg"
                                                    type="file"
                                                    name="attachment"
                                            />
                                        </div>
                                    </div>

                                    <!-- End Grid -->
                                </div>

                                <!-- End Grid -->

                                {{--                        <!-- Checkbox -->--}}
                                {{--                        <div class="mt-3 flex">--}}
                                {{--                            <div class="flex">--}}
                                {{--                                <input id="remember-me" name="remember-me" type="checkbox" class="shrink-0 mt-1.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">--}}
                                {{--                            </div>--}}
                                {{--                            <div class="ml-3">--}}
                                {{--                                <label for="remember-me" class="text-sm text-gray-600 dark:text-gray-400">By submitting this form I have read and acknowledged the <a class="text-blue-600 decoration-2 hover:underline font-medium" href="#">Privact policy</a></label>--}}
                                {{--                            </div>--}}
                                {{--                        </div>--}}
                                <!-- End Checkbox -->

                                <div class="mt-6 grid">
                                    <button type="submit"
                                            class="inline-flex justify-center items-center gap-x-3 text-center bg-blue-600 hover:bg-blue-700 border border-transparent text-sm lg:text-base text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-white transition py-3 px-4 dark:focus:ring-offset-gray-800">
                                        {{ __('locale.complaint.submit_button') }}
                                    </button>
                                </div>
                            </form>

                            <div class="mt-3 text-center">
                                <p class="text-sm text-gray-500">
                                    {{ __('locale.complaint.response_message') }}
                                </p>
                            </div>
                        </div>
                        <!-- New Ticket Section -->
                        <div class="py-4 text-black font-bold">
                            {{ __('new_ticket_section') }}
                            <a class="text-blue-700"
                               href="{{ route('support.inquiries') }}">{{ __('inquiries_page') }}</a>
                        </div>

                        <!-- End Card -->
                </div>

                <!-- End Col -->
            </div>
            <!-- End Grid -->
        </div>
    </div>

    <!-- End Hire Us -->

</x-guest-layout>
