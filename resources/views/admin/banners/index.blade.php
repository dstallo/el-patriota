@extends('adminlte::page')

@section('content_header')
    <h1>Banners</h1>
@stop

@section('content')
    
    <div class="row">
        
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Acciones</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12">
                        <a href="{{ route('crear_banner') }}" class="btn btn-primary">Crear banner</a>
                    </div>
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
                        <div class="col-md-3 form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                                <input type="text" class="form-control" name="buscando_id" placeholder="ID#" value="{{ $listado->old('buscando_id') }}">
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <select class="form-control" name="buscando_ubicacion" onchange="$(this).closest('form').submit()">
                                @foreach(App\Banner::ubicaciones() as $i_ubicacion)
                                    <option value="{{ $i_ubicacion }}"{{ selected($listado->old('buscando_ubicacion')==$i_ubicacion) }}>{{ $i_ubicacion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                                <input type="text" class="form-control" name="buscando" placeholder="Buscar banner..." value="{{ $listado->old('buscando') }}">
                            </div>
                        </div>

                        <input type="submit" class="hidden">
                    </div>
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
                        <th>Clicks</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td class="hidden">{{ $banner->orden }}</td>
                            <td>{{ $banner->id }}</td>
                            <td>{{ $banner->nombre }}</td>
                            <td>{{ $banner->clicks }}</td>
                            <td class="text-right">
                                {!! accion_visibilidad($banner->visible, route('visibilidad_banner',compact('banner'))) !!}
                                <a href="{{ route('editar_banner', compact('banner')) }}" role="button" class="btn btn-warning btn-circle"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="{{ route('eliminar_banner', compact('banner')) }}" role="button" class="btn btn-danger btn-circle axys-confirmar-eliminar"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                            <td class="hidden">{{ $banner->id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No se encontraron banners.</td>
                        </tr>
                    @endforelse
                    <?php $banner = null; ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix text-center">
            
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
                        url:'{{ route("ordenar_banners") }}',
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
