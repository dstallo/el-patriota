require('./bootstrap');

require('./mantener-relacion-alto');

require('./abrir-html-fancy');

require('sweetalert');

require('lity');

require('./formularios')

//javascript general para el front

$(function() {
    $('.fancybox').fancybox({ padding: 0, afterShow: function() { $(window).resize(); } });
    GLightbox({selector: ".glightbox-video",closeOnOutsideClick: true,videosWidth: "90%",skin: 'glightbox-vid glightbox-clean'});

    // $('.menu > .desplegar').click(function(e) {
    //     e.preventDefault();
    //     $(this).siblings('nav').slideToggle(200);
    // });

    // $('a.desplegable').click(function(e) {
    //     e.preventDefault();
    //     $(this).siblings('.submenu').slideToggle();
    //     $(this).toggleClass('desplegado');
    // });

    //menu lateral
    $('[data-abrir-menu-lateral]').click(function(e) {
        e.preventDefault();
        $('.menu-lateral').addClass('abierto');
    });
    $('[data-cerrar-menu-lateral]').click(function(e) {
        e.preventDefault();
        $('.menu-lateral').removeClass('abierto');
    });

    $.ajax({
    	dataType: "json",
    	url: '/ajax/clima',
    	success: function(data) {
    		if(typeof data.temperatura != 'undefined') {
    			var html = '<i class="owf owf-' + data.codigo + '"></i><span>' + (data.temperatura != '' ? data.temperatura + 'º' : '') + '</span>';
    			$('header .clima').html(html);
    		}
    	},
    	error: function() {
    		$('header .clima').html('');
    	}
    });
});