jQuery(window).load(function () {
    /*======================================================
            Reorganizar la descripción corta del producto
    =======================================================*/
    //Ejecutarlo 1ms despues de que se hayan cargado todos los procesos JS y jQuery
    setTimeout(function () {
        jQuery('.page .products li, .single .products li, .post-type-archive-product .products li, .tax-product_cat .products li, .related.products .products li, #shop-isle-blog-container .products li, .upsells.products li, .products_shortcode .products li, .cross-sells .products li, .woocommerce.archive .products li').each(function () {
            jQuery(this).find('.product-button-wrap').prepend(jQuery(this).find('.product-excerpt'));
        })
    }, 1);

    /*================================================================================
            Obtener ubicación actual del usuario para calcular el almacen mas cercano
    =================================================================================*/

    // function calcularDistancia(lat1, long1, lat2, long2) {
    //     var earth = 6371; //km change accordingly

    //     //Point 1 cords
    //     var lat1 = lat1 * Math.PI / 180;
    //     var long1 = long1 * Math.PI / 180;

    //     //Point 2 cords
    //     lat2 = lat2 * Math.PI / 180;
    //     long2 = long2 * Math.PI / 180;

    //     //Haversine Formula
    //     var dlong = long2 - long1;
    //     var dlat = lat2 - lat1;

    //     sinlat = Math.sin(dlat / 2);
    //     sinlong = Math.sin(dlong / 2);

    //     var a = (sinlat * sinlat) + Math.cos(lat1) * Math.cos(lat2) * (sinlong * sinlong);

    //     var c = 2 * Math.asin(Math.min(1, Math.sqrt(a)));

    //     var d = Math.round(earth * c);

    //     alert(d);
    // }

    // pull cords out of database

    //echo "Distance in miles from CB2 to SS4: ".getDistance(52.163, 0.133, 51.594, 0.715);

    var content = document.getElementById("almacen-cercano");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (objPosition) {
            var lon = objPosition.coords.longitude;
            var lat = objPosition.coords.latitude;
            //calcularDistancia(lon, lat)
            //var lugares = array(['San Carlos de Bariloche Las Terrazas', -41.13555711387061, -71.2995396])
            content.innerHTML = "<p>Alamacén recomendado:<strong>Latitud:</strong> " + lat + "</p><p><strong>Longitud:</strong> " + lon + "</p>";
            jQuery('#almacen').val(lat + ' ' + lon);
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