<x-guest-layout>
    <div class="grid items-center justify-center pt-5">
        <div class="p-4">
            <h1 class="text-3xl font-bold mb-4">{{ __('support_center') }}</h1>
            <p class="text-gray-800">{{ __('support_description') }}</p>
        </div>

        <div class="p-4">
            <!-- Add your search button and table here -->

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-100">
                <div class="pb-4 dark:bg-gray-900">
                    <label for="table-search" class="sr-only">{{ __('search_your_complaint') }}</label>
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                             role="alert">
                            <strong class="font-bold">{{ __('incorrect_ticket_number') }}</strong>
                            <br>
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path
                                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div>
                        <br>
                        @if(session('lockoutDuration'))
                            <div class='bg-yellow-200 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative'
                                 role='alert'>
                                {{ __('max_attempts_exceeded', ['lockoutDuration' => session('lockoutDuration')]) }}
                            </div>
                            <br>
                        @endif
                    @endif
                    <form method="post" action="{{ route('support.inquiries.search') }}">
                        @csrf
                        <label for="default-search"
                               class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">{{ __('search_ticket_number') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="search" id="ticket_number" name="ticket_number"
                                   class="block bg-white w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="{{ __('search_ticket_number') }}" required>
                            <button type="submit"
                                    class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('search') }}</button>
                        </div>
                    </form>
                </div>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">
                            {{ __('ticket_id') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            {{ __('type_complaint') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            {{ __('email') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            {{ __('submission_time') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            {{ __('status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            {{ __('resolved_time') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            {{ __('action') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($complaint))
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$complaint->ticket_id}}
                            </th>
                            <td class="px-6 py-4 text-center">
                                {{$complaint->report_subtype->en_name}}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{$complaint->email}}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{$complaint->submission_time}}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{$complaint->status}}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(empty($complaint->resolved_time))
                                    -
                                @else
                                    {{$complaint->resolved_time}}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($complaint->is_resolved == 0)
                                    -
                                @else
                                    <a href="{{ route('support.compliant.show', ['uuid' => $complaint->uuid]) }}"
                                       class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('read_reply') }}</a>
                                @endif
                            </td>
                        </tr>
                    @else
                        <td class="px-6 py-4 text-center" colspan="7">
                            <p>{{ __('no_complaints_found') }}</p>
                        </td>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-guest-layout>
