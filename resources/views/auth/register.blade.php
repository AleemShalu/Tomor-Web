<x-authentication-layout>
    <h1 class="text-3xl text-slate-800 font-bold mb-6">{{ __('locale.auth.register.create_account_title') }}</h1>
    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" id="registration-form">
        @csrf
        <div class="space-y-4">
            <div>
                <x-jet-label for="name">{{ __('locale.auth.register.full_name') }} <span class="text-rose-500">*</span>
                </x-jet-label>
                <x-jet-input id="name" type="text" name="name" :value="old('name')" autofocus
                             autocomplete="name"/>
                <span id="name-error"></span>

            </div>

            <div>
                <span id="responseMessage"></span>
                <x-jet-label for="email">{{ __('locale.auth.register.email') }} <span class="text-rose-500">*</span>
                </x-jet-label>
                <div class="flex">
                    <x-jet-input id="email" type="text" name="email" :value="old('email')"/>
                </div>
                <span id="email-error"></span>
            </div>

            <!-- Additional form fields with translations -->
            <div>
                <x-jet-label for="contact_no">{{ __('locale.auth.register.phone') }} <span
                            class="text-rose-500">*</span>
                </x-jet-label>
                {{--                <x-phone-number-input id="contact_no" name="contact_no" placeholder="{{ __('locale.auth.register.phone_placeholder') }}"/>--}}

                <div class="flex items-center">
                    <!-- Country Code Select -->
                    <div class="relative">
                        <select id="dial_code" name="dial_code"
                                class="block appearance-none text-center w-24 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 py-2 px-3 shadow-sm focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="966">+966</option>
                        </select>
                    </div>
                    <!-- Phone Number Input -->
                    <input id="contact_no" name="contact_no" type="tel"
                           class="ml-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 shadow-sm focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                           placeholder="{{ __('locale.contact.contact_number_placeholder') }}"
                           oninput="numberOnly(this.id);"
                           pattern="[0-9]{9}"
                           maxlength="9"
                           title="Please enter a 9-digit number"
                           value=""
                    />
                </div>

                <span id="contact-no-error"></span>
            </div>
            <div>
                <x-jet-label for="password" value="{{ __('locale.auth.register.password') }}"/>
                <div class="password-input-container relative">
                    <x-jet-input id="password" type="password" name="password" autocomplete="new-password"/>
                    <span id="password-toggle" class="password-toggle absolute top-0 right-2 mt-2 mr-2"
                          aria-label="Toggle password visibility">
            <i class="far fa-eye"></i>
        </span>
                </div>
                <span id="password-error"></span>
            </div>

            <div>
                <x-jet-label for="password_confirmation" value="{{ __('locale.auth.register.confirm_password') }}"/>
                <div class="password-input-container relative">
                    <x-jet-input id="password_confirmation" type="password" name="password_confirmation"
                                 autocomplete="new-password"/>
                    <span id="password-confirmation-toggle" class="password-toggle absolute top-0 right-2 mt-2 mr-2"
                          aria-label="Toggle password visibility">
            <i class="far fa-eye"></i>
        </span>
                </div>
                <span id="password-confirmation-error"></span>
            </div>


        </div>
        <!-- Usher Section -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-indigo-600" id="isFromUsher"
                       onchange="toggleUsherInput()"/>
                <span class="ml-2 text-sm">{{ __('locale.auth.register.i_am_from_usher') }}</span>
            </label>
        </div>
        <div class="mt-4" id="usherCodeDiv" style="display: none;">
            <label for="usher_code"
                   class="block text-sm font-medium text-gray-700">{{ __('locale.auth.register.usher_code') }}</label>
            <input id="usher_code" type="text" name="usher_code"
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
        </div>
        <span id="usher-code-error"></span>
        <br>

        {{--        <div>--}}
        {{--            {!! NoCaptcha::display() !!}--}}
        {{--        </div>--}}
        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <label class="flex items-start">
                    <input type="checkbox" class="form-checkbox mt-1" name="terms" id="terms"/>
                    <span class="text-sm ml-2">
                            {!! __('locale.auth.register.privacy', [
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-sm underline hover:no-underline">'.__('locale.auth.register.privacy_policy').'</a>',
                            ]) !!}
                        </span>
                </label>
            </div>
            <div id="terms-error" class="text-red-500"></div>

        @endif
        <div class="flex items-center justify-between mt-6">
            <x-jet-button id="register-button">
                {{ __('locale.auth.register.register_button') }}
            </x-jet-button>
        </div>

    </form>
    <x-jet-validation-errors class="mt-4"/>
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-slate-200">
        <div class="text-sm">
            {{ __('locale.auth.register.already_registered') }} <a
                    class="font-medium text-indigo-500 hover:text-indigo-600"
                    href="{{ route('login') }}">{{ __('locale.auth.register.sign_in') }}</a>
        </div>
    </div>
