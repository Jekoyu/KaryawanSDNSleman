<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback when Vite manifest is not present --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            border: "hsl(214.3 31.8% 91.4%)",
                            input: "hsl(214.3 31.8% 91.4%)",
                            ring: "hsl(222.2 84% 4.9%)",
                            background: "hsl(0 0% 100%)",
                            foreground: "hsl(222.2 84% 4.9%)",
                            primary: {
                                DEFAULT: "hsl(222.2 47.4% 11.2%)",
                                foreground: "hsl(210 40% 98%)",
                            },
                            secondary: {
                                DEFAULT: "hsl(210 40% 96%)",
                                foreground: "hsl(222.2 84% 4.9%)",
                            },
                            muted: {
                                DEFAULT: "hsl(210 40% 96%)",
                                foreground: "hsl(215.4 16.3% 46.9%)",
                            },
                            accent: {
                                DEFAULT: "hsl(210 40% 96%)",
                                foreground: "hsl(222.2 84% 4.9%)",
                            },
                        },
                        borderRadius: {
                            lg: "0.5rem",
                            md: "calc(0.5rem - 2px)",
                            sm: "calc(0.5rem - 4px)",
                        },
                    }
                }
            }
        </script>
    @endif
</head>
<body class="bg-slate-50 min-h-screen">
    @yield('content')

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