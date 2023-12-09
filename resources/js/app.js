require('./bootstrap');

window.axios = require('axios'); //necesario para tiny5

require('./a-metodo-rest-laravel');

require('./input-numero-mas-menos');

require('./mantener-relacion-alto');

require('sweetalert');

require('lity');

require('bootstrap-toggle');

require('select2');

//codigos generales

$(function(){
	//resaltar elemento activo del menú
	var url = window.location;
	// Will only work if string in href matches with location
	$('ul.nav a[href="'+ url +'"]').parent().addClass('active');

	// Will also work for relative and absolute hrefs
	$('ul.nav a').filter(function() {
	    return this.href == url;
	}).parent().addClass('active');

	// for treeview
	$('ul.treeview-menu a').filter(function() {
		return this.href == url;
	}).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');

	
	//confirmar eliminar
	$('.axys-confirmar-eliminar').click(function(e) {
		var anchor=$(this); //para pasarle al closure

		e.preventDefault();
		swal({
		  title: "Seguro?",
		  text: "Estás a punto de eliminar este registro, querés continuar?",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Sí, eliminar!",
		  cancelButtonText: "No, cancelar",
		  closeOnConfirm: false
		},
		function(){
		  //TODO: eliminar de fondo por ajax, recargar tabla y....
		  //swal("Eliminado!", "Registro eliminado.", "success");
		  //también podría hacer la api más RESTful
		  
		  window.location.href=anchor.attr('href');

		});
	});

	//dropzones

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
	})
});