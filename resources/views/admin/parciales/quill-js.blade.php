<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script type="text/javascript">
    $(document).ready(function() {

        $('.quill-editor').hide().each(function(){
            let $container = $('<div class="quill-container"></div>').css('height', $(this).height()).insertBefore($(this));
            $container.html($(this).val());

            let quill = new Quill($container[0], {
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                        [{ align: [] }],
                        ['link', 'blockquote', 'code-block', 'image', 'video'],
                        ['clean'],
                        
                    ],
                },
                theme: 'snow', // or 'bubble'
            });

            quill.on('text-change', function(delta, oldDelta, source) {
                
                let content = $container.data('quill').root.innerHTML;
                $container.siblings('.quill-editor').val(content);
            });

            $container.data('quill', quill);
        });

    });
</script>