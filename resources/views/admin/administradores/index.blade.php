@extends('adminlte::page')

@section('content_header')
    <h1>Cuentas de administrador</h1>
@stop

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-info">
			    <div class="box-header with-border">
			      <h3 class="box-title">Acciones</h3>
			    </div>
			    <div class="box-body">
			    	<a href="{{ route('AdministradoresCrear') }}" class="btn btn-primary">Crear nuevo administrador</a>
			    </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-primary">
			    <div class="box-header with-border">
			      <h3 class="box-title">Filtros</h3>
			    </div>
			    <form>
			    	<div class="box-body">
				        <div class="col-md-4">
				            <div class="input-group">
				                <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
				                <input type="text" class="form-control" name="buscando_id" placeholder="ID#" value="{{ $listado->old('buscando_id') }}">
				            </div>
				        </div>
				        <div class="col-md-8">
				            <div class="input-group">
				                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
				                <input type="text" class="form-control" name="buscando" placeholder="Buscar administrador..." value="{{ $listado->old('buscando') }}">
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
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><a href="{{ $listado->linkOrden('id') }}">#</a></th>
                        <?php /* <th><a href="{{ $listado->linkOrden('rol') }}">Rol</a></th> */ ?>
                        <th><a href="{{ $listado->linkOrden('nombre') }}">Nombre</a></th>
                        <th><a href="{{ $listado->linkOrden('email') }}">E-Mail</a></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($administradores as $administrador)
                        <tr>
                            <td>{{ $administrador->id }}</td>
                            <?php /* <td>{{ $administrador->rol }}</td> */?>
                            <td>{{ $administrador->nombre }}</td>
                            <td>{{ $administrador->email }}</td>
                            <td class="text-right">
                                <a href="{{ route('AdministradoresEditar', compact('administrador')) }}" role="button" class="btn btn-warning btn-circle"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="{{ route('AdministradoresEliminar', compact('administrador')) }}" role="button" class="btn btn-danger btn-circle axys-confirmar-eliminar"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix text-center">
        	{{ $administradores->links() }}
        </div>
    </div>

@stop