<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-4  w-full max-w-9xl mx-auto">

        <nav class="flex" aria-label="Breadcrumb">
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
                        <a href="{{route('store')}}"
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
                        <a href="{{route('branch.manage',['id'=>$storeId])}}"
                           class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{$store->commercial_name_en}}</a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <section class="bg-white dark:bg-gray-900 w-full">
            <div class="py-8 px-4 mx-auto max-w-6xl lg:py-16">
                @if ($errors->any())
                    <div
                            class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
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
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-store"></i>
                    {{__('locale.branch.add_new_branch')}}
                </h2>
                <form action="{{route('branch.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="store_id" name="store_id" value="{{$storeId}}">
                    <div class="grid gap-2 grid-cols-2  bg-gray-100 rounded-xl p-3">
                        <div class="">
                            <label for="name_en"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><span
                                        style="color: red">*</span>
                                {{__('locale.branch.name_branch_en')}}
                            </label>
                            <input type="text" name="name_en" id="name_en"
                                   class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="" required="" value="{{old('name') }}">
                        </div>
                        <div class="">
                            <label for="name_ar"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><span
                                        style="color: red">*</span>
                                {{__('locale.branch.name_branch_ar')}}
                            </label>
                            <input type="text" name="name_ar" id="name_ar"
                                   class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="" required="" value="{{old('name') }}">
                        </div>
                        <div class="">
                            <label for="commercial_registration_no"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><span
                                        style="color: red">*</span>
                                {{__('locale.branch.commercial_registration_number')}}

                            </label>
                            <input type="text" name="commercial_registration_no" id="commercial_registration_no"
                                   class="block w-full rounded-md border-0  text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="{{ old('commercial_registration_no') }}"
                                   maxlength="10">
                        </div>
                        <div class="">
                            <label for="commercial_registration_expiry"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span style="color: red">*</span>
                                {{ __('locale.branch.commercial_registration_expiry') }}
                            </label>
                            <x-date-converter inputName="commercial_registration_expiry"
                                              saveAs="gregorian"></x-date-converter>

                        </div>


                        <div class="">
                            <label for="category"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><span
                                        style="color: red">*</span>
                                {{__('locale.branch.commercial_registration_attachment')}}

                            </label>
                            <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    id="commercial_registration_attachment" name="commercial_registration_attachment"
                                    type="file"
                                    accept=".pdf, .docx, .doc">

                        </div>
                        <div>
                            <label for="city_id"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><span
                                        style="color: red">*</span>
                                {{__('locale.branch.city')}}

                            </label>
                            <select name="city_id" id="city_id"
                                    class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                            {{ (isset($project) && $city->id == $project->city->id) ? 'selected' : (old('city_id') == $city->id ? 'selected' : '') }} data-longitude="{{ isset($project) && $project->longitude != 0 && $project->latitude != 0 ? $project->longitude : $city->center_longitude }}"
                                            data-latitude="{{ isset($project) && $project->longitude != 0 && $project->latitude != 0 ? $project->latitude : $city->center_latitude }}"
                                            data-isSet="{{ isset($project) && $project->longitude != 0 && $project->latitude != 0 ? 'true' : 'false' }}">
                                        {{ $city->en_name }}
                                    </option>
                                @endforeach
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="bg-gray-100 p-3 rounded-xl mt-5">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-map-marker-alt"></i> {{__('locale.branch.add_location_for_branch')}}

                        </h2>
                        <div class="sm:col-span-2">
                            <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                {{__('locale.branch.google_maps')}}
                                <div>
                                    <span class="ml-1 font-bold text-red-600 text-xs">Select the location from the map</span>

                                </div>
                            </label>

                            <div id="accordion" class="mx-2">
                                <div class="card  mt-3 rounded-lg shadow-md border-2 w-full  py-4 bg-gray-50 ">
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
                                                <div class="modal-body" style="height: 50vh;" id="map"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>


                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div class="col-span-1">
                                        <label for="district" class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> {{__('locale.branch.district')}}
                                        </label>
                                        <input placeholder="" type="text" name="district" id="district"
                                               value="{{ isset($project) ? $project->district : old('district') }}"
                                               required
                                               class="mt-1 p-2.5 bg-gray-200 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-600 focus:border-primary-600">
                                    </div>
                                    <div class="col-span-1">
                                        <label for="location" class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> {{__('locale.branch.location_url')}}
                                        </label>
                                        <input id="Location_URL" placeholder="" type="text" name="location"
                                               id="location"
                                               value="{{ isset($project) ? $project->location : old('location') }}"
                                               required
                                               class="mt-1 p-2.5 bg-gray-200 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-600 focus:border-primary-600">
                                    </div>
                                    <div class="col-span-1">
                                        <label for="longitude" class="block text-sm font-medium text-gray-700">
                                            <span style="color: red">*</span> {{__('locale.branch.longitude')}}
                                        </label>
                                        <input type="text"
                                               name="longitude" id="longitude"
                                               class="mt-1 p-2.5 bg-gray-200 block w-full rounded-md border-gray-300 text-gray-900 shadow-sm focus:ring-primary-600 focus:border-primary-600"
                                               value="{{ isset($project) ? $project->longitude : '' }}"
                                        >
                                    </div>
                                    <div class="col-span-1">
                                        <label for="latitude" class="block text-sm font-medium text-gray-700">
                                            <span style="color: red">*</span> {{__('locale.branch.latitude')}}
                                        </label>
                                        <input type="text" name="latitude"
                                               id="latitude"
                                               class="mt-1 p-2.5 bg-gray-200 block w-full rounded-md border-gray-300 text-gray-900 shadow-sm focus:ring-primary-600 focus:border-primary-600"
                                               value="{{ isset($project) ? $project->latitude : '' }}"
                                        >
                                    </div>
                                    <div class="col-span-1">
                                        <label for="location_radius" class="block text-sm font-medium text-gray-700">
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
                                        <input placeholder="" type="number" name="location_radius" id="location_radius"
                                               value="{{ isset($project) ? $project->location_radius : '' }}"
                                               min="{{$config->min_radius}}" max="{{$config->max_radius}}"
                                               class="mt-1 p-2.5 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-600 focus:border-primary-600">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <hr class="h-px my-4 w-full bg-gray-200 border-0 dark:bg-gray-700">
                    <div class="bg-gray-100 rounded-xl p-3">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            <i class="fas fa-address-book mr-2"></i>{{__('locale.branch.bank_information')}}
                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">{{__('locale.branch.bank_selection_info')}}</p>
                        <br>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <label for="bank_account_id" class="block text-sm font-medium text-gray-700">
                                    {{__('locale.branch.bank_account')}}
                                </label>
                                <select id="bank_account_id" name="bank_account_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                                    @foreach($bankAccounts as $bankAccount)
                                        <option
                                                value="{{ $bankAccount->id }}">{{ $bankAccount->account_holder_name . ' - ' . $bankAccount->iban_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr class="h-px my-4 w-full bg-gray-200 border-0 dark:bg-gray-700">

                    <div class="bg-gray-100 rounded-xl p-3">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            <i class="fas fa-address-book mr-2"></i> {{__('locale.branch.contact')}}

                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">  {{__('locale.branch.notification_info')}}</p>
                        <br>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium leading-6 text-gray-900"><span
                                            style="color: red">*</span> {{__('locale.branch.email')}}</label>
                                <div class="mt-2">
                                    <input type="email" name="email" id="email"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                           placeholder="Enter your email address" value="{{ old('email') }}">
                                </div>
                            </div>

                            <br>

                            <div>
                                <label for="contact_no" class="block text-sm font-medium leading-6 text-gray-900"><span
                                            style="color: red">*</span>{{__('locale.branch.contact_number')}}</label>
                                <div class="mt-2">
                                    <div class="flex items-center">
                                        <!-- Country Code Select -->
                                        <div class="relative">
                                            <select id="dial_code" name="dial_code"
                                                    class="block appearance-none text-center w-24 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 py-2 px-3 shadow-sm focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option value="966">+966</option>
                                                <!-- Add more options for other countries as needed -->
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                {{-- <!-- Country Code Arrow Icon --> --}}
                                                {{-- <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
                                                {{-- <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
                                                {{-- </svg> --}}
                                            </div>
                                        </div>
                                        <!-- Phone Number Input -->
                                        <input id="contact_no" name="contact_no" type="tel"
                                               class="ml-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 shadow-sm focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                               placeholder="{{ __('locale.contact.contact_number_placeholder') }}"
                                               oninput="numberOnly(this.id);"
                                               pattern="[0-9]{9}"
                                               maxlength="9"
                                               title="Please enter a 9-digit number"
                                               value="{{$store->contact_no}}"
                                               required
                                        />
                                    </div>
                                    <p id="contact_no_error" class="text-sm text-red-500"></p>

                                </div>
                            </div>
                        </div>
                    </div>


                    <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        {{__('locale.branch.create_branch')}}
                    </button>
                </form>
            </div>

        </section>
    </div>
</x-app-layout>

<script>
    function goBack() {
        window.history.back();
    }
</script>


<script src="{{ asset('js/datetimepicker.js') }}"></script>
<script src="{{ asset('js/project.js') }}"></script>

<!-- Inline CSS based on choices in "Settings" tab -->
<style>
    @font-face {
        font-family: "heiti";
        font-style: normal;
        font-weight: normal;
        src: local("heiti"), local("heiti"),
        url("{{ storage_path('fonts/heiti.otf') }}") format("truetype");
    }

    .ar {
        font-family: heiti !important;
    }

    .bootstrap-iso .formden_header h2,
    .bootstrap-iso .formden_header p,
    .bootstrap-iso form {
        font-family: Arial, Helvetica, sans-serif;
        color: black
    }

    .bootstrap-iso form button,
    .bootstrap-iso form button:hover {
        color: white !important;
    }

    .asteriskField {
        color: red;
    }

    button[id="search-button"] {
        height: 32px;
        width: 50px;
        background-color: #ffffff;
        border: 1px solid #000000;
        border-radius: 0 3px 3px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #pac-input {
        width: 300px;
        height: 32px;
        background-color: #ffffff !important;
        border-radius: 3px 0 0 3px;

    }
</style>

<script>
    function numberOnly(id) {
        // Get element by id which passed as parameter within HTML element event
        var element = document.getElementById(id);
        // This removes any other character but numbers as entered by user
        element.value = element.value.replace(/[^0-9]/gi, "");
    }
</script>
</body>

<style>
    .iti {
        display: block !important;
        direction: ltr;
    }
</style>
