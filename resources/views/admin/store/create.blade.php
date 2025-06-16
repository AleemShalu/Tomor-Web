@php
    $locale = app()->getLocale();
@endphp
<x-app-admin-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto dark:bg-gray-900 dark:text-white">
        <nav class="flex py-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{route('dashboard')}}"
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{route('admin.store.index')}}"
                           class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Store</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span
                                class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Create New Store</span>
                    </div>
                </li>
            </ol>
        </nav>

        <section class="bg-white dark:bg-gray-800 p-5 rounded-xl">
            <div class="flex text-black font-bold text-2xl pb-4">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path
                            d="M17.876.517A1 1 0 0 0 17 0H3a1 1 0 0 0-.871.508C1.63 1.393 0 5.385 0 6.75a3.236 3.236 0 0 0 1 2.336V19a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6h4v6a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V9.044a3.242 3.242 0 0 0 1-2.294c0-1.283-1.626-5.33-2.124-6.233ZM15.5 14.7a.8.8 0 0 1-.8.8h-2.4a.8.8 0 0 1-.8-.8v-2.4a.8.8 0 0 1 .8-.8h2.4a.8.8 0 0 1 .8.8v2.4ZM16.75 8a1.252 1.252 0 0 1-1.25-1.25 1 1 0 0 0-2 0 1.25 1.25 0 0 1-2.5 0 1 1 0 0 0-2 0 1.25 1.25 0 0 1-2.5 0 1 1 0 0 0-2 0A1.252 1.252 0 0 1 3.25 8 1.266 1.266 0 0 1 2 6.75C2.306 5.1 2.841 3.501 3.591 2H16.4A19.015 19.015 0 0 1 18 6.75 1.337 1.337 0 0 1 16.75 8Z"/>
                </svg>
                <div class="pl-3">
                    Create New Store
                </div>
            </div>


            <section class="bg-white dark:bg-gray-800 p-5 rounded-xl">
                <div class="flex text-black font-bold text-2xl pb-4">
                    <!-- Create New Store title -->
                </div>

                <!-- Stepper -->
                <div class="flex items-center space-x-4 stepper">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full" id="step1_indicator">
                        <span class="text-white">1</span>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center rounded-full" id="step2_indicator">
                        <span class="text-white">2</span>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center rounded-full" id="step3_indicator">
                        <span class="text-white">3</span>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center rounded-full" id="step4_indicator">
                        <span class="text-white">4</span>
                    </div>
                </div>

                @if($errors->any())
                    <div
                            class="flex mt-4 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
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

                <form id="stepped-form" method="POST" action="{{ route('admin.store.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-12">
                        <!-- Store Information section -->
                        <div id="step1" class="border-b border-gray-900/10">
                            <!-- Store information form inputs -->
                            <div class="pt-2">
                                <div for="store_name" class="pt-2 bg-gray-100 rounded-md p-2 m-2">
                                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                        <i class="fas fa-store mx-2"></i>
                                        {{ __('locale.store.name_store') }}
                                    </h2>
                                    <p class="mt-1 text-sm leading-6 text-gray-600">
                                        {{ __('locale.store.information_displayed_publicly') }}
                                    </p>
                                    <div class="py-3">
                                        <div class="border-b border-gray-900/10 pb-12">
                                            <h2 class="font-semibold">
                                                {{--                                                <i class="fas fa-store mr-2"></i>--}}
                                                {{ __('locale.store.select_owner') }}
                                            </h2>
                                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                            {{ __('locale.store.search_user_by_name_email_phone') }}
                                            </p>

                                            <div class="mt-4 relative">
                                                <input type="text" autocomplete="off" id="owner-search"
                                                       name="owner-search"
                                                       placeholder="Search or Select an owner..."
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-200 focus:border-indigo-300 focus:ring focus:ring-opacity-50"/>
                                                <div id="search-results"
                                                     class="hidden absolute z-10 bg-white w-1/3 mt-1 py-2 border border-gray-300 rounded-md shadow-lg">
                                                    <!-- The search results will be dynamically populated here -->
                                                </div>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Hidden input field to store the selected owner's ID -->
                                            <input type="hidden" id="owner_id" name="owner_id" value="" required>
                                        </div>
                                        <br>
                                    </div>
                                        <div class="border-b border-gray-900/10 pb-12">
                                            <h2 class="font-semibold">
                                                {{ __('locale.store.capacity') }}
                                            </h2>
                                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                            {{ __('locale.store.choose_capacity') }}
                                            </p>

                                            <div class="mt-4 relative">
                                                <select id="capacity" name="capacity" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-200 focus:border-indigo-300 focus:ring focus:ring-opacity-50">
                                                        <option value="" disabled selected>-- Select a category --</option>
                                                            <option value="large">{{ __('locale.store.large') }}</option>
                                                            <option value="small">{{ __('locale.store.small') }}</option>
                                                </select>

                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                    </div>
                                                    <p id="capacity_error" class="text-sm text-red-500 mt-1"></p>
                                            </div>
                                </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        <div class="pb-2">
                                            <label class="block mb-1">
                                                <span style="color: red">*</span> {{ __('locale.store.commercial_name_en') }}
                                            </label>
                                            <input dir="ltr" type="text" id="commercial_name_en"
                                                   name="commercial_name_en"
                                                   class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="" value="{{ old('commercial_name_en') }}"
                                            >
                                            <span id="commercial_name_en_error" class="text-red-500 text-sm"></span>
                                        </div>
                                        <div class="pb-2">
                                            <label class="block mb-1">
                                                <span style="color: red">*</span> {{ __('locale.store.commercial_name_ar') }}
                                            </label>
                                            <input dir="rtl" type="text" id="commercial_name_ar"
                                                   name="commercial_name_ar"
                                                   class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="" value="{{ old('commercial_name_ar') }}"
                                            >
                                            <span id="commercial_name_ar_error" class="text-red-500 text-sm"></span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
                                        <div class="">
                                            <label class="block mb-1">
                                                <span style="color: red">*</span> {{ __('locale.store.short_name_en') }}
                                            </label>
                                            <input dir="ltr" type="text" id="short_name_en" name="short_name_en"
                                                   class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="" value="{{ old('short_name_en') }}"
                                            >
                                            <span id="short_name_en_error" class="text-red-500 text-sm"></span>
                                        </div>

                                        <div>
                                            <label class="block mb-1">
                                                <span style="color: red">*</span> {{ __('locale.store.short_name_ar') }}
                                            </label>
                                            <input dir="rtl" type="text" id="short_name_ar" name="short_name_ar"
                                                   class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                   placeholder="" value="{{ old('short_name_ar') }}"
                                            >
                                            <span id="short_name_ar_error" class="text-red-500 text-sm"></span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div for="store_dec" class="pt-2 bg-gray-100 rounded-md p-2 pb-4 m-2">
                                    <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                        {{ __('locale.page.description_store') }}
                                    </h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                                        <div>
                                            <label for="description_en">
                                                <span style="color: red">*</span> {{ __('locale.page.description_about_store_en') }}
                                            </label>
                                            <textarea dir="ltr" id="description_en" name="description_en" rows="4"
                                                      class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                      placeholder="{{ __('locale.page.write_sentences_about_store_ِen') }}"
                                                      oninput=" updateCharacterCount('description_en', 'description_en_counter', 255);"></textarea>
                                            <span id="description_en_error" class="text-red-500 text-sm"></span>
                                            <div>
                                            <span id="description_en_counter"
                                                  class="text-gray-500 text-sm">0 / 255</span>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="description_ar">
                                                <span style="color: red">*</span> {{ __('locale.page.description_about_store_ar') }}
                                            </label>
                                            <textarea dir="rtl" id="description_ar" name="description_ar" rows="4"
                                                      class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                      placeholder="{{ __('locale.page.write_sentences_about_store_ِar') }}"
                                                      oninput=" updateCharacterCount('description_ar', 'description_ar_counter', 255);"></textarea>
                                            <span id="description_ar_error" class="text-red-500 text-sm"></span>
                                            <div>
                                            <span id="description_ar_counter"
                                                  class="text-gray-500 text-sm">0 / 255</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div for="store_logo" class="pt-2 rounded-md pb-4 m-2">
                                    <div class="pb-4 rounded-md">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-gray-100 p-4 rounded-md">
                                                <div class="pt-3">
                                                    <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                                        <span style="color: red">*</span>{{ __('locale.page.logo') }}
                                                    </h2>
                                                    <div class="mx-auto flex items-center gap-x-3"
                                                         style="justify-content: center;">
                                                        <link
                                                                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
                                                                rel="stylesheet">
                                                        <div class="">
                                                            <div class="avatar-upload px-3">
                                                                <div class="avatar-edit">
                                                                    <input type='file' id="logo" name="logo"
                                                                           accept=".png, .jpg, .jpeg"
                                                                           oninput="">
                                                                    <label for="logo">
                                                                        <i class="fas fa-camera w-full mt-2"></i>
                                                                        <!-- Use a FontAwesome class directly -->
                                                                    </label>
                                                                </div>
                                                                <div class="avatar-preview">
                                                                    <div id="imagePreview"
                                                                         style="background-image: url('{{ asset('images/bg-gray-store.png') }}');"></div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span id="logo_error" class="text-red-500 text-sm"></span>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100 p-4 rounded-md">
                                                <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                                    <span style="color: red">*</span>{{ __('locale.page.header') }}
                                                </h2>
                                                <!-- input file -->
                                                <div class="box">
                                                    <input name="header" type="file" class="rounded bg-gray-100 mb-2"
                                                           accept="image/*" required id="file-input"
                                                           oninput="">
                                                </div>
                                                <!-- leftbox -->
                                                <div class="box-2 ">
                                                    <div class="result"
                                                         style=" border-radius:90px;"></div>
                                                </div>
                                                <!-- rightbox -->
                                                <div class="box-2 img-result hide">
                                                    <!-- result of crop -->
                                                    <img class="cropped rounded" src="" alt="">
                                                    <input type="hidden" id="croppedImageData" name="croppedImageData">
                                                </div>
                                                <!-- input file -->
                                                <div class="pt-3">
                                                    <div class="options hide">
                                                        <!-- <label>Width</label> -->
                                                        <input type="hidden" class="img-w" max="1200"/>
                                                    </div>
                                                    <!-- Delete button -->
                                                    <a href="#"
                                                       class="delete bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded"
                                                       onclick="deleteImage()">Delete</a>
                                                    <!-- save btn -->
                                                    <a href="#"
                                                       class="save bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded"
                                                       onclick="saveCroppedImage()">Save</a>
                                                </div>
                                            </div>
                                            <span id="header_error" class="text-red-500 text-sm"></span>
                                        </div>

                                        @error('cropped')
                                        <p class="text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                                        style="color: red">*</span>{{ __('locale.tax.tax_id_pdf') }}
                                            </label>
                                            <div class="mt-2">
                                                <div class="mt-2">
                                                    <input
                                                            class="border border-gray-300 bg-white text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            name="tax_id_attachment"
                                                            id="tax_id_attachment"
                                                            type="file"
                                                            accept=".pdf"
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
                                                                  date-format="iYYYY/iMM/iDD"></datepicker-hijri>
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

                        <!-- Bank Information section -->
                        <div id="step3" class="border-gray-900/10 pb-12" style="display: none;">
                            <div class="border-b border-gray-900/10 pb-5">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">
                                    <i class="fas fa-info-circle mr-2"></i>{{ __('locale.bank.bank_information') }}
                                </h2>
                                <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 bg-gray-100 p-4 rounded-l-md">
                                    <div class="sm:col-span-3">
                                        <label for="account_holder_name"
                                               class="block text-sm font-medium text-gray-700">
                                            <span style="color: red">*</span>
                                            {{ __('locale.bank.account_holder_name') }}
                                        </label>
                                        <input type="text" name="account_holder_name" id="account_holder_name"
                                               autocomplete="off" placeholder="AHMED WALLED"
                                               value="{{ old('account_holder_name') }}"
                                               class="move-on-enter mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <!-- Error message element for account_holder_name -->
                                        <p id="account_holder_name_error" class="text-sm text-red-500"></p>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="iban_number" class="block text-sm font-medium text-gray-700">
                                            <span style="color: red">*</span>
                                            {{ __('locale.bank.iban_number') }}
                                        </label>
                                        <input type="text" name="iban_number" id="iban_number" autocomplete="off"
                                               value="{{ old('iban_number') }}"
                                               maxlength="24" placeholder="SA0380000000608010167519"
                                               class="move-on-enter mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full
                                    shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <!-- Error message element for iban_number -->
                                        <p id="iban_number_error" class="text-sm text-red-500"></p>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="iban_attachment" class="block text-sm font-medium text-gray-700">
                                            <span style="color: red">*</span>
                                            {{ __('locale.bank.iban_attachment') }}
                                        </label>
                                        <input type="file" name="iban_attachment" id="iban_attachment" accept=".pdf"
                                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <!-- Error message element for iban_attachment -->
                                        <p id="iban_attachment_error" class="text-sm text-red-500"></p>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="bank_name" class="block text-sm font-medium text-gray-700">
                                            <span style="color: red">*</span>
                                            {{ __('locale.bank.name_bank') }}
                                        </label>
                                        <input type="text" name="bank_name" id="bank_name" autocomplete="off"
                                               placeholder="AlRajh"
                                               value="{{ old('bank_name') }}"
                                               class="move-on-enter mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <!-- Error message element for bank_name -->
                                        <p id="bank_name_error" class="text-sm text-red-500"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information section -->
                        <x-store-create.contact-information/>
                    </div>


                    <!-- Buttons for cancel, previous, and next -->
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="button" onclick="goBack()"
                                class="text-sm font-semibold leading-6 text-gray-900">{{ __('locale.button.cancel') }}</button>
                        <button type="button" onclick="prevStep()" id="prev_button" name="prev_button"
                                class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-600 shadow-sm hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-300">
                            {{ __('locale.button.back') }}
                        </button>
                        <button type="button" onclick="nextStep()" id="next_button"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            {{ __('locale.button.next') }}
                        </button>
                        <button id="save_button" type="submit"
                                class="hidden rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                style="display: none;">
                            {{ __('locale.button.save') }}
                        </button>
                    </div>
                </form>
            </section>

        </section>
    </div>


