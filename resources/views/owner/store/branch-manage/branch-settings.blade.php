<x-app-layout>

    <x-branch-header :branch="$branch"></x-branch-header>

    <div class="bg-white m-4 rounded-lg p-8 font-sans">
        <div class="text-2xl font-bold p-3 mt-3">
            <i class="fa-solid fa-gears mr-2"></i>
            {{__('locale.branch_settings.title')}}
        </div>
        <div class="p-3 w-4/5">
            <p>
                {{__('locale.branch_settings.description')}}
            </p>
        </div>
        <br>
        <div class="flex flex-wrap">
            <div class="w-1/2 sm:w-2/4 md:w-2/2 lg:w-2/4 xl:w-2/4">
                <div class="bg-gray-100 p-4 mb-4 mx-2 rounded shadow border border-slate-300 hover:border-indigo-300">
                    <!-- Card 1 content -->
                    <h3 class="text-xl font-bold"><i class="fas fa-user mr-2"></i>
                        {{__('locale.branch_settings.contact_details.title')}}
                    </h3>
                    <hr class="border-gray-300 dark:border-gray-700 w-full mt-2">
                    <p class="pt-3 pb-3">
                        {{__('locale.branch_settings.contact_details.description')}}

                    </p>

                    <form action="{{ route('updateBranchContact') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}">

                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="business-name-ar">
                                {{__('locale.branch_settings.contact_details.branch_name_arabic')}}
                            </label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text"
                                   id="business-name-ar" name="business-name-ar" placeholder=""
                                   value="{{$branch->name_ar}}"
                                   oninput="validateLanguageInput('business-name-ar', 'ar')">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="business-name-en">
                                {{__('locale.branch_settings.contact_details.branch_name_english')}}
                            </label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text"
                                   id="business-name-en" name="business-name-en" placeholder=""
                                   value="{{$branch->name_en}}"
                                   oninput="validateLanguageInput('business-name-en', 'en')">
                        </div>


                        <div class="flex mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="mobile-number">
                                {{__('locale.branch_settings.contact_details.mobile_number')}}
                            </label>
                            <select id="country_code" name="country_code"
                                    class="block appearance-none text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 px-3 shadow-sm focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="966" {{ $branch->dial_code == '966' ? 'selected' : '' }}>+966</option>
                            </select>
                            <!-- Phone Number Input -->
                            <input id="contact_no" name="contact_no" type="tel"
                                   class="w-full px-3 py-2 border border-gray-300 rounded"
                                   placeholder="{{ __('locale.contact.contact_number_placeholder') }}"
                                   oninput="numberOnly(this.id);"
                                   pattern="[0-9]{9}"
                                   maxlength="9"
                                   title="Please enter a 9-digit number"
                                   value="{{ $branch->contact_no }}"
                            />
                        </div>
                        <div class="w-1/2 mb-3">
                            <label class="block font-bold text-gray-700" for="email">
                                {{__('locale.branch_settings.contact_details.email')}}
                            </label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded" type="email" id="email"
                                   name="email" placeholder="" value="{{$branch->email}}">
                        </div>
                        <button class="bg-blue-500 mt-3 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                type="submit">
                            {{__('locale.branch_settings.contact_details.update_button')}}
                        </button>
                    </form>
                </div>
            </div>

            <div class="w-1/2 sm:w-2/4 md:w-2/2 lg:w-2/4 xl:w-2/4">
                <div class="bg-gray-100 p-4 mb-4 mx-2 rounded shadow border border-slate-300 hover:border-indigo-300">
                    <!-- Card 2 content -->
                    <h3 class="text-xl font-bold"><i
                                class="fas fa-user mr-2"></i>
                        {{__('locale.branch_settings.commercial_information.title')}}
                    </h3>
                    <hr class="border-gray-300 dark:border-gray-700 w-full mt-2">
                    <p class="pt-3 pb-3">
                        {{__('locale.branch_settings.commercial_information.description')}}
                    </p>
                    <form action="{{ route('updateBranchCommercial') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}">

                        <div class="flex mb-4">
                            <div class="mr-2 w-1/2">
                                <label class="block mb-2 font-bold text-gray-700" for="commercial-registration-no">
                                    {{__('locale.branch_settings.commercial_information.registration_number')}}
                                </label>
                                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text"
                                       id="commercial-registration-no" name="commercial-registration-no"
                                       placeholder="{{ __('locale.tax.tax_id_number_input') }}"
                                       maxlength="10"
                                       oninput="numberOnly(this.id);"
                                       value="{{ old('commercial-registration-no', $branch->commercial_registration_no) }}">

                                @error('commercial-registration-no')
                                <p class="text-red-500 mt-2 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mr-2 w-1/2">
                                <label class="block mb-2 font-bold text-gray-700" for="commercial-registration-expiry">
                                    {{__('locale.branch_settings.commercial_information.registration_expiry')}}
                                </label>

                                <!-- x-date-converter Component for Hijri Date -->
                                <x-date-converter inputName="commercial_registration_expiry" saveAs="gregorian"
                                                  :initialValue="$branch->commercial_registration_expiry"></x-date-converter>


                                @error('commercial_registration_expiry')
                                <p class="text-red-500 mt-2 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700" for="commercial-registration-attachment">
                                {{__('locale.branch_settings.commercial_information.registration_attachment')}}
                            </label>
                            <input class="input input-bordered w-full" type="file"
                                   id="commercial-registration-attachment" name="commercial-registration-attachment"
                                   accept=".pdf,.doc,.docx">
                            @if($branch->commercial_registration_attachment)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $branch->commercial_registration_attachment) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline text-sm">{{ __('locale.store.view_attachment') }}</a>
                                </div>
                            @endif
                            @error('commercial-registration-attachment')
                            <p class="text-red-500 mt-2 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                type="submit">
                            {{__('locale.branch_settings.commercial_information.update_button')}}

                        </button>
                    </form>
                </div>
            </div>

            <div class="w-full sm:w-2/4 md:w-full lg:w-full xl:w-full">
                <div class="bg-gray-100 p-4 mb-4 mx-2 rounded shadow border border-slate-300 hover:border-indigo-300">
                    <!-- Card 3 content -->
                    <h3 class="text-xl font-bold"><i class="fa-solid fa-map-location-dot mr-2"></i>
                        {{__('locale.branch_settings.location.title')}}

                    </h3>
                    <hr class="border-gray-300 dark:border-gray-700 w-full mt-2">
                    <p class="pt-3 pb-3">
                        {{__('locale.branch_settings.location.description')}}
                    </p>

                    <div class="sm:col-span-2">
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">

                            {{__('locale.branch_settings.location.google_maps_label')}}

                        </label>
                        <form action="{{ route('updateBranchLocation') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}">
                            <div>
                                <label for="city_id"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><span
                                            style="color: red">*</span>
                                    {{__('locale.branch_settings.location.city_label')}}
                                </label>
                                <select name="city_id" id="city_id"
                                        class="bg-gray-50 border border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    @foreach (\App\Models\City::all() as $city)
                                        <option value="{{ $city->id }}"
                                                {{ ($city->id == $branchLocation->city->id) ? 'selected' : '' }}
                                                data-longitude="{{ $branchLocation->location->longitude }}"
                                                data-longitude-city="{{$city->center_longitude}}"
                                                data-latitude="{{ $branchLocation->location->latitude }}"
                                                data-latitude-city="{{$city->center_latitude}}"
                                                data-isSet="{{ ($branchLocation->location->longitude != 0 && $branchLocation->location->latitude != 0) ? 'true' : 'false' }}">
                                            {{ app()->getLocale() === 'ar' ? $city->ar_name : $city->en_name }}
                                        </option>
                                    @endforeach

                                </select>
                                <br>
                            </div>
                            <div id="accordion" class="mx-2">
                                <div class="card rounded-lg border border-gray-300 w-full mb-4">
                                    <div id="collapseMap" class="collapsing" aria-labelledby="headingMap"
                                         data-parent="#accordion">
                                        <div class="card-body" id="card-body-map">
                                            <div class="row d-flex justify-content-center">
                                                <div class="input-group">
                                                    <input id="pac-input" type="text" name="SearchDualList"
                                                           placeholder="search"
                                                           class="form-control bg-light border-0 small"
                                                           aria-label="Search" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button id="search-button" class="btn btn-dark" type="button">
                                                            <i class="fas fa-search fa-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="modal-body text-center" style="height: 50vh;"
                                                     id="map"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row justify-start mb-4">
                                    <div class="col-lg-6 col-md-12 col-sm-12 flex justify-start">
                                        <div class="col-lg-11 col-md-10 col-sm-12">

                                            <div class="form-group">
                                                <label for="district"><span
                                                            class="text-red-500">*</span>
                                                    {{__('locale.branch_settings.location.district_label')}}
                                                </label>
                                                <input placeholder="" type="text" name="district" id="district"
                                                       class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                       value="{{ $branchLocation->location->district }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 flex justify-start pt-2">
                                        <div class="col-lg-11 col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <label for="location"><span class="text-red-500">*</span>
                                                    {{__('locale.branch_settings.location.location_url_label')}}
                                                    <span class="ml-1 font-bold text-red-600 text-xs">
                                                        Select the location from the map
                                                    </span></label>
                                                <label for="Location_URL"></label><input id="Location_URL"
                                                                                         placeholder="" type="text"
                                                                                         name="location"
                                                                                         class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                                                         value="{{ $branchLocation->location->google_maps_url }}"
                                                                                         required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row flex flex-row justify-start items-center mb-4 ">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="longitude"><span style="color: red">*</span>
                                                {{__('locale.branch_settings.location.longitude_label')}}

                                            </label>
                                            <input placeholder="" type="text"
                                                   name="longitude" id="longitude"
                                                   class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                   value="{{ $branchLocation->location->longitude }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="latitude"><span style="color: red">*</span>
                                                {{__('locale.branch_settings.location.latitude_label')}}
                                            </label>
                                            <input placeholder="" type="text"
                                                   name="latitude" id="latitude"
                                                   class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                   value="{{ $branchLocation->location->latitude  }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="location_radius"
                                                   class="block text-sm font-medium text-gray-700">
                                                <span style="color: red">*</span>{{__('locale.branch.location_radius')}}
                                                <small>({{__('locale.branch.number_in_meters')}})</small>
                                                <br>
                                                <small>
                                                    {{ __(
                                                        'locale.radius_format',
                                                        [
                                                            'min_meter' => $config->min_radius,
                                                            'max_meter' => $config->max_radius
                                                        ]
                                                    ) }}
                                                </small>

                                            </label>

                                            <input placeholder="" type="number" name="location_radius"
                                                   id="location_radius"
                                                   value="{{ isset($branchLocation) ? $branchLocation->location->location_radius : '' }}"
                                                   min="{{$config->min_radius}}" max="{{$config->max_radius}}"
                                                   class="mt-1 p-2.5 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-600 focus:border-primary-600">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                    type="submit">
                                {{__('locale.branch_settings.location.update_button')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="flex grid-col-2">
                <div class="">
                    <div class="bg-gray-100 p-4 mb-4 mx-2 rounded shadow border border-slate-300 hover:border-indigo-300">
                        <h3 class="text-xl font-bold"><i class="fa-solid fa-clock-rotate-left mr-3"></i>
                            {{__('locale.branch_settings.work_status.title')}}
                        </h3>
                        <hr class="border-gray-300 dark:border-gray-700 w-full mt-2">
                        <p class="pt-3 pb-3">
                            {{__('locale.branch_settings.work_status.description')}}
                        </p>

                        @if (session('success_update_work_status'))
                            <div class="bg-green-100 text-green-700 p-4 rounded mt-4">
                                {{ session('success_update_work_status') }}
                            </div>
                        @endif

                        <!-- Display errors for work status -->
                        @if ($errors->hasBag('errors_update_work_status'))
                            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                                <ul>
                                    @foreach ($errors->errors_update_work_status->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('updateWorkStatus') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}">

                            <label for="work-status" class="block mb-2 font-bold text-gray-700">
                                {{__('locale.branch_settings.work_status.status_label')}}
                            </label>
                            <select id="work-status" name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option value="active" {{ $branch->work_statuses[0]->status === 'active' ? 'selected' : '' }}>
                                    {{__('locale.branch_settings.work_status.active_label')}} ✅
                                </option>
                                <option value="inactive" {{ $branch->work_statuses[0]->status === 'inactive' ? 'selected' : '' }}>
                                    {{__('locale.branch_settings.work_status.inactive_label')}} ❌
                                </option>
                            </select>
                            @error('status', 'errors_update_work_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <div class="mt-4">
                                <label class="block mb-2 font-bold text-gray-700" for="work-start-time">
                                    {{__('locale.branch_settings.work_status.start_time_label')}}
                                </label>
                                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text"
                                       id="work-start-time"
                                       name="start_time"
                                       value="{{  $branch->work_statuses[0]->start_time}}"
                                       data-input>
                                @error('start_time', 'errors_update_work_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="block mb-2 font-bold text-gray-700" for="work-end-time">
                                    {{__('locale.branch_settings.work_status.end_time_label')}}
                                </label>
                                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text"
                                       id="work-end-time"
                                       name="end_time"
                                       value="{{ $branch->work_statuses[0]->end_time }}"
                                       data-input>
                                @error('end_time', 'errors_update_work_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <br>
                            <input id="timzone" name="timezone" type="hidden">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                    type="submit">
                                {{__('locale.branch_settings.work_status.update_button')}}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="">
                    <div
                            class="bg-gray-100 p-4 mb-4 mx-2 rounded shadow border border-slate-300 hover:border-indigo-300">
                        <!-- Card 5 content -->
                        <h3 class="text-xl font-bold"><i class="fa-solid fa-qrcode mr-3"></i>
                            {{__('locale.branch_settings.bank_account.title')}}
                        </h3>
                        <hr class="border-gray-300 dark:border-gray-700 w-full mt-2">
                        <p class="pt-3 pb-3">
                            {{__('locale.branch_settings.bank_account.description')}}
                        </p>

                        <div class="">
                            <div>
                                <form action="{{ route('updateBranchBank') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}">

                                    <label for="bank_account_id" class="block text-sm font-medium text-gray-700">
                                        {{__('locale.branch_settings.bank_account.bank_account_label')}}
                                    </label>
                                    <select id="bank_account_id" name="bank_account_id"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                                        @foreach($branch->store->bank_accounts as $bankAccount)
                                            {{$bankAccount}}
                                            <option
                                                    value="{{ $bankAccount->id }}" {{ $bankAccount->id == $branch->bank_account->id ? 'selected' : '' }}>
                                                {{ $bankAccount->account_holder_name . ' - ' . $bankAccount->iban_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <br>

                                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                            type="submit">
                                        {{__('locale.branch_settings.bank_account.update_button')}}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    flatpickr('#work-start-time', {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i:S",
    });

    flatpickr('#work-end-time', {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i:S",
    });


    function numberOnly(id) {
        // Get element by id which passed as parameter within HTML element event
        var element = document.getElementById(id);
        // This removes any spaces
        element.value = element.value.replace(/\s+/g, '');
        // This removes any other character but numbers as entered by user
        element.value = element.value.replace(/[^0-9]/gi, "");
    }

    // Function to clean the initial value
    function cleanInitialValue(id) {
        var element = document.getElementById(id);
        element.value = element.value.replace(/\s+/g, '').replace(/[^0-9]/gi, "");
    }

    // Call the cleanInitialValue function on page load
    document.addEventListener('DOMContentLoaded', function () {
        cleanInitialValue('contact_no');
    });

    document.addEventListener("DOMContentLoaded", function () {
        let clientTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.getElementById('timzone').value = clientTimezone;
    });
</script>
<script src="{{ asset('js/update_location.js') }}"></script>
<script>
    var input_1 = document.querySelector("#mobile-number");
    window.intlTelInput(input_1, {
        initialCountry: "SA",  // Set initial country to Saudi Arabia
        separateDialCode: true,
        nationalMode: false,   // Allow users to input phone number without country code
        utilsScript: "path/to/intl-tel-input/build/js/utils.js"
    });

    input_1.addEventListener("input", function () {
        var inputValue = input_1.value.trim();
        var regex = /^5\d{8}$/; // Regex to match phone numbers starting with 5 and having exactly nine digits

        if (regex.test(inputValue)) {
            // Valid phone number
            input_1.setCustomValidity(""); // Clear any previous validation message
        } else {
            // Invalid phone number
            input_1.setCustomValidity("Please enter a valid phone number starting with 5 and containing nine digits.");
        }
    });

</script>
<script>
    function validateLanguageInput(inputId, languageCode) {
        var input = document.getElementById(inputId);
        var inputValue = input.value;
        var isArabic = /[\u0600-\u06FF]/.test(inputValue); // Check if input contains Arabic characters
        var isEnglish = /[a-zA-Z]/.test(inputValue); // Check if input contains English characters

        if ((languageCode === 'ar' && isEnglish) || (languageCode === 'en' && isArabic)) {
            // Clear the input if the wrong language is detected
            input.value = '';
            alert('Please switch to the correct language: ' + languageCode.toUpperCase());
        }
    }
</script>
