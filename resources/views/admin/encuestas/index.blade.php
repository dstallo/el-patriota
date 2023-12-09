@extends('adminlte::page')

@section('content_header')
    <h1>Encuestas</h1>
@stop

@section('content')
    
    <div class="row">
        
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Acciones</h3>
                </div>
                <div class="box-body">
                    <a href="{{ route('crear_encuesta') }}" class="btn btn-primary">Crear encuesta</a>
                </div>
            </div>
        </div>
        

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Filtros</h3>
                </div>
                <form>
                    <div class="box-body">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                                <input type="text" class="form-control" name="buscando_id" placeholder="ID#" value="{{ $listado->old('buscando_id') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                                <input type="text" class="form-control" name="buscando" placeholder="Buscar encuesta..." value="{{ $listado->old('buscando') }}">
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="hidden">
                </form>    
            </div>
        </div>
    </div>

    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">Listado</h3>
            <p>* Para modificar el orden de los elementos, arrastralos con el mouse.</p>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table id="tabla-ordenable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($encuestas as $encuesta)
                        <tr>
                            <td class="hidden">{{ $encuesta->orden }}</td>
                            <td>{{ $encuesta->id }}</td>
                            <td>{{ $encuesta->nombre }}</td>
                            <td class="text-right">
                                <a href="{{ route('opciones', compact('encuesta')) }}" class="btn btn-primary btn-sm">Opciones</a>
                                {!! accion_visibilidad($encuesta->visible, route('visibilidad_encuesta',compact('encuesta'))) !!}
                                <a href="{{ route('editar_encuesta', compact('encuesta')) }}" role="button" class="btn btn-warning btn-circle"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="{{ route('eliminar_encuesta', compact('encuesta')) }}" role="button" class="btn btn-danger btn-circle axys-confirmar-eliminar"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                            <td class="hidden">{{ $encuesta->id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No se encontraron encuestas.</td>
                        </tr>
                    @endforelse
                    <?php $encuesta = null; ?>
                </tbody>
            </table>
        </div>
    </div>
@endsection