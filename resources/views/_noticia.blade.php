<div class="noticia">
    <div class="contenido">
        <div class="imagen">
            <div style="background-image:url({{ $noticia->url('thumbnail') }});"></div>
            <div class="categorias">
                {{ $noticia->region->nombre }} | {{ $noticia->seccion->nombre }}
            </div>
            @if ($noticia->con_video)
                <span class="contiene-video"></span>
            @endif
        </div>
        <div class="info">
            <div class="fecha">{{ $noticia->fecha_f }}</div>
            <div class="cuerpo">
                <div class="volanta">{{ $noticia->volanta }}</div>
                <h2>{!! $noticia->titulo_h !!}</h2>
                <div class="bajada texto">{!! $noticia->bajada !!}</div>
                @if ($noticia->autor)
                    <div class="autor">Por {{ $noticia->autor }}</div>
                @endif
            </div>
        </div>
        <a href="{{ $noticia->link() }}" class="cover"></a>
    </div>
</div>
@if (($encuesta = App\Encuesta::activa()) && !isset($GLOBALS['encuesta_responsive_mostrada']))
    <?php $GLOBALS['encuesta_responsive_mostrada'] = true; ?>
    <div class="bloque-encuesta-responsive">
        @include('_encuesta')
    </div>
@elseif($banner = $banners['responsive']->shift())
    <div class="banner-responsive">
        <a href="{{ $banner->linkContador() }}" target="_blank"><img src="{{ $banner->urlImagenResponsive() }}"></a>
    </div>
@endif
