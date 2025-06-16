function goBack() {
    window.history.back();
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
const img_w = document.querySelector('.img-w');
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

//
// var phoneInput = document.querySelector("#contact_no");
// var secondaryPhoneInput = document.querySelector("#secondary_contact_no");
//
// var phoneInputOptions = {
//     // autoHideDialCode: false,
//     // nationalMode: false,
//     separateDialCode: true,
//     initialCountry: "sa",
//     preferredCountries: ["sa"], // Specify the preferred countries
//     // placeholderNumberType: "MOBILE",
//     onlyCountries: ["sa"],
//     formatOnDisplay: false,
//     utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js",
// };
// window.intlTelInput(phoneInput, phoneInputOptions);
//
// var phoneInput = document.querySelector("#contact_no");
// var countryCode = document.querySelector("#dial_code");
//
// var iti1 = window.intlTelInputGlobals.getInstance(phoneInput);
// var countryData1 = iti1.getSelectedCountryData();
// countryCode.value = countryData1.dialCode;
//
// phoneInput.addEventListener("countrychange", function () {
//     iti1 = window.intlTelInputGlobals.getInstance(phoneInput);
//     countryData1 = iti1.getSelectedCountryData();
//     countryCode.value = countryData1.dialCode;
// });
//
// window.intlTelInput(secondaryPhoneInput, phoneInputOptions);
// var secondaryPhoneInput = document.querySelector("#secondary_contact_no");
// var secondaryCountryCode = document.querySelector("#secondary_dial_code");
//
// var iti2 = window.intlTelInputGlobals.getInstance(secondaryPhoneInput);
// var countryData2 = iti2.getSelectedCountryData();
// secondaryCountryCode.value = countryData2.dialCode;
//
// secondaryPhoneInput.addEventListener("countrychange", function () {
//     iti2 = window.intlTelInputGlobals.getInstance(secondaryPhoneInput);
//     countryData2 = iti2.getSelectedCountryData();
//     secondaryCountryCode.value = countryData2.dialCode;
// });


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



