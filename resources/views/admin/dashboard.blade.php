@extends('adminlte::page')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Bienvenido al sistema.</p>
    <div class="box">
        <form method="post" enctype="multipart/form-data" action="{{ route('guardar_configuraciones') }}">
            {{ csrf_field() }}
            <div class="box-header with-border">
                <h3 class="box-title">Configuraciones</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <div class="col-md-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <table id="tabla-ordenable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Configuración</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding-top:13px;">Grupo de noticias activo</td>
                            <td><x-form.select label="" container="" name="grupo" :opciones="$grupos" :selected="old('grupo', $configuraciones['GRUPO_ACTIVO'])" placeholder="Elegí un grupo de noticias" leyend="El grupo de noticias activo figurará en la home del sitio." field_value="valor" field_name="valor" :allow_clear="true" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('home') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
    
@stop