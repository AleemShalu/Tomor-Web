@props(['id', 'name', 'placeholder', 'value' => ''])

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css">
<style>
    .iti-flag.sa {
        background-image: url('https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/img/flags.png');
        background-position: 0 -1045px; /* Position of Saudi Arabia flag in the sprite */
        width: 20px; /* Adjust as needed */
        height: 15px; /* Adjust as needed */
    }
</style>

<div>
    <input id="{{ $id }}" type="tel" name="{{ $name }}"
           oninput="numberOnly(this.id);"
           class="{{ $name }} border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
           placeholder="{{ $placeholder }}" value="{{ $value }}"/>

    <!-- Hidden input for dial code -->
    <input id="dial_code_{{ $id }}" type="hidden" name="dial_code_{{ $name }}" value=""/>

    <!-- Error message -->
    <span id="phone-error_{{ $id }}" class="text-red-500 hidden">Please enter a valid Saudi phone number.</span>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var input = document.querySelector("#{{ $id }}");
            var iti = window.intlTelInput(input, {
                initialCountry: "sa",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js"
            });

            // Set initial value if provided
            var initialValue = "{{ $value ?? '' }}";
            if (initialValue) {
                // Clean initial value
                initialValue = initialValue.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                iti.setNumber(initialValue);
                input.value = initialValue;
            }

            // Listen for input changes to validate the number
            input.addEventListener('input', function () {
                var isValid = iti.isValidNumber();
                var phoneNumber = iti.getNumber('national');
                var dialCode = iti.getSelectedCountryData().dialCode;

                // Remove spaces from the phone number
                phoneNumber = phoneNumber.replace(/\s/g, '');

                // Update hidden input for dial code
                document.querySelector("#dial_code_{{ $id }}").value = dialCode;

                // Example validation: Saudi numbers should start with 5 and have 9 digits
                var saudiRegex = /^5\d{8}$/;
                if (isValid && phoneNumber.match(saudiRegex)) {
                    document.querySelector("#phone-error_{{ $id }}").classList.add('hidden');
                } else {
                    document.querySelector("#phone-error_{{ $id }}").classList.remove('hidden');
                }
            });
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
    </script>
@endpush
