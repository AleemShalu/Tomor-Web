<x-app-layout>
    <div class="p-6 bg-white shadow-md rounded-md">
        <!-- Heading and Add New button -->
        <div class="flex justify-between mb-6">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold">{{ __('locale.product.heading') }}</h1>
                <span class="ml-4 bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{{$productsCount}}</span>
            </div>
            <div>
                <button class="bg-blue-color-1 hover:bg-blue-600 text-white py-2 px-4 rounded mr-2">
                    <a href="{{ route('product.create', ['id' => $storeId]) }}" class="text-white no-underline">
                        {{ __('locale.product.add_new') }}
                    </a>
                </button>
                <button id="uploadBtn" class="bg-blue-color-1 hover:bg-blue-600 text-white py-2 px-4 rounded">
                    {{ __('locale.product.import_json') }}
                </button>
                <form id="jsonUploadForm" action="{{ route('product.import.json') }}" method="POST"
                      enctype="multipart/form-data" class="mb-2 hidden">
                    @csrf
                    <input type="hidden" name="store_id" id="store_id" value="{{$storeId}}">
                    <input type="file" name="json_file" id="jsonFileInput" accept=".json" class="mr-2">
                </form>
                <progress id="uploadProgress" value="0" max="100" class="w-full hidden"></progress>
                <p id="uploadMessage" class="hidden">Your file is being uploaded and processed in the background. You
                    will be notified once completed.</p>
            </div>
        </div>

        <!-- Search bar -->
        <div class="mb-6 flex items-center border border-gray-300 rounded">
            <span class="px-2 text-xl text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                          d="M21 21l-6-6m2-6a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" id="searchInput" placeholder="Search by Product Code, Model Number"
                   class="py-2 px-2 flex-1 outline-none">
        </div>
        <button id="deleteSelectedBtn" class="bg-red-500 hover:bg-red-600 text-white mb-2 py-2 px-4 rounded hidden">
            {{ __('locale.product.delete_selected') }}
        </button>

        <div class="overflow-x-auto" style="max-height: 500px; overflow-y: scroll;">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.id_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.category_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.product_name_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.price_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.product_code_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.model_number_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.status_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.has_active_offer_header') }}</th>
                    <th class="py-2 px-4 border-b">{{ __('locale.product.actions_header') }}</th>
                </tr>
                </thead>
                <tbody id="productTableBody">
                @forelse ($products as $product)
                    <tr>
                        <td class="py-2 px-4 border-b text-center">{{ $product->id }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $product->product_category->category_en }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $product->translations[0]->name }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $product->unit_price }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $product->product_code }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $product->model_number }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $product->status === 1 ? 'In Stock' : 'Out of Stock' }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $product->has_active_offer ? 'Yes' : 'No' }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <a href="{{ route('product.edit', $product->id) }}"
                               class="text-blue-600 hover:underline mx-2 font-bold">{{ __('locale.product.edit_product') }}</a>
                            <a href="{{ route('product.show', $product->id) }}"
                               class="text-green-600 hover:underline mx-2 font-bold">{{ __('locale.product.preview_product') }}</a>
                            <form method="POST" action="{{ route('product.delete') }}"
                                  class="inline-block delete-product-form">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="store_id" id="store_id" value="{{ $storeId }}">
                                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                                <button type="submit"
                                        class="text-red-600 hover:underline mx-2 font-bold">{{ __('locale.product.delete_product') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9"
                            class="text-center py-4 text-gray-500">{{ __('locale.product.no_products') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <style>
        /* Example CSS for error message formatting */
        .swal2-content ul {
            list-style-type: none;
            padding: 0;
        }

        .swal2-content ul li {
            margin-bottom: 5px;
        }

        .swal2-content p {
            margin-bottom: 10px;
        }
    </style>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');

        function applySearchFilter() {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#productTableBody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let found = false;
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });
                row.style.display = found ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', applySearchFilter);
    });
</script>

<script>
    $(document).ready(function () {
        var xhr; // Define the XHR variable to keep track of the upload

        $('#uploadBtn').click(function () {
            $('#jsonFileInput').click();
        });

        $('#jsonFileInput').change(function () {
            var fileInput = $('#jsonFileInput')[0];

            // Initialize SweetAlert2 for upload progress
            const uploadingAlert = Swal.fire({
                title: 'Uploading File',
                html: 'Please wait... (0%)<br>Estimated time remaining: Calculating...',
                icon: 'info',
                showConfirmButton: false,
                showCancelButton: true, // Show the cancel button
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            var form = $('#jsonUploadForm')[0];
            var data = new FormData(form);

            var startTime = Date.now(); // Record start time

            xhr = $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{ route('product.import.json') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            var currentTime = Date.now();
                            var elapsedTime = (currentTime - startTime) / 1000; // in seconds
                            var uploadSpeed = evt.loaded / elapsedTime; // bytes per second
                            var totalSize = evt.total;
                            var remainingSize = totalSize - evt.loaded;
                            var estimatedTime = remainingSize / uploadSpeed; // seconds

                            uploadingAlert.update({
                                title: 'Uploading File',
                                html: `Please wait... (${percentComplete.toFixed(2)}%)<br>Estimated time remaining: ${formatTime(estimatedTime)}`,
                                icon: 'info'
                            });
                        }
                    }, false);
                    return xhr;
                },
                success: function (response) {
                    console.log("SUCCESS: ", response);

                    if (response.failed > 0) {
                        // Prepare the error message with HTML structure
                        var errorMessage = "<p>Data import completed with errors:</p><ul>";
                        response.data.items.forEach(function (item) {
                            errorMessage += `<li><strong>Product ${item.item.product_code}:</strong> ${item.error}</li>`;
                        });
                        errorMessage += "</ul>";

                        Swal.fire({
                            title: 'Partial Success',
                            html: errorMessage,
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: false,
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancel',
                            customClass: {
                                content: 'text-left'
                            },
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        // Handle successful import
                        Swal.fire({
                            title: 'Success',
                            text: 'File has been uploaded successfully',
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function (error) {
                    console.log("ERROR: ", error);
                    uploadingAlert.close();
                    Swal.fire({
                        title: 'Error',
                        text: 'File upload failed',
                        icon: 'error'
                    });
                }
            });
        });

        // Helper function to format time in HH:MM:SS
        function formatTime(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var remainingSeconds = Math.round(seconds % 60);
            return (
                (hours < 10 ? '0' : '') + hours + ':' +
                (minutes < 10 ? '0' : '') + minutes + ':' +
                (remainingSeconds < 10 ? '0' : '') + remainingSeconds
            );
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-product-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this product?')) {
                    form.submit();
                }
            });
        });
    });
</script>