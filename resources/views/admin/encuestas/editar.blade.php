@extends('adminlte::page')

@section('content_header')
    <h1>Encuestas</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Editar encuesta</h3>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ route('guardar_encuesta', $encuesta) }}">
            {{ csrf_field() }}
            <div class="box-body">
                @include('admin.encuestas._form')
            </div>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('encuestas') }}" class="btn btn-info">Volver</a>
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
                    <a href="{{ route('opciones', $encuesta) }}" class="btn btn-warning">Cargar opciones</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script.abajo')
    @include('admin.parciales.quill-js')
@endsection