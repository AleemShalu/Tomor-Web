@props(['profile_enable'])


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
    <button id="user-button" class="inline-flex justify-center items-center group" aria-haspopup="true"
            aria-expanded="false">
        <!-- User info here -->
        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" width="32" height="32"
             alt="{{ Auth::user()->name }}"/>
        <div class="flex items-center truncate">
            <span class="truncate ml-2 text-sm font-medium group-hover:text-slate-800">{{ Auth::user()->name }}</span>
            <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" viewBox="0 0 12 12">
                <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
            </svg>
        </div>
    </button>
    <div id="dropdown-menu"
         class="origin-top-right z-10 absolute top-full min-w-44 bg-white border border-slate-200 py-1.5 rounded shadow-lg overflow-hidden mt-1 right-0 hidden transition-enter transition-leave">
        <div class="pt-0.5 pb-2 px-3 mb-1 border-b border-slate-200">
            <div class="font-medium text-slate-800">{{ Auth::user()->name }}</div>
            <div class="text-xs text-slate-500">{{ Auth::user()->roles->pluck('name')[0] ?? '' }}</div>
        </div>
        <ul>
            @if(isset($profile_enable) && $profile_enable === 'true')
            <li>
                <a class="font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3"
                   href="{{ route('profile.show') }}">Settings</a>
            </li>
            @endif

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3"
                       href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Sign
                        Out</a>
                </form>
            </li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('user-button');
        const dropdown = document.getElementById('dropdown-menu');

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
