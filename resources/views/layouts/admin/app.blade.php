<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    @livewireStyles

    <!-- Development version -->
    <script src="{{ asset('libs/lucide/lucide.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2/sweetalert2.js') }}"></script>


    <!-- Flowbite CSS -->
    <link href="{{ asset('libs/flowbite/flowbite.min.css') }}" rel="stylesheet"/>

    <!-- Intl-tel-input CSS -->
    <link href="{{ asset('libs/intl-tel-input/intlTelInput.css') }}" rel="stylesheet"/>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.css"/>

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <!-- JS Libraries -->
    <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
    <link href="{{ asset('libs/intl-tel-input/intlTelInput.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('libs/intl-tel-input/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('libs/hijri-date/datepicker-hijri.js') }}"></script>
    <script src="{{ asset('libs/iban/iban.min.js') }}"></script>


    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <!-- Development version -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Production version -->
    {{-- <script src="https://unpkg.com/lucide@latest"></script> --}}

    <!-- Flowbite JS -->
    <!-- Note: Keep the latest version only if not needed both -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Ckeditor.js -->
    <script src="https://cdn.ckeditor.com/4.17.0/standard/ckeditor.js"></script>

    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC30aYilDmqeefuM-hDVkrW3g5V8RRKj5A&libraries=places" defer></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>

    <!-- Moment.js -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>

    <!-- Daterangepicker JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- ag-Grid JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@latest/ag-grid-community.min.js"></script> --}}
    {{-- <script src="https://unpkg.com/ag-grid-enterprise@latest/dist/ag-grid-enterprise.min.js"></script> --}}

    <script src="{{ asset('libs/ag-grid/ag-grid-community.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <!-- Additional Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
        class="font-inter antialiased bg-slate-100 text-slate-600"
        :class="{ 'sidebar-expanded': sidebarExpanded }"
        x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }"
        x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))"
>

<script>
    if (localStorage.getItem('sidebar-expanded') == 'true') {
        document.querySelector('body').classList.add('sidebar-expanded');
    } else {
        document.querySelector('body').classList.remove('sidebar-expanded');
    }
</script>

<!-- Page wrapper -->
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <x-admin.sidebar/>

    <!-- Content area -->
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if($attributes['background']){{ $attributes['background'] }}@endif" x-ref="contentarea">
        <x-admin.header/>
        <main>
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
<script>
    lucide.createIcons();
</script>
