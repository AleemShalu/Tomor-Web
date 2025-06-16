<x-app-layout>
    @php
        $locale = App::getLocale();
    @endphp
    <div class="container mx-auto py-8 px-4 rounded">
        <div class="py-4 w-full max-w-9xl mx-auto">

            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            {{ $locale == 'ar' ? 'الرئيسية' : 'Home' }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('store') }}"
                               class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                {{ $locale == 'ar' ? 'المتجر' : 'Store' }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('employee.manage', ['id' => $store->id]) }}"
                               class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                                {{ $locale == 'ar' ? $store->commercial_name_ar : $store->commercial_name_en }}
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        @if($hasStoreBranches === true)
            <div class="px-3 py-6 bg-white rounded">

                @if ($errors->any())

                    <div role="alert" class="rounded border-s-4 border-red-500 bg-red-50 p-4">
                        <strong class="block font-medium text-red-800"> Something went wrong </strong>
                        <p class="mt-2 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                        </p>
                        @endforeach

                    </div>
                    <br>
                @endif


                <h2 class="text-2xl font-bold mb-4">                        {{ __('locale.employee.register') }}
                </h2>
                <form action="{{ route('employee.store') }}" method="post" id="registration-form">
                    @csrf
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div>
                            <label for="position_id"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span style="color: red">*</span> {{ __('locale.employee.position') }}</label>

                            <select name="role_id" id="role_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}">{{ ($locale == 'ar' ? $position->name_ar : $position->name_en) }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div>
                            <label for="full_name"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span style="color: red">*</span>
                                {{ __('locale.employee.full_name') }}</label>

                            <input type="text" name="full_name" id="full_name"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="English">
                            <span id="full-name-error" class="text-red-500"></span>
                        </div>
                        <div>
                            <label for="password"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span style="color: red">*</span>
                                {{ __('locale.employee.password') }}</label>
                            <input type="password" name="password" id="password"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="">
                            <span id="password-error" class="text-red-500"></span>
                        </div>
                        <div>
                            <label for="email"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span style="color: red">*</span>
                                {{ __('locale.employee.email') }}</label>
                            <input type="email" name="email" id="email"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="">
                            <span id="email-error" class="text-red-500"></span>
                        </div>

                        <div>
                            <label for="contact_no"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span style="color: red">*</span>
                                {{ __('locale.employee.phone') }}</label>

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
                                       required
                                />
                            </div>
                            <p id="contact_no_error" class="text-sm text-red-500"></p>
                        </div>
                        <div>
                            <label for="store_branch_id"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span style="color: red">*</span>
                                {{ __('locale.employee.branch_store') }}</label>

                            <select name="store_branch_id" id="store_branch_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach($branches as $storeBranch)
                                    <option value="{{ $storeBranch->id }}">{{ "$storeBranch->id - " . ($locale == 'ar' ? $storeBranch->name_ar : $storeBranch->name_en) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input name="store_id" id="store_id" type="hidden" value="{{$store->id}}">
                    </div>
                    <br>
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        {{ __('locale.employee.register_button') }}
                    </button>
                </form>

            </div>
        @elseif($hasStoreBranches === false)
            <!-- Display when no store branches are found -->
            <div class="px-5 py-10 bg-white rounded text-center">
                <p class="text-lg text-gray-800">
            <span class="inline-flex items-center justify-center w-10 h-10 mr-2 rounded-full bg-red-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </span>
                    For add new employees you should be added new branch.
                    <a href="{{ route('branch.create',$store->id) }}"
                       class="text-blue-700 hover:text-blue-800 underline">Create a new branch</a>
                </p>
            </div>
        @endif
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function toggleRegisterButton() {
            var selectElement = document.getElementById('store_branch_id');
            var registerButton = document.getElementById('registerButton');

            if (selectElement.value === '') {
                registerButton.disabled = true;
            } else {
                registerButton.disabled = false;
            }
        }
    </script>
</x-app-layout>
<style>
    .iti {
        display: block !important;
        direction: ltr;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('registration-form');
        const fullNameInput = document.getElementById('full_name');
        const passwordInput = document.getElementById('password');
        const emailInput = document.getElementById('email');
        const fullNameError = document.getElementById('full-name-error');
        const passwordError = document.getElementById('password-error');
        const emailError = document.getElementById('email-error');
        const contactNoError = document.getElementById('contact-no-error');
        const contactNoInput = document.getElementById('contact_no');

        fullNameInput.addEventListener('input', function () {
            validateFullName();
        });

        passwordInput.addEventListener('input', function () {
            validatePassword();
        });

        emailInput.addEventListener('input', function () {
            validateEmail();
            if (isValidEmail(emailInput.value)) {
                checkEmailAvailability(emailInput.value, emailError);
            }
        });

        contactNoInput.addEventListener('input', function () {
            restrictContactNoInput();
            validateContactNo();
        });

        form.addEventListener('submit', function (event) {
            validateFullName();
            validatePassword();
            validateEmail();
            if (isValidEmail(emailInput.value)) {
                checkEmailAvailability(emailInput.value, emailError);
            }
            validateContactNo();
            if (fullNameError.textContent || passwordError.textContent || emailError.textContent || contactNoError.textContent) {
                event.preventDefault();
            }
        });

        function validateFullName() {
            fullNameError.textContent = '';

            // Define a regular expression to match names with at least three words
            const nameRegex = /^(\w+\s+\w+\s+\w+)$/;

            if (!nameRegex.test(fullNameInput.value.trim())) {
                fullNameError.textContent = @json(__('locale.validation_error_messages.validation_messages.name'));
            }
        }

        function validatePassword() {
            passwordError.textContent = '';

            const passwordValue = passwordInput.value.trim();

            if (passwordValue !== '' && passwordValue.length < 8) {
                passwordError.textContent = @json(__('locale.validation_error_messages.error_messages.password'));
            }
        }

        function validateEmail() {
            emailError.textContent = '';
            const emailValue = emailInput.value.trim();

            if (isValidEmail(emailValue)) {
                emailError.textContent = '';
            } else {
                emailError.textContent =  @json(__('locale.validation_error_messages.error_messages.email'));
            }
        }

        function isValidEmail(email) {
            const emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
            return emailRegex.test(email);
        }

        async function checkEmailAvailability(email, errorSpan) {
            const response = await fetch('/validation/validate-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({email: email}),
            });


            if (response.status === 200) {
                errorSpan.textContent = '';
            } else if (response.status === 422) {
                errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.email_taken'));
            } else {
                errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.email_error'));
            }
        }

        function restrictContactNoInput() {
            // Remove any non-numeric characters and limit the length to 9 digits
            contactNoInput.value = contactNoInput.value.replace(/[^0-9]/g, '').substring(0, 9);
        }

        async function validateContactNo() {
            contactNoError.textContent = '';
            const contactNoValue = contactNoInput.value.trim().replace(/\s/g, ''); // Remove spaces
            const contactNoWithoutSpaces = contactNoValue.replace(/\s/g, '');

            if (!/^\d{9}$/.test(contactNoValue)) {
                // Check if it has exactly 9 digits
                contactNoError.textContent = @json(__('locale.validation_error_messages.validation_messages.contact_no'));
                return false;
            }

            const response = await fetch('/validation/validate-contact-no', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token if using Laravel
                },
                body: JSON.stringify({contact_no: contactNoValue}),
            });
            if (response.status === 200) {
                // The contact number is available
                contactNoError.textContent = '';
                return true;
            } else if (response.status === 422) {
                // The contact number is already taken
                contactNoError.textContent = @json(__('locale.validation_error_messages.error_messages.contact_no_taken'));
                return false;
            } else {
                // Handle other responses (e.g., server error)
                return false;
            }
        }
    });
</script>
