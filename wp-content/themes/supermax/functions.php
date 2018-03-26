<?php 
	 add_action( 'wp_enqueue_scripts', 'shop_isle_supermercados_max_enqueue_styles' );
	 function shop_isle_supermercados_max_enqueue_styles() {
 		  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
		   } 
		   


		   /*Modificar estilos Shop Page*/
		   function shop_isle_shop_page_wrapper() {
			?>
			<section class="module-large module-large-shop">
					<div class="container">
	
					<?php
					if ( is_shop() || is_product_tag() || is_product_category() ) :
	
							do_action( 'shop_isle_before_shop' );
	
						if ( is_active_sidebar( 'shop-isle-sidebar-shop-archive' ) ) :
						?>
	
								<div class="col-sm-9 shop-with-sidebar" id="shop-isle-blog-container">
	
							<?php endif; ?>
	
					<?php endif; ?>
	
			<?php
		}

		/*Agregar Rating*/
		add_action('woocommerce_after_shop_loop_item', 'add_star_rating' );
		function add_star_rating()
		{
		global $woocommerce, $product;
		$average = $product->get_average_rating();
		
		echo '<div class="star-rating"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>';
		}
 ?>