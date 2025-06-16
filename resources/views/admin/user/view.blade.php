<x-app-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800">{{__('admin.user_management.users.user_details')}}</h2>
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full">
                </div>

                <div class="my-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-600">{{__('admin.user_management.users.personal_info')}}</h3>
                        <div class="mt-4 space-y-2">
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_id')}}:</span>
                                <span class="ml-2">{{ $user->id }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_name')}}:</span>
                                <span class="ml-2">{{ $user->name }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_email')}}:</span>
                                <span class="ml-2">{{ $user->email }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_Dial_code')}}:</span>
                                <span class="ml-2">
                                    @if (empty($user->dial_code) || empty($user->contact_no))
                                        N/A
                                    @else
                                        {{ '+' . $user->dial_code . ' ' . $user->contact_no }}
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_contact_no')}}:</span>
                                <span class="ml-2">
                                    @if (empty($user->contact_no))
                                        N/A
                                    @else
                                        {{ $user->contact_no }}
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_status')}}:</span>
                                <span class="ml-2">{{ $user->status ? 'Active' : 'Inactive' }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_user_type')}}:</span>
                                <span class="ml-2">{{ $user->roles[0]->name }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_created_at')}}:</span>
                                <span class="ml-2">{{ $user->created_at }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_updated_at')}}:</span>
                                <span class="ml-2">{{ $user->updated_at }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.user_last_seen')}}:</span>
                                <span class="ml-2">{{ $user->last_seen }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($user->roles[0]->name === 'customer' && $user->customer_with_special_needs)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Customer Details</h3>
                            <div class="mt-4 space-y-2">
                                <div>
                                    <span class="font-semibold text-gray-600">Special Needs Status:</span>
                                    <span class="ml-2">{{ $user->customer_with_special_needs ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">Special Needs Description:</span>
                                    <span class="ml-2">{{ $user->customer->special_needs_description ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($user->roles[0]->name === 'owner')
                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">{{__('admin.user_management.users.owner_stores')}}</h3>
                            <div class="mt-4 space-y-4">
                                {{-- logo, stor-Header, Status, view store, business_type_id--}}
                                @foreach ($user->owner_stores as $store)
                                    <div class="border rounded-lg p-4 flex gap-7">
                                        <img class="w-fit" src="{{ $store->logo_url }}" alt="Store Header">
                                        <div>
                                            <div>
                                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.store_name')}}:</span>
                                                <span class="ml-2">{{ $store->commercial_name_en }}</span>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.store_description')}}:</span>
                                                <span class="ml-2">{{ $store->description }}</span>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.store_email')}}:</span>
                                                <span class="ml-2">{{ $store->email }}</span>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">{{__('admin.user_management.users.store_website')}}:</span>
                                                <span class="ml-2">{{ $store->website }}</span>
                                            </div>
                                        </div>

                                        <a class="text-white p-3 bg-blue-500 rounded-md place-self-center"
                                           href="{{ route('admin.store.show', ['id' => $store->id]) }}">{{__('admin.user_management.users.preview')}}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
                {{--                <a class="text-white p-3 bg-blue-500 rounded-md"--}}
                {{--                   href="{{ route('admin.users.print', ['id' => $user->id]) }}">{{ __('admin.user_management.users.print') }}</a>--}}
                <a class="text-white p-3 bg-blue-500 rounded-md"
                   href="{{ route('admin.users.edit', ['id' => $user->id]) }}">{{ __('admin.user_management.users.edit') }}</a>

            </div>
        </div>
    </div>
</x-app-admin-layout>
