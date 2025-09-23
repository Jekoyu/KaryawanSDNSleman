<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login')</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        /* quick visual adjustments to center content like image example */
        .auth-left {
            background-color: #6fb0e6; /* similar blue */
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
        <div class="auth-left hidden md:flex items-center justify-center p-10">
            <div class="max-w-lg text-center text-white">
                @if (file_exists(public_path('images/logo.png')))
                    <div class="mb-6 flex justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-150 h-150 object-contain" />
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                });
            @endif
        });
    </script>
</body>
</html>
