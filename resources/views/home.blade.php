@extends('layout')

@section('script.header')
@endsection

@section('contenido')

    <div class="contenedor listado">


        @if ($banner = $banners['horizontales']->shift())
            <div class="banner horizontal">
                <a href="{{ $banner->linkContador() }}" target="_blank"><img src="{{ $banner->url('imagen') }}"></a>
            </div>
        @endif

        <?php /*
	<div class="banner horizontal">
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6107105686338244"
		     crossorigin="anonymous"></script>
		<!-- Banner Horizontal -->
		<ins class="adsbygoogle"
		     style="display:block"
		     data-ad-client="ca-pub-6107105686338244"
		     data-ad-slot="1971376428"
		     data-ad-format="auto"
		     data-full-width-responsive="true"></ins>
		<script>
		     (adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</div>
	*/
        ?>

        <?php $primera = $partes['principales']->shift(); ?>
        @if ($primera)
            <div class="principales">
                <div class="primera">
                    <?php $noticia = $primera; ?>
                    @include('_noticia')
                </div>
            </div>
        @endif

    </div>
    @if (count($destacadas))
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

                @if (count($leidas))
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

                <div class="terciarias">
                    @foreach ($partes['terciarias_1'] as $noticia)
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
                    @foreach ($partes['terciarias_2'] as $noticia)
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
