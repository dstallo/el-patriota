@extends('adminlte::page')

@section('content_header')
    <h4><a href="{{ route('encuestas') }}">Encuestas</a> > <a href="{{ route('editar_encuesta', $encuesta) }}">{{ $encuesta->nombre }}</a></h4>
    <h1>Opciones</h1>
@stop

@section('content')
    
    <div class="row">
        
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Acciones</h3>
                </div>
                <div class="box-body">
                    <a href="{{ route('crear_opcion', $encuesta) }}" class="btn btn-primary">Crear opción</a>
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
                                <input type="text" class="form-control" name="buscando" placeholder="Buscar opción..." value="{{ $listado->old('buscando') }}">
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
                        <th>Opción</th>
                        <th>Votos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = $opciones->sum('votos'); ?>
                    @forelse($opciones as $opcion)
                        <tr>
                            <td class="hidden">{{ $opcion->orden }}</td>
                            <td>{{ $opcion->id }}</td>
                            <td>{{ $opcion->opcion }}</td>
                            <td>{{ $opcion->votos }} ({{ number_format(($opcion->votos / ($total ? $total : 1)) * 100, 2, ',', '.') }}%)</td>
                            <td class="text-right">
                                <a href="{{ route('editar_opcion', compact('encuesta', 'opcion')) }}" role="button" class="btn btn-warning btn-circle"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="{{ route('eliminar_opcion', compact('encuesta', 'opcion')) }}" role="button" class="btn btn-danger btn-circle axys-confirmar-eliminar"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                            <td class="hidden">{{ $opcion->id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No se encontraron opciones.</td>
                        </tr>
                    @endforelse
                    <?php $opcion = null; ?>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script.abajo')
    <script type="text/javascript" src="/js/lib/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/lib/jquery-ui/jquery-ui.touch-punch.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#tabla-ordenable tbody").sortable({
                update:function(){
                    array=[];
                    $(this).children().each(function(i){
                        array.push($(this).children().last().html());
                    });
                    $.ajax({
                        url:'{{ route("ordenar_opciones", $encuesta) }}',
                        method:'post',
                        data:{'ids':array},
                        success:function(ret){
                            if(ret.ok) {
                                orden=1;
                                $('#tabla-ordenable tbody').children().each(function(i){
                                    $(this).children().first().html(orden);
                                    orden+=1;
                                });
                            } else {
                                sweetAlert('Error', 'Hubo un error al actualizar el orden de los elementos, por favor intentá nuevamente.', 'error');
                            }
                        },
                        error:function(){ sweetAlert('Error', 'Hubo un error al actualizar el orden de los elementos, por favor recargá la página e intentá nuevamente.', 'error'); }
                    });
                }
            });
        });
    </script>
@endsection
