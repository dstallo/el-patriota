@extends('adminlte::page')

@section('css')
    <style type="text/css">
        .contenedor { display:flex; flex-flow:row wrap; }
    </style>
@stop

@section('content_header')
    <h4><a href="{{ route('noticias') }}">Fichas</a> > <a href="{{ route('editar_noticia', $noticia) }}">{{ $noticia->titulo }}</a></h4>
    <h1>Contenido multimedia</h1>
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Contenido subido</h3>
      <p>* Arrastrar para cambiar el orden.</p>
    </div>
    <div class="box-body">
        <div class="row row-grid">
            <div class="contenedor" id="ordenable">
                @forelse($contenidos as $contenido)
                <div class="col-md-2 col-xs-6" data-id-contenido="{{ $contenido->id }}" style="position:relative; margin-bottom:20px;">
                    <div style="position:absolute; left:0; top:4px;">
                        <a href="{{ route('editar_contenido_noticia',[$noticia, $contenido]) }}" class="btn btn-circle btn-sm btn-warning" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                        <a href="{{ route('eliminar_contenido_noticia',[$noticia, $contenido]) }}" class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
                    </div>
                    @if($contenido->tipo == 'imagen')
                        <a href="{{ $contenido->url('imagen') }}" data-lity><img src="{{ $contenido->url('imagen') }}" style="border-radius:5px;"></a>
                    @elseif($contenido->tipo == 'video')
                        @if($videoResuelto = $contenido->getVideo())
                            <a href="" data-abrir-html-fancy='{!! $videoResuelto->embed(560, 56, true) !!}' data-mantener-relacion-alto="56" class="play">
                                <img src="{{ $contenido->tiene('imagen') ? $contenido->url('imagen') : $videoResuelto->thumb([550,370]) }}" style="border-radius:5px;">
                            </a>
                        @else
                            <p style="padding-top:35px;">VIDEO ROTO</p>
                        @endif
                    @endif
                    <p>{{ $contenido->nombre }}</p>
                </div>
                @empty
                    <div class="col-md-12"><p>No hay contenidos multimedia</p></div>
                @endforelse
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 form-group">
                <h4>Subir imágenes</h4>
                <div class="dropzone" data-input="imagen" data-cantidad="multi" data-mimes="image/*" data-url="{{ route('subir_imagen_noticia', $noticia) }}" max="2" data-reload="si">
                    <div class="dz-message" data-dz-message><span>Arrastrá los archivos o clickeá para subir imagenés.</span></div>
                </div>
            </div>
            <div id="crear-video" class="col-md-6 form-group">
                <h4>Crear un video</h4>
                
                <form method="post" enctype="multipart/form-data" action="{{ route('crear_video_noticia', $noticia) }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            @if (count($errors)>0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5 form-group{{ has_error($errors, 'nombre') }}">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}">
                        </div>
                        <div class="col-md-7 form-group{{ has_error($errors,'video') }}">
                            <label>Video</label>
                            
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-film"></i></span>
                                <input type="text" class="form-control" name="video" value="{{ old('video') }}">
                            </div>
                        </div>
                        <div class="col-md-6 form-group{{ has_error($errors,'imagen') }}">
                            <label>Imagen</label>
                            <input type="file" class="form-control" name="imagen" value="">
                        </div>
                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Crear video</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('editar_noticia', $noticia) }}" class="btn btn-info">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script.header')
    @include('admin.parciales.dropzone-js')
@endsection

@section('script.abajo')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css" />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js'></script>
    <script>
        var ordenable = dragula([document.getElementById('ordenable')]);
        ordenable.on('dragend', function(el) {
            ids = [];
            $('#ordenable > div').each(function(i) {
                ids.push($(this).data('id-contenido'));
            });
            $.ajax({
                url:'{{ route("ordenar_contenidos_noticia", compact("noticia")) }}',
                method:'post',
                data:{'ids':ids},
                success:function(ret){
                    if(!ret.ok) {
                        sweetAlert('Error', 'Hubo un error al actualizar el orden de las contenidos, por favor intentá nuevamente.', 'error');
                    }
                },
                error:function(){ sweetAlert('Error', 'Hubo un error al actualizar el orden de las contenidos, por favor recargá la página e intentá nuevamente.', 'error'); }
            });
        })
    </script>
@endsection
