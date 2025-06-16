<x-app-layout>
    <div class="max-w-7xl mx-auto pt-6">
        <x-store-main :store-id="$store->id">
            <x-slot name="main">
                <div class="mt-5 w-full max-w-6xl mx-auto bg-white rounded">
                    <div class="">
                        <div class="font-bold text-2xl">
                            {{ __('locale.store_manage_products.title') }}
                        </div>
                        <div>
                            {{ __('locale.store_manage_products.description') }}

                        </div>
                        <hr class="my-2 pb-4">

                        <div class="p-2 mt-2 bg-gray-100 rounded my-2 border border-gray-200">
                            <div class="font-bold">
                                {{ __('locale.store_manage_products.manage_product_menu') }}
                            </div>
                            <div class="mb-4">
                                <p class="mb-2">
                                    {{ __('locale.store_manage_products.manage_product_menu_description') }}
                                </p>
                                <a href="{{route('products',[$store->id])}}" id="productLink"
                                   class="inline-block px-4 py-2 text-white bg-blue-color-1 rounded hover:bg-blue-700">
                                    {{ __('locale.store_manage_products.click_here') }}
                                </a>

                            </div>

                            <hr class="mt-2">
                            <div class="font-bold flex mt-2">
                                {{ __('locale.store_manage_products.offers') }}
                            </div>
                            <div class="">
                                <p class="mb-2">
                                    {{ __('locale.store_manage_products.offers_description') }}
                                </p>
                                <a href="{{ route('offer.product', [$store->id]) }}" id="productLink"
                                   class="inline-block px-4 py-2 text-white bg-blue-color-1 rounded hover:bg-blue-700">
                                    {{ __('locale.store_manage_products.click_here') }}
                                </a>
                            </div>
                        </div>

                        {{--                        @if ($store->products->count() !== 0)--}}
                        {{--                            <div class="p-2 mt-2 bg-gray-100 rounded my-2 border border-gray-200">--}}
                        {{--                                <div class="font-bold flex">--}}
                        {{--                                    {{ __('locale.store_manage_products.offers') }}--}}
                        {{--                                </div>--}}
                        {{--                                <div class="">--}}
                        {{--                                    <p class="mb-2">--}}
                        {{--                                        {{ __('locale.store_manage_products.offers_description') }}--}}
                        {{--                                    </p>--}}
                        {{--                                    <a href="{{ route('offer.product', [$store->id]) }}" id="productLink"--}}
                        {{--                                       class="inline-block px-4 py-2 text-white bg-blue-color-1 rounded hover:bg-blue-700">--}}
                        {{--                                        {{ __('locale.store_manage_products.click_here') }}--}}
                        {{--                                    </a>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        @endif--}}


                        <div class="p-2 bg-gray-100 rounded my-2 border border-gray-200">
                            <div class="font-bold">
                                {{ __('locale.store_manage_products.manage_pdf_menu') }}
                            </div>
                            <div class="">
                                <div class="pt-2 w-full mx-auto">
                                    @if ($store->menu_pdf)
                                        <!-- If menu PDF exists -->
                                        <div class="">
                                            <div class="flex gap-x-3">
                                                <button
                                                        onclick="window.open('{{ asset('storage/' . $store->menu_pdf) }}', '_blank');"
                                                        class="bg-blue-color-1 hover:bg-blue-700 text-white rounded p-2 my-1 flex mr-2">
                                                    {{ __('locale.store_manage_products.view_menu_pdf') }}
                                                    <i class="mx-2" data-lucide="eye"></i>
                                                </button>
                                                <form action="{{ route('store.destroy-menu') }}" method="POST"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                                                    <button type="submit"
                                                            class="bg-red-500 hover:bg-red-700 text-white  rounded p-2 my-1">
                                                        {{ __('locale.store_manage_products.delete_menu_pdf') }}
                                                    </button>
                                                </form>
                                            </div>

                                            <iframe src="{{ asset('storage/' . $store->menu_pdf) }}" width="100%"
                                                    height="1000px" class="rounded-md"></iframe>

                                        </div>
                                    @else
                                        <!-- If menu PDF doesn't exist -->
                                        <div class="mb-4">
                                            <div class=" w-full">
                                                <form action="{{ route('store.upload-menu') }}" method="POST"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="my-3">
                                                        <label
                                                                for="formFile"
                                                                class="mb-2 inline-block text-neutral-700 dark:text-neutral-200">
                                                            {{ __('locale.store_manage_products.upload_menu_pdf') }}
                                                        </label>
                                                        <input
                                                                class="relative bg-white m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
                                                                type="file"
                                                                id="dropzone-file"
                                                                name="menu_pdf"/>
                                                    </div>
                                                    <input type="hidden" id="store_id" name="store_id"
                                                           value="{{$store->id}}">
                                                    <button type="submit"
                                                            class="bg-blue-color-1 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                        Upload
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- End coding here -->
                                </div>
                                <!-- End block -->
                            </div>
                        </div>

                    </div>


                </div>
            </x-slot>

        </x-store-main>
    </div>
</x-app-layout>

