<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div name="header">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('locale.product.offer.update_offer_title') }}
                        </h2>
                    </div>
                    <!-- Validation Errors -->

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

                    <form action="{{ route('offer.update', ['storeId' => $store->id, 'offerId' => $offer->id]) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Offer Name -->
                        <div class="my-4">
                            <label for="offer_name" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.offer_name') }}
                            </label>
                            <input type="text" id="offer_name" name="offer_name"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ $offer->offer_name }}" required>
                        </div>

                        <!-- Offer Description -->
                        <div class="mb-4">
                            <label for="offer_description" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.offer_description') }}
                            </label>
                            <textarea id="offer_description" name="offer_description" rows="3"
                                      class="rounded-md shadow-sm mt-1 block w-full">{{ $offer->offer_description }}</textarea>
                        </div>

                        <!-- Discount Percentage -->
                        <div class="mb-4">
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.discount_percentage') }}
                            </label>
                            <input type="number" id="discount_percentage" name="discount_percentage"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ $offer->discount_percentage }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.status') }}
                            </label>
                            <select id="status" name="status" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                    required>
                                <option value="1" {{ $offer->status ? 'selected' : '' }}>
                                    {{ __('locale.product.offer.active') }}
                                </option>
                                <option value="0" {{ !$offer->status ? 'selected' : '' }}>
                                    {{ __('locale.product.offer.inactive') }}
                                </option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.start_date') }}
                            </label>
                            <input type="date" id="start_date" name="start_date"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ $offer->start_date->format('Y-m-d') }}" required>
                        </div>

                        <!-- End Date -->
                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.end_date') }}
                            </label>
                            <input type="date" id="end_date" name="end_date"
                                   class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ $offer->end_date->format('Y-m-d') }}" required>
                        </div>

                        <x-button class="ml-5">
                            {{ __('locale.product.offer.update_offer') }}
                        </x-button>
                    </form>

                    <!-- Delete Offer Button -->
                    <form action="{{ route('offer.destroy', ['storeId' => $store->id, 'offerId' => $offer->id]) }}"
                          method="POST"
                          onsubmit="return confirm('{{ __('locale.product.offer.confirm_delete_offer') }}');"
                          class="ml-5 mt-3"> <!-- Add left margin here for spacing between buttons -->
                        @csrf
                        @method('DELETE')
                        <x-button class="bg-red-500">
                            {{ __('locale.product.offer.delete_offer') }}
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
