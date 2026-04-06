@extends('adminlte::page')

@section('content_header')
    <h1>Encuestas</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Crear encuesta</h3>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ route('guardar_encuesta') }}">
            {{ csrf_field() }}
            <div class="box-body">
                @include('admin.encuestas._form')
            </div>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Crear</button>
                <a href="{{ route('encuestas') }}" class="btn btn-info">Volver</a>
            </div>
        </form>
    </div>
@endsection

@section('script.abajo')
    @include('admin.parciales.quill-js')
@endsection