</x-app-admin-layout>

<script>

    window.Laravel = {!! json_encode([
        'locale' => app()->getLocale(),
    ]) !!};

    function numberOnly(id) {
        // Get element by id which passed as parameter within HTML element event
        var element = document.getElementById(id);
        // This removes any other character but numbers as entered by user
        element.value = element.value.replace(/[^0-9]/gi, "");
    }

    // JavaScript for the stepper
    let currentStep = 1;
    let save_button = document.getElementById('save_button');
    let next_button = document.getElementById('next_button'); // Assuming your "Next" button has an ID
    let prev_button = document.getElementById('prev_button');

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepper();
        }
    }

    async function nextStep() {
        // Disable the button to prevent multiple clicks
        document.getElementById('next_button').disabled = true;

        try {
            if (currentStep === 1) {
                // Perform validation for step 1 before moving to step 2
                if (validateStep1()) {
                    currentStep++;
                    updateStepper();
                }
            } else if (currentStep === 2) {
                // Perform validation for step 2 before moving to step 3
                const isValidStep2 = await validateStep2();
                if (isValidStep2) {
                    currentStep++;
                    updateStepper();
                }
            } else if (currentStep === 3) {
                // Perform validation for step 3 before moving to step 4
                const isValidStep3 = await validateStep3();
                if (isValidStep3) {
                    currentStep++;
                    updateStepper();
                }
            } else if (currentStep === 4) {
                // Perform validation for step 4 before completing the process
                const isValidStep4 = await validateStep4();
                if (isValidStep4) {
                    currentStep++;
                    updateStepper();
                }
            } else if (currentStep < 4) {
                // Check if the data for the current step is already saved or valid
                if (validateCurrentStepData(currentStep)) {
                    currentStep++;
                    updateStepper();
                }
            }
        } catch (error) {
            console.error('Error during next step:', error);
        } finally {
            // Enable the button again after all operations are completed
            document.getElementById('next_button').disabled = false;
        }
    }

    // Define an object with translations for different languages
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


    // function handleKeyDown(event) {
    //     if (event.keyCode === 13) {
    //         // Enter key was pressed, so call the validation function
    //         if (validateStep1()) {
    //             // The validation passed, you can perform any further actions here
    //         } else {
    //             // Validation failed, you can handle it as needed
    //         }
    //     }
    // }

    function getLocale() {
        return window.Laravel.locale; // Assumes Laravel sets the "locale" variable in a global context
    }

    // Your JavaScript functions for validation
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

    async function checkTaxIdNumber(tax_id_number) {

        const response = await fetch('/validation/validate-tax-id', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({tax_id: tax_id_number}),
        });

        if (response.status === 200) {
            return true;
        } else if (response.status === 422) {
            return false;
        } else {
            return false;
        }
        return false;
    }

    async function checkCommercialRegistration(commercial_registration) {
        const response = await fetch('/validation/validate-commercial-registration', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({commercial_registration: commercial_registration}),
        });

        if (response.status === 200) {
            return true;
        } else if (response.status === 422) {
            return false;
        } else {
            return false;
        }
    }

    async function checkMunicipalLicense(municipal_license) {
        const response = await fetch('/validation/validate-municipal-license', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({municipal_license: municipal_license}),
        });

        if (response.status === 200) {
            return true;
        } else if (response.status === 422) {
            return false;
        } else {
            return false;
        }
    }

    async function checkIban(iban_number) {
        const response = await fetch('/validation/validate-iban', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({iban_number: iban_number}),
        });

        if (response.status === 200) {
            return true;
        } else if (response.status === 422) {
            return false;
        } else {
            return false;
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


    function validateStep1() {
        const requiredFields = [
            'commercial_name_en',
            'commercial_name_ar',
            'short_name_en',
            'short_name_ar',
            'description_en',
            'description_ar',
            'logo',
            'file-input',
            'capacity'
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
            } else if (field === 'capacity') {
           
            if (!inputValue) {
                inputField.style.border = '2px solid red';
                if (errorSpan) errorSpan.textContent = translations[getLocale()].required;
                valid = false;
            } else {
                inputField.style.border = '2px solid green';
                if (errorSpan) errorSpan.textContent = '';
            }
        }else {
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

    async function validateStep2() {
        const requiredFields = [
            'business_type_id',
            'country_id',
            'tax_id_number',
            'tax_id_attachment',
            'commercial_registration_no',
            'commercial_registration_expiry',
            'commercial_registration_attachment',
            'municipal_license_no',
        ];

        let valid = true;

        for (const field of requiredFields) {
            console.log('Validating field: ' + field);
            const inputField = document.getElementById(field);

            if (inputField) {
                const inputValue = inputField.value.trim();
                const errorSpan = document.getElementById(field + '_error');

                if (field === 'tax_id_number') {
                    const isTaxIdValid = await checkTaxIdNumber(inputValue, errorSpan);

                    if (inputValue.length !== 15 || !/^\d+$/.test(inputValue)) {
                        console.log('Invalid tax ID number: ' + inputValue);
                        inputField.style.border = '2px solid red';
                        valid = false;
                        if (errorSpan) {
                            errorSpan.textContent = translations[getLocale()].tax_id_number;
                        }
                    } else {

                        console.log('Tax ID number is valid: ' + isTaxIdValid);
                        if (isTaxIdValid === false) {
                            inputField.style.border = '2px solid red';
                            valid = false;
                            if (errorSpan) {
                                errorSpan.textContent = translations[getLocale()].tax_id_number_used;
                            }
                        } else {
                            inputField.style.border = '2px solid green'; // Change border color to green for success
                            if (errorSpan) {
                                errorSpan.textContent = ''; // Clear the error message
                            }
                        }
                    }
                } else if (field === 'commercial_registration_no') {
                    const isCommercialRegistrationValid = await checkCommercialRegistration(inputValue, errorSpan);

                    if (inputValue.length !== 10 || !/^\d+$/.test(inputValue)) {
                        console.log('Invalid commercial registration number: ' + inputValue);
                        inputField.style.border = '2px solid red';
                        valid = false;
                        if (errorSpan) {
                            errorSpan.textContent = translations[getLocale()].commercial_registration_number;
                        }
                    } else {
                        console.log('Commercial registration number is valid: ' + isCommercialRegistrationValid);
                        if (isCommercialRegistrationValid === false) {
                            inputField.style.border = '2px solid red';
                            valid = false;
                            if (errorSpan) {
                                errorSpan.textContent = translations[getLocale()].commercial_registration_used;
                            }
                        } else {
                            inputField.style.border = '2px solid green'; // Set border to green when valid
                            if (errorSpan) {
                                errorSpan.textContent = ''; // Clear the error message
                            }
                        }
                    }
                } else if (field === 'commercial_registration_expiry') {
                    // Check if the date is in the correct format (iYYYY/iMM/iDD)
                    if (!/^\d{4}\/\d{2}\/\d{2}$/.test(inputValue)) {
                        console.log('Invalid commercial registration expiry date format: ' + inputValue);
                        inputField.style.border = '2px solid red';
                        valid = false;
                        if (errorSpan) {
                            errorSpan.textContent = translations[getLocale()].commercial_registration_expiry;
                        }
                    } else {
                        // You can optionally check if the date is a valid Hijri date here
                        inputField.style.border = '2px solid green'; // Set border to green when valid
                        if (errorSpan) {
                            errorSpan.textContent = ''; // Clear the error message
                        }
                    }
                } else if (field === 'municipal_license_no') {
                    // Allow it to be null or 11 digits
                    const isMunicipalLicenseValid = await checkMunicipalLicense(inputValue);

                    if (inputValue !== '' && !/^\d{11}$/.test(inputValue)) {
                        console.log('Invalid municipal license number: ' + inputValue);
                        inputField.style.border = '2px solid red';
                        valid = false;
                        if (errorSpan) {
                            errorSpan.textContent = translations[getLocale()].municipal_license_number;
                        }
                    } else {
                        console.log('Municipal license number is valid: ' + isMunicipalLicenseValid);
                        if (isMunicipalLicenseValid === false) {
                            inputField.style.border = '2px solid red';
                            valid = false;
                            if (errorSpan) {
                                errorSpan.textContent = translations[getLocale()].municipal_license_number_used;
                            }
                        } else {
                            inputField.style.border = '2px solid green'; // Set border to green when valid
                            if (errorSpan) {
                                errorSpan.textContent = ''; // Clear the error message
                            }
                        }
                    }
                } else if (inputValue === '' || inputValue === null) {
                    console.log('Field is empty: ' + field);
                    inputField.style.border = '2px solid red';
                    valid = false;
                    if (errorSpan) {
                        errorSpan.textContent = translations[getLocale()].required;
                    }
                } else {
                    inputField.style.border = '2px solid green'; // Set border to green when valid
                    if (errorSpan) {
                        errorSpan.textContent = ''; // Clear the error message
                    }
                }
            }
        }

        console.log('Validation result for step 2: ' + valid);
        return valid;
    }

    async function validateStep3() {
        const requiredFields = [
            'account_holder_name',
            'iban_number',
            'iban_attachment',
            'bank_name',
        ];

        let valid = true;

        for (const field of requiredFields) {
            console.log('Validating field: ' + field);
            const inputField = document.getElementById(field);
            const inputValue = inputField ? inputField.value.trim() : '';
            const errorSpan = document.getElementById(field + '_error');

            if (field === 'iban_attachment') {
                if (inputField && inputField.files.length === 0) {
                    console.log('No file selected for ' + field);
                    inputField.style.border = '2px solid red';
                    valid = false;
                    if (errorSpan) {
                        errorSpan.textContent = translations[getLocale()].file.replace(':attribute', field);
                    }
                } else {
                    inputField.style.border = '2px solid green'; // Set border to green when valid
                    if (errorSpan) {
                        errorSpan.textContent = ''; // Clear the error message
                    }
                }
            } else if (field === 'iban_number') {
                const isIbanValid = await checkIban(inputValue);

                // Check if IBAN is empty
                if (inputValue.trim() === '') {
                    console.log('IBAN is empty');
                    if (inputField) {
                        inputField.style.border = '2px solid red';
                    }
                    if (errorSpan) {
                        errorSpan.textContent = translations[getLocale()].required;
                    }
                    valid = false;
                } else if (!IBAN || !IBAN.isValid(inputValue)) {
                    // Validate the IBAN format based on Saudi Arabia using IBAN.js (if available)
                    console.log('Invalid IBAN format for Saudi Arabia: ' + inputValue);
                    if (inputField) {
                        inputField.style.border = '2px solid red';
                    }
                    if (errorSpan) {
                        errorSpan.textContent = translations[getLocale()].iban_format;
                    }
                    valid = false;
                } else {
                    console.log('IBAN is valid: ' + isIbanValid);
                    if (isIbanValid === false) {
                        if (inputField) {
                            inputField.style.border = '2px solid red';
                        }
                        if (errorSpan) {
                            errorSpan.textContent = translations[getLocale()].iban_used;
                        }
                        valid = false;
                    } else {
                        if (inputField) {
                            inputField.style.border = '2px solid green'; // Set border to green when valid
                        }
                        if (errorSpan) {
                            errorSpan.textContent = ''; // Clear the error message
                        }
                    }
                }
            } else {
                if (inputValue.trim() === '') {
                    console.log('Field ' + field + ' is empty');
                    if (inputField) {
                        inputField.style.border = '2px solid red';
                    }
                    if (errorSpan) {
                        errorSpan.textContent = translations[getLocale()].required;
                    }
                    valid = false;
                } else {
                    if (inputField) {
                        inputField.style.border = '2px solid green'; // Set border to green when valid
                    }
                    if (errorSpan) {
                        errorSpan.textContent = ''; // Clear the error message
                    }
                }
            }
        }

        console.log('Validation result for step 3: ' + valid);
        return valid;
    }

    async function validateStep4() {
        const requiredFields = ['contact_no', 'secondary_contact_no', 'terms'];

        let valid = true;

        for (const field of requiredFields) {
            console.log('Validating field: ' + field);
            const inputField = document.getElementById(field);
            const errorSpan = document.getElementById(field + '_error');

            if (field === 'terms') {
                const isChecked = inputField.checked;
                if (!isChecked) {
                    console.log('Terms and conditions not agreed');
                    valid = false;
                }
            } else {
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
        }

        console.log('Validation result: ' + valid);
        return valid;
    }

    //
    // // Add event listeners for input fields to trigger live validation
    // const inputFields = document.querySelectorAll('input[type="text"], textarea, input[type="file"],input[type="tel"]',);
    // inputFields.forEach(inputField => {
    //     inputField.addEventListener('input', () => {
    //         validateCurrentStepData(currentStep)
    //
    //     });
    // });

    // Function to validate the data for the current step
    function validateCurrentStepData(step) {

        if (step === 1) {
            validateStep1();

        } else if (step === 2) {
            validateStep2();

        } else if (step === 3) {
            validateStep3();

        } else if (step === 4) {
            validateStep4();

        }
        return true;
    }

    function isEnglishWordsWithNumbers(inputValue) {
        // Use a regular expression to check for English words and numbers
        return /^[A-Za-z0-9\s]*$/.test(inputValue.trim());
    }


    function updateStepper() {
        // Update the visual stepper elements and their styles
        for (let i = 1; i <= 4; i++) {
            const stepperElement = document.querySelectorAll('.stepper div')[i - 1];
            if (i === currentStep) {
                stepperElement.classList.remove('bg-gray-300', 'text-gray-500', 'mx-2');
                stepperElement.classList.add('bg-indigo-500', 'text-white', 'mx-2');

            } else {
                stepperElement.classList.remove('bg-indigo-500', 'text-white');
                stepperElement.classList.add('bg-gray-300', 'text-white');
            }
        }

        for (let i = 1; i <= 4; i++) {
            const stepElement = document.getElementById(`step${i}`);
            if (i === currentStep) {
                stepElement.style.display = 'block';
            } else {
                stepElement.style.display = 'none';
            }
        }

        // Handle the display of the save button
        if (currentStep === 4) {
            save_button.style.display = 'block';
            next_button.style.display = 'none'; // Hide the "Next" button in the last step (step 4)
            prev_button.style.display = 'block'; // Show the "Previous" button in the last step (step 4)
        } else if (currentStep === 1) {
            save_button.style.display = 'none';
            next_button.style.display = 'block'; // Show the "Next" button for other steps
            prev_button.style.display = 'none'; // Hide the "Previous" button in the first step (step 1)
        } else {
            save_button.style.display = 'none';
            next_button.style.display = 'block'; // Show the "Next" button for other steps
            prev_button.style.display = 'block'; // Show the "Previous" button for steps other than the first step
        }
    }

    // Initial update of the stepper
    updateStepper();
</script>


<!-- Add Cropper.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.min.css">--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/abublihi/datepicker-hijri@v1.1/build/datepicker-hijri.js"></script>
<script src="https://cdn.jsdelivr.net/npm/iban@0.0.14/iban.min.js"></script>


<script src="{{asset('js/owner/store/store.js')}}"></script>
<script src="{{ asset('js/datetimepicker.js') }}"></script>

<!------------------------- CSS ------------------------------->

<!-- Add Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">


<!---------------------- Custom CSS --------------------------->
<style>
    .iti {
        display: block !important;
        direction: ltr;
    }

    body {
        background: whitesmoke;
        font-family: 'Open Sans', sans-serif;
    }

    .container {
        max-width: 960px;
        margin: 30px auto;
        padding: 20px;
    }

    h1 {
        font-size: 20px;
        text-align: center;
        margin: 20px 0 20px;

        small {
            display: block;
            font-size: 15px;
            padding-top: 8px;
            color: gray;
        }

    }

    .avatar-upload {
        position: relative;
        max-width: 205px;
        margin: 50px auto;

        .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;

            input {
                display: none;

                + label {
                    display: inline-block;
                    width: 34px;
                    height: 34px;
                    margin-bottom: 0;
                    border-radius: 100%;
                    background: #FFFFFF;
                    border: 1px solid transparent;
                    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                    cursor: pointer;
                    font-weight: normal;
                    transition: all .2s ease-in-out;

                    &
                    :hover {
                        background: #f1f1f1;
                        border-color: #d6d6d6;
                    }

                    &
                    :after {
                        content: "\f040";
                        font-family: 'FontAwesome';
                        color: #757575;
                        position: absolute;
                        top: 10px;
                        left: 0;
                        right: 0;
                        text-align: center;
                        margin: auto;
                    }

                }
            }
        }

        .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);

            > div {
                width: 100%;
                height: 100%;
                border-radius: 100%;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }

        }
    }

    .avatar-upload {
        position: relative;
        max-width: 205px;
        margin: 50px auto;
    }

    .avatar-upload .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
    }

    .avatar-upload .avatar-edit input {
        display: none;
    }

    .avatar-upload .avatar-edit input + label {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-bottom: 0;
        border-radius: 100%;
        background: #FFFFFF;
        border: 1px solid transparent;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        font-weight: normal;
        transition: all 0.2s ease-in-out;
    }

    .avatar-upload .avatar-edit input + label:hover {
        background: #f1f1f1;
        border-color: #d6d6d6;
    }

    .avatar-upload .avatar-preview {
        width: 192px;
        height: 192px;
        position: relative;
        border-radius: 100%;
        border: 6px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload .avatar-preview > img {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .avatar-upload .avatar-edit input + label:after {
        font-family: 'FontAwesome';
        color: #757575;
        position: absolute;
        top: 10px;
        left: 0;
        right: 0;
        text-align: center;
        margin: auto;
    }
</style>


<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#logo").change(function () {
        readURL(this);
    });
