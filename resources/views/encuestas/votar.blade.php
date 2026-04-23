@extends('layout')

@section('titulo')
{{ config('app.name') }} - {{ strip_tags($encuesta->pregunta) }}
@endsection


@section('contenido')
<div class="contenedor ficha">
	<div class="columnas">
		<div class="columna-noticias">
			<div class="cuerpo-noticia">
				<div class="copete">
					<div class="categorias">
						ENCUESTA
					</div>
					<div class="botones">
						<a class="compartir addthis_button_more" href="#compartir"></a>
					</div>
				</div>
				<h1>{!! $encuesta->pregunta !!}</h1>
				<div class="encuesta">
					<form class="votar" method="post">
						{{ csrf_field() }}
						<ul>
							@foreach($encuesta->opciones as $opcion)
								<li>
									<input type="radio" id="opcion_{{ $opcion }}" name="opcion" value="{{ $opcion->id }}" required>
									<label for="opcion_{{ $opcion }}">{{ $opcion->opcion }}</label>
								</li>
							@endforeach
						</ul>
						<div class="boton">
							<button type="submit">VOTAR</button>
						</div>
					</form>
				</div>
			</div>

			<div class="listado">
				@if(count($leidas))
					<div class="leidas">
						<div class="contenido">
							<h2>MÁS LEÍDAS</h2>
							<ul>
								@foreach($leidas as $leida)
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
					@foreach($partes['principales'] as $noticia)
						@include('_noticia')
					@endforeach
				</div>
			</div>
		</div>

		<div class="columna-banners" style="position:relative; top:-10px;">
			@foreach($banners['laterales'] as $banner)
				@break($loop->iteration > 5)
				<x-banner type="imagen" class="banner lateral" :banner="$banner" />
			@endforeach
		</div>
	</div>
</div>
@endsection