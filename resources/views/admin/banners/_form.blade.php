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
<div class="col-md-4 form-group{{ has_error($errors, 'ubicacion') }}">
    <label>Ubicación</label>
    <select name="ubicacion" data-allow-clear="true" id="ubicacion" class="form-control select2"{{ $banner->id ? ' disabled' : '' }} data-placeholder="Seleccionar">
        <option></option>    
        @foreach (App\Banner::ubicaciones() as $ubicacion)
            <option value="{{ $ubicacion }}" {!! selected($ubicacion == old('ubicacion', $banner->ubicacion)) !!}>{{ $ubicacion }}</option>
        @endforeach
    </select>
    @if ($banner->id)
        <input type="hidden" name="ubicacion" value="{{ $banner->ubicacion }}">
    @endif
</div>
<div class="col-md-4 form-group{{ has_error($errors, 'nombre') }}">
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $banner->nombre) }}">
</div>
<div class="col-md-4 form-group{{ has_error($errors, 'link') }}">
    <label>Link</label>
    <input type="text" class="form-control" name="link" value="{{ old('link', $banner->link) }}">
</div>
<div
    class="col-md-{{ $banner->ubicacion == 'Horizontal' ? '12' : '4' }} form-group{{ has_error($errors, 'imagen') }}">
    <label>Imagen</label>
    @if ($banner->tiene('imagen'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_banner', ['banner' => $banner, 'campo' => 'imagen']) }}"
                    class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span
                        class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $banner->url('imagen') }}" data-lity><img src="{{ $banner->url('imagen') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="imagen" value="{{ old('imagen') }}">
    @endif
</div>
<?php /*
<div class="col-md-4 form-group{{ has_error($errors,'imagen_responsive') }}">
    <label>Imagen responsive</label>
    @if($banner->tiene('imagen_responsive'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('eliminar_archivo_banner', ['banner' => $banner, 'campo' => 'imagen_responsive']) }}" class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $banner->url('imagen_responsive') }}" data-lity><img src="{{ $banner->url('imagen_responsive') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="imagen_responsive" value="{{ old('imagen_responsive') }}">
    @endif
</div>
*/
?>
