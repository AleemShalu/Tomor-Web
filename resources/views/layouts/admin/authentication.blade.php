<!DOCTYPE html>
{{--<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    {!! NoCaptcha::renderJs() !!}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
          rel="stylesheet">

    <script src="{{ asset('config.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter antialiased bg-slate-100 text-slate-600">

<main class="bg-white">

    <div class="relative flex">

        <!-- Content -->
        <div class="w-full md:w-1/2">

            <div class="min-h-screen h-full flex flex-col after:flex-1">

                <!-- Header -->
                <div class="flex-1">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        <!-- Logo -->
                        <a class="block" href="{{ route('dashboard') }}" style="margin-top: 10px; ">
                            {{--                                    <img src="{{asset('images/tomor-logo5.png')}}" width="30%"  style="border-radius:30px ; margin-top: 10px;  ">--}}
                            <defs>
                                <linearGradient x1="28.538%" y1="20.229%" x2="100%" y2="108.156%" id="logo-a">
                                    <stop stop-color="#A5B4FC" stop-opacity="0" offset="0%"/>
                                    <stop stop-color="#A5B4FC" offset="100%"/>
                                </linearGradient>
                                <linearGradient x1="88.638%" y1="29.267%" x2="22.42%" y2="100%" id="logo-b">
                                    <stop stop-color="#38BDF8" stop-opacity="0" offset="0%"/>
                                    <stop stop-color="#38BDF8" offset="100%"/>
                                </linearGradient>
                            </defs>
                            <rect fill="#6366F1" width="32" height="32" rx="16"/>
                            <path d="M18.277.16C26.035 1.267 32 7.938 32 16c0 8.837-7.163 16-16 16a15.937 15.937 0 01-10.426-3.863L18.277.161z"
                                  fill="#4F46E5"/>
                            <path d="M7.404 2.503l18.339 26.19A15.93 15.93 0 0116 32C7.163 32 0 24.837 0 16 0 10.327 2.952 5.344 7.404 2.503z"
                                  fill="url(#logo-a)"/>
                            <path d="M2.223 24.14L29.777 7.86A15.926 15.926 0 0132 16c0 8.837-7.163 16-16 16-5.864 0-10.991-3.154-13.777-7.86z"
                                  fill="url(#logo-b)"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="w-full max-w-sm mx-auto px-4 py-8">
                    {{ $slot }}
                </div>

            </div>

        </div>
        <!-- Image -->
        <div class="hidden md:flex md:items-center md:justify-center absolute top-0 bottom-0 right-0 md:w-1/2 bg-blue-color-1-lighter"
             aria-hidden="true">
            <img class="object-cover w-full " src="{{ asset('images/Illustration-02.png') }}"
                 alt="Authentication image"/>
        </div>

    </div>
</main>
</body>
</html>
