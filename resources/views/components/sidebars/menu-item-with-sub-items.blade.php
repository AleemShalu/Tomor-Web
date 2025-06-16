<!-- resources/views/components/SidebarMenuItemWithSubItems.blade.php -->

@props(['routeName', 'iconColor', 'hasSubItems','isActive', 'icon', 'translationKey'])

@php
    $bgColor = $isActive ? 'bg-blue-color-1-light' : '';
@endphp

<li class="px-3 py-2 mt-2 rounded-lg mb-0.5 last:mb-0  {{ $bgColor }}"
    x-data="{ open: {{ in_array(Route::currentRouteName(), [$routeName]) ? 1 : 0 }} }">
    <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['branch.manage'])){{ 'hover:text-slate-200' }}@endif"
       href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i data-lucide="{{ $icon }}" style="color: {{ $iconColor }}" class="mx-2"></i>
                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                    {{ __($translationKey) }}
                </span>
            </div>
            <!-- Icon -->
            <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['business'])){{ 'rotate-180' }}@endif"
                     :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                </svg>
            </div>
        </div>
    </a>
    <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
        <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['business'])){{ 'hidden' }}@endif"
            :class="open ? '!block' : 'hidden'">
            {{ $slot }}
        </ul>
    </div>
</li>
