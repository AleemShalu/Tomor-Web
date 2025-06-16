<div class="mt-5 p-3 bg-gray-100 rounded p-3">
    <h2 class="text-lg font-bold mb-4">
        <i class="fas fa-file-invoice-dollar mr-2"></i> Invoice
    </h2>
    <div class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3">
        <form class='px-7 w-2/5'>
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3">
                <form class='px-7 w-2/5'>
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="search" id="default-search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Mockups, Logos..." required wire:model.debounce.300ms="query">
                    </div>
                </form>
                <div class="relative float-right">
                    <input class="datepicker form-input pl-9 text-slate-500 hover:text-slate-600 font-medium focus:border-slate-300 w-60" placeholder="Select dates" data-class="flatpickr-right" />
                    <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
                        <svg class="w-4 h-4 fill-current text-slate-500 ml-3" viewBox="0 0 16 16">
                            <path d="M15 2h-2V0h-2v2H9V0H7v2H5V0H3v2H1a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V3a1 1 0 00-1-1zm-1 12H2V6h12v8z" />
                        </svg>
                    </div>
                </div>
            </div>
        </form>
        <button type="button" class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none" viewbox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Update invoices
        </button>
        <form action="#" method="GET" class="flex items-center">
            {{--                    <input type="hidden" name="id" value="{{ $id }}"> <!-- Add this hidden input field -->--}}
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <div>
                    <button @click="open = !open" type="button" class="flow-button flow-button-primary flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        Export
                        <svg x-bind:class="{ 'transform rotate-180': open }" class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <div x-show="open" class="absolute right-0 w-40 mt-2 py-2 bg-white border border-gray-200 rounded-lg shadow-md z-10">
                    <a href="{{ route('invoices.export.pdf', ['branchId' => $branch->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-700">Export as PDF</a>
                    <a href="{{ route('invoices.export.csv', ['branchId' => $branch->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-700">Export as CSV</a>
                    <a href="{{ route('invoices.export.excel', ['branchId' => $branch->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-700">Export as XLSX</a>
                </div>
            </div>
        </form>
        <div class="relative float-right">
            <input class="datepicker form-input pl-9 text-slate-500 hover:text-slate-600 font-medium focus:border-slate-300 w-60" placeholder="Select dates" data-class="flatpickr-right" />
            <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
                <svg class="w-4 h-4 fill-current text-slate-500 ml-3" viewBox="0 0 16 16">
                    <path d="M15 2h-2V0h-2v2H9V0H7v2H5V0H3v2H1a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V3a1 1 0 00-1-1zm-1 12H2V6h12v8z" />
                </svg>
            </div>
        </div>

    </div>
    <br>
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-700">
        <thead class="bg-gray-50">
        <tr class="bg-gray-50">
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Id Order</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Items</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Date</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Unit Price</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Discount</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Subtotal</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Tax</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">Total</th>
            <th class="px-6 py-4 font-medium text-gray-900 border-b">-</th>

        </tr>
        </thead>
        <tbody>
        @forelse($invoice as $inv)
            <tr>
                <td class="py-2 px-4 border-b">{{ $inv->order_id }}</td>
                <td class="py-2 px-4 border-b">{{ $inv->items_quantity }}</td>
                <td class="py-2 px-4 border-b">{{ $inv->invoice_date }}</td>
                <td class="py-2 px-4 border-b">{{ $inv->base_grand_total / $inv->items_quantity }}</td>
                <td class="py-2 px-4 border-b">{{ $inv->base_discount_total / $inv->items_quantity }}</td>
                <td class="py-2 px-4 border-b">{{ $inv->base_sub_total / $inv->items_quantity }}</td>
                <td class="py-2 px-4 border-b">{{ $inv->base_tax_total / $inv->items_quantity }}</td>
                <td class="py-2 px-4 border-b">{{ $inv->base_grand_total }}</td>
                <td class="px-6 py-4 border-b">
                    <button data-tooltip-target="tooltip-animation-print" class="mr-2">
                        <a href="{{ route('invoice.pdf', ['invoiceId' => $inv->id]) }}" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                        </a>
                        <div id="tooltip-animation-print" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Print
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </button>
                    <button data-tooltip-target="tooltip-animation-preview" class="mr-2">
                        <a href="{{ route('preview-invoice', ['invoiceId' => $inv->id]) }}" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>
                        <div  id="tooltip-animation-preview" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Preview
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center">
                    No invoices found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
