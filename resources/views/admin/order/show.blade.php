<x-app-admin-layout>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">
            {{ __('admin.order_management.details.title') }}
        </h1>

        <div class="grid grid-cols-2 gap-x-3">
            <div class="mb-4 bg-white p-4 shadow-md rounded-l-md">
                <h2 class="text-xl font-bold pb-2"><i class="fa-solid fa-receipt pr-2"></i>
                    {{ __('admin.order_management.details.order_information') }}
                </h2>
                <p class="text-gray-700">
                    {{ __('admin.order_management.details.order_number') }}: {{ $order->order_number }}
                </p>
                <p class="text-gray-700">{{ __('admin.order_management.details.status') }}:
                    <span class="px-2 py-1 rounded-full text-white"
                          style="background-color: {{ getOrderStatusColor($order->status)}}">
                {{ __('order-status.' . mb_strtolower($order->status)) }}
                </span>
                </p>
                <p class="text-gray-700">{{ __('admin.order_management.details.order_date') }}
                    : {{ $order->order_date }}</p>

                @if ($order->image_invoice_path)
                    <div>

                        <a class="text-blue-500 hover:underline"
                           href="{{asset('storage/'.$order->image_invoice_path)}}">
                            {{ __('admin.order_management.details.image_invoice_path') }}
                        </a>
                    </div>
                @endif

                @if ($order->image_order_path)
                    <div>
                        <a class="text-blue-500 hover:underline" href="{{asset('storage/'.$order->image_order_path)}}">
                            {{ __('admin.order_management.details.image_order_path') }}
                        </a>
                    </div>

                @endif
            </div>

            <div class="mb-4 bg-white p-4 shadow-md rounded-l-md ">
                <h2 class="text-xl font-bold pb-2">{{ __('admin.order_management.details.customer_information') }}</h2>
                <p class="text-gray-700">{{ __('admin.order_management.details.customer_name') }}
                    : {{ $order->customer_name }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.customer_contact') }}:
                    {{ $order->customer_dial_code }} {{ $order->customer_contact_no }}
                </p>
                <p class="text-gray-700">{{ __('admin.order_management.details.customer_email') }}
                    : {{ $order->customer_email }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.customer_vehicle_manufacturer') }}
                    : {{ $order->customer_vehicle_manufacturer }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.customer_vehicle_color') }}
                    : {{ $order->customer_vehicle_color }}</p>
                <!-- Add more customer details as needed -->
            </div>
        </div>

        <div class="grid grid-cols-3 gap-x-3">
            <div class="mb-4 bg-white p-4 shadow-md rounded-l-md ">
                <h2 class="text-xl font-bold pb-2"><i
                            class="fa-solid fa-store pr-2"></i>{{ __('admin.order_management.details.store_information') }}
                </h2>
                <p class="text-gray-700">{{ __('admin.order_management.details.store_name') }}
                    : {{ $order->store->commercial_name_en }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.branch_name') }}
                    : {{ $order->store_branch->name_en }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.store_email') }}
                    : {{ $order->store->email }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.store_contact_no') }}
                    : {{ $order->store->contact_no }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.commercial_registration_no') }}
                    : {{ $order->store->commercial_registration_no }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.commercial_registration_expiry') }}
                    : {{ $order->store->commercial_registration_expiry }}</p>
            </div>

            <div class="mb-4 bg-white p-4 shadow-md rounded-l-md">
                <h2 class="text-xl font-bold pb-2">{{ __('admin.order_management.details.order_totals') }}</h2>
                <p class="text-gray-700">{{ __('admin.order_management.details.items_count') }}
                    : {{ $order->items_count }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.items_quantity') }}
                    : {{ $order->items_quantity }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.grand_total') }}
                    : {{ number_format($order->grand_total, 2) }} SAR</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.sub_total') }}
                    : {{ number_format($order->sub_total, 2) }} SAR</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.service_total') }}
                    : {{ number_format($order->service_total, 2) }} SAR</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.discount_total') }}
                    : {{ number_format($order->discount_total, 2) }} SAR</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.tax_total') }}
                    : {{ number_format($order->tax_total, 2) }} SAR</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.taxable_total') }}
                    : {{ number_format($order->taxable_total, 2) }} SAR</p>

                <!-- Add more order totals as needed -->
            </div>

            <div class="mb-4 bg-white p-4 shadow-md rounded-l-md ">
                <h2 class="text-xl font-bold pb-2">{{ __('admin.order_management.details.payment_information') }}</h2>
                <p class="text-gray-700">{{ __('admin.order_management.details.exchange_rate') }}
                    : {{ $order->exchange_rate }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.conversion_time') }}
                    : {{ $order->conversion_time }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.order_currency_code') }}
                    : {{ $order->order_currency_code }}</p>
                <p class="text-gray-700">{{ __('admin.order_management.details.base_currency_code') }}
                    : {{ $order->base_currency_code }}</p>
                <!-- Add more payment details as needed -->
            </div>

        </div>

        <!-- Order Items -->
        <h2 class="text-2xl font-bold mt-5 mb-4">{{ __('admin.order_management.details.order_items') }}</h2>
        <div class="grid grid-cols-1 gap-y-3">
            @foreach($order->order_items as $item)
                <div class="bg-white p-4 shadow-md rounded-l-md">
                    @if($item->product)
                        <img src="{{ asset('storage/' . $item->product->images[0]->url) ?? 'https://via.placeholder.com/240x240' }}"
                             alt="{{ $item->product->translations[0]->name }}"
                             class="w-24 h-24 object-cover rounded mb-3">
                        <h3 class="text-xl font-bold">{{ $item->product->translations[0]->name }}</h3>
                        <p class="text-gray-700">{{ $item->product->translations[0]->description }}</p>
                        <p class="text-gray-700">{{ __('admin.order_management.details.product_code') }}
                            : {{ $item->product->product_code }}</p>
                        <p class="text-gray-700">{{ __('admin.order_management.details.model_number') }}
                            : {{ $item->product->model_number }}</p>
                        <p class="text-gray-700">{{ __('admin.order_management.details.barcode') }}
                            : {{ $item->product->barcode }}</p>
                        @if($item->note)
                            <p class="text-gray-700">{{ __('admin.order_management.details.note') }}
                                : {{ $item->note }}</p>
                        @endif
                        @if($item->voice_url)
                            <a href="{{ $item->voice_url }}"
                               class="text-blue-500 hover:underline">{{ __('admin.order_management.details.voice_note') }}</a>
                        @endif
                    @else
                        <h3 class="text-xl font-bold"> {{$item->item_name ?? null}}
                            <span>( {{ __('admin.order_management.details.custom_product') }} )</span></h3>
                        <p class="text-gray-700">{{ __('admin.order_management.details.item_unit_price') }}
                            : {{ $item->item_unit_price }}</p>
                        <p class="text-gray-700">{{ __('admin.order_management.details.item_quantity') }}
                            : {{ $item->item_quantity }}</p>
                        <p class="text-gray-700">{{ __('admin.order_management.details.item_total') }}
                            : {{ $item->item_total }}</p>
                        <p class="text-gray-700">{{ __('admin.order_management.details.item_status') }}
                            : {{ $item->item_status }}</p>
                        <p class="text-gray-700">{{ __('admin.order_management.details.special_instructions') }}
                            : {{ $item->item_description }}</p>
                        @if($item->note)
                            <p class="text-gray-700">{{ __('admin.order_management.details.note') }}
                                : {{ $item->note }}</p>
                        @endif
                        @if($item->image_path)
                            <div>
                                <a href="{{ asset('storage/'.$item->image_path) }}"
                                   class="text-blue-500 hover:underline">{{ __('admin.order_management.details.image_path') }}</a>
                            </div>

                        @endif

                        @if($item->voice_path)
                            <div>
                                <a href="{{ asset('storage/'.$item->voice_path) }}"
                                   class="text-blue-500 hover:underline">{{ __('admin.order_management.details.voice_note') }}</a>
                            </div>
                        @endif
                    @endif

                </div>
            @endforeach
        </div>

        <!-- Print button -->
        <div class="text-right ">
            <a href="{{ route('admin.order.edit', $order->id) }}">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('admin.common.save') }}
                </button>
            </a>
        </div>
    </div>
</x-app-admin-layout>

<!-- JavaScript function to trigger print -->
<script>
    function printContent() {
        var contentToPrint = document.querySelector('.p-4'); // Select the div with class "p-4"
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = contentToPrint.outerHTML;
        window.print();
        document.body.innerHTML = originalContent; // Restore the original content after printing
    }
</script>
