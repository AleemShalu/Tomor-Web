<x-app-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-inter mb-4 flex">
                    <i class="fa-solid fa-gears fa-lg pr-2"></i>
                    <div class="text-2xl">
                        {{ __('admin.settings.store_settings.title') }}
                    </div>
                </h3>
                <div>
                    <p class="mb-4">
                        {{ __('admin.settings.store_settings.description') }}
                    </p>

                    <hr>
                    <section class="mb-6 pt-4">
                        <h4 class="text-xl font-medium mb-2">
                            {{ __('admin.settings.store_settings.sections.terms_and_conditions.title') }}
                        </h4>
                        <p>
                            {{ __('admin.settings.store_settings.sections.terms_and_conditions.description') }}
                        </p>

                        <form action="{{ route('admin.settings.update-terms') }}" method="POST">
                            @csrf
                            <div>
                                <div class="mt-4">
                                    <label for="terms_ar">{{ __('admin.settings.store_settings.sections.terms_and_conditions.fields.terms_ar.label') }}
                                        :</label>
                                    <textarea name="terms_ar" id="editor_terms_ar"
                                              class="border w-full mt-2 px-3 py-2 rounded"
                                              rows="10">{{ $latestTerms->body_ar ?? __('admin.settings.store_settings.sections.terms_and_conditions.fields.terms_ar.placeholder') }}</textarea>

                                    <label for="terms_en"
                                           class="mt-4 block">{{ __('admin.settings.store_settings.sections.terms_and_conditions.fields.terms_en.label') }}
                                        :</label>
                                    <textarea name="terms_en" id="editor_terms_en"
                                              class="border w-full mt-2 px-3 py-2 rounded"
                                              rows="10">{{ $latestTerms->body_en ?? __('admin.settings.store_settings.sections.terms_and_conditions.fields.terms_en.placeholder') }}</textarea>
                                </div>
                                <button type="submit"
                                        class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                    @lang('admin.settings.platform_settings.update_terms_and_conditions.form.button')
                                </button>
                                @if($latestTerms && $latestTerms->issued_at)
                                    <p class="mt-4 text-sm text-gray-500">@lang('admin.settings.platform_settings.update_terms_and_conditions.form.last_updated')
                                        : {{ $latestTerms->issued_at }}</p>
                                @endif
                            </div>
                        </form>
                    </section>

                    <hr>

                    <section class="mb-6 pt-4">
                        <h4 class="text-xl font-medium mb-2">
                            {{ __('admin.settings.store_settings.sections.store_range.title') }}
                        </h4>
                        <p>
                            {{ __('admin.settings.store_settings.sections.store_range.description') }}
                        </p>

                        <div class="grid grid-cols-2 gap-x-3">

                            <div class="mt-4 bg-gray-100 rounded-md p-3">
                                <div class="font-bold text-xl mb-2">
                                    {{ __('admin.settings.store_settings.sections.store_range.fields.store_range_radius.title') }}
                                    <div class="text-sm font-light">
                                        {{ __('admin.settings.store_settings.sections.store_range.fields.store_range_radius.description') }}
                                    </div>
                                </div>
                                <form action="{{ route('admin.settings.update-store-range') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="items-center">
                                            <label for="min-radius" class="mr-2">
                                                {{ __('admin.settings.store_settings.sections.store_range.fields.store_range_radius.fields.min.label') }}
                                                :
                                            </label>
                                            <input id="min-radius" name="min_radius" type="number"
                                                   class="border rounded-md p-2 focus:outline-none focus:ring focus:border-blue-300 w-full"
                                                   value="{{$branch_range->min_radius  ?? 0 }}">
                                        </div>
                                        <div class="items-center">
                                            <label for="max-radius" class="mr-2">
                                                {{ __('admin.settings.store_settings.sections.store_range.fields.store_range_radius.fields.max.label') }}
                                                :
                                            </label>
                                            <input id="max-radius" name="max_radius" type="number"
                                                   class="border rounded-md p-2 focus:outline-none focus:ring focus:border-blue-300 w-full"
                                                   value="{{ $branch_range->max_radius ?? 0 }}">
                                        </div>
                                        <input type="hidden" name="unit" value="m">
                                    </div>
                                    <button type="submit"
                                            class="{{ __('admin.settings.store_settings.sections.store_range.fields.store_range_radius.button.classes') }}">
                                        {{ __('admin.settings.store_settings.sections.store_range.fields.store_range_radius.button.label') }}
                                    </button>
                                </form>
                            </div>

                            <div class="mt-4 bg-gray-100 rounded-md p-3">
                                <div class="font-bold text-xl mb-2">
                                    {{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.title') }}
                                    <div class="text-sm font-light">
                                        {{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.description') }}
                                    </div>
                                </div>

                                <form action="{{ route('admin.settings.update-customer-range') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="items-center">
                                            <label for="customer-distance" class="mr-2">
                                                {{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.fields.distance.label') }}
                                                :
                                            </label>
                                            <input id="max_radius" name="max_radius" type="number"
                                                   class="border rounded-md p-2 focus:outline-none focus:ring focus:border-blue-300 w-full"
                                                   value="{{$customer_range->max_radius ?? 0 }}">
                                            <input type="hidden" name="unit" value="m">
                                        </div>
                                    </div>
                                    <button type="submit"
                                            class="{{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.button.classes') }}">
                                        {{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.button.label') }}
                                    </button>
                                </form>
                            </div>

                        </div>
                    </section>

                    <hr>

                    <section class="mb-6 pt-4 flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/2 px-3">
                            <div class="mt-4 bg-gray-100 rounded-md p-3">
                                <h4 class="text-xl font-bold mb-2">
                                    {{ __('admin.settings.store_settings.service_cost.title') }}
                                </h4>
                                <p>
                                    {{ __('admin.settings.store_settings.service_cost.description') }}
                                </p>
                                <form action="{{ route('admin-service-definition.update') }}" method="POST">
                                    @csrf
                                    <!-- Hidden input to pass the code of the service definition -->
                                    <input type="hidden" name="code" value="{{ $service_definition->code }}">

                                    <!-- Input field for updating the price -->
                                    <div class="mb-4 mt-2">
                                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">
                                            {{ __('admin.settings.store_settings.service_cost.fields.price.label', ['currency' => $service_definition->service_currency_code]) }}
                                        </label>
                                        <input type="text" name="price" id="price"
                                               oninput="numberOnly(this.id);"
                                               value="{{ $service_definition->price }}"
                                               class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                               required>
                                    </div>

                                    <button type="submit"
                                            class="{{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.button.classes') }}">
                                        {{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.button.label') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="w-full md:w-1/2 px-3">
                            <div class="mt-4 bg-gray-100 rounded-md p-3">
                                <h4 class="text-xl font-bold mb-2">
                                    {{ __('admin.settings.store_settings.payment_time_order.title') }}
                                </h4>
                                <p>
                                    {{ __('admin.settings.store_settings.payment_time_order.description') }}
                                </p>
                                <form action="{{ route('admin.settings.update-payment-time') }}" method="POST">
                                    @csrf
                                    <!-- Hidden input to pass the code of the service definition -->
                                    {{-- <input type="hidden" name="code" value="{{ $service_definition->code }}"> --}}

                                    <!-- Input field for updating the payment time -->
                                    <div class="mb-4 mt-2">
                                        <label for="payment_time" class="block text-gray-700 text-sm font-bold mb-2">
                                            {{ __('admin.settings.store_settings.payment_time_order.fields.price.label') }}
                                        </label>
                                        <input type="number" name="payment_time" id="payment_time"
                                               value="{{ $payment_settings->value ?? 0 }}"
                                               class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                               placeholder="{{ __('admin.settings.store_settings.payment_time_order.fields.price.placeholder') }}"
                                               required>
                                    </div>

                                    <button type="submit"
                                            class="{{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.button.classes') }}">
                                        {{ __('admin.settings.store_settings.sections.store_range.fields.customer_range_distance.button.label') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-admin-layout>

<script>
    CKEDITOR.replace('terms_ar');
    CKEDITOR.replace('terms_en');


    function numberOnly(id) {
        // Get element by id which passed as parameter within HTML element event
        var element = document.getElementById(id);
        // This allows only numbers and a single decimal point
        element.value = element.value.replace(/[^0-9.]/gi, "");

        // Ensure only one decimal point is allowed
        if (element.value.split('.').length > 2) {
            element.value = element.value.replace(/\.+$/, "");
        }
    }

</script>
