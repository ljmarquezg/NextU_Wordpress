/*======================================================
        Reorganizar la descripción corta del producto
=======================================================*/
jQuery(window).load(function () {
    //Ejecutarlo 1ms despues de que se hayan cargado todos los procesos JS y jQuery
    setTimeout(function () {
        jQuery('.page .products li, .single .products li, .post-type-archive-product .products li, .tax-product_cat .products li, .related.products .products li, #shop-isle-blog-container .products li, .upsells.products li, .products_shortcode .products li, .cross-sells .products li, .woocommerce.archive .products li').each(function () {
            jQuery(this).find('.product-button-wrap').prepend(jQuery(this).find('.product-excerpt'));
        })
    }, 1);
});