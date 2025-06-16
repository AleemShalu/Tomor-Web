@php
    $locale = app()->getLocale();
@endphp

<x-app-admin-layout>
    <div class="rounded-t bg-white mb-0 px-6 py-6">
        <div class="header justify-between items-center">
            <div class="flex items-center">
                <i class="fa-solid fa-shop text-xl mr-2"></i>
                <div class="font-bold text-xl ml-4 mr-4">
                    {{ __('admin.store_management.promoters.create_new') }}
                </div>
            </div>
        </div>
        <div class="mb-3 text-right"></div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="post" action="{{ route('admin.promoters.store') }}">

                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @csrf

                    <!-- Code -->
                    <div class="mb-4">
                        <label for="code"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.code') }}</label>
                        <input type="text" name="code" id="code"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <!-- Store ID -->
                    <div class="mb-4">
                        <label for="store_id"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.store') }}</label>
                        <select name="store_id" id="store_id"
                                class="form-select rounded-md shadow-sm mt-1 block w-full">
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->short_name_en }}</option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Name (English) -->
                    <div class="mb-4">
                        <label for="name_en"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.name_en') }}</label>
                        <input type="text" name="name_en" id="name_en"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <!-- Name (Arabic) -->
                    <div class="mb-4">
                        <label for="name_ar"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.name_ar') }}</label>
                        <input type="text" name="name_ar" id="name_ar"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <!-- Description (English) -->
                    <div class="mb-4">
                        <label for="description_en"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.description_en') }}</label>
                        <textarea name="description_en" id="description_en"
                                  class="form-textarea rounded-md shadow-sm mt-1 block w-full" rows="3"></textarea>
                    </div>

                    <!-- Description (Arabic) -->
                    <div class="mb-4">
                        <label for="description_ar"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.description_ar') }}</label>
                        <textarea name="description_ar" id="description_ar"
                                  class="form-textarea rounded-md shadow-sm mt-1 block w-full" rows="3"></textarea>
                    </div>


                    <div class="bg-gray-100 p-4 rounded-md">
                        <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                            <span style="color: red">*</span>{{ __('admin.store_management.promoters.page.header') }}
                        </h2>
                        <!-- input file -->
                        <div class="box">
                            <input name="header" type="file" class="rounded bg-gray-100 mb-2"
                                   accept="image/*" required id="file-input"
                                   oninput="validateFileType(this)">
                        </div>
                        <!-- leftbox -->
                        <div class="box-2">
                            <div class="result" style="border-radius:90px;"></div>
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
                               onclick="deleteImage()">{{ __('admin.store_management.promoters.delete') }}</a>
                            <!-- save btn -->
                            <a href="#"
                               class="save bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded"
                               onclick="saveCroppedImage()">{{ __('admin.store_management.promoters.save_cropped') }}</a>
                        </div>
                    </div>
                    <span id="header_error" class="text-red-500 text-sm"></span>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.status') }}</label>
                        <input type="checkbox" name="status" id="status" value="1"
                               class="form-checkbox h-5 w-5 text-indigo-600"/>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-4">
                        <label for="start_date"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.start_date') }}</label>
                        <input type="datetime-local" name="start_date" id="start_date"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <!-- End Date -->
                    <div class="mb-4">
                        <label for="end_date"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.end_date') }}</label>
                        <input type="datetime-local" name="end_date" id="end_date"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('admin.store_management.promoters.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</x-app-admin-layout>

<!-- Add Cropper.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<!-- Add Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

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
    function goBack() {
        window.history.back();
    }

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

    document.addEventListener('DOMContentLoaded', function () {
        const inputFields = document.querySelectorAll('.move-on-enter');

        for (let i = 0; i < inputFields.length; i++) {
            inputFields[i].addEventListener('keypress', function (event) {
                const key = event.which || event.keyCode;
                if (key === 13) { // Check if Enter key is pressed
                    event.preventDefault();
                    const nextIndex = i + 1;
                    if (nextIndex < inputFields.length) {
                        inputFields[nextIndex].focus();
                    }
                }
            });
        }
    });

    function updateLogo(event) {
        var logoIcon = document.getElementById('logoIcon');
        var file = event.target.files[0];

        // Check if a file is selected
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                logoIcon.innerHTML = '<image xlink:href="' + e.target.result + '" height="24" width="24" />';
            }
            reader.readAsDataURL(file);
        }
    }

    const upload = document.getElementById('file-input');
    const result = document.querySelector('.result');
    result.innerHTML = '<img src="https://placehold.co/720x300" alt="Placeholder" style="border-radius: 10px">';
    const save = document.querySelector('.save');
    const deleteBtn = document.querySelector('.delete');
    const options = document.querySelector('.options');
    const cropped = document.querySelector('.cropped');
    const img_result = document.querySelector('.img-result');

    let cropper;

    // on change show image with crop options
    upload.addEventListener('change', (e) => {
        if (e.target.files.length) {
            // start file reader
            const reader = new FileReader();
            reader.onload = (e) => {
                if (e.target.result) {
                    // create new image
                    let img = document.createElement('img');
                    img.id = 'image';
                    img.src = e.target.result;
                    // clean result before
                    result.innerHTML = '';
                    // append new image
                    result.appendChild(img);
                    // show save btn, delete button, and options
                    save.classList.remove('hidden');
                    deleteBtn.classList.remove('hidden');
                    options.classList.remove('hidden');
                    // init cropper with aspect ratio 500:1200
                    cropper = new Cropper(img, {
                        viewMode: 1,
                        aspectRatio: 4 / 1, // Updated to 4:1 aspect ratio
                        minContainerWidth: 300,
                        minContainerHeight: 200,
                        movable: true
                    });

                }
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            // If no file is selected, set a placeholder image and hide buttons
            result.innerHTML = '<img src="https://placehold.co/720x300" alt="Placeholder" style="border-radius: 10px">';
            save.classList.add('hidden');
            deleteBtn.classList.add('hidden');
            options.classList.add('hidden');
            cropped.classList.add('hidden');
            img_result.classList.add('hidden');
        }
    });

    function deleteImage() {
        // Remove the selected image and display the placeholder image
        result.innerHTML = '<img src="https://placehold.co/720x300" alt="Placeholder" style="border-radius: 10px">';
        save.classList.add('hidden');
        deleteBtn.classList.add('hidden');
        options.classList.add('hidden');
        cropped.classList.add('hidden');
        img_result.classList.add('hidden');
        result.classList.remove('hidden'); // Remove the 'hide' class to make the result heading visible

        cropped.src = ''; // Reset the cropped image source
        cropper.destroy(); // Destroy the Cropper instance
    }


    function saveCroppedImage() {
        // get result to data uri
        let imgSrc = cropper.getCroppedCanvas().toDataURL(); // No need to specify width

        result.classList.add('hidden'); // Remove the 'hide' class to make the result heading visible

        // remove hide class of img
        cropped.classList.remove('hidden');
        img_result.classList.remove('hidden');
        // show image cropped
        cropped.src = imgSrc;
        cropped.style.width = '720px';
        cropped.style.height = '200px';

        // Set the data URL to the hidden input field
        document.getElementById('croppedImageData').value = imgSrc;
    }


    var phoneInput = document.querySelector("#contact_no");
    var secondaryPhoneInput = document.querySelector("#secondary_contact_no");

    var phoneInputOptions = {
        // autoHideDialCode: false,
        // nationalMode: false,
        separateDialCode: true,
        initialCountry: "sa",
        preferredCountries: ["sa"], // Specify the preferred countries
        // placeholderNumberType: "MOBILE",
        onlyCountries: ["sa"],
        formatOnDisplay: false,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js"
    };
    window.intlTelInput(phoneInput, phoneInputOptions);

    var phoneInput = document.querySelector("#contact_no");
    var countryCode = document.querySelector("#dial_code");

    var iti1 = window.intlTelInputGlobals.getInstance(phoneInput);
    var countryData1 = iti1.getSelectedCountryData();
    countryCode.value = countryData1.dialCode;

    phoneInput.addEventListener("countrychange", function () {
        iti1 = window.intlTelInputGlobals.getInstance(phoneInput);
        countryData1 = iti1.getSelectedCountryData();
        countryCode.value = countryData1.dialCode;
    });

    window.intlTelInput(secondaryPhoneInput, phoneInputOptions);
    var secondaryPhoneInput = document.querySelector("#secondary_contact_no");
    var secondaryCountryCode = document.querySelector("#secondary_dial_code");

    var iti2 = window.intlTelInputGlobals.getInstance(secondaryPhoneInput);
    var countryData2 = iti2.getSelectedCountryData();
    secondaryCountryCode.value = countryData2.dialCode;

    secondaryPhoneInput.addEventListener("countrychange", function () {
        iti2 = window.intlTelInputGlobals.getInstance(secondaryPhoneInput);
        countryData2 = iti2.getSelectedCountryData();
        secondaryCountryCode.value = countryData2.dialCode;
    });


    $(function () {
        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if (month < 10)
            month = '0' + month.toString();
        if (day < 10)
            day = '0' + day.toString();

        var minDate = year + '-' + month + '-' + day;

        $('#commercial_registration_expiry').attr('min', minDate);
    });


    $(document).ready(function () {
        // Show dropdown menu
        $('#dropdownActionButton').click(function () {
            $('#dropdownAction').toggle();
        });

        // Delete user
        $('.delete-user').click(function (e) {
            e.preventDefault();
            var userId = $(this).data('user-id');

            // Make an AJAX request to delete the user
            $.ajax({
                url: '/users/delete',
                method: 'POST',
                data: {
                    id: userId
                },
                success: function (response) {
                    // Handle success response, such as updating the table or showing a success message
                    console.log(response);
                },
                error: function (xhr) {
                    // Handle error response, such as displaying an error message
                    console.log(xhr.responseText);
                }
            });
        });
    });

</script>
