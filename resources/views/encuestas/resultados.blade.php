@extends('layout')

@section('titulo')
{{ config('app.name') }} - Resultados de la encuesta
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
					<div class="resultados">
						<ul>
							<?php $total = $encuesta->opciones->sum('votos'); ?>
							@foreach($encuesta->opciones as $opcion)
								<?php
									$votos = round($opcion->votos / ($total ? $total : 1) * 100, 2);
								?>
								<li>
									<div class="opcion">{{ $opcion->opcion }}</div>
									<div class="barra">
										<span style="width:calc({{ $votos }}% - 40px)"></span>
										<label>{{ number_format($votos, 2, ',', '.') }}%</label>
									</div>
								</li>
							@endforeach
						</ul>
						<div class="boton">
							<a href="{{ url('/') }}">VOLVER AL INICIO</a>
						</div>
					</div>
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