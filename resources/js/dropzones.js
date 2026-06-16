//dropzones
$(function() {
	$(".dropzone").each(function() {
		var url = $(this).data('url');
		var input = $(this).data('input');
		var mimes = $(this).data('mimes');
		var cantidad = $(this).data('cantidad');
		var reload = $(this).data('reload');
		var max = $(this).data('max');
		
		if(!url) return;
		if(!input) input = 'archivo';
		if(!mimes) mimes = null;

		$(this).dropzone({
			url: url,
			paramName: input,
            headers: {
                'X-CSRF-TOKEN': window.Laravel['csrfToken']
            },
			maxFiles: (cantidad == 'multi' ? null : 1),
			maxFilesize: (max ? max : 10),
			acceptedFiles: mimes,
			init: function() {
			    this.on("success", function() { 
		            if(reload=='si') {
			            if(this.getUploadingFiles().length==0) {
			                setTimeout(function(){ window.location.reload(); }, 1000);
			            }
			        }
		        });
			}
		});
	});
});