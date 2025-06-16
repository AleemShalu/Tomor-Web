<x-app-admin-layout>
    <!-- Main content area -->
    <div class="py-8 px-4">
        <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
        <link rel="stylesheet"
              href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">


        <div class="w-full lg:w-8/12 px-4 mx-auto mt-6">
            <div
                    class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-gray-100 border-0">
                <div class="rounded-t bg-white mb-0 px-6 py-6">
                    <div class="text-center flex justify-between">
                        <h6 class="text-blueGray-700 text-xl font-bold">
                            {{ __('admin.user_management.users.create_user') }}
                        </h6>
                        <form action="{{ route('admin.users.register.store') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <button
                                    class="bg-blue-500 text-white active:bg-pink-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                                    type="submit">
                                {{ __('admin.common.save')}}
                            </button>
                    </div>
                </div>


                <h6 class="text-blueGray-400 text-sm mt-3 pl-3 mb-6 font-bold uppercase">
                    {{ __('admin.user_management.users.personal_info')}}
                </h6>

                <div class="flex flex-wrap">
                    <div class="w-full lg:w-6/12 px-4">
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                   htmlfor="grid-password">
                                {{ __('admin.user_management.users.user_name')}}
                            </label>
                            <input type="text" name="name" id="name"
                                   class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                   value="Lucky">
                            @error('name')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full lg:w-6/12 px-4">
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                   htmlfor="grid-password">
                                {{ __('admin.user_management.users.user_email')}}
                            </label>
                            <input type="email" id="email" name="email"
                                   class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                   value="jesse@example.com">
                            @error('email')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full lg:w-6/12 px-4">
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="grid-password">
                                {{ __('admin.user_management.users_list.role')}}
                            </label>
                            <select id="user_type" name="user_type"
                                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150">
                                <option value="owner">
                                    {{__('locale.api.roles.labels.owner')}}
                                </option>
                                <option value="customer">
                                    {{__('locale.api.roles.labels.customer')}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="w-full lg:w-6/12 px-4">
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                   htmlfor="grid-password">
                                {{ __('admin.user_management.users.user_password')}}
                            </label>
                            <input type="password" id="password" name="password"
                                   class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                   placeholder="Enter password">
                            @error('password')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="mt-6 border-b-1 border-blueGray-300">

                <h6 class="text-blueGray-400 text-sm mt-3 pl-3 mb-6 font-bold uppercase">
                    {{ __('admin.user_management.users.contact_information') }}
                </h6>
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-12/12 px-4">
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                                {{ __('admin.user_management.users.user_contact_no') }}
                            </label>
                            <div class="flex">
                                <input id="contact_no" name="contact_no" type="tel"
                                       class="intl-tel-input contact_no border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       placeholder="{{ __('admin.user_management.users.contact_number_placeholder') }}"
                                       value="{{ old('contact_no') }}"
                                       maxlength="9"
                                       oninput="updateHiddenContactNo()"/>

                                <input type="hidden" id="hidden_contact_no" name="hidden_contact_no"
                                       value="{{ old('contact_no') }}">
                                <input type="hidden" id="dial_code_contact_no" name="dial_code_contact_no" value="966">
                            </div>
                            <p id="contact_no_error" class="text-sm text-red-500">
                                @error('contact_no')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div id="specialNeedsDiv" class="relative w-full mb-3" style="display: none;">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2 pt-4">
                                {{ __('admin.user_management.users.special_needs') }}
                                <input type="checkbox" id="special_needs" name="special_needs"
                                       class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring ease-linear transition-all duration-150"
                                       placeholder="+996">
                            </label>
                            @error('special_needs')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="specialNeedsDescription" class="relative w-full mb-3" style="display: none;">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2 pt-4">
                                {{ __('admin.user_management.users.special_needs_description') }}
                            </label>
                            <input type="text" id="special_needs_description" name="special_needs_description"
                                   class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring ease-linear transition-all duration-150"
                                   placeholder="{{ __('admin.user_management.users.enter_description') }}">
                            @error('special_needs_description')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="specialNeedsAttachment" class="relative w-full mb-3" style="display: none;">
                            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2 pt-4">
                                {{ __('admin.user_management.users.special_needs_attachment') }}
                            </label>
                            <input type="file" id="special_needs_attachment" name="special_needs_attachment"
                                   class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring ease-linear transition-all duration-150">
                            @error('special_needs_attachment')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Initialize intlTelInput
        var input = document.querySelector("#contact_no");
        var iti = window.intlTelInput(input, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            initialCountry: "sa",  // Set the initial country to Saudi Arabia
            separateDialCode: true,
            placeholderNumberType: "MOBILE",
            onlyCountries: ['sa'], // Limit the countries to only Saudi Arabia
            nationalMode: false, // Optional: this depends on your use case
            autoHideDialCode: false // Keep the dial code visible at all times
        });

        // Update the hidden field on input change
        input.addEventListener("countrychange", function (e) {
            document.getElementById("dial_code_contact_no").value = iti.getSelectedCountryData().dialCode;
        });
    </script>


    <script>
        document.getElementById('user_type').addEventListener('change', function () {
            var selectedValue = this.value;
            var specialNeedsDiv = document.getElementById('specialNeedsDiv');
            var specialNeedsDescriptionDiv = document.getElementById('specialNeedsDescription');
            var specialNeedsAttachmentDiv = document.getElementById('specialNeedsAttachment');
            var specialNeedsCheckBox = document.getElementById('special_needs');

            if (selectedValue === 'customer') {
                specialNeedsDiv.style.display = 'block';
                specialNeedsDescriptionDiv.style.display = specialNeedsCheckBox.checked ? 'block' : 'none';
                specialNeedsAttachmentDiv.style.display = specialNeedsCheckBox.checked ? 'block' : 'none';
            } else {
                specialNeedsDiv.style.display = 'none';
                specialNeedsDescriptionDiv.style.display = 'none';
                specialNeedsAttachmentDiv.style.display = 'none';
            }
        });

        // Additionally, handle the initial state of the sections based on the checkbox
        document.getElementById('special_needs').addEventListener('change', function () {
            var specialNeedsDiv = document.getElementById('specialNeedsDiv');
            var specialNeedsDescriptionDiv = document.getElementById('specialNeedsDescription');
            var specialNeedsAttachmentDiv = document.getElementById('specialNeedsAttachment');

            if (this.checked) {
                specialNeedsDiv.style.display = 'block';
                specialNeedsDescriptionDiv.style.display = 'block';
                specialNeedsAttachmentDiv.style.display = 'block';
            } else {
                specialNeedsDiv.style.display = 'block';
                specialNeedsDescriptionDiv.style.display = 'none';
                specialNeedsAttachmentDiv.style.display = 'none';
            }
        });

        function updateHiddenContactNo() {
            var contactNoInput = document.getElementById('contact_no');
            var hiddenContactNoInput = document.getElementById('hidden_contact_no');
            hiddenContactNoInput.value = contactNoInput.value;
        }
    </script>
</x-app-admin-layout>


