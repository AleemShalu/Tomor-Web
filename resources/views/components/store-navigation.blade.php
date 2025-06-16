<div class="w-full lg:w-2/3 mx-auto bg-white rounded p-6 shadow-md">
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <a href="{{ route('store.manage', $store->id) }}"
                   class="{{ request()->routeIs('store.manage') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    @lang('navigation.store_details')
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('branch.manage', $store->id) }}"
                   class="{{ request()->routeIs('branch.manage') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    @lang('navigation.branches')
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('employee.manage', $store->id) }}"
                   class="{{ request()->routeIs('employee.manage') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    @lang('navigation.employees')
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('product.manage', $store->id) }}"
                   class="{{ request()->routeIs('product.manage') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    @lang('navigation.products')
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('settings.manage', $store->id) }}"
                   class="{{ request()->routeIs('settings.manage') ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    @lang('navigation.settings')
                </a>
            </li>
        </ul>
    </div>
</div>
