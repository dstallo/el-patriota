@extends('layout')

@section('script.header')
    
@endsection

@section('contenido')

<div class="contenedor listado">
	
	
	@if($banner = $banners['horizontales']->shift())
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
	*/ ?>

	<?php $primera = $partes['principales']->shift(); ?>
	@if($primera)
		<div class="principales">
			<div class="primera">
				<?php $noticia = $primera ?>
				@include('_noticia')
			</div>
			<div class="segundas">
				@foreach($partes['principales'] as $noticia)
					@include('_noticia')
				@endforeach
			</div>
			<hr>
		</div>
	@endif

	@if($banner = $banners['horizontales']->shift())
		<div class="banner horizontal">
			<a href="{{ $banner->linkContador() }}" target="_blank"><img src="{{ $banner->url('imagen') }}"></a>
		</div>
	@endif

	<div class="columnas">
		<div class="columna-noticias">
			<div class="secundarias">
				@foreach($partes['secundarias'] as $noticia)
					@include('_noticia')
				@endforeach
			</div>

			@if($banner = $banners['horizontales']->shift())
				<div class="banner horizontal">
					<a href="{{ $banner->linkContador() }}" target="_blank"><img src="{{ $banner->url('imagen') }}"></a>
				</div>
			@endif

			<div class="terciarias">
				@foreach($partes['terciarias_1'] as $noticia)
					@include('_noticia')
				@endforeach
			</div>

			@if(count($leidas))
				<div class="leidas">
					<div class="contenido">
						<h2>NOTICIAS MÁS LEÍDAS</h2>
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
				@foreach($partes['terciarias_2'] as $noticia)
					@include('_noticia')
				@endforeach
			</div>
		</div>
		<div class="columna-banners">
			@forelse($banners['laterales'] as $banner)
				<div class="banner lateral">
					<a href="{{ $banner->linkContador() }}" target="_blank"><img src="{{ $banner->url('imagen') }}"></a>
				</div>
				@if($loop->iteration == 2 || ($loop->iteration<2 && $loop->last))
					@include('_encuesta')
				@endif
			@empty
				@include('_encuesta')
			@endforelse
			<div class="banner lateral">
				<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6107105686338244"
				     crossorigin="anonymous"></script>
				<!-- Banner lateral -->
				<ins class="adsbygoogle"
				     style="display:block"
				     data-ad-client="ca-pub-6107105686338244"
				     data-ad-slot="7233094358"
				     data-ad-format="auto"
				     data-full-width-responsive="true"></ins>
				<script>
				     (adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
		</div>
	</div>
</div>

@endsection