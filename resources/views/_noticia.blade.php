<div class="noticia">
    <div class="contenido">
        <div class="imagen">
            <div style="background-image:url({{ $noticia->url('thumbnail') }});"></div>
        @if ($noticia->obtenerCategorias())
            <div class="categorias">
                {{ $noticia->obtenerCategorias() }}
            </div>
        @endif
            @if ($noticia->con_video)
                <span class="contiene-video"></span>
            @endif
        </div>
        <div class="info">
            <div class="fecha">{{ $noticia->fecha_f }}</div>
            <div class="cuerpo">
                <div>
                    <div class="volanta">{{ $noticia->volanta }}</div>
                    <h2>{!! $noticia->titulo_h !!}</h2>
                    <div class="bajada texto">{!! $noticia->bajada !!}</div>
                </div>
                @if ($noticia->autor)
                    <div class="autor">Por {{ $noticia->autor }}</div>
                @endif
            </div>
        </div>
        <a href="{{ $noticia->link() }}" class="cover"></a>
    </div>
</div>
