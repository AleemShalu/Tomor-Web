<x-app-admin-layout>

    <div class="w-full lg:w-2/3 mx-auto flex mt-4">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.store.index') }}" class="text-blue-500 underline">
                {{ __('admin.common.back') }}
            </a>
        </div>
    </div>
    <div class="w-full lg:w-2/3 mx-auto flex text-black font-bold text-2xl py-4">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
             fill="currentColor" viewBox="0 0 20 20">
            <path
                    d="M17.876.517A1 1 0 0 0 17 0H3a1 1 0 0 0-.871.508C1.63 1.393 0 5.385 0 6.75a3.236 3.236 0 0 0 1 2.336V19a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6h4v6a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V9.044a3.242 3.242 0 0 0 1-2.294c0-1.283-1.626-5.33-2.124-6.233ZM15.5 14.7a.8.8 0 0 1-.8.8h-2.4a.8.8 0 0 1-.8-.8v-2.4a.8.8 0 0 1 .8-.8h2.4a.8.8 0 0 1 .8.8v2.4ZM16.75 8a1.252 1.252 0 0 1-1.25-1.25 1 1 0 0 0-2 0 1.25 1.25 0 0 1-2.5 0 1 1 0 0 0-2 0 1.25 1.25 0 0 1-2.5 0 1 1 0 0 0-2 0A1.252 1.252 0 0 1 3.25 8 1.266 1.266 0 0 1 2 6.75C2.306 5.1 2.841 3.501 3.591 2H16.4A19.015 19.015 0 0 1 18 6.75 1.337 1.337 0 0 1 16.75 8Z"/>
        </svg>
        <div class="px-3">
            {{__('admin.store_management.update_store_info')}}
        </div>
    </div>

    <div class="w-full lg:w-2/3 mx-auto bg-white rounded p-6 shadow-md">
        @if($errors->any())
            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                 role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor"
                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                          clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Danger</span>
                <div>
                    <span class="font-medium">Ensure that these requirements are met:</span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(session('success'))
            <div id="alert-border-3"
                 class="flex p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50 dark:text-green-400 dark:bg-gray-800 dark:border-green-800"
                 role="alert">
                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                          clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
                <button type="button"
                        class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                        data-dismiss-target="#alert-border-3" aria-label="Close">
                    <span class="sr-only">Dismiss</span>
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif
        <section class="bg-white dark:bg-gray-900">
            <div class="py-4 px-4 pl-10 max-w-2xl">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-edit mr-2"></i> {{__('locale.store_manage_settings.update_commercial_name')}}
                </h2>

                <form action="{{ route('admin.store.update-commercial-name') }}" method="POST"
                      id="update-commercial-name" name="update-commercial-name">
                    @csrf
                    @method('POST')

                    <div class="py-3">
    <div class="border-b border-gray-900/10 pb-12">
        <h2 class="font-semibold">
            {{ __('locale.store.capacity') }}
        </h2>
        <p class="mt-1 text-sm leading-6 text-gray-600">
            {{ __('locale.store.update_capacity') }}
        </p>

        <div class="mt-4 relative">
            <select id="capacity" name="capacity" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-200 focus:border-indigo-300 focus:ring focus:ring-opacity-50">
                <option value="" disabled {{ old('capacity', $store->capacity) == '' ? 'selected' : '' }}>
                    -- Select a category --
                </option>
                <option value="large" {{ old('capacity', $store->capacity) == 'large' ? 'selected' : '' }}>
                    {{ __('locale.store.large') }}
                </option>
                <option value="small" {{ old('capacity', $store->capacity) == 'small' ? 'selected' : '' }}>
                    {{ __('locale.store.small') }}
                </option>
            </select>

            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>
    <br>
