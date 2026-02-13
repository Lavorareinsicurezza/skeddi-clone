<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAFEGEST</title>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('assets/styles/tailwind-all.min.css') }}">
    {{--
    <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        input::placeholder,
        textarea::placeholder,
        select::placeholder {
            color: gray !important;
        }

        /* Custom circular checkbox styling - exclude settings page toggle switches */
        input[type=checkbox]:not(.sr-only) {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 1.3em;
            height: 1.3em;
            border-radius: 1em;
            background-color: #fff;
            cursor: pointer;
            position: relative;
        }

        input[type=checkbox]:not(.sr-only):checked {
            print-color-adjust: exact;
            background-image: url({{ asset('assets/images/checkbox-circle.png') }});
            background-repeat: no-repeat;
            background-size: 1.3em 1.3em;
            background-position: center;
        }
    </style>

    @yield('styles')

</head>

<body class="bg-gray-50">

    @include('layouts.partials.sidebar')

    @include('layouts.partials.header')

    <!-- Main content -->
    <div class="p-4 pt-20 lg:ml-64">
        <div class="rounded-lg p-4 ">
            @yield('content')
        </div>
    </div>

    @include('layouts.partials.company-modal')


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <script>
        setTimeout(function () {
            $('.flash-message-box').hide();
        }, 5000);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('cta-button-sidebar');
            if (!sidebar) return;
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        if (sidebar.classList.contains('-translate-x-full')) {
                            document.querySelectorAll('[drawer-backdrop]').forEach(backdrop => {
                                backdrop.remove();
                            });
                        }
                    }
                });
            });

            // Start observing the sidebar's class changes
            observer.observe(sidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>

    @yield('scripts')

</body>

</html>
