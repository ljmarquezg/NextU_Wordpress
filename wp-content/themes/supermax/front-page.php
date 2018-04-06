<?php
/**
 * The front-page.php
 *
 * @package ShopIsle
 */

get_header();
/* Wrapper start */

echo '<div class="main">';
$big_title = dirname( __FILE__ ) . '/inc/sections/shop_isle_big_title_section.php';
load_template( apply_filters( 'shop-isle-subheader', $big_title ) );

/* Wrapper start */
$shop_isle_bg = get_theme_mod( 'background_color' );

if ( isset( $shop_isle_bg ) && $shop_isle_bg != '' ) {
	echo '<div class="main front-page-main" style="background-color: #' . $shop_isle_bg . '">';
} else {

	echo '<div class="main front-page-main" style="background-color: #FFF">';

}

if ( defined( 'WCCM_VERISON' ) ) {

	/* Woocommerce compare list plugin */
	echo '<section class="module-small wccm-frontpage-compare-list">';
	echo '<div class="container">';
	do_action( 'shop_isle_wccm_compare_list' );
	echo '</div>';
	echo '</section>';

}

// // /******* Slider Section */
// //$products_slider = get_template_directory() . '/inc/sections/shop_isle_products_slider_section.php';
// $slider = dirname( __FILE__ ) .'/inc/sections/shop_isle_slider_section.php';
// require_once( $slider );

/******* Products Slider Section - Ofertas Diarias*/
// $products_slider = get_template_directory() . '/inc/sections/shop_isle_products_slider_section.php';
$products_slider = dirname( __FILE__ ) .'/inc/sections/shop_isle_products_slider_section.php';
require_once( $products_slider );


/******  Banners Section - Ofertas Mensuales*/
$banners_section = get_template_directory() . '\inc\sections\shop_isle_banners_section.php';
//require_once( $banners_section );
include_once(dirname( __FILE__ ) .'\inc\sections\shop_isle_banners_section.php');
// echo do_shortcode( '[ofertas categoria="Ofertas Mensuales"]' );

/******* Products Section - Ofertas Semanales*/
$latest_products = dirname( __FILE__ ) . '/inc/sections/shop_isle_products_section.php';
require_once( $latest_products );

/******* Video Section */
$video = get_template_directory() . '/inc/sections/shop_isle_video_section.php';
require_once( $video );

echo do_shortcode( '[wpgmza id="1"]' );


get_footer();

