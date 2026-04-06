@extends('adminlte::page')

@section('content_header')
    <h1>Noticias</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Editar noticia</h3>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ route('guardar_noticia', $noticia) }}">
            {{ csrf_field() }}
            <div class="box-body">
                @include('admin.noticias._form')
            </div>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('noticias') }}" class="btn btn-info">Volver</a>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Acciones</h3>
                </div>
                <div class="box-body">
                    <a href="{{ route('contenidos', $noticia) }}" class="btn btn-warning">Cargar contenido multimedia</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script.abajo')
    @include('admin.parciales.quill-js')
@endsection