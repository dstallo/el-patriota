@extends('adminlte::page')

@section('content_header')
    <h1>Contenido multimedia</h1>
    <h3><a href="{{ route('editar_noticia', compact('noticia', 'contenido')) }}">{{ $noticia->titulo }}</a></h3>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Editar contenido</h3>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ route('guardar_contenido_noticia', [$noticia, $contenido]) }}">
            {{ csrf_field() }}
            <div class="box-body">
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
                <div class="col-md-6 form-group{{ has_error($errors, 'nombre') }}">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $contenido->nombre) }}">
                </div>
                <?php /* <div class="col-md-6 form-group{{ has_error($errors, 'epigrafe') }}">
                    <label>Epígrafe</label>
                    <input type="text" class="form-control" name="epigrafe" value="{{ old('epigrafe', $contenido->epigrafe) }}">
                </div> */ ?>
                <div class="col-md-12">&nbsp;</div>
                @if($contenido->tipo == 'video')
                    <div class="col-md-6 form-group{{ has_error($errors,'video') }}">
                        <label>Video</label>
                        
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-film"></i></span>
                            {!! isset($video)?$video->embed():'' !!}
                            <input type="text" class="form-control" name="video" value="{{ old('video',$contenido->video) }}">
                        </div>
                    </div>
                    <div class="col-md-6 form-group{{ has_error($errors,'imagen') }}">
                        <label>Imagen</label>
                        @if($contenido->tiene('imagen'))
                            <div style="position:relative;">
                                <div style="position:absolute; left:-14px; top:4px;">
                                    <a href="{{ route('eliminar_imagen_contenido_noticia', compact('noticia', 'contenido')) }}" class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
                                </div>
                                <a href="{{ $contenido->url('imagen') }}" data-lity><img src="{{ $contenido->url('imagen') }}"></a>
                            </div>
                        @else
                            <input type="file" class="form-control" name="imagen" value="{{ old('imagen') }}">
                        @endif
                    </div>
                @endif
            </div>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('contenidos', $noticia) }}" class="btn btn-info">Volver</a>
            </div>
        </form>
    </div>
@endsection