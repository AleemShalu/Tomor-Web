<x-app-admin-layout>

    <div class="max-w-lg mx-auto p-8 bg-white rounded shadow-md mt-16 mb-10">
        <a href="{{route('admin.notifications.index')}}"
           class="text-blue-500 hover:text-blue-700 transition duration-300 flex items-center mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{__('admin.common.back')}}
        </a>
        <h1 class="text-2xl font-semibold mb-4">
            {{__('admin.notification_management.create.title')}}
        </h1>
        <form id="notificationForm" action="{{ route('send-notification-to-users') }}" method="post">
            @csrf
            <label for="notificationType" class="block mb-2 font-bold">
                {{__('admin.notification_management.create.notification_type')}}

            </label>
            <select name="notificationType" id="notificationType" required class="border p-2 rounded w-full mb-4">
                <option value="offer">
                    {{__('admin.notification_management.create.offer')}}
                </option>
                <option value="discount">
                    {{__('admin.notification_management.create.discount')}}
                </option>
                <option value="update">
                    {{__('admin.notification_management.create.update')}}
                </option>
                <!-- يمكنك إضافة المزيد من الخيارات هنا -->
            </select>

            <label for="notificationPlatform" class="block mb-2 font-bold">
                {{__('admin.notification_management.create.notification_platform')}}

            </label>
            <div class="mb-4">
                <label class="block mb-1">
                    <input type="radio" name="notificationPlatform" value="web" required>
                    {{__('admin.notification_management.create.web')}}
                </label>
                <label class="block mb-1">
                    <input type="radio" name="notificationPlatform" value="mobile" required>
                    {{__('admin.notification_management.create.mobile')}}
                </label>
                <label class="block mb-1">
                    <input type="radio" name="notificationPlatform" value="email" required>
                    {{__('admin.notification_management.create.email')}}
                </label>
                <label class="block mb-1">
                    <input type="radio" name="notificationPlatform" value="whatsapp" required>
                    {{__('admin.notification_management.create.whatsapp')}}
                </label>
                <!-- يمكنك إضافة المزيد من الخيارات هنا -->
            </div>


            <label class="block mb-2 font-bold">
                {{__('admin.notification_management.create.target_audience')}}
            </label>
            <label class="block mb-1"><input type="radio" name="targetAudience" value="all" required>
                {{__('admin.notification_management.create.all_users')}}

            </label>
            <label class="block mb-1"><input type="radio" name="targetAudience" value="customers" required>
                {{__('admin.notification_management.create.customers')}}
            </label>
            <label class="block mb-4"><input type="radio" name="targetAudience" value="owners" required>
                {{__('admin.notification_management.create.owners')}}
            </label>

            <label for="notificationTitleAr" class="block mb-2 font-bold">

                {{__('admin.notification_management.create.title_ar')}}

            </label>
            <input type="text" name="notificationTitleAr" class="border p-2 rounded w-full mb-4" required>
            <label for="notificationMessageAr" class="block mb-2 font-bold">

                {{__('admin.notification_management.create.message_ar')}}

            </label>
            <textarea name="notificationMessageAr" rows="4" cols="50" class="border p-2 rounded w-full mb-4"
                      required></textarea>

            <label for="notificationTitleEn" class="block mb-2 font-bold">
                {{__('admin.notification_management.create.title_en')}}


            </label>
            <input type="text" name="notificationTitleEn" class="border p-2 rounded w-full mb-4" required>
            <label for="notificationMessageEn" class="block mb-2 font-bold">
                {{__('admin.notification_management.create.message_en')}}

            </label>
            <textarea name="notificationMessageEn" rows="4" cols="50" class="border p-2 rounded w-full mb-4"
                      required></textarea>
            <br>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{__('admin.notification_management.create.send_notification')}}
            </button>
        </form>
    </div>

</x-app-admin-layout>
<!-- Include SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<script>
    document.getElementById('notificationForm').addEventListener('submit', function (event) {
        event.preventDefault();

        Swal.fire({
            title: 'Sending notification',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false, // Hide the "OK" button
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(this.action, {
            method: this.method,
            body: new FormData(this),
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Notification sent successfully',
                        icon: 'success',
                    });
                    // You may also redirect or perform other actions after success
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while sending the notification',
                        icon: 'error',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);

                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while sending the notification',
                    icon: 'error',
                });
            })
            .finally(() => {
                // After completion (success or error), close the SweetAlert2 modal
                Swal.close();
            });
    });
</script>