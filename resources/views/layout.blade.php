<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ url('img/logo.png') }}">

    <meta name="description" content="Todo lo que tenés que saber está en entrenoticias.com.ar. Encontrá la actualidad política del país, la Provincia y la Ciudad de Buenos Aires. Entre noticias: así vivimos.">
    <meta name="keywords" content="noticias,periodismo,actualidad,política,buenos,aires,entre,noticias">
    <meta name="author" content="Entre Noticias">
       
    <meta property="og:image" content="@yield('imagen', url('img/icono.png'))">
    <meta property="og:site_name" content="Entre Noticias">
    <meta property="og:title" content="@yield('titulo', 'Entre Noticias')">
    <meta property="og:description" content="@yield('bajada', 'Todo lo que tenés que saber está en entrenoticias.com.ar. Encontrá la actualidad política del país, la Provincia y la Ciudad de Buenos Aires. Entre noticias: así vivimos.')">

    <meta property="twitter:creator" content="Entre Noticias">
    <meta property="twitter:title" content="@yield('titulo', 'Entre Noticias')">
    <meta property="twitter:description" content="@yield('bajada', 'Todo lo que tenés que saber está en entrenoticias.com.ar. Encontrá la actualidad política del país, la Provincia y la Ciudad de Buenos Aires. Entre noticias: así vivimos.')">
    <meta property="twitter:card" content="summary_large_image" />
    <?php /*
    <meta property="twitter:site" content="@entre_noticias" />
    <meta property="twitter:creator" content="@entre_noticias" />
    */ ?>

    <title>@yield('titulo', 'Entre Noticias')</title>

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

    <link rel="stylesheet" type="text/css" href="{{ url('clima/css/owfont-regular.min.css') }}">

    <?php /*
    <script type="text/javascript" src="/js/lib/cycle2/jquery.cycle2.min.js"></script>
    <script type="text/javascript" src="/js/lib/cycle2/jquery.cycle2.center.min.js"></script>
    */ ?>

    @if(config('app.env') == 'production')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-9QH4KEQ619"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-9QH4KEQ619');
        </script>
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
                    @foreach(App\Seccion::front() as $seccion)
                        <li><a href="{{ $seccion->link() }}">{{ $seccion->nombre }}</a></li>
                    @endforeach
                </ul>
                <ul class="regiones">
                    @foreach(App\Region::front() as $region)
                        <li><a href="{{ $region->link() }}">{{ $region->nombre }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <header>
            <div class="arriba">
                <div class="contenedor">
                    <div class="col">
                        <a class="menu-secciones" href="#desplegar-menu"  data-abrir-menu-lateral>Secciones</a>
                        <div class="buscar">
                            <form method="GET" action="{{ url('/') }}">
                                <input type="text" name="buscar" value="{{ Request::get('buscar') }}" placeholder="BUSCAR">
                            </form>
                        </div>
                    </div>
                    <div class="logo"><a href="{{ url('/') }}"><h1>Entre Noticias</h1></a></div>
                    <div class="col">
                        <div class="fecha">
                            <div>{{ date('d/m/Y') }}</div>
                            <div class="clima"></div>
                        </div>
                        <div class="redes">
                            <a class="facebook" target="_blank" href="https://www.facebook.com/profile.php?id=100088812571474"><i></i></a>
                            <a class="twitter" target="_blank" href="https://twitter.com/entrenoticiasok"><i></i></a>
                            <a class="instagram" target="_blank" href="https://www.instagram.com/entrenoticiasok"><i></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        
        @yield('contenido')

        <footer>
            <div class="contenedor">

                <div class="col">
                    <div class="redes">
                        <a class="facebook" target="_blank" href="https://www.facebook.com/profile.php?id=100088812571474"><i></i></a>
                        <a class="twitter" target="_blank" href="https://twitter.com/entrenoticiasok"><i></i></a>
                        <a class="instagram" target="_blank" href="https://www.instagram.com/entrenoticiasok"><i></i></a>
                    </div>
                    <div class="info">
                        <p>
                            <a href="mailto:info@entrenoticias.com.ar">info@entrenoticias.com.ar</a><br>
                            <strong>Edición nº {{ ceil(abs(strtotime(date('Y-m-d')) - strtotime(config('app.fecha_inicio_edicion'))) / 86400) + 1 }}</strong>
                        </p>
                    </div>
                </div>

                <div class="col">
                    <a class="logo" href="{{ url('/') }}"></a>
                </div>
                
                <div class="col">
                    <div id="newsletter" class="newsletter">
                        <h3>Newsletter</h3>
                        <p>Quérés recibir toda la infoen tu mail? Suscribite.</p>
                        <form autocomplete="off" data-formulario="{{ url('ajax/newsletter') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="exito_titulo" value="Suscripto!">
                            <input type="hidden" name="exito_texto" value="Te suscribiste exitosamente a nuestro newsletter.">
                            <input type="hidden" name="error_titulo" value="Error">
                            <input type="hidden" name="error_texto" value="Ocurrió un error con tu suscripción, por favor intenta de nuevo en unos minutos.">
                            <input type="email" name="email" placeholder="tu mail acá" autocomplete="off" required>
                            <button type="submit"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="sub">
                <p><?php /* Editor responsable: Gastón Siseles / */ ?>Copyright todos los derechos reservados {{ date('Y') }}</p>
            </div>
        </footer>

        
    </div>
    @yield('script.body')
</body>
</html>