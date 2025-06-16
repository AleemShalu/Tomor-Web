@props(['routeName', 'iconColor', 'isActive', 'icon', 'translationKey'])

@php
    $bgColor = $isActive ? 'bg-blue-color-1-light' : '';
@endphp

<li class="px-3 py-2 mt-2 rounded-lg mb-0.5 last:mb-0 {{ $bgColor }}">
    <a class="block text-slate-200 hover:text-white truncate transition duration-150"
       href="{{ route($routeName) }}">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i data-lucide="{{ $icon }}" style="color: {{ $iconColor }}" class="mx-2"></i>
                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                    {{ __($translationKey) }}
                </span>
            </div>
        </div>
    </a>
</li>
