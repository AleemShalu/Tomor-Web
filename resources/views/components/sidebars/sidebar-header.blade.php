<!-- resources/views/components/sidebars/sidebar-header.blade.php -->

<!-- Sidebar header -->
<div class="flex justify-between mb-10 pr-3 sm:px-2">
    <!-- Close button -->
    <button class="lg:hidden text-slate-500 hover:text-slate-400" @click.stop="sidebarOpen = !sidebarOpen"
            aria-controls="sidebar" :aria-expanded="sidebarOpen">
        <span class="sr-only">Close sidebar</span>
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z"/>
        </svg>
    </button>
    <!-- Logo -->
    <a class="block" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/tomor-logo5.png') }}"
             :class="{ 'w-20': sidebarOpen, 'w-24': !sidebarOpen }"
             style="border-radius:3px; width: 80%;">
    </a>
</div>
