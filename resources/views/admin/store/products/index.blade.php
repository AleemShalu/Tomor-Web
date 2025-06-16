<x-app-admin-layout>

    <div class="w-full lg:w-2/3 mx-auto">
        <div class=" flex text-black font-bold text-2xl py-4">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 fill="currentColor" viewBox="0 0 20 20">
                <path
                        d="M17.876.517A1 1 0 0 0 17 0H3a1 1 0 0 0-.871.508C1.63 1.393 0 5.385 0 6.75a3.236 3.236 0 0 0 1 2.336V19a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6h4v6a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V9.044a3.242 3.242 0 0 0 1-2.294c0-1.283-1.626-5.33-2.124-6.233ZM15.5 14.7a.8.8 0 0 1-.8.8h-2.4a.8.8 0 0 1-.8-.8v-2.4a.8.8 0 0 1 .8-.8h2.4a.8.8 0 0 1 .8.8v2.4ZM16.75 8a1.252 1.252 0 0 1-1.25-1.25 1 1 0 0 0-2 0 1.25 1.25 0 0 1-2.5 0 1 1 0 0 0-2 0 1.25 1.25 0 0 1-2.5 0 1 1 0 0 0-2 0A1.252 1.252 0 0 1 3.25 8 1.266 1.266 0 0 1 2 6.75C2.306 5.1 2.841 3.501 3.591 2H16.4A19.015 19.015 0 0 1 18 6.75 1.337 1.337 0 0 1 16.75 8Z"/>
            </svg>
            <div class="pl-3">
                Show Products
            </div>
        </div>

        <div class="bg-white">
            <div class="mx-auto px-6 m-4">
                <h2 class="sr-only">Products</h2>


                <div class="p-4">
                    <label for="categoryFilter" class="font-semibold">Filter by Category:</label>
                    <div class="flex items-center">
                        <input
                                name="name_product"
                                type="text"
                                id="searchInput"
                                placeholder="Search products..."
                                class="p-1 border rounded"
                                value="{{old('searchInput')}}"
                        >
                        <select id="categoryFilter" name="category" class="ml-2 p-1 border rounded">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option
                                        value="{{ $category->id }}" {{ $category->id == $selectedCategoryId ? 'selected' : '' }}>
                                    {{ $category->category_en }}
                                </option>
                            @endforeach
                        </select>
                        <button onclick="applyFilters()" class="px-2 py-1 bg-blue-500 text-white rounded ml-2">Apply
                            Filters
                        </button>
                    </div>
                </div>


                <div
                        class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8 py-4 bg-white">
                    @if($products->isEmpty())
                        <p class="text-gray-700 text-left w-full">No products available.</p>
                    @else
                        @foreach($products as $product)
                            <div class="bg-gray-200 m-4 p-4 rounded-md hover:bg-gray-300">
                                <a href="#" class="group">
                                    <div
                                            class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
                                        <img
                                                src="{{ $product->images->isEmpty() ? 'https://placehold.co/600x400?text=No+Image' : asset('storage/' . $product->images[0]->url) }}"
                                                alt="{{ $product->translations[0]->name }}" style="height: 190px; "
                                                class="w-full object-cover group-hover:opacity-75">
                                    </div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-700">{{ $product->translations[1]->name }}</h3>
                                    <p class="mt-2 text-sm font-sm text-gray-900">{{ $product->translations[0]->description }}</p>
                                    <p class="mt-1 text-sm font-sm text-gray-900">
                                        Category:{{ $product->product_category->category_en }}</p>
                                    <p class="mt-1 text-sm font-sm text-gray-900">Model
                                        Number:{{ $product->model_number }}</p>
                                    <p class=" text-sm font-sm text-gray-900">Quantity: {{ $product->quantity }}</p>
                                    <p class=" text-sm font-sm text-gray-900">
                                        Availability: {{ $product->availability }}</p>
                                    <p class="mt-1 text-lg font-medium text-gray-900">SAR {{ $product->unit_price }}</p>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="p-2">
                    {{ $products->links() }} <!-- Pagination links -->
                </div>
            </div>
        </div>
    </div>
</x-app-admin-layout>


<script>
    function applyFilters() {
        const selectedCategoryId = document.getElementById('categoryFilter').value;
        const searchKeyword = document.getElementById('searchInput').value;

        // Construct the URL with both category and search filters
        const url = `{{ route('admin.product.index', ['id' => $id]) }}?category=${selectedCategoryId}&search=${searchKeyword}`;

        window.location.href = url;
    }
</script>