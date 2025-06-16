<header class="sticky top-0 bg-header-color-1 border-b border-slate-200 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 -mb-px">

            <!-- Header: Left side -->
            <div class="flex">

                <!-- Hamburger button -->
                <button
                    class="text-slate-500 hover:text-slate-600 lg:hidden"
                    @click.stop="sidebarOpen = !sidebarOpen"
                    aria-controls="sidebar"
                    :aria-expanded="sidebarOpen"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="5" width="16" height="2"/>
                        <rect x="4" y="11" width="16" height="2"/>
                        <rect x="4" y="17" width="16" height="2"/>
                    </svg>
                </button>

                <form action="{{ route('setLanguage') }}" method="POST" class="flex items-center space-x-2 ">
                    @csrf
                    <label for="locale" class="hidden">Select Language</label>
                    <div class="relative">
                        <select name="locale" id="locale" onchange="this.form.submit()"
                                class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English (UK)</option>
                            <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية (السعودية)
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 8l4 4 4-4H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        @if(app()->getLocale() === 'ar')
                            <img src="{{asset('images/flags/sa.jpg')}}" alt="Saudi Arabia"
                                 class="w-6 h-4 ">
                        @elseif(app()->getLocale() === 'en')
                            <img src="{{asset('/images/flags/uk.png')}}" alt="United Kingdom"
                                 class="w-6 h-4 rounded-sm">
                        @endif
                    </div>
                </form>
            </div>

            <div>
                {{--                <input type="checkbox" x-model="darkMode" id="dark-mode-toggle">--}}
                {{--                <label for="dark-mode-toggle">Dark Mode</label>--}}
            </div>

            <!-- Header: Right side -->
            <div class="flex items-center space-x-3">

                <!-- Search Button with Modal -->
                {{--                <x-modal-search/>--}}

                <!-- Notifications button -->
                <x-dropdown-notifications align="right"/>

                <!-- Info button -->
                {{--                <x-dropdown-help align="right" />--}}

                <!-- Divider -->
                <hr class="w-px h-6 bg-slate-200"/>

                <!-- User button -->
                <x-dropdown-profile align="right"/>

            </div>

        </div>
    </div>
</header>
