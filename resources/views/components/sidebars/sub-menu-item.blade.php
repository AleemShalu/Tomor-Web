@props(['route', 'isActive'])

<li class="mb-1 last:mb-0">
    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if($isActive){{ 'text-indigo-500' }}@endif"
       href="{{ route($route) }}">
        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
            {{ $slot }}
        </span>
    </a>
</li>