</x-authentication-layout>
<style>
    #contact_no {
        padding-right: 31px;
    }
</style>
<script>
    function toggleUsherInput() {
        const checkbox = document.getElementById('isFromUsher');
        const codeDiv = document.getElementById('usherCodeDiv');

        if (checkbox.checked) {
            codeDiv.style.display = 'block';
            return this;
        } else {
            codeDiv.style.display = 'none';
            return false
        }
    }


</script>


<script>

    function numberOnly(id) {
        var element = document.getElementById(id);
        element.value = element.value.replace(/[^0-9]/gi, "");
    }


    document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const passwordToggle = document.getElementById('password-toggle');
            const passwordConfirmationToggle = document.getElementById('password-confirmation-toggle');
            const usherCodeInput = document.getElementById('usher_code');

            usherCodeInput.addEventListener('input', liveValidateUsherCode);

            passwordToggle.addEventListener('click', function () {
                togglePasswordVisibility(passwordInput, passwordToggle);
            });

            passwordConfirmationToggle.addEventListener('click', function () {
                togglePasswordVisibility(passwordConfirmationInput, passwordConfirmationToggle);
            });

            function togglePasswordVisibility(inputElement, toggleElement) {
                if (inputElement.type === 'password') {
                    inputElement.type = 'text';
                    toggleElement.innerHTML = '<i class="far fa-eye-slash"></i>';
                } else {
                    inputElement.type = 'password';
                    toggleElement.innerHTML = '<i class="far fa-eye"></i>';
                }
            }

            function liveValidateUsherCode() {
                const usherCode = document.getElementById('usher_code').value;
                const isUsherChecked = document.getElementById('isFromUsher').checked;
                const usherCodeErrorSpan = document.getElementById('usher-code-error');

                if (isUsherChecked) {
                    const isUsherCodeEmpty = usherCode === null || usherCode.trim() === "";

                    usherCodeErrorSpan.textContent = isUsherCodeEmpty ? 'Usher code must be not empty' : '';
                    usherCodeErrorSpan.classList.toggle('text-red-500', isUsherCodeEmpty);

                    if (!isUsherCodeEmpty) {
                        // Make an Ajax request to validate the usher code
                        fetch('/validation/validate-usher-code', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({usher_code: usherCode})
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message === 'founded') {
                                    usherCodeErrorSpan.textContent = '';  // Clear error message
                                    usherCodeErrorSpan.classList.remove('text-red-500');
                                } else {
                                    usherCodeErrorSpan.textContent = @json(__('locale.validation_error_messages.validation_messages.usher_code_not_found'));
                                    usherCodeErrorSpan.classList.add('text-red-500');
                                    console.log('Usher code is not valid');
                                }
                            })
                            .catch(error => {
                                console.error('Error during usher code validation:', error);
                                usherCodeErrorSpan.textContent = @json(__('locale.validation_error_messages.validation_messages.usher_code_not_error'));
                                usherCodeErrorSpan.classList.add('text-red-500');
                            });
                    }
                    return !isUsherCodeEmpty;
                } else {
                    usherCodeErrorSpan.textContent = '';
                    usherCodeErrorSpan.classList.remove('text-red-500');
                    return true;
                }
            }

            const registrationForm = document.getElementById('registration-form');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const contactNoInput = document.getElementById('contact_no');
            const contactNoErrorSpan = document.getElementById('contact-no-error');
            const termsCheckbox = document.getElementById('terms');
            const termsErrorSpan = document.getElementById('terms-error');

            if (nameInput && emailInput && contactNoInput && passwordInput && passwordConfirmationInput) {
                // Add event listeners for live input validation
                nameInput.addEventListener('input', () => liveValidateInput(nameInput, 'name-error'));
                emailInput.addEventListener('input', () => liveValidateEmail(emailInput, 'email-error'));
                contactNoInput.addEventListener('input', () => liveValidateContactNo(contactNoInput, 'contact-no-error'));

                if (contactNoInput) {
                    contactNoInput.addEventListener('input', function (event) {
                        let inputValue = event.target.value;
                        let numericValue = inputValue.replace(/\D/g, '');

                        // تأكد من أن الرقم لا يزيد عن 9 أرقام
                        if (numericValue.length > 9) {
                            numericValue = numericValue.slice(0, 9);
                        }

                        // تأكد من أن الرقم يبدأ بالرقم 5
                        if (numericValue.length >= 1 && numericValue[0] !== '5') {
                            contactNoErrorSpan.textContent = @json(__('locale.validation_error_messages.validation_messages.contact_no'));
                            contactNoErrorSpan.classList.add('text-red-500');
                        } else {
                            contactNoErrorSpan.textContent = '';
                            contactNoErrorSpan.classList.remove('text-red-500');
                        }

                        // إعادة تعيين قيمة الحقل بالأرقام فقط
                        event.target.value = numericValue;
                    });
                }

                passwordInput.addEventListener('input', () => liveValidatePassword(passwordInput, 'password-error'));
                passwordConfirmationInput.addEventListener('input', () => liveValidatePasswordConfirmation(passwordConfirmationInput, 'password-confirmation-error'));
                contactNoInput.addEventListener('input', () => liveValidateContactNo(contactNoInput, 'contact-no-error'));

                registrationForm.addEventListener('submit', async function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    let valid = true;

                    if (!await liveValidateInput(nameInput, 'name-error')) {
                        valid = false;
                    }

                    const isEmailValid = await liveValidateEmail(emailInput, 'email-error');
                    console.log(isEmailValid);
                    if (!isEmailValid) {
                        valid = false;
                    }

                    const isContactNoValid = await liveValidateContactNo(contactNoInput, 'contact-no-error');
                    console.log(isContactNoValid);

                    if (!isContactNoValid) {
                        valid = false;
                    }

                    if (!liveValidatePassword(passwordInput, 'password-error')) {
                        valid = false;
                    }

                    if (!liveValidatePasswordConfirmation(passwordConfirmationInput, 'password-confirmation-error')) {
                        valid = false;
                    }

                    if (!validateTermsCheckbox(termsCheckbox, termsErrorSpan)) {
                        valid = false;
                    }

                    if (!await liveValidateUsherCode()) {
                        valid = false;
                    }

                    if (valid) {
                        // Proceed with form submission
                        registrationForm.submit();
                    }
                });
            }

            // دالة للتحقق من صحة خانة الشروط والأحكام
            function validateTermsCheckbox(checkbox, errorSpan) {
                if (checkbox.checked) {
                    errorSpan.textContent = '';
                    errorSpan.classList.remove('text-red-500');
                    return true;
                } else {
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.terms'));
                    errorSpan.classList.add('text-red-500');
                    return false;
                }
            }

            function liveValidateInput(inputElement, errorId) {
                const errorSpan = document.getElementById(errorId);
                const inputValue = inputElement.value.trim();

                // Define a regular expression to match names with at least three words
                const nameRegex = /^(\w+\s+\w+\s+\w+)$/;

                if (nameRegex.test(inputValue)) {
                    inputElement.classList.remove('border-red-500');
                    errorSpan.textContent = ''; // Clear the error message
                    errorSpan.classList.remove('text-red-500'); // Remove red text effect
                    return true;
                } else {
                    inputElement.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.validation_messages.name'));
                    errorSpan.classList.add('text-red-500'); // Apply red text effect
                    return false;
                }
            }

            async function checkContactNoAvailability(contactNo, errorSpan) {
                const response = await fetch('/validation/validate-contact-no', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({contact_no: contactNo}),
                });

                if (response.status === 200) {
                    // Contact number is available
                    contactNoInput.classList.remove('border-red-500');
                    errorSpan.textContent = '';
                    return true;
                } else if (response.status === 422) {
                    // Contact number is already taken
                    contactNoInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.contact_no_taken'));
                    errorSpan.classList.add('text-red-500');
                    return false;
                } else {
                    // Handle other responses (e.g., server error)
                    contactNoInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.contact_no_error'));
                    errorSpan.classList.add('text-red-500');
                    return false;

                }
            }

            async function liveValidateContactNo(contactNoInput, errorId) {
                const errorSpan = document.getElementById(errorId);
                const contactNoValue = contactNoInput.value.trim();

                const saudiRegex = /^5\d{8}$/; // Matches 9-digit numbers starting with '5'

                if (saudiRegex.test(contactNoValue)) {
                    contactNoInput.classList.remove('border-red-500');
                    errorSpan.textContent = ''; // Clear the error message

                    const isContactNoAvailable = await checkContactNoAvailability(contactNoValue, errorSpan);
                    if (isContactNoAvailable === true) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    contactNoInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.validation_messages.contact_no'));
                    errorSpan.classList.add('text-red-500'); // Apply red text effect
                    return false;
                }
            }


            function liveValidatePassword(passwordInput, errorId) {
                const errorSpan = document.getElementById(errorId);
                const passwordValue = passwordInput.value.trim();

                if (passwordValue.length >= 8) {
                    passwordInput.classList.remove('border-red-500');
                    errorSpan.textContent = '';
                    return true;
                } else {
                    passwordInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.password'));
                    errorSpan.classList.add('text-red-500');
                    return false;
                }
            }

            function liveValidatePasswordConfirmation(passwordConfirmationInput, errorId) {
                const errorSpan = document.getElementById(errorId);
                const passwordConfirmationValue = passwordConfirmationInput.value.trim();

                if (passwordConfirmationValue === passwordInput.value) {
                    passwordConfirmationInput.classList.remove('border-red-500');
                    errorSpan.textContent = ''; // تفريغ رسالة الخطأ
                    return true;
                } else {
                    passwordConfirmationInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.password_confirmation'));
                    errorSpan.classList.add('text-red-500');
                    return false;
                }
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
                    // Email is available
                    emailInput.classList.remove('border-red-500');
                    errorSpan.textContent = '';
                    return true;
                } else if (response.status === 422) {
                    // Email is already taken
                    emailInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.email_taken'));
                    errorSpan.classList.add('text-red-500');
                    return false;
                } else {
                    // Handle other responses (e.g., server error)
                    emailInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.email_error'));
                    errorSpan.classList.add('text-red-500');
                    return false;
                }
            }

            async function liveValidateEmail(emailInput, errorId) {
                const emailValue = emailInput.value.trim();
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                const errorSpan = document.getElementById(errorId);

                if (emailValue.match(emailPattern)) {
                    emailInput.classList.remove('border-red-500');
                    errorSpan.textContent = '';
                    const isEmailAvailable = await checkEmailAvailability(emailValue, errorSpan);
                    if (isEmailAvailable === true) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    emailInput.classList.add('border-red-500');
                    errorSpan.textContent = @json(__('locale.validation_error_messages.error_messages.email'));
                    errorSpan.classList.add('text-red-500');
                    return false;

                }
            }


        }
    );
</script>
