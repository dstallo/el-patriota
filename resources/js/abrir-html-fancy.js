$(document).ready(function() {
	$('[data-abrir-html-fancy]').each(function(){
		$(this).click(function(e) {
			if(window.innerWidth < 500 && $(this).attr('href').indexOf('youtube') >= 0) {
				$(this).attr('target', '_blank');
			} else {
				e.preventDefault();
				$('.abrir-html-fancy-contenedor').remove();
				var id = 'abrir-html-fancy-contenedor-' + parseInt(Math.random() * 1000000);
				$(this).parent().append('<div id="' + id + '" class="abrir-html-fancy-contenedor">' + $(this).data('abrir-html-fancy') + '</div>');
				$.fancybox({ 'href': '#' + id, 'padding': 0, 'autoScale': true, afterClose: function() { $('.abrir-html-fancy-contenedor').remove(); } });
			}
		});
	})
});