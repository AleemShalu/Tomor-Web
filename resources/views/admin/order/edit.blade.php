<x-app-admin-layout>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">
            {{ __('admin.order_management.details.title') }}
        </h1>
        <!-- Display the success message if it exists in the session -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 bg-white p-4 rounded-l-md">
            <h2 class="text-xl font-bold pb-2"><i class="fa-solid fa-receipt pr-2"></i>
                {{ __('admin.order_management.details.order_information') }}
            </h2>
            <p class="text-gray-700">
                {{ __('admin.order_management.details.order_number') }}: {{ $order->order_number }}
            </p>
            <p class="text-gray-700">  {{ __('admin.order_management.details.status_name') }}:
                <span class="px-2 py-1 rounded-full text-white"
                      style="background-color: {{ getOrderStatusColor($order->status)}}">
                {{ __('order-status.' . $order->status) }}
                </span>
            </p>
            <p class="text-gray-700">{{ __('admin.order_management.details.order_date') }}: {{ $order->order_date }}</p>

            <p class="text-gray-700">
                {{ __('admin.order_management.details.store_name') }}: {{ $order->store->commercial_name_en }}
            </p>
            <p class="text-gray-700">{{ __('admin.order_management.details.branch_name') }}
                : {{ $order->store_branch->name }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.store_email') }}
                : {{ $order->store->email }}</p>
            <p class="text-gray-700">
                {{ __('admin.order_management.details.store_contact_no') }}: {{ $order->store->contact_no }}
            </p>

            <p class="text-gray-700">{{ __('admin.order_management.details.items_count') }}
                : {{ $order->items_count }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.items_quantity') }}
                : {{ $order->items_quantity }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.grand_total') }}
                : {{ $order->grand_total }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.sub_total') }}: {{ $order->sub_total }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.service_total') }}
                : {{ $order->service_total }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.discount_total') }}
                : {{ $order->discount_total }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.tax_total') }}: {{ $order->tax_total }}</p>
            <p class="text-gray-700">{{ __('admin.order_management.details.taxable_total') }}
                : {{ $order->taxable_total }}</p>
        </div>


        <!-- Your order management form goes here -->
        <form action="{{ route('admin.order.update', ['id' => $order->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Add a select field for order status -->
            <div class="mb-4">
                <label for="status" class="block font-bold text-gray-700">
                    {{ __('admin.order_management.details.status_name') }}:
                </label>
                <select name="status" id="status" class="form-select mt-1 block w-full">
                    <option value="pending_payment" {{ $order->status === 'pending_payment' ? 'selected' : '' }}>
                        {{ __('order-status.pending_payment') }}
                    </option>
                    <option value="received" {{ $order->status === 'received' ? 'selected' : '' }}>
                        {{ __('order-status.received') }}
                    </option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                        {{ __('order-status.processing') }}
                    </option>
                    <option value="delivering" {{ $order->status === 'delivering' ? 'selected' : '' }}>
                        {{ __('order-status.delivering') }}
                    </option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                        {{ __('order-status.delivered') }}
                    </option>
                    @if (!$order->invoice)
                        <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>
                            {{ __('order-status.canceled') }}
                        </option>
                    @endif
                    <!-- Add more status options as needed -->
                </select>
            </div>

            <!-- Display the current status indicator -->
            <div class="mb-4">
                <label class="block font-bold text-gray-700">
                    {{ __('admin.order_management.details.current_status') }}:
                </label>
                <span class="px-2 py-1 rounded-full text-white"
                      style="background-color: {{ getOrderStatusColor($order->status)}}">
                {{ __('order-status.' . $order->status) }}
                </span></div>

            <!-- Include other form fields to update order details -->

            <div class="text-right mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('admin.common.save') }}
                </button>
            </div>
        </form>
    </div>
</x-app-admin-layout>

@php
    function getStatusHtml($status) {
        if ($status === 'delivered') {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-green-300 px-2 py-1 text-xs font-semibold text-black">Delivered</span>';
        } elseif ($status === 'delivering') {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-purple-300 px-2 py-1 text-xs font-semibold text-black">Delivering</span>';
        } elseif ($status === 'canceled') {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-red-300 px-2 py-1 text-xs font-semibold text-black">Canceled</span>';
        } elseif ($status === 'received') {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-blue-300 px-2 py-1 text-xs font-semibold text-black">Received</span>';
        } elseif ($status === 'processing') {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-yellow-300 px-2 py-1 text-xs font-semibold text-black">Processing</span>';
        } elseif ($status === 'completed') {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-purple-300 px-2 py-1 text-xs font-semibold text-black">Completed</span>';
        } elseif ($status === 'pending') {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-gray-300 px-2 py-1 text-xs font-semibold text-black">Pending</span>';
        } else {
            return '<span class="inline-flex items-center gap-1 rounded-full bg-gray-300 px-2 py-1 text-xs font-semibold text-black">Status Unknown</span>';
        }
    }
@endphp
