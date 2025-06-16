<x-app-layout>

    <div class="container mx-auto py-8 px-4 rounded">
        <div class="py-4 w-full max-w-9xl mx-auto">

            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{route('dashboard')}}"
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{route('store')}}"
                               class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Store</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{route('product.manage',['id'=>$store->id])}}"
                               class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{$store->commercial_name_en}}</a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="px-3 py-6 bg-white">

            <h2 class="text-2xl font-bold mb-4">{{__('locale.product.page_product.product_details')}}</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-inter text-2xl mt-4">{{__('locale.product.page_product.info')}}</h3>

                    <div class="bg-gray-100 p-3 mt-2 rounded-2xl ">
                        <p class="mb-2"><span
                                    class="font-semibold">{{__('locale.product.page_product.product_code')}}</span> {{$product['product_code']}}
                        </p>
                        <p class="mb-2"><span
                                    class="font-semibold">{{__('locale.product.page_product.unit_price')}}</span> {{ number_format($product['unit_price'], 2) }}
                            SAR</p>
                        <p class="mb-2"><span
                                    class="font-semibold">{{__('locale.product.page_product.status.status')}}:</span>
                            @if($product['status'] == 1)
                                {{__('locale.product.page_product.status.active')}}
                            @elseif($product['status'] == 0)
                                {{__('locale.product.page_product.status.inactive')}}
                            @else
                                {{__('locale.product.page_product.status.unknown')}}
                            @endif
                        </p>
                        <p class="mb-2"><span
                                    class="font-semibold">{{__('locale.product.page_product.created_at')}}</span> {{$product['created_at']}}
                        </p>
                    </div>

                </div>
                <div>
                    <h3 class="font-inter text-2xl mt-4">{{__('locale.product.page_product.translations')}}</h3>
                    @foreach($product['translations'] as $translation)
                        <div class="bg-gray-100 p-3 mt-2 rounded-2xl ">
                            <p class="mb-2"><span
                                        class="font-bold">{{__('locale.product.page_product.locale')}}: {{get_locale_name($translation['locale'])}}</span>
                            </p>
                            <p class="mb-2"><span
                                        class="font-semibold">{{__('locale.product.page_product.name')}}</span> {{$translation['name']}}
                            </p>
                            <p class="mb-2"><span
                                        class="font-semibold">{{__('locale.product.page_product.description')}}</span> {{$translation['description']}}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <h3 class="font-inter text-2xl mt-8">{{__('locale.product.page_product.product_images')}}</h3>
            <div class="grid grid-cols-2 gap-4 mt-2">
                @foreach($product['images'] as $image)
                    <div class="bg-gray-100 p-3 mt-2 p-4 rounded-2xl ">
                        <img src="{{ asset('storage/'. $image['url']) }}"
                             alt="{{ __('locale.product.page_product.product_image') }}"
                             height="500px" width="500px" class="mb-2">
                        {{--                        @else--}}
                        {{--                            <!-- Placeholder image or error image URL -->--}}
                        {{--                            <img src="https://www.cvent-assets.com/brand-page-guestside-site/assets/images/venue-card-placeholder.png"--}}
                        {{--                                 alt="https://www.cvent-assets.com/brand-page-guestside-site/assets/images/venue-card-placeholder.png"--}}
                        {{--                                 height="500px" width="500px" class="mb-2">--}}
                        {{--                        @endif--}}

                        <p class="mb-2"><span
                                    class="font-semibold">{{ __('locale.product.page_product.created_at') }}</span> {{ $image['created_at'] }}
                        </p>
                        <p class="mb-2"><span
                                    class="font-semibold">{{ __('locale.product.page_product.updated_at') }}</span> {{ $image['updated_at'] }}
                        </p>
                    </div>
                @endforeach
            </div>


        </div>
    </div>

</x-app-layout>

<script>
    function goBack() {
        window.history.back();
    }
</script>