</script>

<script>
    const ownerSearchInput = document.getElementById('owner-search');
    const searchResults = document.getElementById('search-results');
    const ownerIdInput = document.getElementById('owner_id');

    ownerSearchInput.addEventListener('input', function () {
        const searchQuery = this.value.trim();

        // Check if the search query is empty
        if (searchQuery === '') {
            hideSearchResults();
            return;
        }

        // Make an AJAX request to fetch owners based on the search query
        fetch(`/admin/stores/search/owners?query=${searchQuery}`)
            .then(response => response.json())
            .then(data => {
                showSearchResults(data);
            })
            .catch(error => {
                console.error('Error fetching owners:', error);
            });
    });

    function showSearchResults(results) {
        if (results.length === 0) {
            searchResults.innerHTML = '<p class="p-2">No results found.</p>';
            searchResults.classList.remove('hidden');
            return;
        }

        let html = '';
        results.forEach(result => {
            html += `<p class="p-2 cursor-pointer hover:bg-indigo-100" data-id="${result.id}" data-name="${result.name}" data-email="${result.email}" data-contact-no="${result.contact_no}">${result.id} - ${result.name} - ${result.email} - ${result.contact_no}</p>`;
        });

        searchResults.innerHTML = html;
        searchResults.classList.remove('hidden');
    }

    function hideSearchResults() {
        searchResults.innerHTML = '';
        searchResults.classList.add('hidden');
    }

    searchResults.addEventListener('click', function (event) {
        const clickedElement = event.target;
        if (clickedElement.tagName === 'P') {
            const ownerId = clickedElement.getAttribute('data-id');
            const ownerName = clickedElement.getAttribute('data-name');
            const ownerEmail = clickedElement.getAttribute('data-email');
            const ownerContactNo = clickedElement.getAttribute('data-contact-no');
            ownerSearchInput.value = `${ownerId} - ${ownerName} - ${ownerEmail} - ${ownerContactNo}`;
            hideSearchResults();

            ownerIdInput.value = ownerId;
        }
    });

    document.addEventListener('click', function (event) {
        const clickedElement = event.target;
        if (!searchResults.contains(clickedElement)) {
            hideSearchResults();
        }
    });
</script>
