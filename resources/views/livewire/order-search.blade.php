<div class="bg-gray-100 rounded p-2">

    <div class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4">

        <div class="flex items-center flex-1 space-x-4">
            <h5>
                <span class="text-gray-500">All Orders:</span>
                <span class="dark:text-white">{{$orders_count}}</span>
            </h5>
            <h5>
                <span class="text-gray-500">Total sales:</span>
                <span class="dark:text-white">{{ number_format($totalBaseTaxable, 2)}}$</span>
            </h5>

        </div>
        <div class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3">
            <form class='px-7 w-2/5'>
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.debounce.500ms="search" type="search" id="default-search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Mockups, Logos..." required>
                    <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                </div>
            </form>

            <button type="button" class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none" viewbox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Update orders
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
                    <div x-show="open" @click.away="open = false" class="flow-dropdown absolute right-0 w-40 mt-2 py-2 bg-white border border-gray-200 rounded-lg shadow-md z-10">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-700">Export as PDF</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-700">Export as CSV</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-700">Export as XLSX</a>
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


    </div>
    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
        <table class="w-full border-collapse bg-white text-left text-sm text-gray-700">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Order Number</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Date</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Customer</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">States</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Item</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Total</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Rating</th> <!-- Added column header -->
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">-</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
            @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        {{$order->order_number}}
                    </td>

                    <td class="px-6 py-4">
                        {{$order->order_date}}
                    </td>

                    <td class="px-6 py-4">
                        {{$order['user']['id']}} #{{$order['user']['name']}}
                    </td>

                    <td class="px-6 py-4">
                       @if ($order['status'] == 'received')
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-300 px-2 py-1 text-xs font-semibold text-black">
                            <span class="h-1.5 w-1.5 rounded-full bg-orange-600"></span>
                            Received
                        </span>
                        @elseif ($order['status'] == 'processing')
                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-300 px-2 py-1 text-xs font-semibold text-black">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                            Processing
                        </span>
                        @elseif ($order['status'] == 'delivering')
                        <span class="inline-flex items-center gap-1 rounded-full bg-purple-300 px-2 py-1 text-xs font-semibold text-black">
                            <span class="h-1.5 w-1.5 rounded-full bg-white-600"></span>
                            Delivering
                        </span>
                        @elseif ($order['status'] == 'delivered')
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-300 px-2 py-1 text-xs font-semibold text-black">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-200"></span>
                            Delivered
                        </span>
                        @elseif ($order['status'] == 'canceled')
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-300 px-2 py-1 text-xs font-semibold text-black">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                Canceled
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-300 px-2 py-1 text-xs font-semibold text-black">
                                Status Unknown
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{$order->items_count}}</td>
                    <td class="px-6 py-4">
                        {{$order->base_taxable_total}}
                    </td>
                    <td class="items-center">
                        @if (isset($order['rating']['rating']))
                            @for ($i = 1; $i <= $order['rating']['rating']; $i++)
                                <span class="text-yellow-500 text-2xl">&#9733;</span>
                            @endfor
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <button data-tooltip-target="tooltip-animation-print" class="mr-2">
                            <a href="#" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            </a>
                            <div  id="tooltip-animation-print" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Print
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </button>
                        <button data-tooltip-target="tooltip-animation-preview" class="mr-2">
                            <a href="#" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                            <div  id="tooltip-animation-preview" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Preview
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </button>
                    </td>
                </tr>
            @endforeach


            </tbody>
        </table>
    </div>


    <script type="module" src="{{asset('js/7ader/test_ajax.js')}}"></script>

</div>

