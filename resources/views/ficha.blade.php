@extends('layout')

@section('titulo')
    Entre Noticias - {{ $noticia->titulo_p }}
@endsection

@section('script.header')
    <script type="text/javascript">
        $(function() {
            if ($('.multimedia').length > 0) {
                $('.multimedia').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                });
            }
        });
    </script>
@endsection

@if ($imagen = $noticia->contenidosVisibles->firstWhere('tipo', 'imagen'))
    @section('imagen')
        {{ $imagen->url('tn') }}
    @endsection
@endif

@section('bajada')
    {{ str_replace("\n", ' ', strip_tags(html_entity_decode($noticia->bajada))) }}
@endsection

@section('contenido')
    <div class="contenedor ficha">
        <div class="columnas">
            <div class="columna-noticias">
                <div class="cuerpo-noticia">
                    <div class="copete">
                        <div class="categorias">
                            {{ $noticia->region->nombre }} | {{ $noticia->seccion->nombre }}
                        </div>
                        <div class="botones">
                            <a class="compartir addthis_button_more" href="#compartir"></a>
                        </div>
                    </div>
                    <div class="fecha">{{ $noticia->fecha_f }}</div>
                    <h1>{!! $noticia->titulo_h !!}</h1>
                    <div class="texto bajada">{!! $noticia->bajada !!}</div>
                    @if ($noticia->autor)
                        <div class="autor">
                            <p>Por {{ $noticia->autor }}</p>
                        </div>
                    @endif
                    @if (count($noticia->contenidosVisibles))
                        <div class="multimedia">
                            @foreach ($noticia->contenidosVisibles as $contenido)
                                @if ($contenido->tipo == 'video')
                                    @if ($videoResuelto = $contenido->getVideo())
                                        <div>
                                            <div class="foto video"
                                                style="background-image:url({{ $contenido->tiene('imagen') ? $contenido->url('tn') : $videoResuelto->thumb([1290, 585]) }});">
                                                <a class="glightbox-video" href="{{ $videoResuelto->embedUrl() }}"
                                                    target="_blank"></a>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($contenido->tipo == 'imagen')
                                    <div>
                                        <div class="foto" style="background-image:url({{ $contenido->url('tn') }});">
                                            <a href="{{ $contenido->url('imagen') }}" data-lity></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <div class="texto">
                        {!! $noticia->texto_con_embebidos !!}
                    </div>


                </div>

                @if ($banner = $banners['horizontales']->shift())
                    <div class="banner horizontal">
                        <a href="{{ $banner->linkContador() }}" target="_blank"><img
                                src="{{ $banner->url('imagen') }}"></a>
                    </div>
                @endif

                <div class="listado">
                    @if (count($leidas))
                        <div class="leidas">
                            <div class="contenido">
                                <h2>MÁS LEÍDAS</h2>
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
                        @foreach ($partes['principales'] as $noticia)
                            @include('_noticia')
                        @endforeach
                    </div>
                </div>
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
@endsection
