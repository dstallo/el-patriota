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


    $(".select2").select2({
	    theme: "bootstrap"
	});

    $(".select2-nosearch").select2({
        theme: "bootstrap",
        minimumResultsForSearch: Infinity
    })
    
    $('.select2-icono,.select2-custom').select2({
        theme: "bootstrap",
        templateResult: select2TemplateCustom,
        templateSelection: select2TemplateCustom
    });

    $('.select-sync').on('change', function(){
        selectSync($($(this).data('sync-with')), $(this).val(), ! $(this).data('sync-no-trigger-change'));
    }).each(function(){
        selectSync($($(this).data('sync-with')), $(this).val(), false);
    });

    $('.select2-multiple').on('change', function(){
        select2AppendHidden($(this));
    }).each(function(){
        select2AppendHidden($(this));
    });
	
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
	});

    window.addEventListener('resize', setWindowHeight);
    setWindowHeight();

});

function selectSync($synced, value, triggerChange) {

    if (Array.isArray(value) && value.length>0 || ! Array.isArray(value) && value) {
        $synced.find('optgroup,option').attr('disabled', 'disabled');
        if (Array.isArray(value)) { // For multiple select's
            value.forEach((id) => $synced.find('[data-sync-id='+id+']').removeAttr('disabled'));
        } 
        else { // For single select's.
            $synced.find('[data-sync-id='+value+']').removeAttr('disabled');
        }
    }
    else {
        $synced.find('optgroup,option').removeAttr('disabled');
    }
    
    if (triggerChange)
        $synced.val('').trigger('change');
}

function select2AppendHidden($select) {
    let $input = $select.siblings('input[type=hidden]');

    if ($select.val().length == 0) {
        if ($input.length == 0)
            $('<input type="hidden" name="'+$select.prop('name')+'" value="" />').insertBefore($select);
    }
    else {
        if ($input.length > 0)
            $input.remove();
    }
}

function select2TemplateCustom (state) {

    if (!state.id) { return state.text; }

    let icono = state.id;

    let $option = $(state.element);

    if ($option.data('icono'))
        icono = $option.data('icono');

    let color = '';

    if ($option.data('color'))
        color = 'color: '+$option.data('color')+';';

    let $item;

    if (! icono || /^\d+$/.test(icono))
        $item = $('<span>'+state.text+'</span>');
    else if ($option.parents('select').hasClass('sin-texto'))
        $item = $('<span class="tag"><i class="material-icons" style="'+color+'">'+icono+'</i></span>');
    else
        $item = $('<span class="tag"><i class="material-icons" style="'+color+'">'+icono+'</i><span class="text">'+state.text+'</span></span>');

    if ($option.data('label')) {
        let $label = $('<span class="label">'+$option.data('label')+'</span>');
        if ($option.data('label_color'))
            $label.css('background-color', $option.data('label_color'));
        if ($option.data('label_class'))
            $label.addClass($option.data('label_class'));
        $item.append($label);
    }
        
    return $item;
};

function setWindowHeight() {
    
    document.documentElement.style.setProperty('--window-height', `${window.innerHeight}px`);
    
}