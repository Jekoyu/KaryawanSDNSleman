<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'App')</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback when Vite manifest is not present (dev server not running or assets not built) --}}
        {{-- Loads Tailwind via CDN for quick dev/testing. Replace by running `npm install` and `npm run dev` or `npm run build`. --}}
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-lg font-semibold">KaryawanSDN</a>
                </div>

                <div>
                    @if(session()->has('username'))
                        <div class="flex items-center space-x-4">
                            <span class="text-sm">{{ session('username') }}</span>
                            <form method="POST" action="{{ url('/logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-red-600">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ url('/login') }}" class="text-sm text-blue-600">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="py-10">
        <div class="max-w-4xl mx-auto px-4">
            @yield('content')
        </div>
    </main>

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
