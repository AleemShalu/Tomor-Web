@php
    $navItems = [
        ['route' => 'store.manage', 'icon' => 'store', 'text' => 'store_information'],
        ['route' => 'branch.manage', 'icon' => 'git-branch', 'text' => 'branches'],
        ['route' => 'employee.manage', 'icon' => 'users', 'text' => 'employees'],
        ['route' => 'product.manage', 'icon' => 'package', 'text' => 'products'],
        ['route' => 'settings.manage', 'icon' => 'settings', 'text' => 'settings'],
    ];
@endphp

<div class="bg-white border border-gray-200">

    <div class="flex p-3">
        <div class="bg-blue-color-1-lighter rounded" style="width: 55px">
            <i data-lucide="store" width="30" height="30" class="mx-auto mt-3"></i>
        </div>

        <div class="ml-2">
            <div class="font-bold text-xl">
                @if(app()->getLocale() == 'ar')
                    {{$store->short_name_ar}}
                @else
                    {{$store->short_name_en}}
                @endif
            </div>
            <div>

                <div>
                    <div class="text-gray-500">
                        <a href="/" class="hover:underline">@if(app()->getLocale() == 'ar')
                                الصفحة الرئيسية
                            @else
                                Home
                            @endif</a> >
                        <a href="/stores" class="hover:underline">@if(app()->getLocale() == 'ar')
                                المتاجر
                            @else
                                Stores
                            @endif</a> >
                        @if(app()->getLocale() == 'ar')
                            {{$store->short_name_ar}}
                        @else
                            {{$store->short_name_en}}
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Navbar -->
    <div class="bg-blue-color-1-lighter border border-gray-200">
        <div name="navbar" class="flex">
            @foreach ($navItems as $item)
                <div
                        class="text-blue-color-1 {{ request()->routeIs($item['route']) ? 'hover:font-bold border border-l-base-blue rounded font-bold bg-blue-color-1-soft' : 'hover:font-bold font-light' }}">
                    <a href="{{ route($item['route'], $store->id) }}" class="mx-4 flex p-3">
                        <i data-lucide="{{ $item['icon'] }}" width="25" height="25"></i>
                        <span class="ml-2 mr-2">{{__('locale.navigation.'.$item['text'])}}</span>
                    </a>
                </div>
                <div
                        class="bg-blue-color-1 h-6 {{ request()->routeIs($item['route']) ? 'bg-blue-color-1-soft' : '' }}"></div>
            @endforeach
        </div>
    </div>


    <div class="p-4 border border-gray-200">
        {{$main}}
    </div>

</div>
