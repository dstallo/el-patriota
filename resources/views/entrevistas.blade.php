@extends('layout')

@section('script.header')
@endsection

@section('contenido')
    <div class="contenedor">
        <div class="videos">
            <div class="contenidos">
                @foreach ($destacados as $video)
                    <article>
                        <a class="glightbox-video" href="{{ $video->resuelto->embedUrl() }}">
                            <img src="{{ $video->url('imagen') }}" title="{{ $video->nombre }}">
                        </a>
                        <div class="info">
                            <h2>{{ $video->nombre }}</h2>
                            <div class="texto bajada">
                                {!! $video->bajada !!}
                            </div>
                            <div class="boton">
                                <a href="{{ $video->resuelto->embedUrl() }}" class="glightbox-video">Reproducir</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        @if ($banner = $banners['horizontales']->shift())
            <x-banner type="imagen" class="banner horizontal sin-padding" :banner="$banner" />
        @endif

        <div class="columnas">
            <div class="columna-noticias">
                <div class="videos">
                    <ul>
                        @foreach ($videos['primarios'] as $video)
                            <li>
                                @include('_video')
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if ($banner = $banners['horizontales']->shift())
                    <x-banner type="imagen" class="banner horizontal sin-padding" :banner="$banner" />
                @endif

                <div class="videos completo">
                    <ul>
                        @foreach ($videos['secundarios'] as $video)
                            <li>
                                @include('_video')
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="columna-banners">
                @forelse($banners['laterales'] as $banner)
                    <x-banner type="imagen" class="banner lateral" :banner="$banner" />
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
