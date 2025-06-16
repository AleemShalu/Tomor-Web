<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div name="header">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('locale.product.offer.create_offer_title') }}: {{ $store->name }}
                        </h2>
                    </div>
                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>

                            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Status Message -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('offer.storeOffer', $store->id) }}">
                        @csrf

                        <input hidden="hidden" name="store_id" id="store_id" value="{{ $store->id }}">
                        <!-- Offer Name -->
                        <div class="mt-4">
                            <label for="offer_name" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.offer_name') }}
                            </label>
                            <input id="offer_name" class="block mt-1 w-full p-2 border rounded-md" type="text"
                                   name="offer_name" value="{{ old('offer_name') }}" required/>
                        </div>

                        <!-- Offer Description -->
                        <div class="mt-4">
                            <label for="offer_description" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.offer_description') }}
                            </label>
                            <textarea id="offer_description" class="block mt-1 w-full p-2 border rounded-md" type="text"
                                      rows="3"
                                      name="offer_description" value="{{ old('offer_description') }}"></textarea>
                        </div>

                        <!-- Discount Percentage -->
                        <div class="mt-4">
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.discount_percentage') }}
                            </label>
                            <input id="discount_percentage" class="block mt-1 w-full p-2 border rounded-md"
                                   type="number" min="0" max="100"
                                   name="discount_percentage" value="{{ old('discount_percentage') }}" required/>
                            @error('discount_percentage')
                            <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.status') }}
                            </label>
                            <input id="status" class="form-checkbox h-5 w-5 text-blue-600" type="checkbox"
                                   name="status" value="1" {{ old('status') ? 'checked' : '' }}/>
                            <span class="ml-2 text-sm text-gray-600">{{ __('locale.product.offer.active') }}</span>
                        </div>

                        <!-- Start Date -->
                        <div class="mt-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.start_date') }}
                            </label>
                            <input id="start_date" class="block mt-1 w-full p-2 border rounded-md" type="date"
                                   name="start_date" value="{{ old('start_date') }}" required/>
                        </div>

                        <!-- End Date -->
                        <div class="mt-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">
                                {{ __('locale.product.offer.end_date') }}
                            </label>
                            <input id="end_date" class="block mt-1 w-full p-2 border rounded-md" type="date"
                                   name="end_date" value="{{ old('end_date') }}" required/>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                    class=" px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                {{ __('locale.product.offer.create_new_offer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
