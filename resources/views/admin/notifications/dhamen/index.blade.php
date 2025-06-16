<x-app-admin-layout>
    <div class="px-7 mx-auto mt-6">
        <div class="bg-white py-6 px-4 font-bold text-xl rounded mb-4">
            <div>
                <i class="fa-solid fa-comments px-4"></i>
                {{ __('admin.notification_management.title_dhamen') }}
            </div>
        </div>

        @if($notifications->isEmpty())
            <div class="bg-gray-100 p-4 rounded">
                {{ __('admin.notification_management.no_notifications') }}
            </div>
        @else
            <div class="bg-white p-4 border border-gray-200 rounded shadow overflow-x-auto">
                <!-- Print Button -->
                <button onclick="printTable()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
                    {{__('locale.common.print')}}
                </button>

                <table id="notifications-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-center font-bold text-xs text-gray-500 uppercase tracking-wider">
                            Batch ID
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Notification Type
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Order ID
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Supplier ID
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Details
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Transaction ID
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Transaction Time
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Type
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        @php
                            $data = json_decode($notification->data, true);
                            $batchId = $data['Header']['BatchId'] ?? 'N/A';
                            $notificationsList = $data['Notifications'] ?? [];
                        @endphp

                        @foreach($notificationsList as $notify)
                            @php
                                // Handle missing keys
                                $order = $notify['Order'] ?? [];
                                $supplier = $notify['Supplier'] ?? [];
                                $transaction = $notify['Transaction'] ?? [];
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $batchId }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $notify['NotificationType'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $order['OrderID'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $order['SupplierID'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <button type="button" class="text-blue-500 hover:text-blue-700"
                                            onclick="toggleDetails(this)">Show Details
                                    </button>
                                    <div class="hidden mt-2">
                                        <strong>Notification
                                            Time:</strong> {{ \Carbon\Carbon::parse($notify['NotificationTime'] ?? now())->format('Y-m-d H:i:s') }}
                                        <div class="mt-2">
                                            <strong>Order Details:</strong>
                                            <ul class="list-disc ml-4">
                                                <li>Order Amount: {{ $order['OrderAmount'] ?? 'N/A' }}</li>
                                                <li>Supplier ID: {{ $order['SupplierID'] ?? 'N/A' }}</li>
                                            </ul>
                                        </div>
                                        <div class="mt-2">
                                            <strong>Supplier Details:</strong>
                                            <ul class="list-disc ml-4">
                                                <li>Supplier Name: {{ $supplier['SupplierName'] ?? 'N/A' }}</li>
                                                <li>Supplier IBAN: {{ $supplier['SupplierIBAN'] ?? 'N/A' }}</li>
                                                <li>Supplier Email: {{ $supplier['SupplierEmail'] ?? 'N/A' }}</li>
                                                <li>Supplier Mobile: {{ $supplier['SupplierMobile'] ?? 'N/A' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $transaction['TransactionID'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $transaction['Amount'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $transaction['TransactionTime'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $transaction['TransactionStatus'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $transaction['TransactionType'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        function toggleDetails(button) {
            const details = button.nextElementSibling;
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                button.textContent = 'Hide Details';
            } else {
                details.classList.add('hidden');
                button.textContent = 'Show Details';
            }
        }

        function printTable() {
            const table = document.getElementById('notifications-table');
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Table</title>');
            // Include print-specific styles
            printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { padding: 8px; text-align: center; border: 1px solid #ddd; } thead { background-color: #f9fafb; } th { font-weight: bold; } </style>');
            printWindow.document.write('</head><body >');
            printWindow.document.write('<h1>Notification Table</h1>');
            printWindow.document.write(table.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
</x-app-admin-layout>
