<x-app-layout>

    <div class="container mx-auto px-6 py-4 bg-white shadow-md rounded-lg mt-5" id="printableArea">
        <div class="text-center text-xl font-bold mb-6">
            {{ __('locale.branch_orders.order_details') }}
        </div>

        <!-- Basic Order Details -->
        <div class="order-details mb-5 border-b pb-5">
            <p><span class="font-semibold">{{ __('locale.branch_orders.order_number') }}:</span> {{ $order->order_number }}</p>
            <p><span class="font-semibold">{{ __('locale.branch_orders.branch_order_number') }}:</span> {{ $order->branch_order_number }}</p>
            <p><span class="font-semibold">{{ __('locale.branch_orders.status') }}:</span>
                <span class="px-2 py-1 rounded-full text-white" style="background-color: {{ $order->status->getColor() }}">
                {{ __('order-status.' . $order->status->value) }}
                </span>
            </p>

            <p><span class="font-semibold">{{ __('locale.branch_orders.order_date') }}:</span> {{ $order->order_date }}</p>
            <p><span class="font-semibold">{{ __('locale.branch_orders.customer') }}:</span> {{ $order->customer_name }}</p>
        </div>

        <!-- Order Items Details -->
        <div class="order-items mb-5">
            <div class="text-lg font-semibold mb-4">{{ __('locale.branch_orders.items') }}</div>
            @if($order->order_items->count() > 0)
                @foreach($order->order_items as $item)
                    <div class="bg-gray-100 p-4 rounded-md mb-4">
                        <p><span class="font-semibold">{{ __('locale.branch_orders.item_name') }}:</span> {{ $item->item_name }}</p>
                        <p><span class="font-semibold">{{ __('locale.branch_orders.item_description') }}:</span> {{ $item->item_description }}</p>
                        <p><span class="font-semibold">{{ __('locale.branch_orders.item_unit_price') }}:</span>
                            {{ number_format($item->item_unit_price, 2) }}
                        </p>
                        <p><span class="font-semibold">{{ __('locale.branch_orders.item_quantity') }}:</span>
                            {{ number_format($item->item_quantity, 2) }}
                        </p>

                    </div>
                @endforeach
            @else
                <p>{{ __('locale.branch_orders.no_items_available') }}</p>
            @endif
        </div>

        <!-- Ratings -->
        <div class="order-ratings mb-5">
            <div class="text-lg font-semibold mb-4">{{ __('locale.branch_orders.ratings') }}</div>
            @if($order->order_ratings->count() > 0)
                @foreach($order->order_ratings as $rating)
                    <div class="bg-gray-50 p-4 rounded-md mb-4">
                        <p><span class="font-semibold">{{ __('locale.branch_orders.rating') }}:</span> {{ $rating->rating }}</p>
                        <p><span class="font-semibold">{{ __('locale.branch_orders.message') }}:</span> {{ $rating->body_massage }}</p>
                    </div>
                @endforeach
            @else
                <p>{{ __('locale.branch_orders.no_ratings_available') }}</p>
            @endif
        </div>

        <div class="actions mt-5 text-center">
            <!-- Print button to trigger the JavaScript function -->
            <button onclick="printDiv()"
                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md">
                {{ __('locale.branch_orders.print_order') }}
            </button>
        </div>
    </div>
</x-app-layout>

<script>
    function printDiv() {
        var orderDetails = document.getElementById('printableArea').outerHTML;
        var printWindow = window.open('', '_blank');
        printWindow.document.write(orderDetails);
        printWindow.document.close();
        printWindow.print();
    }
</script>
