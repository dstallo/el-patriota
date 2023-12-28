<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ url('img/logo.png') }}">

    <meta name="description" content="Info al Sur">
    <meta name="keywords" content="noticias,periodismo,actualidad,política,buenos,aires,lanús,avellaneda">
    <meta name="author" content="Info al Sur">

    <meta property="og:image" content="@yield('imagen', url('img/logo-fb.png'))">
    <meta property="og:site_name" content="Info al Sur">
    <meta property="og:title" content="@yield('titulo', 'Info al Sur')">
    <meta property="og:description" content="@yield('bajada', 'Info al Sur')">

    <meta property="twitter:creator" content="Info al Sur">
    <meta property="twitter:title" content="@yield('titulo', 'Info al Sur')">
    <meta property="twitter:description" content="@yield('bajada', 'Info al Sur')">
    <meta property="twitter:card" content="summary_large_image" />
    <?php /*
    <meta property="twitter:site" content="@entre_noticias" />
    <meta property="twitter:creator" content="@entre_noticias" />
    */
    ?>

    <title>@yield('titulo', 'Info al Sur')</title>

    <link href="{{ mix('css/front.css') }}" rel="stylesheet">

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <script src="{{ mix('js/front.js') }}"></script>

    <script type='text/javascript'
        src='https://platform-api.sharethis.com/js/sharethis.js#property=647a16c65380390019972120&product=sop'
        async='async'></script>

    <link rel="stylesheet" href="/js/lib/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen">
    <script type="text/javascript" src="/js/lib/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>

    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

    <link rel="stylesheet" type="text/css" href="{{ url('clima/css/owfont-regular.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('js/lib/slick/slick.css') }}">
    <script src="{{ url('js/lib/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>

    @if (config('app.env') == 'production')
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
                    <div class="col">
                        <a class="menu-secciones" href="#desplegar-menu" data-abrir-menu-lateral>Secciones</a>
                        <div class="buscar">
                            <form method="GET" action="{{ url('/') }}">
                                <input type="text" name="buscar" value="{{ Request::get('buscar') }}"
                                    placeholder="BUSCAR">
                            </form>
                        </div>
                    </div>
                    <div class="logo"><a href="{{ url('/') }}">
                            <h1>Info al Sur</h1>
                        </a></div>
                    <div class="col derecha">
                        <div class="fecha">
                            <div>{{ date('d/m/Y') }}</div>
                            <div class="clima"></div>
                        </div>
                        <div class="redes">
                            <a class="x" target="_blank" href="https://twitter.com/infoalsurok"><i></i></a>
                            <a class="instagram" target="_blank" href="instagram.com/infoalsurok/"><i></i></a>
                            <a class="tiktok" target="_blank" href="https://www.tiktok.com/@infoalsurok"><i></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="abajo">
                <div class="contenedor">
                    <div class="col"></div>
                    <ul class="menu">
                        @foreach ($regiones as $region)
                            <li><a href="{{ $region->link() }}">{{ $region->nombre }}</a></li>
                        @endforeach
                    </ul>
                    <div class="col derecha">
                        <div class="entresub">
                            <a href="/entrevistas" class="entrevistas">ENTREVISTAS</a>
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
                        </div>
                    </div>
                </div>
            </div>
        </header>


        @yield('contenido')

        <footer>
            <div class="contenedor">
                <div class="col">
                    <a href="{{ url('/') }}" class="logo">
                        <img src="{{ url('img/logo-c.svg') }}" alt="Info al Sur">
                    </a>
                </div>
                <div class="col">
                    <div class="info">
                        <p>
                            <a href="mailto:contacto@infoalsur.com.ar">contacto@infoalsur.com.ar</a><br>
                            <strong>Edición nº
                                {{ ceil(abs(strtotime(date('Y-m-d')) - strtotime(config('app.fecha_inicio_edicion'))) / 86400) + 1 }}</strong>
                        </p>
                    </div>
                    <div class="redes">
                        <a class="x" target="_blank" href="https://twitter.com/infoalsurok"><i></i></a>
                        <a class="instagram" target="_blank" href="instagram.com/infoalsurok/"><i></i></a>
                        <a class="tiktok" target="_blank" href="https://www.tiktok.com/@infoalsurok"><i></i></a>
                    </div>
                </div>

                <div class="col">
                    <div class="newsletter">
                        <h3>Newsletter</h3>
                        <p>Quérés recibir toda la info en tu mail? Suscribite.</p>
                        <a href="">SUSCRIBITE</a>
                    </div>
                </div>
            </div>
            <div class="sub">
                <p>Editor responsable: Juan Carlos Muestra / Copyright todos los derechos reservados
                    {{ date('Y') }}</p>
            </div>
        </footer>


    </div>
    @yield('script.body')
</body>

</html>
