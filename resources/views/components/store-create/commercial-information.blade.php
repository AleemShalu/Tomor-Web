<!-- Commercial Information section -->
<div id="step2" class="border-b border-gray-900/10 pb-12" style="display: none;">
    <div class="">
        <h2 class="text-base font-semibold text-gray-900">
            <i class="fas fa-info-circle mr-2"></i>{{ __('locale.commercial.commercial_information') }}
        </h2>
        <div class="">

            <div class="grid grid-cols-2 gap-4 bg-gray-100 p-2 rounded-xl m-2">
                <div class="">
                    <label for="business"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        <span style="color: red">*</span>{{ __('locale.store.business_type') }}
                    </label>
                    <div class="">
                        <select id="business_type_id" name="business_type_id"
                                autocomplete="business-name"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach($businesses as $business)
                                <option
                                        value="{{ $business->id }}" {{ old('business_type_id') == $business->id ? 'selected' : '' }}>
                                    @if(app()->getLocale() === 'ar')
                                        {{ $business->name_ar }}
                                    @else
                                        {{ $business->name_en }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <span id="business_type_id_error" class="text-red-500 text-sm"></span>
                    <!-- Error span -->
                </div>
                <div class="">
                    <label for="country"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><span
                                style="color: red">*</span>{{ __('locale.country.country') }}
                    </label>
                    <div class="mt-2">
                        <select id="country_id" name="country_id" autocomplete="country-name"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach($countries as $country)
                                <option
                                        value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    @if(app()->getLocale() === 'ar')
                                        {{ $country->ar_name }}
                                    @else
                                        {{ $country->en_name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <span id="country_id_error" class="text-red-500 text-sm"></span>
                    <!-- Error span -->
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 bg-gray-100 p-2 rounded-xl m-2">
                <div class="">
                    <label for="tax_id_number"
                           class="block text-sm font-medium leading-6 text-gray-900"><span
                                style="color: red">*</span>{{ __('locale.tax.tax_id_number') }}
                    </label>
                    <div class="mt-2">
                        <input type="text" name="tax_id_number" id="tax_id_number"
                               class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               value="{{ old('tax_id_number') }}"
                               placeholder="{{ __('locale.tax.tax_id_number_input') }}"
                               maxlength="15"
                               oninput="numberOnly(this.id);">

                        <p id="tax_id_number_error" class="text-sm text-red-500"></p>
                    </div>
                </div>
                <div class="">
                    <label for="tax_id_attachment"
                           class="block text-sm font-medium leading-6 text-gray-900"><span
                                style="color: red">*</span>{{ __('locale.tax.tax_id_pdf') }}</label>
                    <div class="mt-2">
                        <div class="mt-2">
                            <input
                                    class="border border-gray-300 bg-white text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    name="tax_id_attachment"
                                    id="tax_id_attachment"
                                    type="file"
                                    accept=".pdf"
                                    oninput="validateFileTypePdf(this)"
                                    value="{{ old('tax_id_attachment') }}"
                            >
                            <p id="tax_id_attachment_error"
                               class="text-sm text-red-500"></p> <!-- Error span -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 bg-gray-100 p-2 rounded-xl m-2">
                <div class="">
                    <label for="commercial_registration_no"
                           class="block text-sm font-medium leading-6 text-gray-900"><span
                                style="color: red">*</span>{{ __('locale.commercial.commercial_registration_number') }}
                    </label>
                    <div class="mt-2">
                        <input type="text" name="commercial_registration_no"
                               id="commercial_registration_no"
                               class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               value="{{ old('commercial_registration_no') }}"
                               placeholder="{{ __('locale.commercial.commercial_registration_no_input') }}"
                               maxlength="10"
                               oninput="numberOnly(this.id);"
                        >
                        <p id="commercial_registration_no_error"
                           class="text-sm text-red-500"></p>
                    </div>
                </div>

                <div class="">
                    <label for="commercial_registration_expiry"
                           class="block text-sm font-medium leading-6 text-gray-900"><span
                                style="color: red">*</span>{{ __('locale.commercial.commercial_registration_expiry') }}
                    </label>
                    <div class="mt-2">
                        <input type="text" name="commercial_registration_expiry"
                               id="commercial_registration_expiry"
                               class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               onchange="console.log('changed')"/>
                        <datepicker-hijri reference="commercial_registration_expiry"
                                          placement="bottom"
                                          date-format="iYYYY/iMM/iDD">

                        </datepicker-hijri>
                        <p id="commercial_registration_expiry_error"
                           class="text-sm text-red-500"></p> <!-- Error span -->
                    </div>
                </div>

                <div class="">
                    <label for="commercial_registration_attachment"
                           class="block text-sm font-medium leading-6 text-gray-900"><span
                                style="color: red">*</span>{{ __('locale.commercial.commercial_registration_attachment') }}
                    </label>
                    <div class="mt-2">
                        <input
                                class=" border bg-white border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                name="commercial_registration_attachment"
                                accept=".pdf"
                                oninput="validateFileTypePdf(this)"
                                id="commercial_registration_attachment" type="file">
                        <p id="commercial_registration_attachment_error"
                           class="text-sm text-red-500"></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 bg-gray-100 p-2 rounded-xl m-2">
                <div class="">
                    <label for="municipal_license_no"
                           class="block text-sm font-medium leading-6 text-gray-900">{{ __('locale.commercial.municipal_license_number') }}
                    </label>
                    <div class="mt-2">
                        <input type="text" name="municipal_license_no" id="municipal_license_no"
                               class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               value="{{ old('municipal_license_no') }}"
                               placeholder="{{ __('locale.commercial.municipal_license_number_input') }}"
                               maxlength="11"
                               oninput="numberOnly(this.id);"
                        >
                        <p id="municipal_license_no_error"
                           class="text-sm text-red-500"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
