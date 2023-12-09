@extends('adminlte::page')

@section('content_header')
    <h1>Regiones</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Editar región</h3>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ route('guardar_region', $region) }}">
            {{ csrf_field() }}
            <div class="box-body">
                @include('admin.regiones._form')
            </div>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('regiones') }}" class="btn btn-info">Volver</a>
            </div>
        </form>
    </div>
@endsection