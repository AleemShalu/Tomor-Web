const upload = document.getElementById('file-input');
const result = document.querySelector('.result');
// result.innerHTML = '<img src="https://placehold.co/1200x500" alt="Placeholder">';
const save = document.querySelector('.save');
const headerImage = document.querySelector('.header_img');
const deleteBtn = document.querySelector('.delete');
const options = document.querySelector('.options');
const cropped = document.querySelector('.cropped');
const img_result = document.querySelector('.img-result');
const img_w = document.querySelector('.img-w');
let cropper;

result.classList.add('hidden');


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
                result.classList.remove('hidden');
                save.classList.remove('hidden');
                deleteBtn.classList.remove('hidden');
                options.classList.remove('hidden');
                headerImage.classList.add('hidden')
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
        // result.innerHTML = '<img src="https://placehold.co/1200x500" alt="Placeholder">';
        save.classList.add('hidden');
        deleteBtn.classList.add('hidden');
        options.classList.add('hidden');
        cropped.classList.add('hidden');
        img_result.classList.add('hidden');
    }
});

function deleteImage() {
    // Remove the selected image and display the placeholder image
    result.innerHTML = '<img src="https://placehold.co/1200x500" alt="Placeholder">';
    save.classList.add('hidden');
    deleteBtn.classList.add('hidden');
    options.classList.add('hidden');
    cropped.classList.add('hidden');
    img_result.classList.add('hidden');
    result.classList.remove('hidden'); // Remove the 'hide' class to make the result heading visible
    headerImage.classList.add('hidden')

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
    // cropped.style.width = '1200px';
    // cropped.style.height = '500px';

    // Set the data URL to the hidden input field
    document.getElementById('croppedImageData').value = imgSrc;
}
