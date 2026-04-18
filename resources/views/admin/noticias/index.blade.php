@extends('adminlte::page')

@section('content_header')
    <h1>Novedades</h1>
@stop

@section('content')

    <div class="row">

        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Acciones</h3>
                </div>
                <div class="box-body">
                    <a href="{{ route('crear_noticia') }}" class="btn btn-primary">Crear noticia</a>
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
                                <input type="text" class="form-control" name="buscando_id" placeholder="ID#"
                                    value="{{ $listado->old('buscando_id') }}">
                            </div>
                        </div>
                        <div class="col-md-9 form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                                <input type="text" class="form-control" name="buscando" placeholder="Buscar noticia..."
                                    value="{{ $listado->old('buscando') }}">
                            </div>
                        </div>
                        <x-form.select onchange="$(this).closest('form').submit()" container="col-md-4" name="buscando_id_seccion" :opciones="$secciones" :selected="$listado->old('buscando_id_seccion')" placeholder="Elegí la sección" field_value="id" field_name="nombre" :allow_clear="true" />
                        <x-form.select onchange="$(this).closest('form').submit()" container="col-md-4" name="buscando_id_region" :opciones="$regiones" :selected="$listado->old('buscando_id_region')" placeholder="Elegí la región" field_value="id" field_name="nombre" :allow_clear="true" />
                        <x-form.select onchange="$(this).closest('form').submit()" container="col-md-4" name="buscando_grupo" :opciones="$grupos" :selected="$listado->old('buscando_grupo')" placeholder="Elegí el grupo de noticias" field_value="valor" field_name="valor" :allow_clear="true" />

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
            <table id="tabla-ordenable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><a href="{{ $listado->linkOrden('id') }}">#</a></th>
                        <th><a href="{{ $listado->linkOrden('fecha') }}">Fecha</a></th>
                        <th><a href="{{ $listado->linkOrden('titulo') }}">Título</a></th>
                        <th><a href="{{ $listado->linkOrden('visitas') }}">Visitas</a></th>
                        <th><a href="{{ $listado->linkOrden('id_seccion') }}">Sección</a></th>
                        <th><a href="{{ $listado->linkOrden('id_region') }}">Región</a></th>
                        <th><a href="{{ $listado->linkOrden('grupo') }}">Grupo</a></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($noticias as $noticia)
                        <tr>
                            <td>{{ $noticia->id }}</td>
                            <td>{{ $noticia->fecha_ff }}</td>
                            <td>{{ $noticia->titulo }}</td>
                            <td>{{ $noticia->visitas }}</td>
                            <td>{{ $noticia->seccion?->nombre ?? '-' }}</td>
                            <td>{{ $noticia->region?->nombre ?? '-' }}</td>
                            <td>{{ $noticia->grupo ?? '-' }}</td>
                            <td width="190" class="text-right">
                                <a href="{{ route('contenidos', compact('noticia')) }}"
                                    class="btn btn-primary btn-sm">Contenido</a>
                                {!! accion_visibilidad($noticia->visible, route('visibilidad_noticia', compact('noticia'))) !!}
                                <a href="{{ route('editar_noticia', compact('noticia')) }}" role="button"
                                    class="btn btn-warning btn-circle"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="{{ route('eliminar_noticia', compact('noticia')) }}" role="button"
                                    class="btn btn-danger btn-circle axys-confirmar-eliminar"><i
                                        class="glyphicon glyphicon-remove"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="99">No se encontraron noticias.</td>
                        </tr>
                    @endforelse
                    <?php $noticia = null; ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix text-center">
            {{ $noticias->links() }}
        </div>
    </div>
@endsection
