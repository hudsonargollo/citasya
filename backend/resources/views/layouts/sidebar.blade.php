<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-{{setting('theme_contrast')}}-{{setting('theme_color')}} shadow">
    <!-- Brand Logo -->
    <a href="{{url('dashboard')}}" class="brand-link border-bottom-0">
        <img src="{{asset('favicon.svg?v=20260925')}}" alt="{{setting('app_name', 'CitasYa')}}" class="brand-image img-circle elevation-2" style="background: white; padding: 2px;">
        <span class="brand-text">{{setting('app_name', 'CitasYa')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu',['icons'=>true])
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
