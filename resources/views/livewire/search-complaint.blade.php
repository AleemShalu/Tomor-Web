<div>
    {{-- Do your work, then step back. --}}

    <div class="p-4">
        <!-- Add your search button and table here -->

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-100">
            <div class="pb-4 dark:bg-gray-900">
                <label for="table-search" class="sr-only">Search your complaint</label>

                <form >
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" id="default-search" class="block bg-white w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Ticket Number" required>
                        <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                    </div>
                </form>
            </div>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Ticket Id
                    </th>
                    <th scope="col" class="px-6 py-3">
                        report_subtype_id
                    </th>
                    <th scope="col" class="px-6 py-3">
                        email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        submission_time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        resolved_time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        -
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach( $complaints as $complaint)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$complaint->ticket_id}}
                    </th>
                    <td class="px-6 py-4">
                        {{$complaint->report_subtype_id}}
                    </td>
                    <td class="px-6 py-4">
                        {{$complaint->email}}
                    </td>
                    <td class="px-6 py-4">
                        {{$complaint->submission_time}}
                    </td>
                    <td class="px-6 py-4">
                        {{$complaint->status}}
                    </td>
                    <td class="px-6 py-4">
                        {{$complaint->resolved_time}}
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Read Replay</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

</div>
