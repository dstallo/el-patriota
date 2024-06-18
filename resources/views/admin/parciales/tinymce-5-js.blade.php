<?php /* <script type="text/javascript" src="/js/lib/tinymce/tinymce.min.js"></script> */ ?>
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.apikey') }}/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '.tiny-img',
        language: 'es',
        height: 440,
        menubar: false,
        plugins: [
            'advlist autolink lists link charmap anchor',
            'searchreplace visualblocks fullscreen',
            'insertdatetime table contextmenu paste',
            'image media'
        ],
        toolbar: 'undo redo | removeformat | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | link image media | table',

        //block_formats: 'Texto=p;Título=h3;Subtítulo=h4',

        //valid_elements : 'a[href|target=_blank|title],strong/b,em,p[align|style],img[src|alt|width|height|style],br,ul,ol,li,h3[style],h4[style],table[style|width],tbody,thead,th[style|width|height],td[style|width|height],tr,iframe[title|src|width|height|frameborder|allowfullscreen|allow]',

        //toolbar: 'formatselect | undo redo | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | link | table',
        //toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify bullist numlist | outdent indent | link image'
        //block_formats: 'Normal=p;Título=h3',
        //valid_elements : 'a[href|target=_blank|title],strong/b,em,p[align],br,ul,ol,li,h3,table[style|width],tbody,thead,th[style|width|height],td[style|width|height],tr',

        relative_urls: false,
        //remove_script_host : false,
        convert_urls: true,
        file_picker_types: 'image',
        images_upload_handler: function(blobInfo, success, failure) {
            let data = new FormData();
            data.append('imagen', blobInfo.blob(), blobInfo.filename());
            axios.post('{{ route('subir-tiny') }}', data)
                .then(function(res) {
                    success(res.data.location);
                })
                .catch(function(err) {
                    failure(
                        'Ocurrió un error al subir la imagen, revisar que el formato sea válido y que no pese demasiado.');
                });
        }


    });


    tinymce.init({
        selector: '.tiny',
        language: 'es',
        height: 250,
        menubar: false,
        plugins: [
            'advlist autolink lists link charmap anchor',
            'searchreplace visualblocks fullscreen',
            'insertdatetime table contextmenu paste',
        ],
        toolbar: 'removeformat | undo redo | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | link image | table',
        //block_formats: 'Texto=p;Título=h3;Subtítulo=h4',
        valid_elements: 'a[href|target=_blank|title],strong/b,em,p[align],br,ul,ol,li,h3,h4,table[style|width],tbody,thead,th[style|width|height],td[style|width|height],tr',

        //toolbar: 'formatselect | undo redo | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | link | table',
        //toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify bullist numlist | outdent indent | link image'
        //block_formats: 'Normal=p;Título=h3',
        //valid_elements : 'a[href|target=_blank|title],strong/b,em,p[align],br,ul,ol,li,h3,table[style|width],tbody,thead,th[style|width|height],td[style|width|height],tr',

    });
</script>
