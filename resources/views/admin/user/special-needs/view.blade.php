<x-app-admin-layout>
    <div class="bg-white p-4 m-4 rounded-l-lg max-w-6xl mx-auto">
        <div class="px-4 sm:px-0">
            <h3 class="text-base font-semi-bold leading-7 text-gray-900">
                {{__('admin.user_management.special_needs.title_applicant')}}
            </h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                {{__('admin.user_management.special_needs.description')}}
            </p>
        </div>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">
                        {{__('admin.user_management.special_needs.special_needs_type')}}
                    </dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if(isset($user->customer_with_special_needs->special_needs_type))
                            {{ app()->getLocale() === 'ar' ? $user->customer_with_special_needs->special_needs_type->disability_type_ar : $user->customer_with_special_needs->special_needs_type->disability_type_en }}
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">
                        {{__('admin.user_management.special_needs.qualified_for_special_needs')}}
                    </dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if(isset($user->customer_with_special_needs->special_needs_qualified) && $user->customer_with_special_needs->special_needs_qualified)
                            Yes
                        @else
                            No
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">
                        {{__('admin.user_management.special_needs.sa_card_number')}}
                    </dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if(isset($user->customer_with_special_needs->special_needs_sa_card_number))
                            {{ $user->customer_with_special_needs->special_needs_sa_card_number }}
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">
                        {{__('admin.user_management.special_needs.description_special_needs')}}
                    </dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if(isset($user->customer_with_special_needs->special_needs_description))
                            {{ $user->customer_with_special_needs->special_needs_description }}
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">
                        {{__('admin.user_management.special_needs.status')}}
                    </dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if(isset($user->customer_with_special_needs->special_needs_status))
                            {{ $user->customer_with_special_needs->special_needs_status }}
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">
                        {{__('admin.user_management.special_needs.attachment')}}
                    </dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if(isset($user->customer_with_special_needs->special_needs_attachment))
                            {{__('admin.user_management.special_needs.na')}}
                        @else
                            {{__('admin.user_management.special_needs.na')}}
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
        <!-- Back and Print buttons -->
        <div class="flex justify-between px-4 py-4 border-t border-gray-100 bg-white">
            <div>
                <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-500">
                    {{__('admin.user_management.special_needs.back')}}

                </a>
            </div>
            <div>
                <button class="text-indigo-600 hover:text-indigo-500" onclick="window.print()">
                    {{__('admin.user_management.special_needs.print')}}

                </button>
            </div>
        </div>


    </div>
</x-app-admin-layout>
