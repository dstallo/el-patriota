<?php if(!isset($encuesta)) $encuesta = App\Encuesta::activa(); ?>
@if($encuesta && substr(Request::path(), 0, 8) != 'encuesta')
	<div class="bloque-encuesta">
		<div class="contenido">
			<h3>Encuesta</h3>
			<div class="pregunta">{!! $encuesta->pregunta !!}</div>
			<ul>
				@foreach($encuesta->opciones as $opcion)
					<li>{{ $opcion->opcion }}</li>
				@endforeach
			</ul>
			<div class="boton">
				<a href="{{ route('ver-encuesta', \Illuminate\Support\Str::slug($encuesta->pregunta)) }}">IR A VOTAR</a>
			</div>
		</div>
	</div>
@endif