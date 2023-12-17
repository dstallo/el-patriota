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
<div class="col-md-3 form-group{{ has_error($errors, 'volanta') }}">
    <label>Volanta</label>
    <input type="text" class="form-control" name="volanta" value="{{ old('volanta', $video->volanta) }}">
</div>

<div class="col-md-3 form-group{{ has_error($errors, 'nombre') }}">
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $video->nombre) }}">
</div>

<div class="col-md-6 form-group{{ has_error($errors, 'bajada') }}">
    <label>Bajada</label>
    <textarea class="tiny" style="height:180px;" name="bajada">{{ old('bajada', $video->bajada) }}</textarea>
</div>

<div class="col-md-2 form-group{{ has_error($errors, 'destacado') }}">
    <label>Destacado</label>
    <div>
        <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" name="destacado" value="1"
            {{ old('destacado', $video->destacado) ? 'checked' : '' }}>
    </div>
</div>

<div class="col-md-12">&nbsp;</div>

<div class="col-md-6 form-group{{ has_error($errors, 'link') }}">
    <label>Link</label>

    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-film"></i></span>
        {!! isset($axvideo) ? $axvideo->embed() : '' !!}
        <input type="text" class="form-control" name="link" value="{{ old('link', $video->link) }}">
    </div>
</div>

<div class="col-md-6 form-group{{ has_error($errors, 'imagen') }}">
    <label>Imagen</label>
    @if ($video->tiene('imagen'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_video', ['video' => $video, 'campo' => 'imagen']) }}"
                    class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span
                        class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $video->url('imagen') }}" data-lity><img src="{{ $video->url('imagen') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="imagen" value="{{ old('imagen') }}">
        <span class="help-block">1400x650</span>
    @endif
</div>
<?php /*
<div class="col-md-3 form-group{{ has_error($errors,'imagen_vertical') }}">
    <label>Imagen vertical</label>
    @if($video->tiene('imagen_vertical'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_video', ['video' => $video, 'campo' => 'imagen_vertical']) }}" class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $video->url('imagen_vertical') }}" data-lity><img src="{{ $video->url('imagen_vertical') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="imagen_vertical" value="{{ old('imagen_vertical') }}">
    @endif
</div>
*/
?>
