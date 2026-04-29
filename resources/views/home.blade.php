@extends('layout')

@section('script.header')
    <script>
        $(document).ready(function(){
            $('.listado .leidas ul li h3').on('click', function(){
                window.location.href = $(this).siblings('.cover').prop('href');
            });
        })
    </script>
@endsection

@section('contenido')

    <div class="contenedor listado">

        @if ($banner = $banners['horizontales']->shift())
            <x-banner class="banner horizontal" :banner="$banner" />
        @endif

        <?php $primera = $partes['principales']->shift(); ?>
        @if ($primera)
            <div class="principales">
                <div class="primera">
                    <x-noticia :noticia="$primera" />
                </div>
            @php
                $segundas = $partes['principales']->shift(2);
            @endphp
            @if (count($segundas ?? []))
                <div class="segundas">
                    <div class="contenido">
                    @php $noticia = $segundas->shift() @endphp
                        <x-noticia :noticia="$noticia" type="reducida" />
                    @php $noticia = $segundas->shift() @endphp
                    @if ($noticia)
                        <hr  />
                        <x-noticia :noticia="$noticia" type="reducida" class="tercera" />
                    @endif
                    </div>
                </div>
            @endif
            </div>
        @endif

    </div>
    @if (count($destacadas ?? []))
        <section class="destacadas">
            @foreach ($destacadas as $destacada)
                @continue(!$destacada->tiene('banner'))
                <div class="destacada">
                    <div class="imagen" style="background-image:url({{ $destacada->url('banner') }});"></div>
                    <div class="imagen-vertical" style="background-image:url({{ $destacada->getVertical() }});"></div>
                    <div class="info">
                        <div class="cuerpo">
                            <div class="volanta">{{ $destacada->volanta }}</div>
                            <h2>{!! $destacada->titulo_h !!}</h2>
                            <div class="bajada texto">{!! $destacada->bajada !!}</div>
                            @if ($destacada->autor)
                                <div class="autor">Por {{ $destacada->autor }}</div>
                            @endif
                        </div>
                    </div>
                    <a class="cover" href="{{ $destacada->link() }}" target="_blank"></a>
                </div>
            @endforeach
        </section>
    @endif
    <div class="contenedor listado">

        @if ($banner = $banners['horizontales']->shift())
            <x-banner class="banner horizontal" :banner="$banner" />
        @endif

        <div class="columnas">
            <div class="columna-noticias">
                <div class="secundarias">
                    @foreach ($partes['secundarias'] as $noticia)
                        <x-noticia :noticia="$noticia" />
                    @endforeach
                </div>
                
                @if ($banner = $banners['horizontales']->shift())
                    <x-banner class="banner horizontal" :banner="$banner" />
                @endif

                @if (count($noticias_grupo ?? [])>0)
                    <div class="grupo {{ count($noticias_grupo) > 3 ? 'con-slides' : 'sin-slides' }}">
                        <div class="contenido">
                            <div class="header">
                                <h2>{{ $grupo?->valor }}</h2>
                            @if (count($noticias_grupo)>3)
                                <div class="arrows">
                                    <a href="#" class="prev">&lt;</a>
                                    <a href="#" class="next">&gt;</a>
                                </div>
                            @endif
                            </div>
                            <div class="slides">
                            @foreach($noticias_grupo as $noticia)
                                <div class="slide"><x-noticia :noticia="$noticia" /></div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    @if ($banner = $banners['horizontales']->shift())
                        <x-banner class="banner horizontal" :banner="$banner" />
                    @endif
                @endif
                <?php/* AGREGAR AQUÍ EL GRUPO DE NOTICIAS (con su respectivo banner horizontal por debajo) */?>

                @if (count($leidas ?? []))
                    <div class="leidas">
                        <div class="contenido">
                            <h2>LAS MÁS LEÍDAS</h2>
                            <ul>
                            @foreach ($leidas as $leida)
                                <li>
                                    <h3>{!! $leida->titulo_h !!}</h3>
                                    <a class="cover" href="{{ $leida->link() }}"></a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($banner = $banners['horizontales']->shift())
                    <x-banner class="banner horizontal" :banner="$banner" />
                @endif
                <div class="terciarias">
                    @foreach ($partes['terciarias'] as $noticia)
                        <x-noticia :noticia="$noticia" />
                    @endforeach
                </div>
                @while ($banner = $banners['horizontales']->shift())
                    <x-banner class="banner horizontal" :banner="$banner" />
                @endwhile
            </div>
            <div class="columna-banners">
                @forelse($banners['laterales'] as $banner)
                    <x-banner class="banner lateral" :banner="$banner" />
                    @if ($loop->iteration == 2 || ($loop->iteration < 2 && $loop->last))
                        @include('_encuesta')
                    @endif
                @empty
                    @include('_encuesta')
                @endforelse
            </div>
        </div>
    </div>

    @if (($popup ?? null) && $popup->tiene('imagen'))
        <a href="#popup_general" data-lity data-auto-abrir-popup="{{ $popup->tiene('imagen_vertical') ? '600' : '' }}"></a>
        <div class="popup lity-hide" id="popup_general">
            @if ($popup->link)
                <a href="{{ $popup->link }}" target="_blank"><img src="{{ $popup->url('imagen') }}"></a>
            @else
                <img src="{{ $popup->url('imagen') }}" alt="" />
            @endif
        </div>
    @endif
    @if (($popup ?? null) && $popup->tiene('imagen_vertical'))
        <a href="#popup_general_vertical" data-lity data-auto-abrir-popup></a>
        <div class="popup lity-hide" id="popup_general_vertical">
            @if ($popup->link)
                <a href="{{ $popup->link }}" target="_blank"><img src="{{ $popup->url('imagen_vertical') }}"></a>
            @else
                <img src="{{ $popup->url('imagen_vertical') }}" alt="">
            @endif
        </div>
    @endif

@endsection
