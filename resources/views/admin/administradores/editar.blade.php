@extends('adminlte::page')

@section('content_header')
    <h1>Cuentas de administrador</h1>
@stop

@section('js')
    
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar administrador</h3>
                </div>
                <form method="post" enctype="multipart/form-data" action="{{ route('AdministradoresGuardar', compact('administrador')) }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        @include('admin.administradores._form')
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <?php /* @if(Auth::user()->admin()) */ ?>
                            <a href="{{ route('AdministradoresIndex') }}" class="btn btn-info">Volver</a>
                        <?php /* @endif */ ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop