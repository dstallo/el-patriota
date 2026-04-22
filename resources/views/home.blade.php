@extends('layout')

@section('script.header')
    <script type="text/javascript">
    $(document).ready(function(){
        $('.leidas li h3').on('click', function(e){
            $(this).siblings('.cover').click();
        })
    })
    </script>
@endsection

@section('contenido')

    <div class="contenedor listado">


        @if ($banner = $banners['horizontales']->shift())
            <div class="banner horizontal">
                <a href="{{ $banner->linkContador() }}" target="_blank"><img src="{{ $banner->url('imagen') }}" alt="" /></a>
            </div>
        @endif

        <?php $primera = $partes['principales']->shift(); ?>
        @if ($primera)
            <div class="principales">
                <div class="primera">
                    <?php $noticia = $primera; ?>
                    @include('_noticia')
                </div>
            @php
                $segundas = $partes['principales']->shift(2);
            @endphp
            @if (count($segundas ?? []))
                <div class="segundas">
                    <div class="contenido">
                    @php $noticia = $segundas->shift() @endphp
                        <div class="noticia">
                            <h2>{{ $noticia->titulo_h }}</h2>
                            <a href="{{ $noticia->link() }}" target="_blank" class="cover"></a>
                        </div>
                    @php $noticia = $segundas->shift() @endphp
                    @if ($noticia)
                        <hr  />
                        <div class="noticia tercera">
                            <div>
                                <h2>{{ $noticia->titulo_h }}</h2>
                                <div class="bajada">{!! $noticia->bajada !!}</div>
                            </div>
                            <a href="{{ $noticia->link() }}" class="cover" target="_blank"></a>
                        </div>
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
            <div class="banner horizontal">
                <a href="{{ $banner->linkContador() }}" target="_blank"><img src="{{ $banner->url('imagen') }}"></a>
            </div>
        @endif

        <div class="columnas">
            <div class="columna-noticias">
                <div class="secundarias">
                    @foreach ($partes['secundarias'] as $noticia)
                        @include('_noticia')
                    @endforeach
                </div>

                @if ($banner = $banners['horizontales']->shift())
                    <div class="banner horizontal">
                        <a href="{{ $banner->linkContador() }}" target="_blank"><img
                                src="{{ $banner->url('imagen') }}"></a>
                    </div>
                @endif

                <div class="terciarias">
                @foreach ($partes['terciarias'] as $noticia)
                    <a href="{{ $noticia->link() }}" class="noticia">{!! $noticia->titulo_h !!}</a>
                @endforeach
                </div>

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
                    <div class="banner horizontal">
                        <a href="{{ $banner->linkContador() }}" target="_blank"><img
                                src="{{ $banner->url('imagen') }}"></a>
                    </div>
                @endif
                <div class="cuaternarias">
                    @foreach ($partes['cuaternarias'] as $noticia)
                        @include('_noticia')
                    @endforeach
                </div>
                @if ($banner = $banners['horizontales']->shift())
                    <div class="banner horizontal">
                        <a href="{{ $banner->linkContador() }}" target="_blank"><img
                                src="{{ $banner->url('imagen') }}"></a>
                    </div>
                @endif
            </div>
            <div class="columna-banners">
                @forelse($banners['laterales'] as $banner)
                    <div class="banner lateral">
                        <a href="{{ $banner->linkContador() }}" target="_blank"><img
                                src="{{ $banner->url('imagen') }}"></a>
                    </div>
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
                <img src="{{ $popup->url('imagen') }}">
            @endif
        </div>
    @endif
    @if (($popup ?? null) && $popup->tiene('imagen_vertical'))
        <a href="#popup_general_vertical" data-lity data-auto-abrir-popup></a>
        <div class="popup lity-hide" id="popup_general_vertical">
            @if ($popup->link)
                <a href="{{ $popup->link }}" target="_blank"><img src="{{ $popup->url('imagen_vertical') }}"></a>
            @else
                <img src="{{ $popup->url('imagen_vertical') }}">
            @endif
        </div>
    @endif

@endsection
