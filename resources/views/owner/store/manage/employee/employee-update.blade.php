<x-app-layout>
    @php
        $locale = App::getLocale();
    @endphp
    @if(session('success'))
        <div id="success-alert" role="alert"
             class="rounded-xl border border-gray-100 bg-white p-4 mt-5 shadow-xl w-2/5 mx-auto">

            <div class="flex items-start gap-4">
    <span class="text-green-600">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
           class="h-6 w-6">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </span>

                <div class="flex-1">
                    <strong class="block font-medium text-gray-900">Changes saved</strong>

                    <p class="mt-1 text-sm text-gray-700">{{ session('success') }}</p>
                </div>

                <button id="close-alert" class="text-gray-500 transition hover:text-gray-600">
                    <span class="sr-only">Dismiss popup</span>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <script>
            // Show the success message
            document.getElementById('success-alert').classList.remove('hidden');

            // Hide the success message after 10 seconds
            setTimeout(function () {
                document.getElementById('success-alert').classList.add('hidden');
            }, 10000);

            // Close the success message when the button is clicked
            document.getElementById('close-alert').addEventListener('click', function () {
                document.getElementById('success-alert').classList.add('hidden');
            });
        </script>
    @endif

    <div class="container mx-auto rounded max-w-3xl">
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
                            <a href="{{ route('employee.manage', ['id' =>$id]) }}"
                               class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                                {{ $locale == 'ar' ?  $employee->employee_store->commercial_name_ar:   $employee->employee_store->commercial_name_en  }}
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="px-3 py-6 bg-white rounded-md">


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

            <div id="employee-form-container" class="max-w-3xl mx-auto p-4 bg-white rounded-lg">
                <h2 class="text-2xl  mb-4">
                    {{ __('locale.employee.update') }}
                </h2>

                <form action="{{ route('employee.update') }}" method="POST" class="space-y-4 mt-8" id="form"
                      name="form">


                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="full_name"
                                   class="block text-gray-700 "> {{ __('locale.employee.full_name') }}</label>
                            <input type="text" name="full_name" id="full_name" value="{{ $employee->name }}"
                                   class="input-field">
                            <span id="full-name-error" class="text-red-500"></span>

                        </div>

                        <div>
                            <label for="contact_no"
                                   class="block text-gray-700 ">  {{ __('locale.employee.phone') }}</label>

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
                                       value="{{$employee->contact_no}}"
                                       required
                                />
                            </div>
                            <p id="contact-no-error" class="text-sm text-red-500"></p>

                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="role_id"
                                   class="block text-gray-700 "> {{ __('locale.employee.position') }}</label>
                            <select name="role_id" id="role_id" required class="input-field">
                                @if ($employee && $employee->employee_branches->first())
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ optional($employee->employee_branches->first())->pivot->role_id == $role->id ? 'selected' : '' }}>

                                            {{ ($locale == 'ar' ? $role->name_ar : $role->name_en) }}                                        </option>
                                    @endforeach
                                @else
                                    <option value=""
                                            class="text-gray-500"> {{ __('locale.employee.position') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ ($locale == 'ar' ? $role->name_ar : $role->name_en) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div>
                            <label for="store_branch_id" class="block text-gray-700
                            ">    {{ __('locale.employee.branch_store') }}</label>
                            <select name="store_branch_id" id="store_branch_id" required class="input-field">
                                @if ($employee && $employee->employee_branches->first())
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ optional($employee->employee_branches->first())->pivot->store_branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ "$branch->id - " . ($locale == 'ar' ? $branch->name_ar : $branch->name_en) }}                                        </option>
                                    @endforeach
                                @else
                                    <option value="" class="text-gray-500">Select a store branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name_en }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="items-center space-y-2 grid-cols-2">
                        <div>
                            <label for="email" class="block text-gray-700 font-medium">
                                {{ __('locale.employee.email') }}
                            </label>
                            <input type="email" name="email" id="email" value="{{ $employee->email }}"
                                   class="input-field">
                            <span id="email-error" class="text-red-500"></span>

                        </div>
                        <div>
                            <label for="status" class="block text-gray-700 font-medium">
                                {{ __('locale.employee.status') }}
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="active" name="status" value="1" class="text-blue-600"
                                        {{ $employee->status == 1 ? 'checked' : '' }}>
                                <label for="active">{{__('locale.store_manage_employees.employee.active')}}</label>
                                <input type="radio" id="not_active" name="status" value="0" class="text-red-600"
                                        {{ $employee->status == 0 ? 'checked' : '' }}>
                                <label for="not_active">{{__('locale.store_manage_employees.employee.not_active')}}</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password"
                               class="block text-gray-700 font-medium">{{__('locale.employee.password')}}</label>
                        <input type="password" name="password" id="password" class="input-field">
                        <span id="password-error" class="text-red-500"></span>

                    </div>

                    <input type="hidden" id="employee_id" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" id="store_id" name="store_id" value="{{ $employee->store_id }}">

                    <button type="submit" id="update-button"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                        {{ __('locale.employee.update_button') }}
                    </button>
                </form>
            </div>

        </div>
    </div>


    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</x-app-layout>
<script>
    var phoneInput = document.querySelector("#contact_no");
    var countryCode = document.querySelector("#dial_code");
    const email_employee = '{{$employee->email}}';
    const contact_no_employee = '{{$employee->contact_no}}';

</script>

<script>
    function numberOnly(id) {
        // Get element by id which passed as parameter within HTML element event
        var element = document.getElementById(id);
        // This removes any other character but numbers as entered by user
        element.value = element.value.replace(/[^0-9]/gi, "");
    }

    document.addEventListener("DOMContentLoaded", function () {
        const formContainer = document.getElementById('employee-form-container');
        const form = document.getElementById('form');
        const fullNameInput = document.getElementById('full_name');
        const passwordInput = document.getElementById('password');
        const emailInput = document.getElementById('email');
        const fullNameError = document.getElementById('full-name-error');
        const passwordError = document.getElementById('password-error');
        const emailError = document.getElementById('email-error');
        const contactNoError = document.getElementById('contact-no-error');
        const contactNoInput = document.getElementById('contact_no');
        const updateButton = document.getElementById('update-button');


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

        updateButton.addEventListener('click', async function (event) {
            event.preventDefault(); // Prevent default button click behavior
            validateFullName();
            validatePassword();
            validateEmail();
            if (isValidEmail(emailInput.value)) {
                checkEmailAvailability(emailInput.value, emailError);
            }
            const isContactNoValid = await validateContactNo();

            if (!fullNameError.textContent && !passwordError.textContent && !emailError.textContent && !contactNoError.textContent && isContactNoValid) {
                form.submit(); // Manually submit the form if all validation passes
            }
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
                event.preventDefault(); // Prevent form submission if validation fails
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
                errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.password'));
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

            if (email_employee === emailInput.value) {
                errorSpan.textContent = '';
            } else {
                if (response.status === 200) {
                    errorSpan.textContent = '';
                } else if (response.status === 422) {
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.email_taken'));
                } else {
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.email_error'));
                }
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
            if (contact_no_employee === contactNoWithoutSpaces) {
                contactNoError.textContent = '';
                return true;
            } else {
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
        }


    });
</script>

<style>
    .iti {
        display: block !important;
        direction: ltr;
    }

    .input-field {
        appearance: none;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        width: 100%;
        color: #4b5563;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>
