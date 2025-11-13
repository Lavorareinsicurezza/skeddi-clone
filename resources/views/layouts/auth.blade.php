<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Management Software')</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('styles')
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex min-h-screen">
        <!-- Left Section - Form Content -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
            @yield('content')
        </div>

        <!-- Right Section - Image/Illustration -->
        <div class="hidden lg:flex w-1/2 mt-[7px] justify-center relative overflow-hidden"
            style="background-image: url('{{ asset('assets/images/login.svg') }}'); background-size: cover; background-position: fixed;">
            <div class="text-center z-10 px-8">
                <h2 class="text-5xl font-bold mb-8 mt-[60px]">
                    <span class="text-white">{{ __('lang.work_better') }}, </span>
                    <span class="text-red-500">{{ __('lang.anywhere') }}</span>
                </h2>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @yield('scripts')
</body>

</html>
