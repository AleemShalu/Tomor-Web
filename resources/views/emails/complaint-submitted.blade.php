<!DOCTYPE html>
<html>
<head>
    <title>Complaint Submitted</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @livewireStyles
    {{--    <script src="./node_modules/flowbite/dist/flowbite.min.js"></script>--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
            integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC30aYilDmqeefuM-hDVkrW3g5V8RRKj5A&libraries=places"
            defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Include the Tailwind CSS here */
        /* You can either inline the CSS or link to the CDN for production */
        /* For demonstration purposes, I'll inline the CSS here */
        /* Note: This is just a subset of Tailwind CSS classes, you can include more as needed */
        /* For production, you might want to use PurgeCSS to remove unused classes */
        .container {
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-left: auto;
            margin-right: auto;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mb-8 {
            margin-bottom: 2rem;
        }

        .text-gray-700 {
            color: #4a5568;
        }

        .text-blue-500 {
            color: #3b82f6;
        }

        .font-semibold {
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container mt-8 mb-8">
    <h1 class="text-2xl font-semibold text-blue-500">Your Complaint Has Been Submitted</h1>
    <p class="mt-4 text-gray-700">Your Ticket Number is: {{ $ticketId }}</p>
    <p class="mt-4 text-gray-700">We will review your complaint and get back to you soon.</p>
</div>
</body>
</html>
