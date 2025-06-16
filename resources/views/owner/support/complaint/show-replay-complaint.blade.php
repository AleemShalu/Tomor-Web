<x-guest-layout>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto">

            <!-- Complaint Details Card -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 bg-blue-800 text-white"> <!-- Updated class here -->
                    <h1 class="text-xl font-bold">Complaint Details</h1>
                </div>
                <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="font-bold text-gray-600 dark:text-gray-400">Ticket ID:</div>
                        <div class="text-gray-800 dark:text-white">{{ $complaint->ticket_id }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-600 dark:text-gray-400">Subject:</div>
                        <div class="text-gray-800 dark:text-white">{{ $complaint->report_title }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-600 dark:text-gray-400">Topic:</div>
                        <div class="text-gray-800 dark:text-white">{{ $complaint->report_subtype->ar_name }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-600 dark:text-gray-400">Submission Time:</div>
                        <div class="text-gray-800 dark:text-white">{{ $complaint->submission_time }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-600 dark:text-gray-400">Email:</div>
                        <div class="text-gray-800 dark:text-white">{{ $complaint->email }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-600 dark:text-gray-400">Full Name:</div>
                        <div class="text-gray-800 dark:text-white">{{ $complaint->first_name .' '.$complaint->last_name}}</div>
                    </div>
                    <!-- Body Message Section -->
                    <div class="col-span-2">
                        <div class="font-bold text-gray-600 dark:text-gray-400">Details:</div>
                        <div class="grid grid-cols-2">
                            <div class="col-span-2">
                                <textarea class="text-gray-800 dark:text-white bg-transparent w-full border-none resize-none focus:outline-none" rows="6" readonly>{{ $complaint->body_message }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Replay from the Support Team Card -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-blue-800 text-white"> <!-- Updated class here -->
                    <h1 class="text-xl font-semibold">Replay from the Support Team</h1>
                </div>
                <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="font-bold text-gray-600 dark:text-gray-400">Reply Time:</div>
                        <div class="text-gray-800 dark:text-white">{{ $complaint->resolved_time }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="font-bold text-gray-600 dark:text-gray-400">Details:</div>
                        <div class="grid grid-cols-2">
                            <div class="col-span-2">
                                <textarea class="text-gray-800 dark:text-white bg-transparent w-full border-none resize-none focus:outline-none" rows="6" readonly>{{ $complaint->body_reply }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Ticket Section -->
            <div class="py-4 text-black font-bold">
                Find the response was not enough? Raise a new <a class="text-blue-700" href="{{route('support.compliant')}}">ticket</a>
            </div>

        </div>
    </div>

</x-guest-layout>
