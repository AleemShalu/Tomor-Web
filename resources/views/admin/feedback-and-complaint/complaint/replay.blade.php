<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<x-app-admin-layout>

    <div class="container mx-auto mt-10 px-6" dir="ltr">


        <div id="accordion-open" data-accordion="open" class="py-4">
            <h2 id="accordion-open-heading-1">
                <button type="button"
                        class="bg-white flex items-center justify-between w-full p-5 font-medium text-left text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"
                        data-accordion-target="#accordion-open-body-1" aria-expanded="true"
                        aria-controls="accordion-open-body-1">
            <span class="flex items-center">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                          clip-rule="evenodd"></path>
                </svg>
                Complaint Details
            </span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
            </h2>
            <div id="accordion-open-body-1" class="bg-white rounded shadow-md">
                <div class="p-4">
                    <table class="min-w-full">
                        <tr class="border-b">
                            <th class="py-2 text-left">Ticket ID:</th>
                            <td class="py-2 pl-4">{{ $report->ticket_id }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">First Name:</th>
                            <td class="py-2 pl-4">{{ $report->first_name }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Last Name:</th>
                            <td class="py-2 pl-4">{{ $report->last_name }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Email:</th>
                            <td class="py-2 pl-4">{{ $report->email }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Submission Time:</th>
                            <td class="py-2 pl-4">{{ $report->submission_time }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Status:</th>
                            <td class="py-2 pl-4">{{ $report->status }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Report Title:</th>
                            <td class="py-2 pl-4">{{ $report->report_title }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Body Message:</th>
                            <td class="py-2 pl-4">{{ $report->body_message }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Body Reply:</th>
                            <td class="py-2 pl-4">{{ $report->body_reply }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Resolved Time:</th>
                            <td class="py-2 pl-4">{{ $report->resolved_time }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Is Resolved:</th>
                            <td class="py-2 pl-4">{{ $report->is_resolved ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 text-left">Attachments:</th>
                            <td class="py-2 pl-4">
                                @if(isset($report->attachments))
                                    {{ $report->attachments }}
                                @else
                                    No Attachments
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="border-b pb-4 mb-4">
                <h3 class="text-xl font-semibold">Reply to Complaint</h3>
            </div>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                     role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.complaint.update', ['id' => $report->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="body_reply" class="block text-sm font-medium text-gray-600 mb-2">Reply:</label>
                    <textarea class="form-input mt-1 block w-full p-2 rounded-md border-gray-300"
                              id="body_reply"
                              name="body_reply"
                              rows="5">{{ $report->body_reply }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="is_resolved" class="block text-sm font-medium text-gray-600 mb-2">Is
                        Resolved:</label>
                    <select class="form-input mt-1 block w-full p-2 rounded-md border-gray-300"
                            id="is_resolved"
                            name="is_resolved">
                        <option value="1" @if($report->is_resolved) selected @endif>Yes</option>
                        <option value="0" @if(!$report->is_resolved) selected @endif>No</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status:</label>
                    <select class="form-input mt-1 block w-full p-2 rounded-md border-gray-300"
                            id="status"
                            name="status">
                        <option value="Pending Review" @if($report->status === 'Pending Review') selected @endif>
                            Pending
                            Review
                        </option>
                        <option value="In Progress" @if($report->status === 'In Progress') selected @endif>In
                            Progress
                        </option>
                        <option value="Resolved" @if($report->status === 'Resolved') selected @endif>Resolved
                        </option>
                    </select>
                </div>

                <div>
                    <button type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 focus:outline-none focus:bg-blue-700">
                        Update Complaint
                    </button>
                    <a href="{{route('admin.complaint.index')}}"
                       class="bg-black text-white px-4 py-2 rounded">
                        Complaints
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-app-admin-layout>
