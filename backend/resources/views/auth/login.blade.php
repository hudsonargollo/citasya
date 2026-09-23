@extends('layouts.auth.default')

@section('content')
<div class="w-full">
    <!-- Title Section -->
    <div class="text-center mb-6">
        <h1 class="font-display font-black text-2xl sm:text-3xl text-slate-900 tracking-tight leading-snug">
            ¡Hola de nuevo!
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm mt-1">
            {{ __('auth.login_title') }}
        </p>
    </div>

    <!-- Login Form -->
    <form action="{{ url('/login') }}" method="post" class="space-y-4">
        {!! csrf_field() !!}

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                {{ __('auth.email') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-envelope text-sm"></i>
                </div>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    placeholder="ejemplo@correo.com"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-lime-300 transition-all {{ $errors->has('email') ? 'border-rose-500 ring-1 ring-rose-300' : '' }}"
                >
            </div>
            @if ($errors->has('email'))
                <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('email') }}
                </p>
            @endif
        </div>

        <!-- Password Field -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    {{ __('auth.password') }}
                </label>
                <a href="{{ url('/password/reset') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 hover:underline transition">
                    {{ __('auth.forgot_password') }}
                </a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="••••••••"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-lime-300 transition-all {{ $errors->has('password') ? 'border-rose-500 ring-1 ring-rose-300' : '' }}"
                >
            </div>
            @if ($errors->has('password'))
                <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first('password') }}
                </p>
            @endif
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1">
            <label class="inline-flex items-center gap-2.5 cursor-pointer group">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember"
                    class="w-4 h-4 rounded border-slate-300 text-brand-cruz focus:ring-brand-lime transition cursor-pointer"
                >
                <span class="text-xs font-medium text-slate-600 group-hover:text-slate-900 transition">
                    {{ __('auth.remember_me') }}
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full py-3.5 sm:py-4 px-6 rounded-full bg-slate-900 text-[#A3E635] font-display font-extrabold text-sm sm:text-base hover:bg-slate-950 shadow-xl shadow-slate-900/15 transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 mt-4 cursor-pointer"
        >
            <span>{{ __('auth.login') }}</span>
            <i class="fas fa-arrow-right text-xs"></i>
        </button>
    </form>

    <!-- Social Logins (Google / Twitter) -->
    @if(setting('enable_google',false) || setting('enable_twitter',false))
        <div class="mt-6 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs uppercase font-extrabold tracking-widest text-slate-400 mb-4">
                - {{ __('lang.or') }} -
            </p>
            <div class="space-y-2">
                @if(setting('enable_google',false))
                    <a href="{{ url('login/google') }}" class="w-full py-3 px-4 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-all shadow-sm flex items-center justify-center gap-2">
                        <i class="fab fa-google text-red-500"></i>
                        <span>{{ __('auth.login_google') }}</span>
                    </a>
                @endif
                @if(setting('enable_twitter',false))
                    <a href="{{ url('login/twitter') }}" class="w-full py-3 px-4 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-all shadow-sm flex items-center justify-center gap-2">
                        <i class="fab fa-twitter text-sky-500"></i>
                        <span>{{ __('auth.login_twitter') }}</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Signup CTA Footer link -->
    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
        <p class="text-xs text-slate-500 font-medium">
            ¿Aún no tienes una cuenta registrada?
        </p>
        <a href="{{ url('/register') }}" class="mt-2 inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-4 py-2 rounded-full hover:bg-emerald-100 hover:border-emerald-300 transition-all">
            <i class="fas fa-user-plus text-xs text-brand-cruz"></i>
            <span>{{ __('auth.register_new_member') }}</span>
        </a>
    </div>
</div>
@endsection
