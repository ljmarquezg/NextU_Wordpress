jQuery(window).load(function () {
    /*======================================================
            Modificaciones
    =======================================================*/
    //Ejecutarlo 1ms despues de que se hayan cargado todos los procesos JS y jQuery
    setTimeout(function () {
        //Reorganizar la descripción corta del producto
        jQuery('.page .products li, .single .products li, .post-type-archive-product .products li, .tax-product_cat .products li, .related.products .products li, #shop-isle-blog-container .products li, .upsells.products li, .products_shortcode .products li, .cross-sells .products li, .woocommerce.archive .products li').each(function () {
            jQuery(this).find('.product-button-wrap').prepend(jQuery(this).find('.product-excerpt'));
        })
        //Cambiar el texto del personalizador del tema para seleccionat la categoría a mostrar en los banners
        jQuery('#sub-accordion-section-shop_isle_banners_section .customize-control-title').text('Seleccione categoría a mostrar')
        jQuery('nav.navbar').removeClass('navbar-transparent');

        // jQuery("input[type='radio']").each(function () {
        //     let label = jQuery(this).siblings("label");
        //     label.detach();
        //     jQuery(this).prepend(label)
        // });
        if (jQuery('input[type="radio"][name^="billing_"]').length > 0) {
            jQuery('input[type="radio"][name^="billing_"]').each(function () {
                value = jQuery(this).val();
                jQuery(this).addClass('billing_' + value);
                jQuery('label[for="billing_' + value + '"]').addClass('billing_' + value);
                jQuery('input[value="' + value + '"]').after(jQuery('label[for="billing_' + value + '"]'))
                jQuery('.billing_' + value).wrapAll('<li>');
            })
        }

    }, 1);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (objPosition) {
            var lon = objPosition.coords.longitude;
            var lat = objPosition.coords.latitude;
            //calcularDistancia(lon, lat)
            //var lugares = array(['San Carlos de Bariloche Las Terrazas', -41.13555711387061, -71.2995396])
        }, function (objPositionError) {
            switch (objPositionError.code) {
                case objPositionError.PERMISSION_DENIED:
                    content.innerHTML = "No se ha permitido el acceso a la posición del usuario.";
                    break;
                case objPositionError.POSITION_UNAVAILABLE:
                    content.innerHTML = "No se ha podido acceder a la información de su posición.";
                    break;
                case objPositionError.TIMEOUT:
                    content.innerHTML = "El servicio ha tardado demasiado tiempo en responder.";
                    break;
                default:
                    content.innerHTML = "Error desconocido.";
            }
        }, {
            maximumAge: 75000,
            timeout: 15000
        });
    } else {
        content.innerHTML = "Su navegador no soporta la API de geolocalización.";
    };


});



window.fbAsyncInit = function () {
    FB.init({
        appId: '1816574748404589',
        cookie: true,
        xfbml: true,
        version: '1.0'
    });

    FB.AppEvents.logPageView();

};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function checkLoginState() {
    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
}