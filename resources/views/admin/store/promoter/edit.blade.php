@php
    $locale = app()->getLocale();
@endphp

<x-app-admin-layout>
    <div name="header" class=" p-4 shadow-md bg-white">
        <h2 class="font-semibold text-2xl leading-tight">
            @if($locale == 'en')
                <span class="">{{ $storePromoter->store->short_name_en }}</span>
                - {{ $storePromoter->name_en }}
            @else
                <span class="">{{ $storePromoter->store->short_name_ar }}</span>
                - {{ $storePromoter->name_ar }}
            @endif
        </h2>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="post" action="{{ route('admin.promoters.update', $storePromoter->id) }}"
                      enctype="multipart/form-data">


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
                    @method('PUT')

                    <!-- Code -->
                    <div class="mb-4">
                        <label for="code"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.code') }}</label>
                        <input type="text" name="code" id="code" value="{{ $storePromoter->code }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <!-- Store ID -->
                    <div class="mb-4">
                        <label for="store_id"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.store') }}</label>
                        <select name="store_id" id="store_id"
                                class="form-select rounded-md shadow-sm mt-1 block w-full">
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}"
                                        @if($store->id == $storePromoter->store_id) selected @endif>
                                    {{ $store->short_name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Name (English) -->
                    <div class="mb-4">
                        <label for="name_en"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.name_en') }}</label>
                        <input type="text" name="name_en" id="name_en" value="{{ $storePromoter->name_en }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <!-- Name (Arabic) -->
                    <div class="mb-4">
                        <label for="name_ar"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.name_ar') }}</label>
                        <input type="text" name="name_ar" id="name_ar" value="{{ $storePromoter->name_ar }}"
                               class="form-input rounded-md shadow-sm mt-1 block w-full" required/>
                    </div>

                    <!-- Description (English) -->
                    <div class="mb-4">
                        <label for="description_en"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.description_en') }}</label>
                        <textarea name="description_en" id="description_en"
                                  class="form-textarea rounded-md shadow-sm mt-1 block w-full"
                                  rows="3">{{ $storePromoter->description_en }}</textarea>
                    </div>

                    <!-- Description (Arabic) -->
                    <div class="mb-4">
                        <label for="description_ar"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.description_ar') }}</label>
                        <textarea name="description_ar" id="description_ar"
                                  class="form-textarea rounded-md shadow-sm mt-1 block w-full"
                                  rows="3">{{ $storePromoter->description_ar }}</textarea>
                    </div>

                    <!-- Header Image -->
                    <div class="bg-gray-100 p-4 rounded-md">
                        <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                            <span style="color: red">*</span>{{ __('admin.store_management.promoters.page.header') }}
                        </h2>

                        <div class="">
                            <!-- input file -->
                            <div class="">
                                <input name="header" type="file" class="rounded bg-gray-100 mb-2"
                                       accept="image/*" required id="file-input"
                                       oninput="validateFileType(this)">
                            </div>
                            <div class="">
                                <!-- leftbox -->
                                <div class="box-2">
                                    @if ($storePromoter->promoter_header_path)
                                        <img class="header_img object-cover rounded "
                                             src="{{ asset('storage/'.$storePromoter->promoter_header_path) }}"
                                             alt="Current Logo">

                                    @else

                                    @endif
                                    <div class="result rounded"
                                         style="width: 80% ; height: 50%"></div>

                                    <!-- rightbox -->
                                    <div class="box-2 img-result hide">
                                        <!-- result of crop -->
                                        <img class="cropped rounded" src="" alt="">
                                        <input type="hidden" id="croppedImageData"
                                               name="croppedImageData">
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
                                    @error('cropped')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    <span id="header_error" class="text-red-500 text-sm"></span>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.status') }}</label>
                        <input type="checkbox" name="status" id="status" value="1"
                               class="form-checkbox h-5 w-5 text-indigo-600"
                               @if($storePromoter->status) checked @endif/>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-4">
                        <label for="start_date"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.start_date') }}</label>
                        <input type="datetime-local" name="start_date" id="start_date"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               value="{{ \Carbon\Carbon::parse($storePromoter->start_date)->format('Y-m-d\TH:i') }}"
                               required/>
                    </div>

                    <!-- End Date -->
                    <div class="mb-4">
                        <label for="end_date"
                               class="block text-sm font-medium text-gray-600">{{ __('admin.store_management.promoters.end_date') }}</label>
                        <input type="datetime-local" name="end_date" id="end_date"
                               class="form-input rounded-md shadow-sm mt-1 block w-full"
                               value="{{ \Carbon\Carbon::parse($storePromoter->end_date)->format('Y-m-d\TH:i') }}"
                               required/>
                    </div>

                    <input type="hidden" name="promoter_id" value="{{$storePromoter->id}}">

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                            {{__('admin.common.save')}}
                        </button>
                    </div>
                </form>
            </div>
            <div class="flex justify-end m-4">
                <button onclick="goBack()" class="bg-black hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{__('admin.common.back')}}
                </button>
            </div>
        </div>

    </div>

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
    </script>

    <script src="{{asset('js/owner/store/settings.js')}}"></script>

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


