<x-app-layout>
    <div class="container mx-auto py-8 px-4 rounded">
        <div class="px-4 sm:px-6 lg:px-8 py-4  w-full max-w-9xl mx-auto">

            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{route('dashboard')}}"
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
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
                            <a href="{{route('store')}}"
                               class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Store</a>
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
                            <a href="{{route('product.manage',['id'=>$store->id])}}"
                               class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{$store->commercial_name_en}}</a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="px-3 py-6 bg-white rounded">
            <h2 class="text-2xl font-bold mb-4">Update Product</h2>
            <form action="{{ route('product.update', $product['id']) }}" method="POST" enctype="multipart/form-data"
                  id="form-product">
                @csrf
                @method('PUT')

                <div class="bg-gray-100 p-4 rounded-xl mb-6">
                    <div class="font-semibold text-2xl text-black">
                        {{__('locale.product.page_product.identifying_info')}}
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-3">
                        <div>
                            <label for="product_code" class="block mb-2 text-sm font-medium text-gray-900">
                                {{__('locale.product.page_product.product_code')}}

                                <span style="color: red;">*</span></label>
                            <input type="text" name="product_code" id="product_code"
                                   value="{{ $product['product_code'] }} "
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600"
                            >
                            <div>
                                <span id="productCodeError" class="text-red-500 text-sm"></span>
                            </div>
                            <small class="text-gray-500">
                                {{__('locale.product.page_product.product_code_tip')}}
                            </small>
                        </div>
                        <div>
                            <label for="product_category_id" class="block mb-2 text-sm font-medium text-gray-900">
                                {{__('locale.product.page_product.product_category')}}
                                <span
                                        style="color: red;">*</span></label>
                            <select name="product_category_id" id="product_category_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600">

                                @foreach ($categories->sortBy('category_en')->groupBy(function($item) {
                                    // Get the first letter based on the current locale
                                    $firstLetter = (app()->getLocale() == 'ar') ? mb_substr($item->category_ar, 0, 1, 'UTF-8') : strtoupper($item->category_en[0]);
                                    return strtoupper($firstLetter);
                                }) as $letter => $categoryGroup)

                                    <optgroup label="{{ $letter }}">
                                        @foreach ($categoryGroup as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $product->product_category_id ? 'selected' : '' }}>
                                                @if(app()->getLocale() == 'ar')
                                                    {{ $category->category_ar }}
                                                @else
                                                    {{ $category->category_en }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </optgroup>

                                @endforeach

                            </select>
                        </div>
                    </div>


                </div>

                <div class="bg-gray-100 p-4 rounded-xl mb-6">
                    <div class="font-semibold text-2xl text-black">
                        {{__('locale.product.page_product.product_definition')}}
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="grid grid-cols-1 gap-3 mt-3">
                            <!-- English -->
                            <input type="hidden" id="en_locale" name="translations[0][locale]" value="en">
                            <div>
                                <label for="en_name" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{__('locale.product.page_product.product_name_en')}}
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="translations[0][name]" id="en_name"
                                       value="{{ $product['translations'][1]['name'] ?? '' }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600"/>
                                <span id="enNameError" class="text-red-500 text-sm"></span>
                            </div>
                            <div>
                                <label for="en_description" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{__('locale.product.page_product.product_description_en')}}
                                    <span style="color: red;">*</span>
                                </label>
                                <textarea name="translations[0][description]" id="en_description" rows="4"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600"
                                >{{ $product['translations'][1]['description'] ?? '' }}</textarea>
                                <span id="enDescriptionError" class="text-red-500 text-sm"></span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-3 mt-3">
                            <!-- Arabic -->
                            <input type="hidden" id="ar_locale" name="translations[1][locale]" value="ar">
                            <div>
                                <label for="ar_name" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{__('locale.product.page_product.product_name_ar')}}
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="translations[1][name]" id="ar_name"
                                       value="{{ $product['translations'][0]['name'] ?? '' }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600"
                                       placeholder="">
                                <span id="arNameError" class="text-red-500 text-sm"></span>
                            </div>
                            <div>
                                <label for="ar_description" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{__('locale.product.page_product.product_description_ar')}}
                                    <span style="color: red;">*</span>
                                </label>
                                <textarea id="ar_description" name="translations[1][description]" rows="4"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600"
                                          placeholder="">{{ $product['translations'][0]['description'] ?? '' }}</textarea>
                                <span id="arDescriptionError" class="text-red-500 text-sm"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 rounded-xl mb-6">
                    <div class="font-semibold text-2xl text-black">
                        {{__('locale.product.page_product.price_and_tax_info')}}
                    </div>

                    <div class="mt-3">
                        <label for="unit_price" class="block mb-2 text-sm font-medium text-gray-900">
                            {{__('locale.product.page_product.unit_price')}}

                            (SAR) <span style="color: red;">*</span></label>
                        <input type="text" name="unit_price" id="unit_price" pattern="\d+(\.\d{2})?"
                               value="{{ $product['unit_price'] ?? '' }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600"
                               placeholder="{{__('locale.product.page_product.unit_price_placeholder')}}">
                        <small class="text-gray-500">{{__('locale.product.page_product.unit_price_tip')}}</small>
                        <div>
                            <span id="unitPriceError" class="text-red-500 text-sm mt-2"></span>
                        </div>
                    </div>
                </div>


                <!-- Status field -->
                <div class="bg-gray-100 p-4 rounded-xl mb-6">
                    <div class="font-semibold text-2xl text-black">
                        {{__('locale.product.page_product.status.status')}}
                    </div>
                    <div class="mt-3">
                        <select name="status" id="status"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600">
                            <option value="0" {{ $product['status'] == '0' ? 'selected' : '' }}>
                                {{__('locale.product.page_product.status.inactive')}}
                            </option>
                            <option value="1" {{ $product['status'] == '1' ? 'selected' : '' }}>
                                {{__('locale.product.page_product.status.active')}}
                            </option>
                        </select>
                        <div>
                            <span id="statusError" class="text-red-500 text-sm"></span>
                        </div>
                    </div>
                </div>


                <div class="bg-gray-100 p-4 rounded-xl mb-6">
                    <div class="font-semibold text-2xl text-black">
                        {{__('locale.product.page_product.product_images')}}
                    </div>
                    <div class="relative mt-3">
                        <input type="file" name="product_images[]" id="product_images" multiple
                               accept=".png, .jpg, .jpeg" class="hidden">
                        <label for="product_images"
                               class="cursor-pointer bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-primary-600 focus:border-primary-600 w-full py-2.5 px-4 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>{{__('locale.product.page_product.choose_images')}}</span>
                        </label>
                        <span id="selected_image_count" class="text-sm text-gray-500 mt-1">0 image(s) selected</span>
                        <div class="grid grid-cols-3">
                            <div id="selected_images" class="mt-2">
                                @foreach ($product->images as $image)
                                    <div class="relative my-3 border border-gray-200 p-1 shadow">
                                        <img src="{{asset('storage/'. $image['url'])}}"
                                             alt="{{ $product->product_code }}"
                                             class="w-full h-24 object-cover">
                                        <div class="absolute top-0 right-0 p-1 bg-red-500 text-white cursor-pointer"
                                             onclick="removeImage({{ $image->id }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="store_id" value="{{ $store->id }}">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update
                </button>
            </form>
        </div>
    </div>
