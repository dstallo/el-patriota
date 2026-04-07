require("./bootstrap");

require("./mantener-relacion-alto");

require("./abrir-html-fancy");

require("sweetalert");

require("lity");

require("./formularios");

//javascript general para el front

$(function() {
    $(".fancybox").fancybox({
        padding: 0,
        afterShow: function() {
            $(window).resize();
        }
    });
    GLightbox({
        selector: ".glightbox-video",
        closeOnOutsideClick: true,
        videosWidth: "90%",
        skin: "glightbox-vid glightbox-clean"
    });

    $(".newsletter a.suscribite").on("click", function(e) {
        e.preventDefault();
        $(this).siblings("form").slideToggle("fast", function() {
            if ($(this).is(":visible")) {
                $(this).find("input[name='nombre']")[0].focus();
            }
        });
    });
    window.cerrarSlideNewsletter = () => {
        $(".newsletter form").slideUp("fast");
    };
    /*
    $(".newsletter a").on("click", function(e) {
        e.preventDefault();
        //window.scrollTo(0, 0);
        if (!$(".entresub .newsletter form").is(":visible")) {
            $(".entresub a.suscribite").trigger("click");
        }
    });
    */

    if ($(".destacadas").length) {
        //slides de la home
        $(".destacadas").slick({
            slidesToShow: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 6000,
            arrows: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 650,
                    settings: {
                        // centerMode: true,
                        // centerPadding: '40px',
                        arrows: false
                    }
                }
            ]
        });
    }

    $(".videos .contenidos").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        infinite: true
    });

    $("[data-auto-abrir-popup]").each(function() {
        var ancho_minimo = $(this).data("auto-abrir-popup");
        var vw = Math.max(
            document.documentElement.clientWidth || 0,
            window.innerWidth || 0
        );
        if (!ancho_minimo || parseInt(ancho_minimo) <= vw) {
            $(this).click();
            return false;
        }
    });

    $("[data-cerrar-popup]").click(function(e) {
        e.preventDefault();
        $.fancybox.close();
    });

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
    $("[data-abrir-menu-lateral]").click(function(e) {
        e.preventDefault();
        $(".menu-lateral").addClass("abierto");
    });
    $("[data-cerrar-menu-lateral]").click(function(e) {
        e.preventDefault();
        $(".menu-lateral").removeClass("abierto");
    });

    $.ajax({
        dataType: "json",
        url: "/ajax/clima",
        success: function(data) {
            if (typeof data.temperatura != "undefined") {
                var html =
                    '<i class="owf owf-' +
                    data.codigo +
                    '"></i><span>' +
                    (data.temperatura != "" ? data.temperatura + "º" : "") +
                    "</span>";
                $("header .clima").html(html);
            }
        },
        error: function() {
            $("header .clima").html("");
        }
    });
});
