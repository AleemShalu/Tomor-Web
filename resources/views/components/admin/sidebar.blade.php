<div>
    <!-- Sidebar backdrop (mobile only) -->
    <div
            class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
            :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
            aria-hidden="true"
            x-cloak
    ></div>

    <!-- Sidebar -->
    <div
            id="sidebar"
            class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-blue-color-1-darker p-4 transition-all duration-200 ease-in-out"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
            @click.outside="sidebarOpen = false"
            @keydown.escape.window="sidebarOpen = false"
            x-cloak="lg"
    >

        <!-- Sidebar header -->
        <div class=" justify-center items-center mb-10 pr-3 sm:px-2" style="    text-align: -webkit-center;">
            <!-- Close button -->
            <button class="lg:hidden text-slate-500 hover:text-slate-400 mr-auto"
                    @click.stop="sidebarOpen = !sidebarOpen"
                    aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z"/>
                </svg>
            </button>
            <!-- Logo -->
            <a class="block" href="{{ route('admin.dashboard') }}">
                <img src="{{asset('images/tomor-logo5.png')}}"
                     :class="{ 'w-20': sidebarOpen, 'w-24': !sidebarOpen }"
                     style="border-radius:3px; width: 50%;">
            </a>
        </div>
        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
                        <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                              aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Pages</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(Route::currentRouteName() === 'admin.dashboard'){{ 'bg-blue-color-1-light' }}@endif"
                        x-data="{ open: {{ Route::currentRouteName() === 'admin.dashboard' ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['dashboard'])){{ 'hover:text-slate-200' }}@endif"
                           href="{{ route('admin.dashboard') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if(Route::currentRouteName() === 'admin.dashboard')
                                        <i data-lucide="layout-dashboard" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="layout-dashboard" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        {{ __('admin.sidebar.dashboard') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- User Management -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(Route::currentRouteName() === 'admin.users' || Route::currentRouteName() === 'admin.users.register' || Route::currentRouteName() === 'admin.users.special-needs' || Route::currentRouteName() === 'admin.usher'){{ 'bg-blue-color-1-light' }}@endif"
                        x-data="{ open: {{ in_array(Route::currentRouteName(), ['admin.users', 'admin.users.register']) ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['branch.manage'])){{ 'hover:text-slate-200' }}@endif"
                           href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if(Route::currentRouteName() === 'admin.users' || Route::currentRouteName() === 'admin.users.register' || Route::currentRouteName() === 'admin.users.special-needs' || Route::currentRouteName() === 'admin.usher')
                                        <i data-lucide="user" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="user" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        {{ __('admin.sidebar.user_management') }}
                                    </span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['admin.users'])){{ 'rotate-180' }}@endif"
                                         :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(Route::currentRouteName() !== 'admin.users'){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('admin.users')){{ '!text-indigo-500' }}@endif"
                                       href="{{route('admin.users')}}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.sidebar.list_users') }}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="pl-9 mt-1 @if(Route::currentRouteName() !== 'admin.users.special-needs'){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('admin.users.special-needs')){{ '!text-indigo-500' }}@endif"
                                       href="{{route('admin.users.special-needs')}}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.sidebar.special_needs') }}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            {{-- <ul class="pl-9 mt-1 @if(Route::currentRouteName() !== 'admin.users.register'){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('admin.users.register')){{ '!text-indigo-500' }}@endif"
                                       href="{{route('admin.users.register')}}">
                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                        {{ __('admin.sidebar.register_new_user') }}
                    </span>
                                    </a>
                                </li>
                            </ul> --}}
                            <ul class="pl-9 mt-1 @if(Route::currentRouteName() !== 'admin.usher'){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('admin.usher')){{ '!text-indigo-500' }}@endif"
                                       href="{{route('admin.usher')}}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.user_management.usher_list.usher') }}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Store Management -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(Route::currentRouteName() === 'admin.store.index' || Route::currentRouteName() === 'admin.store.create'){{ 'bg-blue-color-1' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['finance']) ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['finance'])){{ 'hover:text-slate-200' }}@endif"
                           href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if(Route::currentRouteName() === 'admin.store.index' || Route::currentRouteName() === 'admin.store.create')
                                        <i data-lucide="store" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="store" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ __('admin.sidebar.store_management') }}</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['finance'])){{ 'rotate-180' }}@endif"
                                         :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['finance'])){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('admin.store.index')){{ '!text-indigo-500' }}@endif"
                                       href="{{route('admin.store.index')}}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.sidebar.list_stores') }}
                                        </span>
                                    </a>
                                </li>
                                {{--                                <li class="mb-1 last:mb-0">--}}
                                {{--                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('admin.store.create')){{ '!text-indigo-500' }}@endif"--}}
                                {{--                                       href="{{route('admin.store.create')}}">--}}
                                {{--                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">--}}
                                {{--                                            {{ __('admin.sidebar.add_new_store') }}--}}
                                {{--                                        </span>--}}
                                {{--                                    </a>--}}
                                {{--                                </li>--}}
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('admin.promoter.index')){{ '!text-indigo-500' }}@endif"
                                       href="{{route('admin.promoters.index')}}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.sidebar.promoters') }}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Order Management -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(Route::currentRouteName() === 'admin.order.index'){{ 'bg-blue-color-1-light' }}@endif">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['admin.order.index'])){{ 'hover:text-slate-200' }}@endif"
                           href="{{ route('admin.order.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center ">
                                    @if(Route::currentRouteName() === 'admin.order.index')
                                        <i data-lucide="list-ordered" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="list-ordered" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                    {{ __('admin.sidebar.order_management') }}
                </span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Financial Management -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(Route::currentRouteName() === 'admin.financial.index'){{ 'bg-blue-color-1-light' }}@endif">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['dashboard'])){{ 'hover:text-slate-200' }}@endif"
                           href="{{ route('admin.financial.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center ">
                                    @if(Route::currentRouteName() === 'admin.financial.index')
                                        <i data-lucide="landmark" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="landmark" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        {{ __('admin.sidebar.financial_management') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>


                    <!-- API -->
                    <li class="hidden px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['finance'])){{ 'bg-blue-color-1-light' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['finance']) ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['finance'])){{ 'hover:text-slate-200' }}@endif"
                           href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path class="fill-current @if(in_array(Request::segment(1), ['finance'])){{ 'text-indigo-300' }}@else{{ 'text-slate-400' }}@endif"
                                              d="M13 6.068a6.035 6.035 0 0 1 4.932 4.933H24c-.486-5.846-5.154-10.515-11-11v6.067Z"/>
                                        <path class="fill-current @if(in_array(Request::segment(1), ['finance'])){{ 'text-indigo-500' }}@else{{ 'text-slate-700' }}@endif"
                                              d="M18.007 13c-.474 2.833-2.919 5-5.864 5a5.888 5.888 0 0 1-3.694-1.304L4 20.731C6.131 22.752 8.992 24 12.143 24c6.232 0 11.35-4.851 11.857-11h-5.993Z"/>
                                        <path class="fill-current @if(in_array(Request::segment(1), ['finance'])){{ 'text-indigo-600' }}@else{{ 'text-slate-600' }}@endif"
                                              d="M6.939 15.007A5.861 5.861 0 0 1 6 11.829c0-2.937 2.167-5.376 5-5.85V0C4.85.507 0 5.614 0 11.83c0 2.695.922 5.174 2.456 7.17l4.483-3.993Z"/>
                                    </svg>

                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">API Management</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['finance'])){{ 'rotate-180' }}@endif"
                                         :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['finance'])){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('billing')){{ '!text-indigo-500' }}@endif"
                                       href="#0">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Invoices</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('transactions')){{ '!text-indigo-500' }}@endif"
                                       href="#0">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Reports</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('transaction-details')){{ '!text-indigo-500' }}@endif"
                                       href="#0">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Orders</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <!-- Invoice -->
                    <li class="hidden px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['finance'])){{ 'bg-blue-color-1-light' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['finance']) ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['finance'])){{ 'hover:text-slate-200' }}@endif"
                           href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path class="fill-current @if(in_array(Request::segment(1), ['finance'])){{ 'text-indigo-300' }}@else{{ 'text-slate-400' }}@endif"
                                              d="M13 6.068a6.035 6.035 0 0 1 4.932 4.933H24c-.486-5.846-5.154-10.515-11-11v6.067Z"/>
                                        <path class="fill-current @if(in_array(Request::segment(1), ['finance'])){{ 'text-indigo-500' }}@else{{ 'text-slate-700' }}@endif"
                                              d="M18.007 13c-.474 2.833-2.919 5-5.864 5a5.888 5.888 0 0 1-3.694-1.304L4 20.731C6.131 22.752 8.992 24 12.143 24c6.232 0 11.35-4.851 11.857-11h-5.993Z"/>
                                        <path class="fill-current @if(in_array(Request::segment(1), ['finance'])){{ 'text-indigo-600' }}@else{{ 'text-slate-600' }}@endif"
                                              d="M6.939 15.007A5.861 5.861 0 0 1 6 11.829c0-2.937 2.167-5.376 5-5.85V0C4.85.507 0 5.614 0 11.83c0 2.695.922 5.174 2.456 7.17l4.483-3.993Z"/>
                                    </svg>

                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Invoice Management</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['finance'])){{ 'rotate-180' }}@endif"
                                         :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['finance'])){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('billing')){{ '!text-indigo-500' }}@endif"
                                       href="#0">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Billing & Invoices</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('transactions')){{ '!text-indigo-500' }}@endif"
                                       href="#0">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Reports</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('transaction-details')){{ '!text-indigo-500' }}@endif"
                                       href="#0">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Orders</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <!-- Notifications -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0  @if(Route::currentRouteName() === 'admin.notifications.index'){{ 'bg-blue-color-1-light' }}@endif">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['finance'])){{ 'hover:text-slate-200' }}@endif"
                           href="{{ route('admin.notifications.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="grow flex items-center">
                                    @if(Route::currentRouteName() === 'admin.notifications.index')
                                        <i data-lucide="bell-ring" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="bell-ring" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        {{ __('admin.sidebar.notifications') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>


                    <!-- Notifications Dhamaen -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0  @if(Route::currentRouteName() === 'admin.notifications.dhamen.index'){{ 'bg-blue-color-1-light' }}@endif">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['finance'])){{ 'hover:text-slate-200' }}@endif"
                           href="{{ route('admin.notifications.dhamen.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="grow flex items-center">
                                    @if(Route::currentRouteName() === 'admin.notifications.dhamen.index')
                                        <i data-lucide="bell-ring" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="bell-ring" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        {{ __('admin.sidebar.notifications-dhamen') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Support -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(Route::currentRouteName() === 'admin.feedback-and-complaint.index'){{ 'bg-blue-color-1-light' }}@endif"
                        x-data="{ open: {{ Route::currentRouteName() === 'admin.feedback-and-complaint.index' ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['utility'])){{ 'hover:text-slate-200' }}@endif"
                           href="{{ route('admin.feedback-and-complaint.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if(Route::currentRouteName() === 'admin.feedback-and-complaint.index')
                                        <i class="fa-solid fa-list fa-lg" style="color: #c8ff0d;"></i>
                                    @else
                                        <i class="fa-solid fa-list fa-lg" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                    {{ __('admin.sidebar.support') }}
                </span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(Request::segment(1) === 'admin' && in_array(Request::segment(2), ['settings'])) {{ 'bg-blue-color-1-light' }} @endif"
                        x-data="{ open: {{ (Request::segment(1) === 'admin' && in_array(Request::segment(2), ['settings'])) ? 'true' : 'false' }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['settings'])){{ 'hover:text-slate-200' }}@endif"
                           href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if(in_array(Route::currentRouteName(), ['admin.settings', 'admin.settings.account', 'admin.settings.platform','admin.settings.store']))
                                        <i data-lucide="settings" style="color: #c8ff0d;"></i>
                                    @else
                                        <i data-lucide="settings" style="color: #94a3b8;"></i>
                                    @endif
                                    <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ __('admin.sidebar.settings') }} </span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['settings'])){{ 'rotate-180' }}@endif"
                                         :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['settings'])){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('account')){{ '!text-indigo-500' }}@endif"
                                       href="{{ route('admin.settings.account') }}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.sidebar.account_settings') }}
                                        </span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('notifications')){{ '!text-indigo-500' }}@endif"
                                       href="{{ route('admin.settings.platform') }}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.sidebar.platform_settings') }}
                                        </span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('notifications')){{ '!text-indigo-500' }}@endif"
                                       href="{{ route('admin.settings.store') }}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            {{ __('admin.sidebar.store_settings') }}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="px-3 py-2">
                <button @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
                        <path class="text-slate-400"
                              d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z"/>
                        <path class="text-slate-600" d="M3 23H1V1h2z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
