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
<div class="col-md-6 form-group{{ has_error($errors, 'nombre') }}">
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $inscripto->nombre) }}">
</div>
<div class="col-md-6 form-group{{ has_error($errors, 'email') }}">
    <label>Email</label>
    <input type="email" class="form-control" name="email" value="{{ old('email', $inscripto->email) }}">
</div>
