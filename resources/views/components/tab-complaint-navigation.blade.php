<div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        @foreach ($tabs as $tab)
            <li class="mr-2">
                <a href="{{ $tab['route'] }}"
                   class="inline-flex items-center justify-center p-4 {{ $currentRoute === $tab['routeName'] ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }} border-b-2 rounded-t-lg group"
                   aria-current="{{ $currentRoute === $tab['routeName'] ? 'page' : '' }}">
                    <svg class="w-4 h-4 mr-2 {{ $currentRoute === $tab['routeName'] ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}"
                         aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                         viewBox="0 0 20 20">
                        <path d="{{ $tab['iconPath'] }}"/>
                    </svg>
                    {{ __($tab['label']) }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
