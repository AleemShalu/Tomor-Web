<!-- resources/views/components/sidebars/expand-collapse-button.blade.php -->
<div class="pt-3 lg:inline-flex justify-end mt-auto"
     :class="{'hidden': !sidebarOpen && window.innerWidth < 1024, '2xl:hidden': window.innerWidth >= 1536}">
    <div class="px-3 py-2">
        <button @click="sidebarExpanded = !sidebarExpanded">
            <span class="sr-only">Expand / collapse sidebar</span>
            <svg
                    :class="{'rotate-180': sidebarExpanded, '-rotate-180': !sidebarExpanded}"
                    class="w-6 h-6 fill-current transition-transform duration-200 ease-in-out"
                    viewBox="0 0 24 24"
                    :style="{'transform': (document.dir === 'rtl' && window.innerWidth < 1024) ? 'rotate(180deg)' : ''}"
            >
                <path class="text-slate-400"
                      d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z"/>
                <path class="text-slate-600" d="M3 23H1V1h2z"/>
            </svg>
        </button>
    </div>
</div>
