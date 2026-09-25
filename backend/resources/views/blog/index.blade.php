<!DOCTYPE html>
<html class="scroll-smooth" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  {!! SEO::generate() !!}
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg?v=20260925') }}"/>
  <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png?v=20260925') }}"/>
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico?v=20260925') }}"/>
  <link rel="alternate" type="application/rss+xml" title="CitasYa Blog RSS Feed" href="{{ route('blog.feed') }}"/>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet"/>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#F0FDF4',
              100: '#DCFCE7',
              200: '#BBF7D0',
              300: '#86EFAC',
              400: '#4ADE80',
              500: '#22C55E',
              600: '#16A34A',
              DEFAULT: '#84CC16',
              lime: '#A3E635',
              darklime: '#65A30D',
              neon: '#84CC16',
              mint: '#ECFDF5',
              cruz: '#059669',
              slate: '#0F172A',
              cardDark: '#1E293B',
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            display: ['"Outfit"', 'sans-serif']
          }
        }
      }
    }
  </script>
</head>
<body class="bg-[#FAFDF7] text-slate-900 antialiased selection:bg-brand-lime selection:text-brand-slate font-sans flex flex-col min-h-screen">

  <!-- Top Announcement Bar -->
  <div class="bg-brand-slate text-white py-2 px-4 text-xs md:text-sm font-medium border-b border-brand-cardDark">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      <div class="flex items-center gap-2 mx-auto sm:mx-0">
        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-lime text-brand-slate uppercase tracking-wider">Recursos</span>
        <span>💡 Estrategias de automatización, reservas online y cobros QR para negocios en Bolivia</span>
      </div>
      <div class="hidden sm:flex items-center gap-4 text-slate-300 text-xs">
        <a class="hover:text-brand-lime transition" href="/app/">Buscar Servicios</a>
        <a class="hover:text-brand-lime transition" href="/owner/">Portal Propietarios</a>
      </div>
    </div>
  </div>

  <!-- Header -->
  <header class="sticky top-0 z-50 bg-[#FAFDF7]/95 backdrop-blur-md border-b border-lime-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
      <a class="flex items-center gap-3 group" href="{{ route('home') }}">
        <img alt="CitasYa" class="h-10 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105" src="/images/brand/logocitasya.webp?v=10"/>
      </a>

      <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-700">
        <a class="hover:text-brand-darklime transition-colors" href="{{ route('home') }}">Inicio</a>
        <a class="hover:text-brand-darklime transition-colors" href="{{ route('home') }}#servicios">Servicios</a>
        <a class="hover:text-brand-darklime transition-colors" href="{{ route('home') }}#para-negocios">Para Negocios</a>
        <a class="text-brand-darklime font-bold border-b-2 border-brand-lime pb-0.5" href="{{ route('blog.index') }}">Blog</a>
      </nav>

      <div class="flex items-center gap-3">
        <a class="hidden sm:inline-flex text-xs md:text-sm font-semibold text-brand-slate hover:text-brand-darklime px-4 py-2 transition-colors" href="/login">
          Iniciar Sesión
        </a>
        <a class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-xs md:text-sm font-bold text-brand-slate bg-brand-lime hover:bg-[#92dc24] border border-lime-300 shadow-sm shadow-lime-300/40 transition-all hover:scale-105 active:scale-95 cursor-pointer" href="/register">
          Registrar Negocio Gratis
        </a>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-grow">
    
    <!-- Hero Header -->
    <section class="relative pt-12 pb-14 bg-gradient-to-b from-lime-50/60 via-[#FAFDF7] to-[#FAFDF7] border-b border-lime-100/60">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-5">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
          <span>Guías & Estrategias de Crecimiento</span>
        </div>

        <h1 class="font-display font-black text-3xl sm:text-5xl lg:text-6xl text-brand-slate tracking-tight max-w-4xl mx-auto leading-tight">
          Aprende a digitalizar y escalar tu negocio de servicios en Bolivia
        </h1>
        <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
          Descubre tácticas probadas de auto-agendamiento 24/7, cobros instantáneos con QR Simple y automatización de clientes para salones, clínicas y profesionales.
        </p>

        <!-- Search Bar & Category Filter -->
        <div class="mt-8 max-w-xl mx-auto">
          <form action="{{ route('blog.index') }}" method="GET" class="relative flex items-center shadow-md shadow-lime-500/5 rounded-full bg-white border border-slate-200 focus-within:border-brand-darklime focus-within:ring-2 focus-within:ring-lime-500/20 transition-all p-1.5">
            <svg class="w-5 h-5 text-slate-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Buscar por tema (ej. salones, cobros qr, santa cruz)..." class="w-full px-3 py-2 text-sm text-slate-800 bg-transparent border-0 focus:ring-0 placeholder-slate-400 font-medium">
            <button type="submit" class="px-5 py-2 rounded-full bg-brand-slate hover:bg-slate-800 text-brand-lime font-bold text-xs transition-all">Buscar</button>
          </form>
        </div>

        <!-- Categories List -->
        @if(!empty($categories))
          <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
            <a href="{{ route('blog.index') }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all {{ empty($category) ? 'bg-brand-slate text-brand-lime shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
              Todos
            </a>
            @foreach($categories as $catName => $count)
              <a href="{{ route('blog.index', ['categoria' => $catName]) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all {{ strtolower($category ?? '') === strtolower($catName) ? 'bg-brand-slate text-brand-lime shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                {{ $catName }} <span class="opacity-70 text-[10px]">({{ $count }})</span>
              </a>
            @endforeach
          </div>
        @endif

      </div>
    </section>

    <!-- Articles Section -->
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      @if(empty($posts))
        <div class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="w-16 h-16 bg-lime-100 text-brand-darklime rounded-full flex items-center justify-center mx-auto mb-4 font-black text-2xl">?</div>
          <h3 class="text-xl font-bold text-slate-800">No se encontraron artículos</h3>
          <p class="text-slate-500 text-sm mt-1">Intenta con otro término de búsqueda o categoría.</p>
          <a href="{{ route('blog.index') }}" class="mt-4 inline-block px-5 py-2.5 rounded-full bg-brand-slate text-brand-lime font-bold text-xs">Ver todos los artículos</a>
        </div>
      @else

        <!-- Featured Post Card (Hero) -->
        @if($featuredPost)
          <div class="mb-12">
            <a href="{{ route('blog.show', $featuredPost['slug']) }}" class="group block relative bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-[2rem] p-6 sm:p-10 lg:p-12 shadow-xl hover:shadow-2xl hover:shadow-lime-500/10 transition-all border border-slate-800 overflow-hidden">
              <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-lime-500/10 rounded-full blur-3xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>

              <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-4">
                  <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full bg-brand-lime text-brand-slate font-extrabold text-[11px] uppercase tracking-wider">Artículo Destacado</span>
                    <span class="text-xs text-slate-400 flex items-center gap-1 font-medium">
                      <svg class="w-3.5 h-3.5 text-brand-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      {{ $featuredPost['read_time'] }} min de lectura
                    </span>
                    <span class="text-xs text-slate-400 font-medium">{{ date('d M, Y', strtotime($featuredPost['date'])) }}</span>
                  </div>

                  <h2 class="font-display font-black text-2xl sm:text-3xl lg:text-4xl text-white group-hover:text-brand-lime transition-colors leading-tight">
                    {{ $featuredPost['title'] }}
                  </h2>

                  <p class="text-slate-300 text-sm sm:text-base leading-relaxed line-clamp-3">
                    {{ $featuredPost['excerpt'] }}
                  </p>

                  <div class="pt-2 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                      <div class="w-8 h-8 rounded-full bg-brand-lime/20 border border-brand-lime/40 text-brand-lime flex items-center justify-center font-black text-xs">CY</div>
                      <span class="text-xs text-slate-300 font-semibold">{{ $featuredPost['author'] }}</span>
                    </div>

                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-lime group-hover:translate-x-1 transition-transform">
                      Leer artículo completo
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                  </div>
                </div>

                <div class="lg:col-span-5">
                  @if(!empty($featuredPost['image']) && file_exists(public_path($featuredPost['image'])))
                    <div class="relative rounded-2xl overflow-hidden aspect-video border border-lime-500/30 shadow-lg group-hover:scale-105 transition-transform duration-500">
                      <img src="{{ $featuredPost['image'] }}?v={{ filemtime(public_path($featuredPost['image'])) }}" alt="{{ $featuredPost['title'] }}" class="w-full h-full object-cover">
                    </div>
                  @else
                    <div class="relative rounded-2xl bg-gradient-to-tr from-brand-cruz/40 via-lime-500/20 to-slate-800 p-8 border border-lime-500/20 aspect-video lg:aspect-[4/3] flex flex-col justify-between overflow-hidden shadow-inner">
                      <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-lime-400 uppercase tracking-widest">CitasYa Insights</span>
                        <span class="w-3 h-3 rounded-full bg-lime-400 animate-pulse"></span>
                      </div>
                      <div>
                        <span class="text-xs text-slate-300 uppercase tracking-wider block font-semibold mb-1">Palabra Clave Estratégica:</span>
                        <div class="bg-slate-900/80 backdrop-blur-sm px-3.5 py-2 rounded-xl text-xs font-mono text-lime-300 border border-lime-500/30">
                          {{ $featuredPost['target_keyword'] ?: 'reservas online santa cruz' }}
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </a>
          </div>
        @endif

        <!-- Grid of Articles -->
        @if(!empty($gridPosts))
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($gridPosts as $post)
              <article class="bg-white rounded-3xl border border-slate-200/80 hover:border-lime-300 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-lime-500/10 transition-all flex flex-col justify-between group">
                <div class="p-6">
                  
                  <div class="flex items-center justify-between gap-2 text-xs mb-3">
                    <span class="px-2.5 py-1 rounded-full bg-lime-100 text-brand-darklime font-extrabold text-[10px] uppercase tracking-wide">
                      {{ $post['category'] }}
                    </span>
                    <span class="text-slate-400 font-medium flex items-center gap-1">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      {{ $post['read_time'] }} min
                    </span>
                  </div>

                  <h3 class="font-display font-extrabold text-lg sm:text-xl text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                    <a href="{{ route('blog.show', $post['slug']) }}" class="focus:outline-none">
                      {{ $post['title'] }}
                    </a>
                  </h3>

                  <p class="mt-3 text-slate-600 text-xs sm:text-sm leading-relaxed line-clamp-3">
                    {{ $post['excerpt'] }}
                  </p>
                </div>

                <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-xs">
                  <span class="text-slate-500 font-semibold">{{ date('d M, Y', strtotime($post['date'])) }}</span>
                  <a href="{{ route('blog.show', $post['slug']) }}" class="font-bold text-emerald-600 group-hover:text-brand-darklime flex items-center gap-1 transition-colors">
                    Leer más
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                  </a>
                </div>
              </article>
            @endforeach
          </div>
        @endif

      @endif

    </section>

    <!-- Pre-Footer CTA Banner -->
    <section class="py-16 bg-white border-t border-slate-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-[#84CC16] via-[#A3E635] to-[#65A30D] rounded-[2.5rem] p-8 sm:p-12 text-center relative overflow-hidden shadow-2xl shadow-lime-600/20">
          <div class="relative z-10 max-w-2xl mx-auto">
            <span class="inline-block px-4 py-1 rounded-full bg-slate-950 text-white text-xs font-extrabold uppercase tracking-wider mb-4">
              Impulsa Tu Negocio
            </span>
            <h2 class="font-display font-black text-2xl sm:text-4xl text-brand-slate tracking-tight leading-tight">
              ¿Listo para automatizar tus citas y eliminar ausencias?
            </h2>
            <p class="mt-3 text-sm sm:text-base text-slate-900 font-medium">
              Únete a los salones, spas, consultorios y profesionales de Bolivia que gestionan su agenda en piloto automático.
            </p>
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
              <a class="w-full sm:w-auto px-7 py-3.5 rounded-full bg-brand-slate text-brand-lime font-display font-extrabold text-sm hover:bg-slate-900 shadow-xl transition-all" href="/register">
                Registrar Mi Negocio Gratis
              </a>
              <a class="w-full sm:w-auto px-7 py-3.5 rounded-full bg-white text-slate-900 font-display font-bold text-sm hover:bg-slate-50 transition-all" href="/app/">
                Explorar Servicios Disponibles
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="bg-brand-slate text-white pt-12 pb-10 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-800 text-xs text-slate-400">
        <div class="md:col-span-2">
          <a class="inline-flex items-center gap-2 mb-4" href="{{ route('home') }}">
            <img alt="CitasYa Bolivia" class="h-9 w-auto object-contain" src="/images/brand/logocitasya.webp?v=10"/>
          </a>
          <p class="text-slate-300 leading-relaxed max-w-sm">
            La plataforma líder de auto-agendamiento y confirmación instantánea por WhatsApp para profesionales y comercios de servicios en Bolivia.
          </p>
        </div>
        <div>
          <h4 class="font-bold text-white uppercase tracking-wider mb-3 text-sm">Plataforma</h4>
          <ul class="space-y-2">
            <li><a class="hover:text-brand-lime transition" href="{{ route('home') }}">Inicio</a></li>
            <li><a class="hover:text-brand-lime transition" href="/app/">Buscar Citas</a></li>
            <li><a class="hover:text-brand-lime transition" href="/owner/">Portal Propietarios</a></li>
            <li><a class="hover:text-brand-lime transition" href="{{ route('blog.index') }}">Blog & Artículos</a></li>
            <li><a class="hover:text-brand-lime transition" href="{{ route('blog.feed') }}">RSS Feed</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold text-white uppercase tracking-wider mb-3 text-sm">Contacto</h4>
          <p class="leading-relaxed">Av. San Martín #1550, Equipetrol<br>Santa Cruz de la Sierra, Bolivia</p>
          <p class="mt-2 text-brand-lime font-semibold">soporte@citasya.clubemkt.online</p>
        </div>
      </div>
      <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
        <p>© {{ date('Y') }} CitasYa Bolivia. Todos los derechos reservados.</p>
        <div class="flex items-center gap-4 mt-2 sm:mt-0">
          <a href="/privacy" class="hover:text-slate-300">Privacidad</a>
          <a href="/terms" class="hover:text-slate-300">Términos</a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
