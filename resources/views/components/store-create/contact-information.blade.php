<div id="step4" class="border-b border-gray-900/10 pb-12" style="display: none;">
    <div class="border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">
            <i class="fas fa-address-book mr-2"></i>{{ __('locale.contact.contact') }}
        </h2>
        <p class="mt-1 text-sm leading-6 text-gray-600">{{ __('locale.contact.important_changes_notification') }}</p>
        <br>
        <br>
        <div class="grid grid-cols-2 gap-x-6 gap-y-4 bg-gray-100 p-4 rounded-l-md">
            <div class="col-span-1">
                <label for="contact_no" class="block text-sm font-medium leading-6 text-gray-900">
                    <span style="color: red">*</span>{{ __('locale.contact.contact_number') }}
                </label>
                <div class="mt-2 flex items-center">
                    <!-- Country Code Select -->
                    <div class="relative">
                        <select id="country_code" name="country_code"
                                class="block appearance-none text-center w-24 bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="966">+966</option> <!-- Saudi Arabia Example -->
                            <!-- Add more options for other countries as needed -->
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        </div>
                    </div>
                    <!-- Phone Number Input -->
                    <input id="contact_no" name="contact_no" type="tel"
                           class="ml-2 block w-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                           placeholder="{{ __('locale.contact.contact_number_placeholder') }}"
                           oninput="numberOnly(this.id);"
                           pattern="[0-9]{9}"
                           maxlength="9"
                           title="Please enter a 9-digit number"
                           value="{{ old('contact_no') }}"
                    />
                </div>
                <p id="contact_no_error" class="ml-24 text-sm text-red-500"></p>

                <!-- Error message element -->
            </div>
            <div class="col-span-1">
                <label for="secondary_contact_no"
                       class="block text-sm font-medium leading-6 text-gray-900">
                    <span style="color: red">*</span>{{ __('locale.contact.secondary_contact_number') }}
                </label>
                <div class="mt-2 flex items-center">
                    <!-- Country Code Select -->
                    <div class="relative">
                        <select id="country_code_secondary" name="country_code_secondary"
                                class="block appearance-none text-center w-24 bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="966">+966</option> <!-- Saudi Arabia Example -->
                            <!-- Add more options for other countries as needed -->
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <!-- Country Code Arrow Icon -->
                            {{--                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"--}}
                            {{--                                                     viewBox="0 0 24 24"--}}
                            {{--                                                     xmlns="http://www.w3.org/2000/svg">--}}
                            {{--                                                    <path stroke-linecap="round" stroke-linejoin="round"--}}
                            {{--                                                          stroke-width="2"--}}
                            {{--                                                          d="M19 9l-7 7-7-7"></path>--}}
                            {{--                                                </svg>--}}
                        </div>
                    </div>
                    <!-- Secondary Phone Number Input -->
                    <input id="secondary_contact_no" name="secondary_contact_no" type="tel"
                           class="ml-2 block w-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                           placeholder="{{ __('locale.contact.secondary_contact_number_placeholder') }}"
                           oninput="numberOnly(this.id);"
                           pattern="[0-9]{9}"
                           maxlength="9"
                           title="Please enter a 9-digit number"
                           value="{{ old('secondary_contact_no') }}"
                    />
                </div>
                <p id="secondary_contact_no_error" class="ml-24 text-sm text-red-500"></p>
            </div>


        </div>
    </div>
    <!-- Contact information form inputs -->

    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input id="terms" type="checkbox" value=""
                   class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                   required>
        </div>
        <label for="terms" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
            {!! __('locale.contact.agree_with_terms', ['terms_link' => route('store_terms')]) !!}
        </label>


    </div>
    @error('terms')
    <p class="text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
