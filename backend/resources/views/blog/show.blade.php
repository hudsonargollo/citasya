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
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
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
  <style>
    /* Prose and Custom Typography Enhancements */
    .prose-citasya img {
      width: 100%;
      height: auto;
      border-radius: 1.5rem;
      margin-top: 1.5rem;
      margin-bottom: 2.5rem;
      border: 1px solid #E2E8F0;
      box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.12);
    }
    .prose-citasya h1 {
      display: none; /* Already rendered in the header */
    }
    .prose-citasya h2 {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      color: #0F172A;
      font-size: 1.75rem;
      line-height: 1.25;
      margin-top: 2.5rem;
      margin-bottom: 1.25rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid #E2E8F0;
    }
    .prose-citasya h3 {
      font-family: 'Outfit', sans-serif;
      font-weight: 700;
      color: #1E293B;
      font-size: 1.35rem;
      line-height: 1.3;
      margin-top: 2rem;
      margin-bottom: 0.75rem;
    }
    .prose-citasya p {
      color: #334155;
      font-size: 1.0625rem;
      line-height: 1.75;
      margin-bottom: 1.5rem;
    }
    .prose-citasya strong {
      color: #0F172A;
      font-weight: 700;
    }
    .prose-citasya ul {
      list-style-type: disc;
      padding-left: 1.5rem;
      margin-bottom: 1.5rem;
      color: #334155;
    }
    .prose-citasya ol {
      list-style-type: decimal;
      padding-left: 1.5rem;
      margin-bottom: 1.5rem;
      color: #334155;
    }
    .prose-citasya li {
      margin-bottom: 0.5rem;
      line-height: 1.65;
    }
    .prose-citasya a {
      color: #059669;
      font-weight: 600;
      text-decoration: underline;
      text-underline-offset: 3px;
      transition: color 0.2s;
    }
    .prose-citasya a:hover {
      color: #65A30D;
    }
    .prose-citasya pre {
      background-color: #0F172A;
      color: #F8FAFC;
      padding: 1.25rem;
      border-radius: 1rem;
      overflow-x: auto;
      font-size: 0.875rem;
      margin-bottom: 1.5rem;
      border: 1px solid #1E293B;
    }
    .prose-citasya code {
      background-color: #F1F5F9;
      color: #059669;
      padding: 0.2rem 0.4rem;
      border-radius: 0.375rem;
      font-size: 0.875em;
      font-weight: 600;
    }
    .prose-citasya pre code {
      background-color: transparent;
      color: inherit;
      padding: 0;
    }
    .prose-citasya table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      margin-bottom: 1rem;
    }
    .prose-citasya th {
      background-color: #F8FAFC;
      padding: 0.75rem 1rem;
      font-weight: 700;
      color: #0F172A;
      border-bottom: 2px solid #E2E8F0;
      text-align: left;
    }
    .prose-citasya td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #E2E8F0;
      color: #334155;
    }
    .prose-citasya tr:hover td {
      background-color: #F8FAFC;
    }
  </style>
