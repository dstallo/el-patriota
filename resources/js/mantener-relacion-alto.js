$.fn.ajustarRelacionAlto = function(porcentajeAlto) {
	ancho = this.width();
	alto = ancho * porcentajeAlto / 100
	this.height(alto);
};

$(document).ready(function() {
	$('[data-mantener-relacion-alto]').each(function(){
		$(this).ajustarRelacionAlto($(this).data('mantener-relacion-alto'))
	})
});

$(window).resize(function() {
	$('[data-mantener-relacion-alto]').each(function(){
		$(this).ajustarRelacionAlto($(this).data('mantener-relacion-alto'))
	})
});