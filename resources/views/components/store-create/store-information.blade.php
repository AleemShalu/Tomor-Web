<div id="step1" class="border-b border-gray-900/10">
    <!-- Store information form inputs -->
    <div class="pt-2">
        <div for="store_name" class="pt-2 bg-gray-100 rounded-md p-2 m-2">
            <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">
                <i class="fas fa-store mx-2"></i>
                {{ __('locale.store.name_store') }}
            </h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">
                {{ __('locale.store.information_displayed_publicly') }}
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="pb-2">
                    <label class="block mb-1">
                                            <span
                                                    style="color: red">*</span> {{ __('locale.store.commercial_name_en') }}
                    </label>
                    <input dir="ltr" type="text" id="commercial_name_en" name="commercial_name_en"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="" value="{{ old('commercial_name_en') }}"
                    >
                    <span id="commercial_name_en_error" class="text-red-500 text-sm"></span>
                </div>
                <div class="pb-2">
                    <label class="block mb-1">
                                            <span
                                                    style="color: red">*</span> {{ __('locale.store.commercial_name_ar') }}
                    </label>
                    <input dir="rtl" type="text" id="commercial_name_ar" name="commercial_name_ar"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="" value="{{ old('commercial_name_ar') }}"
                    >
                    <span id="commercial_name_ar_error" class="text-red-500 text-sm"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
                <div class="">
                    <label class="block mb-1">
                        <span style="color: red">*</span> {{ __('locale.store.short_name_en') }}
                    </label>
                    <input dir="ltr" type="text" id="short_name_en" name="short_name_en"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="" value="{{ old('short_name_en') }}"
                    >
                    <span id="short_name_en_error" class="text-red-500 text-sm"></span>
                </div>

                <div>
                    <label class="block mb-1">
                        <span style="color: red">*</span> {{ __('locale.store.short_name_ar') }}
                    </label>
                    <input dir="rtl" type="text" id="short_name_ar" name="short_name_ar"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="" value="{{ old('short_name_ar') }}"
                    >
                    <span id="short_name_ar_error" class="text-red-500 text-sm"></span>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div for="store_dec" class="pt-2 bg-gray-100 rounded-md p-2 pb-4 m-2">
            <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                {{ __('locale.page.description_store') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <div>
                    <label for="description_en">
                                            <span
                                                    style="color: red">*</span> {{ __('locale.page.description_about_store_en') }}
                    </label>
                    <textarea dir="ltr" id="description_en" name="description_en" rows="4"
                              class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                              placeholder="{{ __('locale.page.write_sentences_about_store_ِen') }}"
                              oninput=" updateCharacterCount('description_en', 'description_en_counter', 255);"></textarea>
                    <span id="description_en_error" class="text-red-500 text-sm"></span>
                    <div>
                                            <span id="description_en_counter"
                                                  class="text-gray-500 text-sm">0 / 255</span>
                    </div>
                </div>

                <div>
                    <label for="description_ar">
                                            <span
                                                    style="color: red">*</span> {{ __('locale.page.description_about_store_ar') }}
                    </label>
                    <textarea dir="rtl" id="description_ar" name="description_ar" rows="4"
                              class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                              placeholder="{{ __('locale.page.write_sentences_about_store_ِar') }}"
                              oninput=" updateCharacterCount('description_ar', 'description_ar_counter', 255);"></textarea>
                    <span id="description_ar_error" class="text-red-500 text-sm"></span>
                    <div>
                                            <span id="description_ar_counter"
                                                  class="text-gray-500 text-sm">0 / 255</span>
                    </div>

                </div>
            </div>
        </div>

        <div for="store_logo" class="pt-2 rounded-md pb-4 m-2">
            <div class="pb-4 rounded-md">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-100 p-4 rounded-md">
                        <div class="pt-3">
                            <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                <span style="color: red">*</span>{{ __('locale.page.logo') }}
                            </h2>
                            <div class="mx-auto flex items-center gap-x-3"
                                 style="justify-content: center;">
                                <link
                                        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
                                        rel="stylesheet">
                                <div class="">
                                    <div class="avatar-upload px-3">
                                        <div class="avatar-edit">
                                            <input type='file' id="logo" name="logo"
                                                   accept=".png, .jpg, .jpeg"
                                                   oninput="validateFileType(this)">


                                            <label for="logo">
                                                <i class="fas fa-camera w-full mt-2"></i>
                                                <!-- Use a FontAwesome class directly -->
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview"
                                                 style="background-image: url('{{ asset('images/bg-gray-store.png') }}');"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span id="logo_error" class="text-red-500 text-sm"></span>
                        </div>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-md">
                        <h2 class="pl-2 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                            <span style="color: red">*</span>{{ __('locale.page.header') }}
                        </h2>
                        <!-- input file -->
                        <div class="box">
                            <input name="header" type="file" class="rounded bg-gray-100 mb-2"
                                   accept=".png, .jpg, .jpeg" required id="file-input"
                                   oninput="validateFileType(this)">
                        </div>
                        <!-- leftbox -->
                        <div class="box-2 ">
                            <div class="result"
                                 style=" border-radius:90px;"></div>
                        </div>
                        <!-- rightbox -->
                        <div class="box-2 img-result hide">
                            <!-- result of crop -->
                            <img class="cropped rounded" src="" alt="">
                            <input type="hidden" id="croppedImageData" accept=".png, .jpg, .jpeg"
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
                               onclick="deleteImage()">Delete</a>
                            <!-- save btn -->
                            <a href="#"
                               class="save bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded"
                               onclick="saveCroppedImage()">Save</a>
                        </div>
                    </div>
                    <span id="header_error" class="text-red-500 text-sm"></span>
                </div>

                @error('cropped')
                <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