</x-app-layout>


<script>
    function goBack() {
        window.history.back();
    }
</script>

<script>
    function removeImage(imageId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/products/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json().then(data => ({status: response.status, body: data})))
                Swal.fire('Deleted!', 'Your image has been deleted.', 'success').then(() => {
                    window.location.reload();
                });
            }
        });
    }
</script>

<script>
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const imageName = document.getElementById('image-name');

    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.addEventListener('load', () => {
                imagePreview.src = reader.result;
                imagePreview.classList.remove('hidden');
            });

            reader.readAsDataURL(file);
            imageName.textContent = file.name;
        } else {
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            imageName.textContent = '';
        }
    });
</script>
<script>
    document.getElementById('product_images').addEventListener('change', function (event) {
        const files = event.target.files;
        const selectedImagesContainer = document.getElementById('selected_images');
        const selectedImageCount = document.getElementById('selected_image_count');
        selectedImagesContainer.innerHTML = ''; // Clear existing images

        if (files.length > 0 && files.length <= 3) {
            selectedImageCount.textContent = files.length + ' image(s) selected';

            for (let i = 0; i < files.length; i++) {
                if (i >= 3) break; // Limit to 3 images

                const image = document.createElement('img');
                image.src = URL.createObjectURL(files[i]);
                image.alt = 'Selected Image';
                image.classList.add('mt-2');
                image.style.maxWidth = '200px';
                selectedImagesContainer.appendChild(image);
            }
        } else {
            selectedImageCount.textContent = '0 image(s) selected';
        }
    });
</script>


