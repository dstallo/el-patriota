@extends('adminlte::page')

@section('content_header')
    <h1>Newsletter</h1>
@stop

@section('content')
    
    <div class="row">
        
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Acciones</h3>
                </div>
                <div class="box-body">
                    <a href="{{ route('exportar_inscriptos') }}" class="btn btn-primary" target="_blank">Exportar todos</a>
                    <a href="{{ route('crear_inscripto') }}" class="btn btn-primary">Crear inscripto</a>
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
                                <input type="text" class="form-control" name="buscando" placeholder="Buscar inscripto..." value="{{ $listado->old('buscando') }}">
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
            <h3 class="box-title">Inscriptos</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><a href="{{ $listado->linkOrden('id') }}">#</a></th>
                        <th><a href="{{ $listado->linkOrden('email') }}">Email</a></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscriptos as $inscripto)
                        <tr>
                            <td>{{ $inscripto->id }}</td>
                            <td>{{ $inscripto->email }}</td>
                            <td class="text-right">
                                <a href="{{ route('editar_inscripto', compact('inscripto')) }}" role="button" class="btn btn-warning btn-circle"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="{{ route('eliminar_inscripto', compact('inscripto')) }}" role="button" class="btn btn-danger btn-circle axys-confirmar-eliminar"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No se encontraron inscriptos.</td>
                        </tr>
                    @endforelse
                    <?php $inscripto = null; ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix text-center">
            {{ $inscriptos->links() }}
        </div>
    </div>
@endsection