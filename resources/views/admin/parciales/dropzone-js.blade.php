<script type="text/javascript" src="{{ url('js/lib/dropzone/dropzone.min.js') }}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop files here to upload";
    Dropzone.prototype.defaultOptions.dictFallbackMessage = "Your browser does not support drag'n'drop file uploads.";
    Dropzone.prototype.defaultOptions.dictFallbackText = "Please use the fallback form below to upload your files like in the olden days.";
    Dropzone.prototype.defaultOptions.dictFileTooBig = "El archivo es demasiado grande (@{{filesize}}MiB). Máximo permitido: @{{maxFilesize}}MiB.";
    Dropzone.prototype.defaultOptions.dictInvalidFileType = "No se permiten archivos de este tipo.";
    Dropzone.prototype.defaultOptions.dictResponseError = "El servidor respondió con código @{{statusCode}}.";
    Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar subida";
    Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "Seguro que querés cancelar la subida?";
    Dropzone.prototype.defaultOptions.dictRemoveFile = "Eliminar archivo";
    Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "No se pueden subir más archivos.";
</script>