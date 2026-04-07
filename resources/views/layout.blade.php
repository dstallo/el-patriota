<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ url('favicon.svg') }}" type="image/svg+xml"  />
    <link rel="icon" href="{{ url('favicon.ico') }}" sizes="32x32" />
    <link rel="apple-touch-icon" href="{{ url('apple-touch-icon.png') }}" />

    <meta name="description" content="@yield('bajada', config('app.description'))" />
    <meta name="keywords" content="{{ config('app.keywords') }}" />
    <meta name="author" content="{{ config('app.name') }}" />

    <meta property="og:image" content="@yield('imagen', url('favicon.png'))" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:title" content="@yield('titulo', config('app.name'))" />
    <meta property="og:description" content="@yield('bajada', config('app.description'))" />

    <meta property="twitter:creator" content="{{ config('app.name') }}" />
    <meta property="twitter:title" content="@yield('titulo', config('app.name'))" />
    <meta property="twitter:description" content="@yield('bajada', config('app.description'))" />
    <meta property="twitter:card" content="summary_large_image" />

    <title>@yield('titulo', config('app.name'))</title>

    <link href="{{ mix('css/front.css') }}" rel="stylesheet">

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <script src="{{ mix('js/front.js') }}"></script>

    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=647a16c65380390019972120&product=sop' async='async'></script>

    <link rel="stylesheet" href="/js/lib/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen">
    <script type="text/javascript" src="/js/lib/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>

    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ url('clima/css/owfont-regular.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('js/lib/slick/slick.css') }}">
    <script src="{{ url('js/lib/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>

    @if (config('app.env') == 'production')
        <!-- Google tag (gtag.js) -->
    @endif

    @yield('script.header')
</head>

<body>
    @include('flasher.flasher')
    <div id="general">
        <div class="menu-lateral">
            <div class="contenido">
                <div class="logo">
                    <h2></h2>
                    <a href="#cerrar" data-cerrar-menu-lateral>X</a>
                </div>
                <ul class="secciones">
                    <li>
                        <h3>NOTICIAS</h3>
                    </li>
                    @foreach (App\Seccion::front() as $seccion)
                        <li class="sub"><a href="{{ $seccion->link() }}">{{ $seccion->nombre }}</a></li>
                    @endforeach
                </ul>
                <ul class="regiones">
                    <li><a href="/entrevistas">ENTREVISTAS</a></li>
                    @foreach ($regiones = App\Region::front() as $region)
                        <li><a href="{{ $region->link() }}">{{ $region->nombre }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <header>
            <div class="arriba">
                <div class="contenedor">
                    <div class="col gap-1">
                        <a class="menu-secciones" href="#desplegar-menu" data-abrir-menu-lateral>Secciones</a>
                        <div class="buscar">
                            <form method="GET" action="{{ url('/') }}">
                                <input type="text" name="buscar" value="{{ Request::get('buscar') }}"
                                    placeholder="BUSCAR">
                            </form>
                        </div>
                    </div>
                    <div class="entresub">
                        <a href="/entrevistas" class="entrevistas">ENTREVISTAS</a>
                    </div>
                    <div class="col derecha gap-1">
                        <div class="fecha">
                            <div>{{ date('d/m/Y') }}</div>
                            <div class="clima"></div>
                        </div>
                        <div id="newsletter" class="newsletter">
                            <a href="" class="suscribite">SUSCRIBITE</a>
                            <form autocomplete="off" data-formulario="{{ url('ajax/newsletter') }}"
                                data-ok="cerrarSlideNewsletter()">
                                {{ csrf_field() }}
                                <input type="hidden" name="exito_titulo" value="Suscripto!">
                                <input type="hidden" name="exito_texto"
                                    value="Te suscribiste exitosamente a nuestro newsletter.">
                                <input type="hidden" name="error_titulo" value="Error">
                                <input type="hidden" name="error_texto"
                                    value="Ocurrió un error con tu suscripción, por favor intenta de nuevo en unos minutos.">
                                <input type="text" name="nombre" placeholder="nombre" autocomplete="off"
                                    required>
                                <input type="email" name="email" placeholder="email" autocomplete="off"
                                    required>
                                <button type="submit">ENVIAR</button>
                            </form>
                        </div>
                        <div class="redes">
                            <?php /*
                            <a class="whatsapp" target="_blank" href="#"><i></i></a>
                            <a class="telegram" target="_blank" href="#"><i></i></a> */ ?>
                            <a class="x" target="_blank" href="#"><i></i></a>
                            <a class="instagram" target="_blank" href="#"><i></i></a>
                            <?php /*<a class="tiktok" target="_blank" href="#"><i></i></a> */ ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="abajo">
                <div class="contenedor">
                    <div class="logo"><a href="{{ url('/') }}"><h1>Info al Sur</h1></a></div>
                    <ul class="menu">
                        @foreach ($regiones as $region)
                            <li><a href="{{ $region->link() }}">{{ $region->nombre }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </header>


        @yield('contenido')

        <footer>
            <div class="arriba">
                <div class="contenedor">
                    <div class="col">
                        <a href="{{ url('/') }}" class="logo">
                            <img src="{{ url('img/logo-footer.svg') }}" alt="{{ config('config.app') }}">
                        </a>
                    </div>
                    <div class="col">
                        <div class="info">
                            <p>
                            @if (config('app.email_notificacion_consulta'))
                                <a href="mailto:{{ config('app.email_notificacion_consulta') }}">{{ config('app.email_notificacion_consulta') }}</a><br>
                            @endif
                                <strong>Edición nº {{ ceil(abs(strtotime(date('Y-m-d')) - strtotime(config('app.fecha_inicio_edicion'))) / 86400) + 1 }}</strong>
                            </p>
                        </div>
                        <div class="redes">
                        <?php /* <a class="whatsapp" target="_blank" href="#"><i></i></a>
                            <a class="telegram" target="_blank" href="#"><i></i></a> */?>
                            <a class="x" target="_blank" href="#"><i></i></a>
                            <a class="instagram" target="_blank" href="#"><i></i></a>
                        <?php /* <a class="tiktok" target="_blank" href="#"><i></i></a> */ ?>
                        </div>
                    </div>

                    <div class="col">
                        <div class="newsletter">
                            <h3>Newsletter</h3>
                            <p>Quérés recibir toda la info en tu mail? Suscribite.</p>
                            <a href="#" class="suscribite">SUSCRIBITE</a>
                            <form autocomplete="off" data-formulario="{{ url('ajax/newsletter') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="exito_titulo" value="Suscripto!">
                                <input type="hidden" name="exito_texto"
                                    value="Te suscribiste exitosamente a nuestro newsletter.">
                                <input type="hidden" name="error_titulo" value="Error">
                                <input type="hidden" name="error_texto"
                                    value="Ocurrió un error con tu suscripción, por favor intenta de nuevo en unos minutos.">
                                <input type="text" name="nombre" placeholder="nombre" autocomplete="off" required>
                                <input type="email" name="email" placeholder="tu mail acá" autocomplete="off"
                                    required>
                                <button type="submit">SUSCRIBITE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sub">
                <p><?php /* Editor responsable: Juan Carlos Muestra / */ ?>Copyright todos los derechos reservados
                    {{ date('Y') }}</p>
            </div>
        </footer>


    </div>
    @yield('script.body')
</body>

</html>
