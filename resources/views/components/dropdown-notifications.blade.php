<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .hidden {
            display: none;
        }

        .block {
            display: block;
        }

        .transition-enter {
            transition: opacity 0.2s ease-out, transform 0.2s ease-out;
        }

        .transition-enter-start {
            opacity: 0;
            transform: translateY(-0.5rem);
        }

        .transition-enter-end {
            opacity: 1;
            transform: translateY(0);
        }

        .transition-leave {
            transition: opacity 0.2s ease-out;
        }

        .transition-leave-start {
            opacity: 1;
        }

        .transition-leave-end {
            opacity: 0;
        }
    </style>
</head>
<body>
<div class="relative inline-flex">
    <button id="notification-button"
            class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 transition duration-150 rounded-full"
            aria-haspopup="true" aria-expanded="false">
        <!-- PHP logic for notifications -->
        @php
            $userId = auth()->user()->id;
            $users = App\Models\User::with('notifications')->get();
            $notifications = $users->flatMap(function ($user) use ($userId) {
                return $user->notifications->where('data.recipient_id', $userId);
            })->values();
            $notificationsCount = $notifications->where('read_at', null)->count();
            $filteredNotifications = $notifications->where('data.recipient_id', $userId);
        @endphp

        <span class="sr-only">Notifications</span>
        <svg class="w-4 h-4" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="fill-current text-slate-500"
                  d="M6.5 0C2.91 0 0 2.462 0 5.5c0 1.075.37 2.074 1 2.922V12l2.699-1.542A7.454 7.454 0 006.5 11c3.59 0 6.5-2.462 6.5-5.5S10.09 0 6.5 0z"/>
            <path class="fill-current text-slate-400"
                  d="M16 9.5c0-.987-.429-1.897-1.147-2.639C14.124 10.348 10.66 13 6.5 13c-.103 0-.202-.018-.305-.021C7.231 13.617 8.556 14 10 14c.449 0 .886-.04 1.307-.11L15 16v-4h-.012C15.627 11.285 16 10.425 16 9.5z"/>
        </svg>

        @if($notificationsCount === 0)
            <!-- No notifications -->
        @elseif($notificationsCount != 0)
            <span class="absolute top-0 right-0 flex w-2.5 h-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full w-2.5 h-2.5 bg-sky-500"></span>
                </span>
        @endif
    </button>
    <div id="notification-dropdown"
         class="origin-top-right z-10 absolute top-full -mr-48 min-w-80 bg-white border border-slate-200 py-1.5 rounded shadow-lg overflow-hidden mt-1 right-0 hidden transition-enter transition-leave">
        <div class="text-xs font-semibold text-slate-400 uppercase pt-1.5 pb-2 px-4">Notifications</div>
        <ul>
            @if ($filteredNotifications->isEmpty())
                <li class="block py-2 px-4 text-sm text-slate-500">No notifications</li>
            @else
                @foreach ($filteredNotifications as $notification)
                    @if (!$notification->read_at && in_array('web', explode(', ', $notification->data['channel'])))
                        <li class="border-slate-200 last:border-0">
                            <a class="block py-2 px-4 hover:bg-slate-50" href="#0">
                                <span class="block text-sm mb-2">📣 <span
                                            class="font-medium text-slate-800">{{ $notification->data['title'] ?? 'Notification Title' }}</span><br>{{ $notification->data['message'] ?? 'Notification Message' }}</span>
                                <span class="block text-xs font-medium text-slate-400">{{ $notification->created_at }}</span>
                            </a>
                        </li>
                        <li class="border-b border-slate-200 last:border-0">
                            <form action="{{ route('notifications.mark-as-read', ['notificationId' => $notification->id]) }}"
                                  method="POST">
                                @method('PUT')
                                @csrf
                                <button type="submit" class="block py-2 px-4 hover:bg-slate-50">
                                    Mark as Read
                                </button>
                            </form>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('notification-button');
        const dropdown = document.getElementById('notification-dropdown');

        button.addEventListener('click', function (event) {
            event.preventDefault();
            const isExpanded = button.getAttribute('aria-expanded') === 'true' || false;
            button.setAttribute('aria-expanded', !isExpanded);
            toggleDropdown(!isExpanded);
        });

        document.addEventListener('click', function (event) {
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                button.setAttribute('aria-expanded', false);
                toggleDropdown(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                button.setAttribute('aria-expanded', false);
                toggleDropdown(false);
            }
        });

        function toggleDropdown(show) {
            if (show) {
                dropdown.classList.remove('hidden', 'transition-leave-start', 'transition-leave-end');
                dropdown.classList.add('block', 'transition-enter-start');
                setTimeout(() => {
                    dropdown.classList.add('transition-enter-end');
                    dropdown.classList.remove('transition-enter-start');
                }, 1);
            } else {
                dropdown.classList.add('transition-leave-start');
                dropdown.classList.remove('transition-enter-end');
                setTimeout(() => {
                    dropdown.classList.add('hidden', 'transition-leave-end');
                    dropdown.classList.remove('block', 'transition-leave-start');
                }, 200);
            }
        }
    });
</script>
</body>
</html>
