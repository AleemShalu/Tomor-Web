@php
    $floatDirection = app()->getLocale() == 'ar' ? 'float-left' : 'float-right';
@endphp

<x-app-layout>
    <!-- Include Select2 CSS from a CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">

    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-l1 p-5">

                <h3 class="text-3xl pb-4 ">{{__('locale.product.offer.offer_title')}}</h3>

                @if($errors->any())
                    <div class="bg-red-500 text-white p-3 rounded mb-2">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if(session('status'))
                    <div class="bg-green-500 text-white p-3 rounded mb-2">
                        {{ session('status') }}
                    </div>
                @endif


                <div class="">
                    @if($offers->count() > 0)
                        <form method="GET" action="{{ route('offer.product', $store->id) }}">
                            <div class="space-y-1 pb-4">
                                <label for="offer" class="block text-xl font-inter mt-2">
                                    {{__('locale.product.offer.select_offer')}}
                                </label>
                                <select name="offer_id" id="offer" onchange="this.form.submit()"
                                        class="border border-gray-300 rounded" required>
                                    @foreach($offers as $offer)
                                        <option value="{{ $offer->id }}" {{ (request('offer_id') == $offer->id) ? 'selected' : '' }}>
                                            {{ $offer->offer_name }} ({{ $offer->discount_percentage }}%) |
                                            ({{ $offer->start_date->format('Y-m-d') }}
                                            - {{ $offer->end_date->format('Y-m-d') }})
                                            @if($offer->status)
                                                |
                                                ✅                                     {{__('locale.product.page_product.status.active')}}

                                            @else
                                                | ❌     {{__('locale.product.page_product.status.inactive')}}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('offer.create', $store->id) }}"
                                   class="bg-blue-color-1 text-white py-2 px-6 rounded hover:bg-blue-700 {{ $floatDirection }}">
                                    {{__('locale.product.offer.create_new_offer')}}
                                </a>
                            </div>
                        </form>
                        <hr class="py-3">
                        <form action="{{ route('offer.store', $store->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{ optional($currentOffer)->id }}">

                            <!-- Product Selection -->
                            <div class="space-y-1 mt-4">
                                <label for="products" class="block text-2xl font-inter mb-3  text-gray-700">
                                    {{__('locale.product.offer.select_product')}}
                                </label>
                                <p>
                                    {{__('locale.product.offer.select_product_dec')}}
                                </p>

                                <div>
                                    <table id="products" class="min-w-full divide-y divide-gray-200 py-2">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{__('locale.product.product_name_header')}}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{__('locale.product.product_code_header')}}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{__('locale.product.model_number_header')}}
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($products as $product)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="product_ids[]"
                                                           value="{{ $product->id }}"
                                                            {{ in_array($product->id, $currentOfferProductIds) ? 'checked' : '' }}>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $product->translations->firstWhere('locale', 'ar')->name ?? $product->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $product->product_code }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $product->model_number }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="mt-3">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-color-1 text-white rounded" {{ !$currentOffer ? 'disabled' : '' }}>
                                    {{ __('locale.product.offer.submit_button.add_products') }}
                                </button>
                                @if($currentOffer)
                                    <a href="{{ route('offer.edit', ['storeId' => $store->id, 'offerId' => $currentOffer->id]) }}"
                                       class="px-4 py-2 bg-green-700 text-white rounded">
                                        {{ __('locale.product.offer.submit_button.edit_offer') }}
                                    </a>
                                @else
                                    <span class="px-4 py-2 bg-gray-400 text-white rounded cursor-not-allowed">
                                        {{ __('locale.product.offer.submit_button.edit_offer_disabled') }}
                                    </span>
                                @endif
                            </div>


                        </form>
                    @else
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                            <p class="font-bold">{{ __('locale.no_offers.alert_title') }}</p>
                            <p>{{ __('locale.no_offers.alert_message') }}</p>
                        </div>
                        <a href="{{ route('offer.create', $store->id) }}"
                           class="bg-blue-color-1 text-white py-2 px-4 rounded hover:bg-blue-700">
                            {{ __('locale.no_offers.create_button') }}
                        </a>
                    @endif

                </div>
            </div>

            <!-- Existing Offers and Products -->
            <div class="hidden mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">{{ __('locale.existing_offers.heading') }}</h3>
                    @foreach($offers as $offer)
                        <div class="mb-4">
                            <!-- Offer details -->
                            <h4>{{ $offer->offer_name }} - {{ $offer->discount_percentage }}%
                                ({{ optional($offer->end_date)->format('Y-m-d') }})</h4>

                            <div class="space-y-1 mt-4">
                                <label for="products" class="block text-sm font-bold text-gray-700">Select
                                    {{ __('locale.existing_offers.offer_details.label') }}
                                </label>
                                <div id="agGrid" style="height: 400px;" class="ag-theme-alpine"></div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </div>

    <!-- Include Select2 JS from a CDN -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#products').DataTable({
                // DataTables options can go here, if needed
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });

            // Handle click on "Select all" control
            $('#select-all').on('click', function () {
                // Get all rows with search applied
                var rows = table.rows({'search': 'applied'}).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle click on checkbox to set state of "Select all" control
            $('#products tbody').on('change', 'input[type="checkbox"]', function () {
                // If checkbox is not checked
                if (!this.checked) {
                    var el = $('#select-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if (el && el.checked && ('indeterminate' in el)) {
                        // Set visual state of "Select all" control
                        // as 'indeterminate'
                        el.indeterminate = true;
                    }
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            $('.select2').select2({
                placeholder: "Select products",
                allowClear: true
            });
        });
    </script>

</x-app-layout>

<style>
    .dataTables_wrapper {
        padding: 20px;
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }

    table.dataTable {
        width: 100%;
        margin: 0 auto;
        clear: both;
        border-collapse: collapse;
        border-spacing: 0;
        text-align: center;
    }

    table.dataTable thead th,
    table.dataTable thead td {
        padding: 12px 15px;
        border-bottom: 1px solid #0c0c0c;
        text-align: center;
    }

    table.dataTable tbody tr {
        border-bottom: 1px solid #dee2e6;
        text-align: center;
    }


    .dataTables_wrapper .dataTables_length select {
        width: 100px !important;
        padding: 6px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        background-color: #fff;
        margin: 0 auto; /* Add this line to center the select box */
        text-align: center;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: 200px;
        padding: 6px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        background-color: #fff;
    }

    .dataTables_wrapper .dataTables_paginate {
        margin-top: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #007bff;
        background-color: #9ecbff;
        color: #fff;
        cursor: pointer;
        margin: 0 4px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
