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
<div class="col-md-4 form-group{{ has_error($errors,'nombre') }}">
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" value="{{ old('nombre',$administrador->nombre) }}">
</div>
<div class="col-md-4 form-group{{ has_error($errors,'email') }}">
    <label>E-Mail</label>
    <input type="text" class="form-control" name="email" value="{{ old('email',$administrador->email) }}">
</div>
<div class="col-md-4 form-group{{ has_error($errors,'foto') }}">
    <label>Foto</label>
    @if($administrador->tiene('foto'))
        <div style="position:relative;">
            <div style="position:absolute; left:-14px; top:4px;">
                <a href="{{ route('AdministradoresEliminarArchivo', ['administrador' => $administrador, 'campo' => 'foto']) }}" class="btn btn-circle btn-sm btn-danger" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
            <a href="{{ $administrador->url('foto') }}" data-lity><img src="{{ $administrador->url('foto') }}"></a>
        </div>
    @else
        <input type="file" class="form-control" name="foto" value="{{ old('foto') }}">
    @endif
</div>
<?php /*
<div class="col-md-4 form-group{{ has_error($errors,'rol') }}">
    <label>Rol</label>
    <select name="rol" id="rol" class="form-control">
        @foreach($roles as $rol)
            <option value="{{ $rol }}" {!! selected($rol==old('rol',$administrador->rol)) !!}>{{ $rol }}</option>
        @endforeach
    </select>
</div> */ ?>

@if ($mostrarFormPassword)
    <div class="col-md-4 form-group{{ has_error($errors,'password') }}">
        <label>Password</label>
        <input type="password" class="form-control" name="password" autocomplete="new-password">
        @if ($administrador->id)
            <span class="help-block">Dejar en blanco para no cambiar.</span>
        @endif
    </div>
    <div class="col-md-4 form-group{{ has_error($errors,'password_confirmation') }}">
        <label>Confirmar Password</label>
        <input type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
    </div>
@endif