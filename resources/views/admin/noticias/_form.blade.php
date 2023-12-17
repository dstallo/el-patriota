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

<div class="col-md-4 form-group{{ has_error($errors, 'seccion') }}">
    <label>Sección</label>
    <select name="id_seccion" class="form-control">
        @foreach ($secciones as $seccion)
            <option value="{{ $seccion->id }}"{{ selected($seccion->id == old('id_seccion', $noticia->id_seccion)) }}>
                {{ $seccion->nombre }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-4 form-group{{ has_error($errors, 'region') }}">
    <label>Región</label>
    <select name="id_region" class="form-control">
        @foreach ($regiones as $region)
            <option value="{{ $region->id }}"{{ selected($region->id == old('id_region', $noticia->id_region)) }}>
                {{ $region->nombre }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-4 form-group{{ has_error($errors, 'fecha') }}">
    <label>Fecha</label>
    <input type="datetime-local" class="form-control" name="fecha" value="{{ old('fecha', $noticia->fecha_html) }}">
</div>

<div class="col-md-4 form-group{{ has_error($errors, 'volanta') }}">
    <label>Volanta</label>
    <input type="text" class="form-control" name="volanta" value="{{ old('volanta', $noticia->volanta) }}">
</div>
<div class="col-md-4 form-group{{ has_error($errors, 'titulo') }}">
    <label>Título</label>
    <input type="text" class="form-control" name="titulo" value="{{ old('titulo', $noticia->titulo) }}">
    <span class="help-block">Colocar entre llaves para destacar. Por ejemplo: {Urgente:} Resto del título</span>
</div>
<div class="col-md-4 form-group{{ has_error($errors, 'autor') }}">
    <label>Autor</label>
    <input type="text" class="form-control" name="autor" value="{{ old('autor', $noticia->autor) }}">
</div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-6 form-group{{ has_error($errors, 'embebido_1') }}">
    <label>Embebido 1</label>
    <textarea class="form-control" style="height:180px;" name="embebido_1">{{ old('embebido_1', $noticia->embebido_1) }}</textarea>
</div>
<div class="col-md-6 form-group{{ has_error($errors, 'embebido_2') }}">
    <label>Embebido 2</label>
    <textarea class="form-control" style="height:180px;" name="embebido_2">{{ old('embebido_2', $noticia->embebido_2) }}</textarea>
</div>
<div class="col-md-6 form-group{{ has_error($errors, 'bajada') }}">
    <label>Bajada</label>
    <textarea class="tiny" style="height:180px;" name="bajada">{{ old('bajada', $noticia->bajada) }}</textarea>
</div>
<div class="col-md-2 form-group{{ has_error($errors, 'con_video') }}">
    <label>Contiene video</label>
    <div>
        <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" name="con_video" value="1"
            {{ old('con_video', $noticia->con_video) ? 'checked' : '' }}>
    </div>
</div>

<div class="col-md-12 form-group{{ has_error($errors, 'texto') }}">
    <label>Texto</label>
    <textarea class="tiny-img" style="height:180px;" name="texto">{{ old('texto', $noticia->texto) }}</textarea>
    <span class="help-block">{embebido_1} y {embebido_1} para ubicar los embebidos.</span>
</div>

<div class="col-md-12"></div>

<div class="col-md-4 form-group{{ has_error($errors, 'thumbnail') }}">
    <label>Thumbnail</label>
    @if ($noticia->tiene('thumbnail'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_noticia', ['noticia' => $noticia, 'campo' => 'thumbnail']) }}"
                    class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span
                        class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $noticia->url('thumbnail') }}" data-lity><img src="{{ $noticia->url('thumbnail') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="thumbnail" value="{{ old('thumbnail') }}">
    @endif
</div>

<div class="col-md-4 form-group{{ has_error($errors, 'thumbnail_celular') }}">
    <label>Thumbnail celular</label>
    @if ($noticia->tiene('thumbnail_celular'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_noticia', ['noticia' => $noticia, 'campo' => 'thumbnail_celular']) }}"
                    class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span
                        class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $noticia->url('thumbnail_celular') }}" data-lity><img
                    src="{{ $noticia->url('thumbnail_celular') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="thumbnail_celular" value="{{ old('thumbnail_celular') }}">
    @endif
</div>

<div class="col-md-12">&nbsp;</div>

<div class="col-md-4 form-group{{ has_error($errors, 'destacada') }}">
    <label>Destacada</label>
    <div>
        <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" name="destacada" value="1"
            {{ old('destacada', $noticia->destacada) ? 'checked' : '' }}>
    </div>
</div>

<div class="col-md-4 form-group{{ has_error($errors, 'banner') }}">
    <label>Banner</label>
    @if ($noticia->tiene('banner'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_noticia', ['noticia' => $noticia, 'campo' => 'banner']) }}"
                    class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span
                        class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $noticia->url('banner') }}" data-lity><img src="{{ $noticia->url('banner') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="banner" value="{{ old('banner') }}">
    @endif
</div>

<div class="col-md-4 form-group{{ has_error($errors, 'banner_celular') }}">
    <label>Banner celular</label>
    @if ($noticia->tiene('banner_celular'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_noticia', ['noticia' => $noticia, 'campo' => 'banner_celular']) }}"
                    class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span
                        class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $noticia->url('banner_celular') }}" data-lity><img
                    src="{{ $noticia->url('banner_celular') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="banner_celular" value="{{ old('banner_celular') }}">
    @endif
</div>
