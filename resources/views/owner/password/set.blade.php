<!-- resources/views/set.blade.php -->
<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-2 pt-2 w-full max-w-9xl mx-auto">

        <div class="flex justify-center items-center min-h-screen bg-gray-100">
            <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ __('locale.set_password.title') }}</h2>

                <form method="POST" action="{{ route('password.update.new-user') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="contact_no" class="block text-gray-700 font-medium mb-2">
                            {{ __('locale.set_password.contact_no_label') }}
                        </label>
                        <input id="contact_no" type="tel" name="contact_no"
                               class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="e.g., 5XXXXXXXX" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium mb-2">
                            {{ __('locale.set_password.password_label') }}
                        </label>
                        <input id="password" type="password" name="password"
                               class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                               minlength="8" required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">
                            {{ __('locale.set_password.confirm_password_label') }}
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                               minlength="8" required>
                    </div>

                    <div class="mb-4">
                        <label for="terms" class="flex items-center">
                            <input id="terms" type="checkbox" name="terms" class="mr-2" required>
                            <span class="text-sm ml-2">
                            {!! __('locale.auth.register.terms_and_privacy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-sm underline hover:no-underline">'.__('locale.auth.register.terms_of_service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-sm underline hover:no-underline">'.__('locale.auth.register.privacy_policy').'</a>',
                            ]) !!}
                        </span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 focus:bg-indigo-600 focus:outline-none">{{ __('locale.set_password.submit_button') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
<!-- Other head content -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.min.js"></script>
<script>
    var input = document.querySelector("#contact_no");
    window.intlTelInput(input, {
        initialCountry: "SA",  // Set initial country to Saudi Arabia
        separateDialCode: true,
        nationalMode: false,   // Allow users to input phone number without country code
        utilsScript: "path/to/intl-tel-input/build/js/utils.js"
    });

    input.addEventListener("input", function () {
        var inputValue = input.value.trim();
        var regex = /^5\d{8}$/; // Regex to match phone numbers starting with 5 and having exactly nine digits

        if (regex.test(inputValue)) {
            // Valid phone number
            input.setCustomValidity(""); // Clear any previous validation message
        } else {
            // Invalid phone number
            input.setCustomValidity("Please enter a valid phone number starting with 5 and containing nine digits.");
        }
    });
</script>
<script>
    var passwordInput = document.querySelector("#password");
    var passwordConfirmationInput = document.querySelector("#password_confirmation");

    passwordInput.addEventListener("input", function () {
        var passwordValue = passwordInput.value.trim();
        var minLength = 8;

        if (passwordValue.length >= minLength) {
            // Valid password length
            passwordInput.setCustomValidity("");
        } else {
            // Invalid password length
            passwordInput.setCustomValidity("Password must be at least 8 characters long.");
        }
    });

    passwordConfirmationInput.addEventListener("input", function () {
        var passwordValue = passwordInput.value.trim();
        var passwordConfirmationValue = passwordConfirmationInput.value.trim();

        if (passwordValue === passwordConfirmationValue) {
            // Passwords match
            passwordConfirmationInput.setCustomValidity("");
        } else {
            // Passwords do not match
            passwordConfirmationInput.setCustomValidity("Passwords do not match.");
        }
    });

    var termsCheckbox = document.querySelector("#terms");

    termsCheckbox.addEventListener("change", function () {
        if (termsCheckbox.checked) {
            // User has agreed to the terms
            termsCheckbox.setCustomValidity("");
        } else {
            // User has not agreed to the terms
            termsCheckbox.setCustomValidity("You must agree to the terms.");
        }
    });
</script>