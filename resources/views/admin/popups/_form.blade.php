<div class="col-md-12">
    @if (count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
<div class="col-md-6 form-group{{ has_error($errors,'nombre') }}">
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" value="{{ old('nombre',$popup->nombre) }}">
</div>
<div class="col-md-6 form-group{{ has_error($errors,'link') }}">
    <label>Link</label>
    <input type="text" class="form-control" name="link" value="{{ old('link',$popup->link) }}">
</div>
<div class="col-md-4 form-group{{ has_error($errors,'imagen') }}">
    <label>Imagen</label>
    @if($popup->tiene('imagen'))
        <div style="position:relative;">
            <div style="position:absolute; left:-4px; top:4px;">
                <a href="{{ route('eliminar_archivo_popup', ['popup' => $popup, 'campo' => 'imagen']) }}" class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $popup->url('imagen') }}" data-lity><img src="{{ $popup->url('imagen') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="imagen" value="{{ old('imagen') }}" accept=".png,.jpg,.jpeg">
    @endif
</div>

<div class="col-md-3 form-group{{ has_error($errors,'imagen_vertical') }}">
    <label>Imagen vertical</label>
    @if($popup->tiene('imagen_vertical'))
        <div style="position:relative;">
            <div style="position:absolute; left:-4px; top:4px;">
                <a href="{{ route('eliminar_archivo_popup', ['popup' => $popup, 'campo' => 'imagen_vertical']) }}" class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $popup->url('imagen_vertical') }}" data-lity><img src="{{ $popup->url('imagen_vertical') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="imagen_vertical" value="{{ old('imagen_vertical') }}" accept=".png,.jpg,.jpeg">
    @endif
</div>