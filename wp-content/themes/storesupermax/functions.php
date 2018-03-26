<?php 
	 add_action( 'wp_enqueue_scripts', 'shop_isle_supermercados_max_enqueue_styles' );
	 function shop_isle_supermercados_max_enqueue_styles() {
 		  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
 		  } 
 ?>