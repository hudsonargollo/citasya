<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ setting('app_name', 'CitasYa') }} | {{ setting('app_short_description', 'Auto-Agendamiento Inteligente') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png?v=5') }}"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico?v=5') }}"/>
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png?v=5') }}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: {
                lime: '#84CC16',
                lightlime: '#A3E635',
                darklime: '#65A30D',
                slate: '#0F172A',
                cruz: '#059669',
              }
            },
            fontFamily: {
              sans: ['Poppins', 'sans-serif'],
              display: ['Poppins', 'sans-serif'],
            }
          }
        }
      }
    </script>
    @stack('js_lib')
</head>
<body class="bg-gradient-to-b from-[#F0FCE6] via-[#FAFDF7] to-white min-h-screen font-sans text-slate-800 flex flex-col justify-between relative overflow-x-hidden antialiased">

    <!-- Decorative background ambient glows -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[300px] bg-brand-lime/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[200px] bg-emerald-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <!-- Main Auth Container -->
    <main class="flex-1 flex items-center justify-center p-2.5 sm:p-4 my-auto">
        <div class="w-full max-w-md bg-white/90 backdrop-blur-xl border border-lime-200/80 rounded-3xl p-4 sm:p-6 shadow-xl shadow-lime-900/10 relative overflow-hidden">
            <!-- Top Card Accent Line -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-lime via-emerald-500 to-brand-darklime"></div>

            <!-- Brand Header inside Card -->
            <div class="text-center mb-3">
                <a href="{{ url('/') }}" class="inline-block transition-transform hover:scale-105">
                    <img src="{{ asset('images/brand/logocitasya.webp?v=10') }}" alt="{{ setting('app_name', 'CitasYa') }}" class="h-12 sm:h-14 w-auto mx-auto object-contain drop-shadow-sm">
                </a>
            </div>

            <!-- Card Yield Content -->
            @yield('content')
        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="w-full py-2.5 text-center text-[11px] text-slate-500 border-t border-slate-200/60 bg-white/50 backdrop-blur-sm">
        <p>&copy; {{ date('Y') }} <strong>CitasYa Bolivia</strong> · Todos los derechos reservados.</p>
    </footer>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