</head>
<body class="bg-[#FAFDF7] text-slate-900 antialiased selection:bg-brand-lime selection:text-brand-slate font-sans flex flex-col min-h-screen">

  <!-- Reading Progress Bar -->
  <div id="readingProgress" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-emerald-500 via-lime-500 to-brand-DEFAULT z-[100] transition-all duration-75 w-0"></div>

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

  <!-- Main Article Body -->
  <main class="flex-grow pt-8 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-brand-darklime transition-colors">Inicio</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('blog.index') }}" class="hover:text-brand-darklime transition-colors">Blog</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('blog.index', ['categoria' => $post['category']]) }}" class="hover:text-brand-darklime transition-colors">{{ $post['category'] }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 truncate max-w-xs">{{ $post['title'] }}</span>
      </nav>

      <!-- Post Header -->
      <header class="mb-10 max-w-4xl">
        <div class="flex flex-wrap items-center gap-3 mb-4 text-xs">
          <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold uppercase tracking-wide">
            {{ $post['category'] }}
          </span>
          <span class="text-slate-500 font-medium flex items-center gap-1">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Publicado el {{ date('d \d\e F, Y', strtotime($post['date'])) }}
          </span>
          <span class="text-slate-500 font-medium flex items-center gap-1">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $post['read_time'] }} min de lectura ({{ number_format($post['word_count']) }} palabras)
          </span>
        </div>

        <h1 class="font-display font-black text-3xl sm:text-4xl lg:text-5xl text-brand-slate tracking-tight leading-tight mb-4">
          {{ $post['title'] }}
        </h1>

        <p class="text-lg sm:text-xl text-slate-600 leading-relaxed font-normal">
          {{ $post['meta_description'] }}
        </p>

        <!-- Author & Share bar -->
        <div class="mt-6 pt-6 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-brand-slate text-brand-lime flex items-center justify-center font-black text-sm">
              CY
            </div>
            <div>
              <div class="text-xs font-bold text-slate-900">{{ $post['author'] }}</div>
              <div class="text-[11px] text-slate-500">CitasYa Research & Content Hub</div>
            </div>
          </div>

          <!-- Social Share Buttons -->
          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-400 mr-1 hidden sm:inline">Compartir:</span>
            
            <!-- WhatsApp -->
            <a href="https://api.whatsapp.com/send?text={{ urlencode($post['title'] . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 flex items-center justify-center transition-transform hover:scale-110" title="Compartir en WhatsApp">
              <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.771.815 2.796.815 3.182 0 5.767-2.587 5.768-5.766.001-3.181-2.585-5.767-5.768-5.766zm10.007 5.766c-.002 5.51-4.484 9.991-9.992 9.991-1.748 0-3.376-.452-4.795-1.246l-5.251 1.378 1.402-5.12c-.874-1.472-1.374-3.195-1.375-5.003.002-5.51 4.484-9.991 9.993-9.991 5.51 0 9.992 4.481 9.992 9.991z"/></svg>
            </a>

            <!-- Twitter / X -->
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post['title']) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center transition-transform hover:scale-110" title="Compartir en X">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>

            <!-- LinkedIn -->
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 flex items-center justify-center transition-transform hover:scale-110" title="Compartir en LinkedIn">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 8.76c.94 0 1.7-.76 1.7-1.7 0-.93-.76-1.7-1.7-1.7-.93 0-1.7.77-1.7 1.7 0 .94.77 1.7 1.7 1.7m1.39 9.74v-8.37H5.07v8.37h2.78Z"/></svg>
            </a>

            <!-- Copy Link Button -->
            <button onclick="copyArticleUrl()" id="btnCopyLink" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center transition-transform hover:scale-110" title="Copiar enlace">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
            </button>
          </div>
        </div>
      </header>

      <!-- Layout: 2 Columns (Content + Sidebar) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Main Article Content (8 cols) -->
        <article class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-sm">
          <div class="prose prose-slate max-w-none prose-citasya">
            {!! $post['content_html'] !!}
          </div>

          <!-- Article Footer Tags -->
          @if(!empty($post['secondary_keywords']))
            <div class="mt-12 pt-6 border-t border-slate-200">
              <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Temas Relacionados:</div>
              <div class="flex flex-wrap gap-2">
                @foreach($post['secondary_keywords'] as $kw)
                  <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium"># {{ $kw }}</span>
                @endforeach
              </div>
            </div>
          @endif

          <!-- Bottom In-Article CTA Banner -->
          <div class="mt-10 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 p-8 text-white text-center relative overflow-hidden">
            <div class="relative z-10">
              <span class="px-3 py-1 rounded-full bg-brand-lime text-brand-slate text-[10px] font-extrabold uppercase tracking-widest inline-block mb-3">Para Negocios en Bolivia</span>
              <h3 class="font-display font-black text-2xl sm:text-3xl text-white mb-2">Comienza a recibir reservas online hoy mismo</h3>
              <p class="text-slate-300 text-sm max-w-lg mx-auto mb-6">Configura tus servicios, horarios y cobros QR Simple en minutos. Prueba gratis de 30 días sin tarjeta.</p>
              <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="/register" class="w-full sm:w-auto px-6 py-3 rounded-full bg-brand-lime text-brand-slate font-display font-extrabold text-xs uppercase tracking-wider hover:bg-[#92dc24] transition-all">Crear Cuenta Gratis</a>
                <a href="/owner/" class="w-full sm:w-auto px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white font-display font-bold text-xs transition-all">Ver Portal Propietarios</a>
              </div>
            </div>
          </div>
        </article>

        <!-- Sticky Sidebar (4 cols) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">

          <!-- Table of Contents Card -->
          @if(!empty($post['toc']))
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
              <h4 class="font-display font-bold text-sm text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                Tabla de Contenido
              </h4>
              <nav class="space-y-2 text-xs">
                @foreach($post['toc'] as $item)
                  <a href="#{{ $item['id'] }}" class="block text-slate-600 hover:text-emerald-600 font-medium py-1 transition-colors {{ $item['level'] === 3 ? 'pl-4 text-slate-500' : '' }}">
                    {{ $item['title'] }}
                  </a>
                @endforeach
              </nav>
            </div>
          @endif

          <!-- Quick Booking CTA Card -->
          <div class="bg-gradient-to-br from-emerald-900 via-slate-900 to-brand-slate text-white rounded-3xl p-6 shadow-xl border border-emerald-500/20">
            <span class="inline-block px-2.5 py-1 rounded-full bg-brand-lime text-brand-slate text-[10px] font-extrabold uppercase tracking-wider mb-3">
              ¿Eres Cliente?
            </span>
            <h4 class="font-display font-black text-xl text-white mb-2 leading-tight">
              Reserva tu cita en los mejores salones de Santa Cruz
            </h4>
            <p class="text-xs text-slate-300 leading-relaxed mb-5">
              Encuentra especialistas verificados en Equipetrol, Urubó, Las Palmas y más. Reserva en 30 segundos sin llamadas.
            </p>
            <a href="/app/" class="w-full block text-center py-3 px-4 rounded-full bg-brand-lime text-brand-slate font-extrabold text-xs hover:bg-[#92dc24] transition-all shadow-md">
              Explorar Salones & Clínicas
            </a>
          </div>

          <!-- Newsletter / RSS Box -->
          <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm text-center">
            <div class="w-10 h-10 rounded-full bg-lime-100 text-brand-darklime flex items-center justify-center mx-auto mb-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
            </div>
            <h5 class="font-bold text-sm text-slate-900 mb-1">Suscríbete al Feed RSS</h5>
            <p class="text-xs text-slate-500 mb-4">Recibe automáticamente nuestras guías para optimizar tu negocio.</p>
            <a href="{{ route('blog.feed') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-colors">
              <span>Abrir RSS Feed</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
          </div>

        </aside>

      </div>

      <!-- Related Posts Section -->
      @if(!empty($relatedPosts))
        <section class="mt-16 pt-12 border-t border-slate-200">
          <h3 class="font-display font-black text-2xl text-slate-900 mb-6">Artículos Recomendados</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $rel)
              <article class="bg-white rounded-2xl p-5 border border-slate-200 hover:border-lime-300 transition-all shadow-sm hover:shadow-md flex flex-col justify-between">
                <div>
                  <span class="text-[10px] font-bold text-emerald-600 uppercase">{{ $rel['category'] }}</span>
                  <h4 class="font-bold text-sm text-slate-900 mt-1 line-clamp-2">
                    <a href="{{ route('blog.show', $rel['slug']) }}" class="hover:text-emerald-600 transition-colors">{{ $rel['title'] }}</a>
                  </h4>
                  <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $rel['excerpt'] }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                  <span>{{ $rel['read_time'] }} min lectura</span>
                  <a href="{{ route('blog.show', $rel['slug']) }}" class="font-bold text-emerald-600 hover:text-brand-darklime">Leer →</a>
                </div>
              </article>
            @endforeach
          </div>
        </section>
      @endif

    </div>
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

  <!-- Scripts -->
  <script>
    // Reading Progress Indicator
    window.addEventListener('scroll', () => {
      const winScroll = document.documentElement.scrollTop || document.body.scrollTop;
      const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrolled = (winScroll / height) * 100;
      const bar = document.getElementById('readingProgress');
      if (bar) bar.style.width = scrolled + '%';
    });

    // Copy Link Helper
    function copyArticleUrl() {
      navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.getElementById('btnCopyLink');
        if (btn) {
          const original = btn.innerHTML;
          btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
          setTimeout(() => { btn.innerHTML = original; }, 2000);
        }
      });
    }
  </script>
</body>
</html>
