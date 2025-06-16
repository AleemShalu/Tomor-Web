<ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
    <li class="mr-2">
        <a href="{{ route('branch', $branch->id) }}"
           class="{{ request()->routeIs('branch') ? 'inline-flex p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group' : 'inline-flex p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' }}">
            <svg aria-hidden="true"
                 class="w-5 h-5 mr-2 {{ request()->routeIs('branch') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}"
                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            {{ __('locale.branch_navigation.dashboard') }}
        </a>
    </li>
    <li class="mr-2">
        <a href="{{ route('branch.orders', $branch->id) }}"
           class="{{ request()->routeIs('branch.orders') ? 'inline-flex p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group' : 'inline-flex p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' }}">
            <svg aria-hidden="true"
                 class="w-5 h-5 mr-2 {{ request()->routeIs('branch.orders') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}"
                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                      clip-rule="evenodd"></path>
            </svg>
            {{ __('locale.branch_navigation.orders') }}
        </a>
    </li>
    <li class="mr-2">
        <a href="{{ route('branch.orders.live', $branch->id) }}"
           class="{{ request()->routeIs('branch.orders.live') ? 'inline-flex p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group' : 'inline-flex p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' }}">
            <svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
                 fill="currentColor"
                 class="w-5 h-5 mr-2 {{ request()->routeIs('branch.orders.live') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}">
                <path d="M5.98959236,4.92893219 C6.28248558,5.22182541 6.28248558,5.69669914 5.98959236,5.98959236 C2.67013588,9.30904884 2.67013588,14.6909512 5.98959236,18.0104076 C6.28248558,18.3033009 6.28248558,18.7781746 5.98959236,19.0710678 C5.69669914,19.363961 5.22182541,19.363961 4.92893219,19.0710678 C1.02368927,15.1658249 1.02368927,8.83417511 4.92893219,4.92893219 C5.22182541,4.63603897 5.69669914,4.63603897 5.98959236,4.92893219 Z M19.0710678,4.92893219 C22.9763107,8.83417511 22.9763107,15.1658249 19.0710678,19.0710678 C18.7781746,19.363961 18.3033009,19.363961 18.0104076,19.0710678 C17.7175144,18.7781746 17.7175144,18.3033009 18.0104076,18.0104076 C21.3298641,14.6909512 21.3298641,9.30904884 18.0104076,5.98959236 C17.7175144,5.69669914 17.7175144,5.22182541 18.0104076,4.92893219 C18.3033009,4.63603897 18.7781746,4.63603897 19.0710678,4.92893219 Z M8.81801948,7.75735931 C9.1109127,8.05025253 9.1109127,8.52512627 8.81801948,8.81801948 C7.06066017,10.5753788 7.06066017,13.4246212 8.81801948,15.1819805 C9.1109127,15.4748737 9.1109127,15.9497475 8.81801948,16.2426407 C8.52512627,16.5355339 8.05025253,16.5355339 7.75735931,16.2426407 C5.41421356,13.8994949 5.41421356,10.1005051 7.75735931,7.75735931 C8.05025253,7.46446609 8.52512627,7.46446609 8.81801948,7.75735931 Z M16.2426407,7.75735931 C18.5857864,10.1005051 18.5857864,13.8994949 16.2426407,16.2426407 C15.9497475,16.5355339 15.4748737,16.5355339 15.1819805,16.2426407 C14.8890873,15.9497475 14.8890873,15.4748737 15.1819805,15.1819805 C16.9393398,13.4246212 16.9393398,10.5753788 15.1819805,8.81801948 C14.8890873,8.52512627 14.8890873,8.05025253 15.1819805,7.75735931 C15.4748737,7.46446609 15.9497475,7.46446609 16.2426407,7.75735931 Z M12,10.5 C12.8284271,10.5 13.5,11.1715729 13.5,12 C13.5,12.8284271 12.8284271,13.5 12,13.5 C11.1715729,13.5 10.5,12.8284271 10.5,12 C10.5,11.1715729 11.1715729,10.5 12,10.5 Z"></path>
            </svg>
            {{ __('locale.branch_navigation.orders_live') }}
        </a>
    </li>

    <li class="mr-2">
        <a href="{{ route('branch.employees', $branch->id) }}"
           class="{{ request()->routeIs('branch.employees') ? 'inline-flex p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group' : 'inline-flex p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' }}">
            <svg aria-hidden="true"
                 class="w-5 h-5 mr-2 {{ request()->routeIs('branch.employees') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}"
                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                      clip-rule="evenodd"></path>
            </svg>
            {{ __('locale.branch_navigation.employees') }}
        </a>

    </li>
    <li class="mr-2">
        <a id="settings_link" href="#"
        {{-- <a href="{{ route('branch.settings', $branch->id) }}" --}}
           class="{{ request()->routeIs('branch.settings') ? 'inline-flex p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group' : 'inline-flex p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' }}">
            <svg aria-hidden="true"
                 class="w-5 h-5 mr-2 {{ request()->routeIs('branch.settings') ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}"
                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path>
            </svg>
            {{ __('locale.branch_navigation.settings') }}
        </a>
    </li>
</ul>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let clientTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.getElementById('settings_link').href = "/branch-settings/" + @json($branch->id) + "/?timezone=" + clientTimezone;
    });
</script>
