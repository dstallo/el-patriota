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
    <input type="text" class="form-control" name="nombre" value="{{ old('nombre',$encuesta->nombre) }}">
</div>
<div class="col-md-6 form-group{{ has_error($errors,'pregunta') }}">
    <label>Pregunta</label>
    <input type="text" class="tiny" style="height:180px;" name="pregunta" value="{{ old('pregunta',$encuesta->pregunta) }}">
</div>