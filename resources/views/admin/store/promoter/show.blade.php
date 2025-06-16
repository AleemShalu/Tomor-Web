@php
    $locale = app()->getLocale();
@endphp

<x-app-admin-layout>
    <div name="header" class="p-4 shadow-md bg-white">
        <h2 class="font-semibold text-2xl leading-tight">
            @if($locale == 'en')
                <span class="">{{ $storePromoter->store->short_name_en }}</span>
                - {{ $storePromoter->name_en }}
            @else
                <span class="">{{ $storePromoter->store->short_name_ar }}</span>
                - {{ $storePromoter->name_ar }}
            @endif
        </h2>
    </div>


    <div class="py-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <div class="mb-4">
                    <strong class="text-gray-700">{{ __('admin.store_management.promoters.status') }}:</strong>
                    @if($storePromoter->status == 1)
                        <span class="text-green-500">{{ __('admin.common.active') }}</span>
                    @else
                        <span class="text-red-500">{{ __('admin.common.inactive') }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <strong class="text-gray-700">{{ __('admin.store_management.promoters.code') }}
                        :</strong> {{ $storePromoter->code }}
                </div>

                <div class="mb-4">
                    <strong class="text-gray-700">{{ __('admin.store_management.promoters.start_date') }}:
                    </strong> {{ \Carbon\Carbon::parse($storePromoter->start_date)->format('Y-m-d H:i:s') }}
                </div>

                <div class="mb-4">
                    <strong class="text-gray-700">{{ __('admin.store_management.promoters.end_date') }}:
                    </strong> {{ \Carbon\Carbon::parse($storePromoter->end_date)->format('Y-m-d H:i:s') }}

                </div>

                <div class="mb-4">
                    <strong class="text-gray-700">{{ __('admin.store_management.promoters.created_at') }}
                        :</strong> {{ \Carbon\Carbon::parse($storePromoter->created_at)->format('Y-m-d H:i:s') }}
                </div>

                <div class="mb-4">
                    <strong class="text-gray-700">{{ __('admin.store_management.promoters.updated_at') }}
                        :</strong> {{ \Carbon\Carbon::parse($storePromoter->updated_at)->format('Y-m-d H:i:s') }}
                </div>

                <div class="mb-4">
                    <strong class="text-gray-700">{{ __('admin.store_management.promoters.image') }}:</strong>
                    <img src="{{ asset('storage/' . $storePromoter->promoter_header_path) }}" alt="Promoter Image"
                         class="max-w-full h-auto">
                </div>

            </div>
        </div>
    </div>


    <div class="flex justify-end m-4">
        <button onclick="goBack()" class="bg-blue-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            {{__('admin.common.back')}}
        </button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

</x-app-admin-layout>
