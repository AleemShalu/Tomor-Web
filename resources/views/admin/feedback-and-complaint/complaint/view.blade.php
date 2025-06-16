<x-app-admin-layout>

    <div class="container mx-auto mt-10 px-4" dir="ltr">
        <div class="bg-white rounded shadow-md">
            <div class="bg-gray-200 p-4 border-b border-gray-300 flex items-center justify-between mb-5">
                <h3 class="text-lg font-semibold">Complaint Details</h3>
                <button id="printButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Print
                </button>
            </div>
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
            <div class="p-4">
                <a href="{{route('admin.complaint.index')}}"
                   class="bg-black text-white px-4 py-2 rounded">
                    Complaints
                </a>
            </div>
        </div>
    </div>

</x-app-admin-layout>
<script>
    document.getElementById('printButton').addEventListener('click', function () {
        window.print();
    });
</script>
