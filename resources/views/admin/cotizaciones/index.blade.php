@extends('adminlte::page')

@section('content_header')
    <h1>Cotizaciones</h1>
@stop

@section('content')
    <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Cotizaciones</h3>
                <p>* Para modificar el orden de las cotizaciones, arrastralas con el mouse.</p>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table id="tabla-ordenable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Cotización</th>
                            <th>Valor Compra</th>
                            <th>Valor Venta</th>
                            <th>Fecha actualización</th>
                            <th>Última consulta</th>
                            <th>Última respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cotizaciones as $cotizacion)
                        <tr>
                            <td class="hidden">{{ $cotizacion->orden }}</td>
                            <td>{{ $cotizacion->nombre }}</td>
                            <td>{{ $cotizacion->format('compra') ?? '-' }}</td>
                            <td>{{ $cotizacion->format('venta')  ?? '-' }}</td>
                            <td>{{ $cotizacion->refrescada?->format('d/m/Y H:i\h\s') }}</td>
                            <td>{{ $cotizacion->updated_at?->format('d/m/Y H:i\h\s') }}</td>
                            <td>{{ $cotizacion->ultimaActualizacion() }}</td>
                            <td class="hidden">{{ $cotizacion->id }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
    </div>
    
@stop

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
                        url:'{{ route("ordenar_cotizaciones") }}',
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
                                sweetAlert('Error', 'Hubo un error al actualizar el orden de las cotizaciones, por favor intentá nuevamente.', 'error');
                            }
                        },
                        error:function(){ sweetAlert('Error', 'Hubo un error al actualizar el orden de las cotizaciones, por favor recargá la página e intentá nuevamente.', 'error'); }
                    });
                }
            });
        });
    </script>
@endsection