@php
    $locale = app()->getLocale();
@endphp
<x-app-admin-layout>
    <div class="p-6">
        <div class="border border-gray-200 rounded-t bg-white mb-0 px-6 py-6">
            <div class="header justify-between items-center">
                <div class="flex items-center">
                    <i class="fa-solid fa-shop text-xl mr-2"></i>
                    <div class="font-bold text-xl">
                        {{ __('admin.store_management.promoters.title') }}
                    </div>
                </div>
                <a href="{{ route('admin.promoters.create') }}"
                   class="float-left bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('admin.store_management.promoters.create') }}
                </a>
            </div>
            <div class="mb-3 text-right">
                {{ __('admin.store_management.promoters.description') }}
            </div>
        </div>
        <div class="overflow-x-auto py-3">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="border-b border-gray-200">
                <tr>
                    <th class="py-2 px-4 border-r border-gray-200">{{ __('admin.store_management.promoters.id') }}</th>
                    <th class="py-2 px-4 border-r border-gray-200">{{ __('admin.store_management.promoters.code') }}</th>
                    <th class="py-2 px-4 border-r border-gray-200">{{ __('admin.store_management.promoters.name') }}</th>
                    <th class="py-2 px-4 border-r border-gray-200">{{ __('admin.store_management.promoters.description_col') }}</th>
                    <th class="py-2 px-4 border-r border-gray-200">{{ __('admin.store_management.promoters.status') }}</th>
                    <th class="py-2 px-4 border-r border-gray-200">{{ __('admin.store_management.promoters.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($promoters as $promoter)
                    <tr class="border-b border-gray-200">
                        <td class="py-2 px-4 text-center border-r border-gray-200">{{ $promoter->id }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-200">{{ $promoter->code }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-200">{{ $locale == 'en' ? $promoter->name_en : $promoter->name_ar }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-200">{{ $locale == 'en' ? $promoter->description_en : $promoter->description_ar }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-200">{{ $promoter->status == 1 ? __('admin.store_management.promoters.active') : __('admin.store_management.promoters.inactive') }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-200">
                            <a href="{{ route('admin.promoters.edit', $promoter->id) }}"
                               class="text-blue-500">{{ __('admin.store_management.promoters.edit') }}</a>

                            |
                            @if ($promoter->status == 0)
                                <a href="{{ route('admin.promoters.deactivate', $promoter->id) }}"
                                   class="text-red-500">{{ __('admin.store_management.promoters.deactivate') }}</a>
                            @else
                                <a href="{{ route('admin.promoters.activate', $promoter->id) }}"
                                   class="text-green-500">{{ __('admin.store_management.promoters.activate') }}</a>
                            @endif

                            |

                            <a href="{{ route('admin.promoters.show', $promoter->id) }}"
                               class="text-blue-500">{{ __('admin.store_management.promoters.show') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr class="border-b border-gray-200">
                        <td colspan="6" class="py-4 text-center text-gray-500">
                            {{ __('admin.store_management.promoters.empty') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-admin-layout>
