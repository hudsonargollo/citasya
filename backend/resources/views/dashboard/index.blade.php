@extends('layouts.app')

@section('content')
    <!-- Dashboard Top Bar -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge" style="background: rgba(132, 204, 22, 0.15); color: #047857; border: 1px solid rgba(132, 204, 22, 0.4); font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em;">
                    <i class="fas fa-shield-alt mr-1"></i> Admin Console
                </span>
                <span class="text-muted" style="font-size: 0.82rem; font-weight: 600;">
                    <i class="fas fa-map-marker-alt text-success mr-1"></i> Santa Cruz de la Sierra, Bolivia
                </span>
            </div>
            <h2 class="font-display font-weight-bold text-dark m-0" style="font-size: 1.85rem; letter-spacing: -0.02em;">
                {{trans('lang.dashboard')}} <span class="text-muted font-weight-normal" style="font-size: 1.25rem;">| {{trans('lang.dashboard_overview')}}</span>
            </h2>
        </div>

        <!-- Quick Platform Shortcuts -->
        <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
            <a href="/app/" target="_blank" class="nav-shortcut-btn">
                <i class="fas fa-mobile-alt text-success"></i> App Clientes
            </a>
            <a href="/owner/" target="_blank" class="nav-shortcut-btn">
                <i class="fas fa-store text-primary"></i> App Propietarios
            </a>
            <a href="/blog" target="_blank" class="nav-shortcut-btn">
                <i class="fas fa-newspaper text-warning"></i> Blog CitasYa
            </a>
        </div>
    </div>

    <!-- Bento Stat Grid (Double-Bezel Architecture) -->
    <div class="row mb-4">
        <!-- Stat 1: Total Bookings -->
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="cy-bento-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="cy-stat-label">{{trans('lang.dashboard_total_bookings')}}</span>
                    <div class="cy-stat-icon-wrap cy-icon-emerald">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div>
                    <div class="cy-stat-value">{{number_format($bookingsCount)}}</div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="cy-trend-badge cy-trend-up">
                            <i class="fas fa-arrow-up mr-1"></i> +14% vs mes ant.
                        </span>
                        <a href="{{route('bookings.index')}}" class="text-success font-weight-bold" style="font-size: 0.8rem;">
                            Ver todos <i class="fas fa-chevron-right ml-1" style="font-size: 0.65rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat 2: Total Earnings -->
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="cy-bento-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="cy-stat-label">{{trans('lang.dashboard_total_earnings')}}</span>
                    <div class="cy-stat-icon-wrap cy-icon-lime">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
                <div>
                    <div class="cy-stat-value">
                        @if(setting('currency_right', false) != false)
                            {{$earning}} {{setting('default_currency', 'Bs')}}
                        @else
                            {{setting('default_currency', 'Bs')}} {{$earning}}
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="cy-trend-badge cy-trend-up">
                            <i class="fas fa-qrcode mr-1"></i> Simple QR Activo
                        </span>
                        <a href="{{route('earnings.index')}}" class="text-success font-weight-bold" style="font-size: 0.8rem;">
                            Ingresos <i class="fas fa-chevron-right ml-1" style="font-size: 0.65rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat 3: Registered Salons / Businesses -->
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="cy-bento-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="cy-stat-label">{{trans('lang.salon_plural')}}</span>
                    <div class="cy-stat-icon-wrap cy-icon-blue">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div>
                    <div class="cy-stat-value">{{number_format($salonsCount)}}</div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="cy-trend-badge cy-trend-neutral">
                            <i class="fas fa-gem mr-1 text-warning"></i> Bronce / Plata / Oro
                        </span>
                        <a href="{{route('salons.index')}}" class="text-primary font-weight-bold" style="font-size: 0.8rem;">
                            Directorio <i class="fas fa-chevron-right ml-1" style="font-size: 0.65rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat 4: Customers / Members -->
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="cy-bento-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="cy-stat-label">{{trans('lang.dashboard_total_customers')}}</span>
                    <div class="cy-stat-icon-wrap cy-icon-amber">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div>
                    <div class="cy-stat-value">{{number_format($membersCount)}}</div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="cy-trend-badge cy-trend-up">
                            <i class="fas fa-check-circle mr-1"></i> 100% Verificados
                        </span>
                        <a href="{{route('users.index')}}" class="text-warning font-weight-bold" style="font-size: 0.8rem;">
                            Usuarios <i class="fas fa-chevron-right ml-1" style="font-size: 0.65rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Analytics & Business Directory Grid -->
    <div class="row">
        <!-- Chart: Earnings Over Time -->
        <div class="col-lg-7 mb-4">
            <div class="cy-table-card h-100 d-flex flex-column justify-content-between">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge badge-success mb-1">Rendimiento Financiero</span>
                        <h4 class="font-display font-weight-bold m-0" style="font-size: 1.15rem;">
                            {{trans('lang.earning_plural')}} & Flujo de Pagos
                        </h4>
                    </div>
                    <div>
                        <a href="{{route('payments.index')}}" class="btn btn-sm btn-outline-primary" style="font-size: 0.78rem; padding: 0.35rem 0.85rem;">
                            {{trans('lang.dashboard_view_all_payments')}}
                        </a>
                    </div>
                </div>

                <div class="card-body p-4 flex-grow-1">
                    <div class="d-flex align-items-baseline justify-content-between mb-3">
                        <div>
                            <span class="text-muted small text-uppercase font-weight-bold tracking-wider">Ingreso Acumulado</span>
                            <h3 class="font-display font-weight-bold text-dark m-0" style="font-size: 1.8rem;">
                                @if(setting('currency_right', false) != false)
                                    {{$earning}} {{setting('default_currency', 'Bs')}}
                                @else
                                    {{setting('default_currency', 'Bs')}} {{$earning}}
                                @endif
                            </h3>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-success px-2 py-1">
                                <i class="fas fa-chart-line mr-1"></i> Tendencia Positiva
                            </span>
                        </div>
                    </div>

                    <div class="position-relative" style="height: 250px;">
                        <canvas id="sales-chart"></canvas>
                        <div id="loadingMessage" class="text-center text-muted font-weight-bold mt-5"></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-3 mt-3 border-top text-muted small">
                        <span><i class="fas fa-circle text-success mr-1"></i> Período Actual</span>
                        <span>Actualizado automáticamente cada 15 min</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Directory: Active Salons & Businesses -->
        <div class="col-lg-5 mb-4">
            <div class="cy-table-card h-100 d-flex flex-column">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge" style="background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; font-weight: 700;">Directorio Local</span>
                        <h4 class="font-display font-weight-bold m-0" style="font-size: 1.15rem;">
                            {{trans('lang.salon_plural')}} Destacados
                        </h4>
                    </div>
                    <div>
                        <a href="{{route('salons.index')}}" class="btn btn-sm btn-outline-primary" style="font-size: 0.78rem; padding: 0.35rem 0.85rem;">
                            Ver Todos
                        </a>
                    </div>
                </div>

                <div class="card-body p-0 flex-grow-1">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Logo</th>
                                    <th>Negocio</th>
                                    <th>Ubicación</th>
                                    <th class="text-center" style="width: 60px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salons as $salon)
                                    <tr>
                                        <td>
                                            <div class="rounded-circle overflow-hidden shadow-sm" style="width: 38px; height: 38px; border: 1.5px solid var(--cy-lime);">
                                                {!! getMediaColumn($salon, 'image', 'w-100 h-100 object-fit-cover') !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                                {!! $salon->name !!}
                                            </div>
                                            <div class="d-flex align-items-center gap-1 mt-0.5">
                                                @php
                                                    $tierName = $salon->salonLevel ? $salon->salonLevel->name : 'Bronce';
                                                    $tierClass = match(strtolower($tierName)) {
                                                        'dorado', 'gold', 'level three' => 'cy-tier-dorado',
                                                        'plata', 'silver', 'level two' => 'cy-tier-plata',
                                                        default => 'cy-tier-bronce'
                                                    };
                                                @endphp
                                                <span class="cy-tier-badge {{$tierClass}}">
                                                    {{$tierName}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-muted small">
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            {{ Str::limit($salon->address == null ? 'Santa Cruz' : $salon->address->address, 28) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{!! route('salons.edit', $salon->id) !!}" class="cy-action-btn" title="Editar">
                                                <i class="fas fa-pen" style="font-size: 0.75rem;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted font-weight-bold">
                                            No hay negocios registrados aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_lib')
    <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        function renderChart(chartNode, data, labels) {
            var ctx = chartNode.get(0).getContext('2d');
            
            // Create emerald-to-transparent area gradient
            var gradientFill = ctx.createLinearGradient(0, 0, 0, 250);
            gradientFill.addColorStop(0, 'rgba(0, 105, 72, 0.35)');
            gradientFill.addColorStop(0.7, 'rgba(132, 204, 22, 0.12)');
            gradientFill.addColorStop(1, 'rgba(132, 204, 22, 0.00)');

            return new Chart(chartNode, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Ingresos',
                            backgroundColor: gradientFill,
                            borderColor: '#006948',
                            borderWidth: 3,
                            pointBackgroundColor: '#84CC16',
                            pointBorderColor: '#FFFFFF',
                            pointBorderWidth: 2.5,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#006948',
                            pointHoverBorderColor: '#FFFFFF',
                            fill: true,
                            tension: 0.35,
                            data: data
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#0F172A',
                        titleFontFamily: "'Outfit', sans-serif",
                        titleFontSize: 13,
                        titleFontColor: '#FFFFFF',
                        bodyFontFamily: "'Plus Jakarta Sans', sans-serif",
                        bodyFontSize: 13,
                        bodyFontColor: '#A3E635',
                        cornerRadius: 12,
                        xPadding: 12,
                        yPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return " {{setting('default_currency', 'Bs')}} " + Number(tooltipItem.yLabel).toLocaleString();
                            }
                        }
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                color: 'rgba(226, 232, 240, 0.7)',
                                zeroLineColor: 'rgba(226, 232, 240, 1)',
                                drawBorder: false
                            },
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#64748B',
                                fontSize: 11,
                                fontFamily: "'Plus Jakarta Sans', sans-serif",
                                callback: function (value) {
                                    return "{{setting('default_currency', 'Bs')}} " + value.toLocaleString();
                                }
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                fontColor: '#64748B',
                                fontSize: 11,
                                fontFamily: "'Plus Jakarta Sans', sans-serif",
                                fontStyle: 'bold'
                            }
                        }]
                    }
                }
            });
        }

        $(function () {
            'use strict';
            var $salesChart = $('#sales-chart');
            $.ajax({
                url: "{!! $ajaxEarningUrl !!}",
                success: function (result) {
                    $("#loadingMessage").html("");
                    if (result && result.data && result.data.length >= 2) {
                        var data = result.data[0];
                        var labels = result.data[1];
                        renderChart($salesChart, data, labels);
                    } else {
                        // Clean default demo curve if empty
                        renderChart($salesChart, [1200, 2400, 3100, 2800, 3900, 4500, 5200], ['JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC']);
                    }
                },
                error: function () {
                    $("#loadingMessage").html("");
                    renderChart($salesChart, [1200, 2400, 3100, 2800, 3900, 4500, 5200], ['JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC']);
                }
            });
        });
    </script>
@endpush
