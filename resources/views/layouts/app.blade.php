<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    {{--    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">--}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
          rel="stylesheet">

    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC30aYilDmqeefuM-hDVkrW3g5V8RRKj5A&libraries=places"
            defer></script>

    <!-- Styles -->
    @livewireStyles
    <!-- Development version -->
    <script src="{{ asset('libs/lucide/lucide.js') }}"></script>

    <!-- Local Libraries -->
    <script src="{{ asset('libs/sweetalert2/sweetalert2.js') }}"></script>
    <link href="{{ asset('libs/flowbite/flowbite.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('libs/intl-tel-input/intlTelInput.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('libs/intl-tel-input/intlTelInput.css') }}" rel="stylesheet"/>
    <script src="{{ asset('libs/intl-tel-input/utils.min.js') }}"></script>

    <link href="{{ asset('libs/ag-grid/ag-grid.css') }}" rel="stylesheet"/>
    <link href="{{ asset('libs/ag-grid/ag-theme-alpine.css') }}" rel="stylesheet"/>
    <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('libs/font-awesome/all.min.js') }}"></script>
    <script src="{{ asset('libs/flowbite/flowbite.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('libs/flatpickr/flatpickr.min.css') }}">
    <script src="{{ asset('libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('libs/chart.js/chart.js') }}"></script>
    <script src="{{ asset('libs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('libs/hijri-date/hijri-date-latest.js') }}"></script>
    <script src="{{ asset('libs/hijri-date/datepicker-hijri.js') }}"></script>
    <script src="{{ asset('libs/ag-grid/ag-grid-community.min.js') }}"></script>
    {{--    <script src="{{ asset('libs/ag-grid/ag-grid-enterprise.min.js') }}"></script>--}}
    <script src="{{ asset('libs/moment.js/moment.min.js') }}"></script>
    <script src="{{ asset('libs/intl-tel-input/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('libs/iban/iban.min.js') }}"></script>

    <!-- Local Scripts -->
    <script src="./node_modules/flowbite/dist/flowbite.min.js"></script>


</head>

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
        class="font-inter antialiased bg-slate-100 text-slate-600"
        :class="{ 'sidebar-expanded': sidebarExpanded }"
        x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true', isMobile: window.innerWidth < 1024 }"
        x-init="
            $watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value));
            $watch('isMobile', value => {
                if (value) {
                    document.documentElement.setAttribute('dir', 'ltr');
                } else {
                    document.documentElement.setAttribute('dir', localStorage.getItem('document-dir') || 'ltr');
                }
            });
            window.addEventListener('resize', () => {
                isMobile = window.innerWidth < 1024;
            });
        "
>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isMobile = window.innerWidth < 1024;
        const sidebarExpanded = localStorage.getItem('sidebar-expanded') == 'true';
        const documentDir = document.documentElement.getAttribute('dir');

        // Ensure the sidebar is forced LTR on mobile
        if (isMobile) {
            document.documentElement.setAttribute('dir', 'ltr');
        } else {
            // Restore original direction if not on mobile
            document.documentElement.setAttribute('dir', localStorage.getItem('document-dir') || documentDir);
        }

        if (sidebarExpanded) {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }

        // Store original direction
        if (!localStorage.getItem('document-dir')) {
            localStorage.setItem('document-dir', documentDir);
        }

        // Handle window resize
        window.addEventListener('resize', function () {
            const isMobileNow = window.innerWidth < 1024;
            if (isMobileNow) {
                document.documentElement.setAttribute('dir', 'ltr');
            } else {
                document.documentElement.setAttribute('dir', localStorage.getItem('document-dir'));
            }
        });
    });
</script>

<!-- Page wrapper -->
<div class="flex h-screen overflow-hidden">

    <x-app.sidebar/>

    <!-- Content area -->
    <div
            class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if($attributes['background']){{ $attributes['background'] }}@endif"
            x-ref="contentarea">

        <x-app.header/>

        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-app.footer/>


    </div>

</div>


@livewireScripts

</body>
</html>
<script>
    lucide.createIcons();
</script>
