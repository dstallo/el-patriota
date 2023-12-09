$(function() {
    ///// FORMULARIOS //////
    $('[data-formulario] button[type="submit"]').click(function(e){

        var form = $(this).parents('form').first();
        var url = form.data('formulario');
        var cargando = form.find('.cargando');
        if(!cargando[0]) cargando = false;
        var boton = $(this);

        var mensajes = {
            exito: {
                titulo: form.find('input[name="exito_titulo"]').val(),
                texto: form.find('input[name="exito_texto"]').val(),
            },
            error: {
                titulo: form.find('input[name="error_titulo"]').val(),
                texto: form.find('input[name="error_texto"]').val(),
            },
        }

        if(form[0].checkValidity()) {
            e.preventDefault();
            if(cargando) {
                boton.hide();
                cargando.show();
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                data: form.serialize(),
                success: function(r) {
                    if(cargando) {
                        cargando.hide();
                        boton.show();
                    }
                    if(r.ok) {
                        sweetAlert(mensajes.exito.titulo, mensajes.exito.texto, "success");
                        form[0].reset();
                    } else {
                        if(r.errores) {
                            var mensajeFormateado = r.errores.join("\n");
                            sweetAlert(mensajes.error.titulo, mensajeFormateado, "error");
                        } else {
                            sweetAlert(mensajes.error.titulo, mensajes.error.texto, "error");
                        }
                    }
                },
                error: function(request, estado, error) {
                    if(cargando) {
                        cargando.hide();
                        boton.show();
                    }
                    if(error=='Too Many Requests') {
                        sweetAlert("Error", "Realizaste demasiadas solicitudes, por favor aguardá unos minutos e intentá nuevamente.", "error");
                    } else {
                        sweetAlert(mensajes.error.titulo, mensajes.error.texto, "error");
                    }
                }
            });
        }
    });
});