<script>

    // Regular expression to match Arabic characters
    const arabicRegex = /^[\u0600-\u06FF\s,;.:"'`~?!@#\$%^&*()-=-_+|/\\<>{}[\]©®™]+$/;

    // Regular expression to match English characters
    const englishRegex = /^[a-zA-Z0-9\s;.:\"'`~?!@#\$%^&*()\-=_+|,/\\<>{}\[\]©®™\u0080-\u00FF]+$/;


    document.addEventListener('DOMContentLoaded', function () {
        // Add an event listener for form submission

        document.getElementById("form-product").addEventListener('submit', function (event) {
            // Check all form inputs before submitting
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission
            }
        });

        function validateForm() {
            var isValid = true;

            // Check the product code
            if (!checkProductCode()) {
                isValid = false;
            }

            // Check the product name
            if (!checkProductName()) {
                isValid = false;
            }

            // Check the product description
            if (!checkProductDescription()) {
                isValid = false;
            }

            // Check the price and tax info
            if (!checkPriceAndTaxInfo()) {
                isValid = false;
            }

            return isValid;
        }

    });

    function checkProductCode() {
        var product_code = document.getElementById("product_code").value;
        var productCodeError = document.getElementById("productCodeError");

        // Reset the error message
        productCodeError.innerText = '';

        if (isEmpty(product_code)) {
            productCodeError.innerText = "{{__('locale.product.errors.product_code_empty')}}";
            return false;
        } else {
            return true;
        }
    }

    function checkProductName() {
        var product_en_name = document.getElementById("en_name").value;
        var product_ar_name = document.getElementById("ar_name").value;
        var enNameError = document.getElementById("enNameError");
        var arNameError = document.getElementById("arNameError");

        // Reset error messages
        enNameError.innerText = '';
        arNameError.innerText = '';


        // Check if both names are empty
        if (isEmpty(product_en_name) && isEmpty(product_ar_name)) {
            enNameError.innerText = "{{__('locale.product.errors.english_name_empty')}}";
            arNameError.innerText = "{{__('locale.product.errors.arabic_name_empty')}}";
            return false;
        }
        // Check if only English name is empty
        else if (isEmpty(product_en_name) && !isEmpty(product_ar_name)) {
            enNameError.innerText = "{{__('locale.product.errors.english_name_empty')}}";
            return false;
        }
        // Check if only Arabic name is empty
        else if (!isEmpty(product_en_name) && isEmpty(product_ar_name)) {
            arNameError.innerText = "{{__('locale.product.errors.arabic_name_empty')}}";
            return false;
        }

        // Check if the name contains Arabic or English characters
        if (!arabicRegex.test(product_ar_name) && !englishRegex.test(product_en_name)) {
            arNameError.innerText = "{{__('locale.product.errors.invalid_name_ar')}}";
            enNameError.innerText = "{{__('locale.product.errors.invalid_name_en')}}";
            return false;
        }

        // Check if the name contains Arabic or English characters
        if (!arabicRegex.test(product_ar_name)) {
            arNameError.innerText = "{{__('locale.product.errors.invalid_name_ar')}}";
            return false;
        }

        if (!englishRegex.test(product_en_name)) {
            enNameError.innerText = "{{__('locale.product.errors.invalid_name_en')}}";
            return false;
        }

        return true;
    }

    function checkProductDescription() {
        // Get input values and error elements
        var product_en_description = document.getElementById("en_description").value.trim();
        var product_ar_description = document.getElementById("ar_description").value.trim();
        var enDescriptionError = document.getElementById("enDescriptionError");
        var arDescriptionError = document.getElementById("arDescriptionError");

        // Reset error messages
        enDescriptionError.innerText = '';
        arDescriptionError.innerText = '';

        // Maximum length check
        const maxLength = 1000;

        // Check if both descriptions are empty
        if (!product_en_description && !product_ar_description) {
            enDescriptionError.innerText = "{{__('locale.product.errors.english_description_empty')}}";
            arDescriptionError.innerText = "{{__('locale.product.errors.arabic_description_empty')}}";
            return false;
        }

        // Check if only English description is empty
        if (!product_en_description) {
            enDescriptionError.innerText = "{{__('locale.product.errors.english_description_empty')}}";
            return false;
        }

        // Check if only Arabic description is empty
        if (!product_ar_description) {
            arDescriptionError.innerText = "{{__('locale.product.errors.arabic_description_empty')}}";
            return false;
        }

        // Check maximum length
        if (product_en_description.length > maxLength || product_ar_description.length > maxLength) {
            if (product_en_description.length > maxLength) {
                enDescriptionError.innerText = "{{__('locale.product.errors.description_length_exceeds_max')}}";
            }

            if (product_ar_description.length > maxLength) {
                enDescriptionError.innerText = "{{__('locale.product.errors.description_length_exceeds_max')}}";
            }

            return false;
        }

        // Check if the description contains Arabic or English characters
        if (!arabicRegex.test(product_ar_description) && !englishRegex.test(product_en_description)) {
            arDescriptionError.innerText = "{{__('locale.product.errors.invalid_description_ar')}}";
            enDescriptionError.innerText = "{{__('locale.product.errors.invalid_description_en')}}";
            return false;
        }

        // Check if the description contains Arabic characters
        if (!arabicRegex.test(product_ar_description)) {
            arDescriptionError.innerText = "{{__('locale.product.errors.invalid_description_ar')}}";
            return false;
        }

        // Check if the description contains English characters
        if (!englishRegex.test(product_en_description)) {
            enDescriptionError.innerText = "{{__('locale.product.errors.invalid_description_en')}}";
            return false;
        }

        // Descriptions are valid
        return true;
    }

    function checkPriceAndTaxInfo() {
        var unit_price = document.getElementById('unit_price').value;
        var unitPriceError = document.getElementById("unitPriceError");

        // Reset error messages
        unitPriceError.innerText = '';

        if (isEmpty(unit_price)) {
            unitPriceError.innerText = "{{__('locale.product.errors.unit_price_empty')}}";
            return false;
        } else {
            return true;
        }
    }

    function isEmpty(str) {
        return (!str || str.trim().length === 0);
    }
</script>
