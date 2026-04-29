<article>
    <div class="contenido">
        <div class="imagen">
            <div style="background-image:url({{ $video->url('tn') }});"></div>
            <span class="contiene-video"></span>
        </div>
        <div class="info">
            <div class="cuerpo">
                <div class="volanta">{{ $video->volanta }}</div>
                <h2>{!! $video->nombre !!}</h2>
                <div class="bajada texto">{!! $video->bajada !!}</div>
            </div>
        </div>
        <a class="glightbox-video cover" href="{{ $video->resuelto->embedUrl() }}"></a>
    </div>
</article>
@if (($encuesta = App\Encuesta::activa()) && !isset($GLOBALS['encuesta_responsive_mostrada']))
    <?php $GLOBALS['encuesta_responsive_mostrada'] = true; ?>
    <div class="bloque-encuesta-responsive">
        @include('_encuesta')
    </div>
@endif
