<x-app-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fa-solid fa-gears fa-lg pr-2"></i>
                    @lang('admin.settings.platform_settings.title')
                </h3>
                <div>
                    <p class="mb-4">
                        @lang('admin.settings.platform_settings.description')
                    </p>

                    <section class="mb-6 pt-4">
                        <h4 class="text-md font-semibold mb-2">
                            @lang('admin.settings.platform_settings.manage_platform_type.title')
                        </h4>
                        <p>
                            @lang('admin.settings.platform_settings.manage_platform_type.description')
                        </p>
                        <form action="{{ route('admin.settings.platform.update') }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <!-- Web Application -->
                                <div class=" items-center justify-between mt-4">
                                    <div class="font-bold">
                                        @lang('admin.settings.platform_settings.manage_platform_type.form.web_application.label')
                                    </div>
                                    <div class=" items-center space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-red-500"
                                                   name="web_application"
                                                   value="on" {{ $settings->web_status ? 'checked' : '' }}>
                                            <span
                                                class="ml-2">@lang('admin.settings.platform_settings.manage_platform_type.form.web_application.status_on')</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-red-500"
                                                   name="web_application"
                                                   value="off" {{ !$settings->web_status ? 'checked' : '' }}>
                                            <span
                                                class="ml-2">@lang('admin.settings.platform_settings.manage_platform_type.form.web_application.status_off')</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Mobile App -->
                                <div class=" items-center justify-between">
                                    <div class="font-bold">
                                        @lang('admin.settings.platform_settings.manage_platform_type.form.mobile_app.label')
                                    </div>
                                    <div class=" items-center space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-red-500"
                                                   name="mobile_app"
                                                   value="on" {{ $settings->app_status ? 'checked' : '' }}>
                                            <span
                                                class="ml-2">@lang('admin.settings.platform_settings.manage_platform_type.form.mobile_app.status_on')</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-red-500"
                                                   name="mobile_app"
                                                   value="off" {{ !$settings->app_status ? 'checked' : '' }}>
                                            <span
                                                class="ml-2">@lang('admin.settings.platform_settings.manage_platform_type.form.mobile_app.status_off')</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded">
                                        @lang('admin.settings.platform_settings.manage_platform_type.form.submit_button')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>

                    <!-- Terms and Conditions Section -->
                    <section class="mb-6 pt-4 border-t mt-4">
                        <form action="{{ route('admin.settings.platform.web.update-terms') }}" method="post">
                            @csrf
                            <h4 class="text-md font-semibold mb-2">
                                @lang('admin.settings.platform_settings.update_terms_and_conditions.title')
                            </h4>
                            <p>
                                @lang('admin.settings.platform_settings.update_terms_and_conditions.description')
                            </p>
                            <div class="mt-4">
                                <label
                                    for="terms_ar">@lang('admin.settings.platform_settings.update_terms_and_conditions.form.label_arabic')</label>
                                <textarea name="terms_ar" id="editor_terms_ar"
                                          class="border w-full mt- px-3 py-2 rounded"
                                          rows="10">{{ $latestTerms->body_ar ?? __('admin.settings.platform_settings.update_terms_and_conditions.form.placeholder_arabic') }}</textarea>

                                <label for="terms_en"
                                       class="mt-4 block">@lang('admin.settings.platform_settings.update_terms_and_conditions.form.label_english')</label>
                                <textarea name="terms_en" id="editor_terms_en"
                                          class="border w-full mt- px-3 py-2 rounded"
                                          rows="10">{{ $latestTerms->body_en ?? __('admin.settings.platform_settings.update_terms_and_conditions.form.placeholder_english') }}</textarea>
                            </div>
                            <button class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                @lang('admin.settings.platform_settings.update_terms_and_conditions.form.button')
                            </button>
                            @if($latestTerms && $latestTerms->issued_at)
                                <p class="mt-4 text-sm text-gray-500">@lang('admin.settings.platform_settings.update_terms_and_conditions.form.last_updated')
                                    : {{ $latestTerms->issued_at }}</p>
                            @endif
                        </form>
                    </section>

                    <!-- Privacy Policies Section -->
                    <section class="mb-6 pt-4 border-t mt-4">
                        <form action="{{ route('admin.settings.platform.web.update-privacy') }}" method="post">
                            @csrf
                            <h4 class="text-md font-semibold mb-2">
                                @lang('admin.settings.platform_settings.privacy_policies.title')
                            </h4>
                            <p>
                                @lang('admin.settings.platform_settings.privacy_policies.description')
                            </p>
                            <div class="mt-4">
                                <label
                                    for="privacy_ar">@lang('admin.settings.platform_settings.privacy_policies.form.label_arabic')</label>
                                <textarea name="privacy_ar" id="editor_privacy_ar"
                                          class="border w-full mt- px-3 py-2 rounded"
                                          rows="10">{{ $latestPrivacyPolicy->body_ar ?? __('admin.settings.platform_settings.privacy_policies.form.placeholder_arabic') }}</textarea>

                                <label for="privacy_en"
                                       class="mt-4 block">@lang('admin.settings.platform_settings.privacy_policies.form.label_english')</label>
                                <textarea name="privacy_en" id="editor_privacy_en"
                                          class="border w-full mt- px-3 py-2 rounded"
                                          rows="10">{{ $latestPrivacyPolicy->body_en ?? __('admin.settings.platform_settings.privacy_policies.form.placeholder_english') }}</textarea>
                            </div>
                            <button class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                @lang('admin.settings.platform_settings.privacy_policies.form.button')
                            </button>
                            @if($latestPrivacyPolicy && $latestPrivacyPolicy->issued_at)
                                <p class="mt-4 text-sm text-gray-500">@lang('admin.settings.platform_settings.privacy_policies.form.last_updated')
                                    : {{ $latestPrivacyPolicy->issued_at }}</p>
                            @endif
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-admin-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        CKEDITOR.editorConfig = function(config) {
            config.versionCheck = false;
        };

        CKEDITOR.replace('terms_ar');
        CKEDITOR.replace('terms_en');
        CKEDITOR.replace('privacy_ar');
        CKEDITOR.replace('privacy_en');
    });
</script>

