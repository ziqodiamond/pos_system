<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Point of Sales') }}</title>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    <!-- Font -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">



    @stack('styles')


    <!-- JavaScript -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>



    @stack('scripts')
</head>


<body class="h-full bg-gray-200 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-full">



        @include('layouts.sidebar')

        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">


            {{-- <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8"> --}}
            {{ $slot }}
            {{-- </div> --}}
        </main>

    </div>





</body>


</html>