</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-2">
                        <div>
                            <label class="block mb-1">
                                <span style="color: red">*</span> {{ __('locale.store.commercial_name_en') }}
                            </label>
                            <input type="text" id="commercial_name_en" name="commercial_name_en"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="" value="{{ $store->commercial_name_en }}">
                            <span id="commercial_name_en_error" class="text-red-500 text-sm"></span>

                        </div>
                        <div>
                            <label class="block mb-1">
                                                <span
                                                        style="color: red">*</span> {{ __('locale.store.commercial_name_ar') }}
                            </label>
                            <input type="text" id="commercial_name_ar" name="commercial_name_ar"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="" value="{{ $store->commercial_name_ar }}">
                            <span id="commercial_name_ar_error" class="text-red-500 text-sm"></span>

                        </div>
                        <input type="hidden" id="store_id" name="store_id" value="{{ $store->id }}">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1">
                                <span style="color: red">*</span> {{ __('locale.store.short_name_en') }}
                            </label>
                            <input type="text" id="short_name_en" name="short_name_en"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="" value="{{ $store->short_name_en }}">
                            <span id="short_name_en_error" class="text-red-500 text-sm"></span>

                        </div>
                        <div>
                            <label class="block mb-1">
                                <span style="color: red">*</span> {{ __('locale.store.short_name_ar') }}
                            </label>
                            <input type="text" id="short_name_ar" name="short_name_ar"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="" value="{{ $store->short_name_ar }}">
                            <span id="short_name_ar_error" class="text-red-500 text-sm"></span>

                        </div>
                    </div>


                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block mb-1">
                                <span style="color: red">*</span> {{ __('locale.store.description_en') }}
                            </label>
                            <textarea dir="ltr" id="description_en" name="description_en" rows="4"
                                      class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                      placeholder="{{ __('locale.page.write_sentences_about_store_ِen') }}"
                                      oninput=" updateCharacterCount('description_en', 'description_en_counter', 255);">{{$store->description_en}}</textarea>
                            <span id="description_en_error" class="text-red-500 text-sm"></span>

                            <div>
                                <span id="description_en_counter" class="text-gray-500 text-sm">0 / 255</span>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1">
                                <span style="color: red">*</span> {{ __('locale.store.description_ar') }}
                            </label>
                            <textarea dir="rtl" id="description_ar" name="description_ar" rows="4"
                                      class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                      placeholder="{{ __('locale.page.write_sentences_about_store_ِar') }}"
                                      oninput=" updateCharacterCount('description_ar', 'description_ar_counter', 255);">{{$store->description_ar}}</textarea>
                            <span id="description_ar_error" class="text-red-500 text-sm"></span>
                            <div>
                                            <span id="description_ar_counter"
                                                  class="text-gray-500 text-sm">0 / 255</span>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="field">
                        <div class="control">
                            <button
                                    class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover-bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                                    type="submit">
                                {{__('locale.button.update')}}

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <hr class="border-gray-300 dark:border-gray-700">

        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 pl-10 max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-envelope mr-2"></i> {{__('locale.store_manage_settings.update_contact_information')}}
                </h2>

                <form action="{{ route('admin.store.update-contact-information') }}" method="POST"
                      id="update-contact-information" name="update-contact-information">
                    @csrf
                    @method('PUT')
                    <div class="">
                        <div class="sm:col-span-2 my-2">
                            <label for="country_id"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('locale.country.countries')}}</label>
                            <select name="country_id" id="country_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
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
                        <div class="grid grid-cols-2 gap-4">
                            <div class="">
                                <label for="contact_no"
                                       class="w-full mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    {{__('locale.contact.contact_number')}}
                                </label>
                                <input id="contact_no" name="contact_no" type="tel"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="{{ __('locale.contact.contact_number_placeholder') }}"
                                       oninput="numberOnly(this.id);"
                                       maxlength="9"
                                       value="{{$store->contact_no}}"
                                />
                                <input type="hidden" id="dial_code_contact_no"
                                       name="dial_code_contact_no"
                                       value="966">
                                <p id="contact_no_error" class="text-sm text-red-500"></p>

                            </div>
                            <div class="">
                                <label for="secondary_contact_no"
                                       class="w-full mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    {{__('locale.contact.secondary_contact_number')}}
                                </label>
                                <input id="secondary_contact_no" name="secondary_contact_no" type="tel"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="{{ __('locale.contact.secondary_contact_number_placeholder') }}"
                                       oninput="numberOnly(this.id);"
                                       value="{{$store->secondary_contact_no}}"
                                       maxlength="9"
                                />

                                <input type="hidden" id="dial_code_secondary_contact_no"
                                       name="dial_code_secondary_contact_no"
                                       value="966">
                                <p id="secondary_contact_no_error" class="text-sm text-red-500"></p>

                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="store_id" name="store_id" value="{{$store->id}}">
                    <br>
                    <div class="field">
                        <div class="control">
                            <button
                                    class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                                    type="submit">
                                {{__('locale.button.update')}}

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <hr class="border-gray-300 dark:border-gray-700">
        <section class="bg-white dark:bg-gray-900 py-6 px-4">
            <div class="max-w-4xl">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-edit mr-3"></i> {{ __('locale.store_manage_settings.update_additional_information') }}
                </h2>

                <form action="{{ route('admin.store.update-additional-information') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tax ID Number -->
                        <div>
                            <label for="tax_id_number"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <span class="text-red-500">*</span> {{ __('locale.store.tax_id_number') }}
                            </label>
                            <input type="text" id="tax_id_number" name="tax_id_number"
                                   class="bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white"
                                   value="{{ old('tax_id_number', $store->tax_id_number) }}">
                            <span id="tax_id_number_error" class="text-red-500 text-sm"></span>
                        </div>

                        <!-- Tax ID Attachment -->
                        <div>
                            <label for="tax_id_attachment"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('locale.store.tax_id_attachment') }}
                            </label>
                            <input type="file" id="tax_id_attachment" name="tax_id_attachment"
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            @if($store->tax_id_attachment)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $store->tax_id_attachment) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline text-sm">{{ __('locale.store.view_attachment') }}</a>
                                </div>
                            @endif
                            <span id="tax_id_attachment_error" class="text-red-500 text-sm"></span>
                        </div>

                        <!-- Commercial Registration No -->
                        <div>
                            <label for="commercial_registration_no"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <span class="text-red-500">*</span> {{ __('locale.store.commercial_registration_no') }}
                            </label>
                            <input type="text" id="commercial_registration_no"
                                   name="commercial_registration_no"
                                   class="bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white"
                                   value="{{ old('commercial_registration_no', $store->commercial_registration_no) }}">
                            <span id="commercial_registration_no_error"
                                  class="text-red-500 text-sm"></span>
                        </div>

                        <!-- Commercial Registration Attachment -->
                        <div>
                            <label for="commercial_registration_attachment"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('locale.store.commercial_registration_attachment') }}
                            </label>
                            <input type="file" id="commercial_registration_attachment"
                                   name="commercial_registration_attachment"
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            @if($store->commercial_registration_attachment)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $store->commercial_registration_attachment) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline text-sm">{{ __('locale.store.view_attachment') }}</a>
                                </div>
                            @endif
                            <span id="commercial_registration_attachment_error"
                                  class="text-red-500 text-sm"></span>
                        </div>

                        <!-- Municipal License No -->
                        <div>
                            <label for="municipal_license_no"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('locale.store.municipal_license_no') }}
                            </label>
                            <input type="text" id="municipal_license_no" name="municipal_license_no"
                                   class="bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white"
                                   value="{{ old('municipal_license_no', $store->municipal_license_no) }}">
                            <span id="municipal_license_no_error" class="text-red-500 text-sm"></span>
                        </div>

                        <!-- Municipal License Attachment -->
                        <div>
                            <label for="municipal_license_attachment"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('locale.branch_settings.commercial_information.registration_expiry') }}
                            </label>
                            <x-date-converter inputName="commercial_registration_expiry"
                                              saveAs="gregorian"
                                              classStyle="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400"
                                              :initialValue="$store->commercial_registration_expiry">

                            </x-date-converter>


                            @error('commercial_registration_expiry')
                            <p class="text-red-500 mt-2 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <input type="hidden" id="store_id" name="store_id" value="{{$store->id}}">

                    <br>
                    <div class="field">
                        <div class="control">
                            <button
                                    class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                                    type="submit">Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <hr class="border-gray-300 dark:border-gray-700">
        <section class="hidden bg-white dark:bg-gray-900">
            <div class="py-8 px-4 pl-10 max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-cogs mr-2"></i> Update Website and Logo
                </h2>
                <!-- Add your content here -->
                <form action="{{ route('store.update-website-logo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="website" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Website</label>
                            <input type="text" name="website" id="website"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="Website" value="{{ $store->website }}">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="logo"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Logo</label>
                            <div>
                                @if ($store->logo)
                                    <div class="relative w-32 h-32 mb-2 rounded-lg overflow-hidden">
                                        <img class="object-cover w-20 h-20 rounded-full "
                                             src="{{ asset('storage/'.$store->logo) }}" alt="Current Logo">
                                    </div>
                                @else
                                    <div
                                            class="w-32 h-32 mb-2 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400 dark:text-gray-600">No logo found</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-center w-full">
                                <label for="file_input"
                                       class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                               for="file_input">Upload file</label>
                                        <input
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                aria-describedby="file_input_help" id="file_input" type="file"
                                                name="logo"
                                                accept=".svg, .png, .jpg, .jpeg, .gif">
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">
                                            SVG, PNG, JPG, or GIF (MAX. 800x400px)</p>
                                    </div>
                                </label>

                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="store_id" name="store_id" value="{{ $store->id }}">
                    <br>
                    <div class="field">
                        <div class="control">
                            <button
                                    class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                                    type="submit">Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <hr class="border-gray-300 dark:border-gray-700">

        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 ">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-cogs pl-6 mr-2"></i> {{__('locale.store_manage_settings.update_bank_account')}}
                </h2>
                <div>

                    <div class="relative p-4 sm:p-6 rounded-sm overflow-hidden mb-8">
                        @if ($banks->count() == 0)
                            <div class="container mx-auto px-4 py-8">
                                <div class="container mx-auto px-4 py-8 text-center">
                                    <p class="text-lg font-bold mb-4">No accounts found.</p>
                                    <p class="text-gray-500 mb-4">Please Add New Account Bank</p>
                                </div>
                            </div>
                        @else

                            <div class="container mx-auto px-4 py-8">
                                <div class="bg-white shadow overflow-hidden sm:rounded-lg">

                                    <div class="relative overflow-x-auto">
                                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                            <thead
                                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    #
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    {{__('locale.store.commercial_name_en')}}
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    {{__('locale.bank.iban_number')}}
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    {{__('locale.bank.account_holder_name')}}
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($banks as $bank)

                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <th scope="row"
                                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ $bank->id }}
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        {{ \App\Models\Store::all()->where('id',$bank->store_id)->value('commercial_name_en') }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{ $bank->iban_number }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{$bank->account_holder_name}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endforeach

                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif


                    </div>
                </div>

            </div>
        </section>

    </div>
</x-app-admin-layout>


<script>
    window.Laravel = {!! json_encode([
        'locale' => app()->getLocale(),
    ]) !!};

    const translations = {
        en: {
            required: 'This field is required.',
            file: 'Please select a file for :attribute.',
            arabic_characters: 'Invalid input. Please use Arabic characters and numbers and spaces only.',
            english_characters: 'Invalid input. Please use English characters and numbers and spaces only.',
            tax_id_number: 'Tax ID number must have a length of 15 numeric characters.',
            tax_id_number_used: 'Tax ID number is already in use.',
            commercial_registration_number: 'Commercial registration number must have a length of 10 numeric characters.',
            commercial_registration_used: 'Commercial registration number is already in use.',
            commercial_registration_expiry: 'Commercial registration expiry date must be in the format iYYYY/iMM/iDD.',
            municipal_license_number: 'Municipal license number must be 11 numeric digits or empty.',
            municipal_license_number_used: 'Municipal license number is already in use.',
            iban_format: 'Invalid IBAN format for Saudi Arabia.',
            iban_used: 'IBAN is already in use.',
            contact_number_format: 'Invalid contact number format. Please enter a valid Saudi number starting with 5 (e.g., 553434031).',
            secondary_contact_number_format: 'Invalid secondary contact number format. Please enter a valid Saudi number starting with 5 (e.g., 553434031).',
            contact_number_used: 'Contact number is already in use.',
        },
        ar: {
            required: 'هذا الحقل مطلوب.',
            file: 'الرجاء تحديد ملف لـ :attribute.',
            arabic_characters: 'إدخال غير صحيح. يرجى استخدام الحروف العربية والأرقام والمسافات فقط.',
            english_characters: 'إدخال غير صحيح. يرجى استخدام الحروف الإنجليزية والأرقام والمسافات فقط.',
            tax_id_number: 'رقم الهوية الضريبي يجب أن يتكون من 15 رقم.',
            tax_id_number_used: 'الرقم الضريبي قيد الاستخدام بالفعل.',
            commercial_registration_number: 'رقم السجل التجاري يجب أن يتكون من 10 أرقام.',
            commercial_registration_used: 'رقم السجل التجاري قيد الاستخدام بالفعل.',
            commercial_registration_expiry: 'تاريخ انتهاء السجل التجاري يجب أن يكون بالصيغة 1446/05/15.',
            municipal_license_number: 'رقم الترخيص البلدي يجب أن يكون 11 رقم أو فارغ.',
            municipal_license_number_used: 'رقم الترخيص البلدي قيد الاستخدام بالفعل.',
            iban_format: 'صيغة الآيبان غير صالحة للمملكة العربية السعودية.',
            iban_used: 'الآيبان قيد الاستخدام بالفعل.',
            contact_number_format: 'صيغة رقم الاتصال غير صالحة. الرجاء إدخال رقم سعودي صالح يبدأ بالرقم 5 (مثال: 553434031).',
            secondary_contact_number_format: 'صيغة رقم الاتصال الثانوي غير صالحة. الرجاء إدخال رقم سعودي صالح يبدأ بالرقم 5 (مثال: 553434031).',
            contact_number_used: 'رقم الاتصال قيد الاستخدام بالفعل.',
        },
    };

    function getLocale() {
        return window.Laravel.locale; // Assumes Laravel sets the "locale" variable in a global context
    }

    function numberOnly(id) {
        // Get element by id which passed as parameter within HTML element event
        var element = document.getElementById(id);
        // This removes any other character but numbers as entered by user
        element.value = element.value.replace(/[^0-9]/gi, "");
    }

    function updateCharacterCount(inputFieldId, counterId, limit) {
        const inputField = document.getElementById(inputFieldId);
        const characterCount = inputField.value.length;
        const characterCounter = document.getElementById(counterId);

        characterCounter.textContent = characterCount + ' / ' + limit;

        if (characterCount > limit) {
            inputField.value = inputField.value.substring(0, limit);
            characterCounter.textContent = limit + ' / ' + limit;
        }
    }

    function validateEnglishInput(input, errorId) {
        const englishRegex = /^[a-zA-Z0-9\s;.:\"'`~?!@#\$%^&*()\-=_+|,/\\<>{}\[\]©®™\u0080-\u00FF]+$/;
        const value = input.value;
        if (!englishRegex.test(value)) {
            document.getElementById(errorId).innerText = "Only English characters, numbers, and spaces are allowed.";
        } else {
            document.getElementById(errorId).innerText = "";
        }
    }

    function validateArabicInput(input, errorId) {
        const arabicRegex = /^[\u0600-\u06FF\s,;.:"'`~?!@#\$%^&*()-=-_+|/\\<>{}[\]©®™]+$/;
        const value = input.value;
        if (!arabicRegex.test(value)) {
            document.getElementById(errorId).innerText = "Only Arabic characters are allowed.";
        } else {
            document.getElementById(errorId).innerText = "";
        }
    }


    async function checkContactNoStore(contact_no) {
        const response = await fetch('/validation/validate-contact-no-store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({contact_no: contact_no}),
        });

        if (response.status === 200) {
            return true;
        } else if (response.status === 422) {
            return false;
        } else {
            return false;
        }
    }

    async function checkContactNoStoreSecondary(contact_no_secondary) {
        const response = await fetch('/validation/validate-contact-no-store-secondary', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({contact_no_secondary: contact_no_secondary}),
        });

        if (response.status === 200) {
            return true;
        } else if (response.status === 422) {
            return false;
        } else {
            return false;
        }
    }

    // Your existing validation function validateStep1
    function validateCommercialName() {
        const requiredFields = [
            'commercial_name_en',
            'commercial_name_ar',
            'short_name_en',
            'short_name_ar',
            'description_en',
            'description_ar',
        ];

        let valid = true;

        for (const field of requiredFields) {
            const inputField = document.getElementById(field);
            const inputValue = inputField ? inputField.value.trim() : '';
            const errorSpan = document.getElementById(field + '_error');

            if (field === 'file-input' || field === 'logo') {
                // Handle 'header' and 'logo' fields separately
                if (inputField) {
                    if (inputField.files.length === 0) {
                        inputField.style.border = '2px solid red';
                        valid = false;
                        if (errorSpan) {
                            errorSpan.textContent = translations[getLocale()].file.replace(':attribute', field);
                        }
                    } else {
                        inputField.style.border = '2px solid green'; // Change border color to green for success
                        if (errorSpan) {
                            errorSpan.textContent = ''; // Clear the error message
                        }
                    }
                }
            } else {
                // Handle other fields
                if (inputValue.trim() === '') {
                    if (inputField) {
                        inputField.style.border = '2px solid red';
                    }
                    if (errorSpan) {
                        errorSpan.textContent = translations[getLocale()].required;
                    }
                    valid = false;
                } else if (field.endsWith('_ar')) {
                    console.log('Validating Arabic field');
                    validateArabicInput(inputField, errorSpan.id); // Call the Arabic validation function
                    if (errorSpan && errorSpan.textContent === "") {
                        inputField.style.border = '2px solid green'; // Change border color to green for success
                    } else {
                        inputField.style.border = '2px solid red';
                        valid = false; // Set valid to false if there's an error
                    }
                } else {
                    console.log('Validating English field');
                    validateEnglishInput(inputField, errorSpan.id); // Call the English validation function
                    if (errorSpan && errorSpan.textContent === "") {
                        inputField.style.border = '2px solid green'; // Change border color to green for success
                    } else {
                        inputField.style.border = '2px solid red';
                        valid = false; // Set valid to false if there's an error
                    }
                }
            }
        }

        return valid; // Return true if all validations pass, or false if any validation fails
    }

    async function validateContactInformation() {
        const requiredFields = ['contact_no', 'secondary_contact_no'];

        let valid = true;

        for (const field of requiredFields) {
            console.log('Validating field: ' + field);
            const inputField = document.getElementById(field);
            const errorSpan = document.getElementById(field + '_error');


            const inputValue = inputField.value.trim();

            if (inputValue === '') {
                console.log('Field is empty: ' + field);
                if (errorSpan) {
                    errorSpan.textContent = translations[getLocale()].required;
                }
                valid = false;
                inputField.style.borderColor = 'red';
            } else if (field === 'contact_no' || field === 'secondary_contact_no') {
                // Validate the contact number format (Saudi number)
                if (!/^5\d{8}$/.test(inputValue)) {
                    console.log('Invalid contact number format: ' + inputValue);
                    if (errorSpan) {
                        errorSpan.textContent = translations[getLocale()].contact_number_format;
                    }
                    valid = false;
                    inputField.style.borderColor = 'red';
                } else {
                    // Check if the contact number is already in use
                    const isContactNoValid = await checkContactNoStore(inputValue);

                    if (!isContactNoValid) {
                        console.log('Contact number is already in use: ' + inputValue);
                        if (errorSpan) {
                            errorSpan.textContent = translations[getLocale()].contact_number_used;
                        }
                        valid = false;
                        inputField.style.borderColor = 'red';
                    } else {
                        if (errorSpan) {
                            errorSpan.textContent = '';
                        }
                        inputField.style.borderColor = 'green';
                    }
                }
            } else {
                if (errorSpan) {
                    errorSpan.textContent = '';
                }
                inputField.style.borderColor = 'green';
            }
        }

        console.log('Validation result: ' + valid);
        return valid;
    }


    // Add an event listener to the form to trigger the validation on submission
    document.getElementById('update-commercial-name').addEventListener('submit', function (event) {
        if (!validateCommercialName()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });


</script>
