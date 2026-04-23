<link rel="stylesheet" type="text/css" href="{{ url('js/lib/dropzone/dropzone.min.css') }}" />
<script type="text/javascript" src="{{ url('js/lib/dropzone/dropzone.min.js') }}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    Dropzone.prototype.defaultOptions.dictDefaultMessage = "Arrastrá tus archivos aquí para subirlos.";
    Dropzone.prototype.defaultOptions.dictFallbackMessage = "Tu navegador no admite drag & drop.";
    Dropzone.prototype.defaultOptions.dictFallbackText = "Por favor, utilizá el formulario para subir tus imágenes.";
    Dropzone.prototype.defaultOptions.dictFileTooBig = "El archivo es demasiado grande (@{{filesize}}MiB). Máximo permitido: @{{maxFilesize}}MiB.";
    Dropzone.prototype.defaultOptions.dictInvalidFileType = "No se permiten archivos de este tipo.";
    Dropzone.prototype.defaultOptions.dictResponseError = "El servidor respondió con código @{{statusCode}}.";
    Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar subida";
    Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "Seguro que querés cancelar la subida?";
    Dropzone.prototype.defaultOptions.dictRemoveFile = "Eliminar archivo";
    Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "No se pueden subir más archivos.";
</script>