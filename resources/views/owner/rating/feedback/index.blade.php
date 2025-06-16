@php
    $locale = app()->getLocale(); // Get the current locale
@endphp

<x-app-layout>
    <div class="bg-white max-w-7xl mx-auto max-h-full mt-5 p-5 rounded-md">
        <div class="pl-4 flex gap-x-3">
            <div class="text-2xl font-bold">
                {{ __('locale.nav_rating.feedback') }}
            </div>
            <div>
                <i class="fa-solid fa-star fa-2xl"></i>
            </div>
        </div>
        <div class="bg-white mt-4 py-6 px-4 w-full">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                    <li class="mr-2">
                        <a href="{{ route('rating.index') }}"
                           class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                            <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                            </svg>
                            {{ __('locale.nav_rating.summary') }}
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('feedback.index') }}"
                           class="inline-flex items-center justify-center p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group">
                            <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-500" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z"/>
                            </svg>
                            {{ __('locale.nav_rating.feedback') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mt-5">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.id') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.customer_name') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.massage') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.rating') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.order_rating_type') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.order_number') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.name_store') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.name_branch') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('locale.table.created_at') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ratings as $rating)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">{{ $rating->id }}</td>
                                <td class="px-6 py-4">{{ $rating->customer->name }}</td>
                                <td class="px-6 py-4">{{ $rating->body_massage }}</td>
                                <td class="px-6 py-4">
                                    <span class="stars">{{ str_repeat('★', $rating->rating) . str_repeat('☆', 5 - $rating->rating) }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $name = $locale === 'ar' ? $rating->order_rating_type->ar_name : $rating->order_rating_type->en_name }}</td>
                                <td class="px-6 py-4">{{ $rating->order->store_order_number }}</td>
                                <td class="px-6 py-4">{{ $rating->store->commercial_name_en }}</td>
                                <td class="px-6 py-4">{{ $rating->order->store_branch->name_en }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($rating->created_at)->format('M d, Y, h:i:s A') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
