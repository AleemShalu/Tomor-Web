@php
    $locale = app()->getLocale();
@endphp
<x-app-layout>
    <x-store-create-form :businesses="$businesses" :countries="$countries" :errors="$errors"/>
</x-app-layout>

<!------------------------- JS ------------------------------->


<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css">

<!-- Add Cropper.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/abublihi/datepicker-hijri@v1.1/build/datepicker-hijri.js"></script>
<script src="https://cdn.jsdelivr.net/npm/iban@0.0.14/iban.min.js"></script>


<script src="{{asset('js/owner/store/store.js')}}"></script>
<script src="{{ asset('js/datetimepicker.js') }}"></script>

<script>

    function numberOnly(id) {
        // Get element by id which passed as parameter within HTML element event
        var element = document.getElementById(id);
        // This removes any other character but numbers as entered by user
        element.value = element.value.replace(/[^0-9]/gi, "");
    }


    window.Laravel = {!! json_encode([
        'locale' => app()->getLocale(),
    ]) !!};


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

    // Add event listeners for input fields to trigger live validation
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

    function validateFileType(input) {
        const allowedExtensions = ['png', 'jpg', 'jpeg'];
        const maxFileSizeInKB = 520; // 520 kilobytes (5 MB)

        const file = input.files[0];

        // Check if a file is selected
        if (file) {
            // Check file extension
            const fileName = file.name.toLowerCase();
            const fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Invalid file type. Please select a PNG, JPG, or JPEG file.');
                input.value = ''; // Clear the input
                return;
            }

            // Check file size
            const fileSizeInKB = file.size / 1024; // Convert to kilobytes
            if (fileSizeInKB > maxFileSizeInKB) {
                alert('File size exceeds the limit. Please select a file with a size not greater than 520 kilobytes.');
                input.value = ''; // Clear the input
                return;
            }
        }
    }

    function validateFileTypePdf(input) {
        const allowedExtensions = ['pdf'];
        const maxFileSizeInKB = 1024; // 1024 kilobytes

        const file = input.files[0];

        // Check if a file is selected
        if (file) {
            // Check file extension
            const fileName = file.name.toLowerCase();
            const fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Invalid file type. Please select a PDF file.');
                input.value = ''; // Clear the input
                return;
            }

            // Check file size
            const fileSizeInKB = file.size / 1024; // Convert to kilobytes
            if (fileSizeInKB > maxFileSizeInKB) {
                alert('File size exceeds the limit. Please select a PDF file with a size not greater than 1024 kilobytes.');
                input.value = ''; // Clear the input
                return;
            }
        }
    }

</script>
