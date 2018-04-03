<?php
	/*==================================================================================
						Agregar Child Theme
	==================================================================================*/

	 add_action( 'wp_enqueue_scripts', 'shop_isle_supermercados_max_enqueue_styles' );
	 function shop_isle_supermercados_max_enqueue_styles() {
		   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
		   wp_enqueue_script( 'supermax', get_stylesheet_directory_uri() . '/js/supermax.js', array( 'jquery' ), '1.0', true );
		   } 
		   require_once dirname( __FILE__ ).'/session-manager.php';
		   //require_once dirname( __FILE__ ).'/geolocation.php';
	

	/*==================================================================================
						Modificar el estilo del shopPage
	==================================================================================*/
		   remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
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


	/*==================================================================================================================
				Agregar Rating / Calificaciones al producto
	===================================================================================================================*/
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action('woocommerce_after_shop_loop_item_title', 'add_star_rating', 5	 );
	function add_star_rating()
	{
		global $woocommerce, $product;
		$average = $product->get_average_rating();
		echo '<div class="star-rating"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>';
	}
		

?>


<?php
		/*=======================================================================================
				Mostrar la descripción del producto en la tienda
		=========================================================================================*/

		function descripcion_corta($limit, $texto) {
			$excerpt = explode(' ', $texto, $limit);
			if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
			} else {
			$excerpt = implode(" ",$excerpt);
			}
			$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
			return $excerpt;
		  }


		function add_product_description() {
			global $product;
			?>
		
		<div class="woocommerce-product-details__short-description product-excerpt">
				<p><?php echo descripcion_corta(10, get_the_excerpt()); ?>	</p>
		</div>
		<?php
		}

		add_action( 'woocommerce_after_shop_loop_item_title', 'add_product_description', 4 );
		?>
		
		
		<?php
		/*=======================================================================================
				Mostrar Imagen + Título + Descripción Rápida + Valor del producto en carrito 
		=========================================================================================*/
		add_filter( 'woocommerce_cart_item_name', 'add_excerpt_in_cart_item_name', 10, 3 );
		
		function add_excerpt_in_cart_item_name( $item_name,  $cart_item,  $cart_item_key ){
			$excerpt = wp_strip_all_tags( get_the_excerpt($cart_item['product_id']), true ); 
			$excerpt_html = '<br>
				<p name="short-description">'.$excerpt.'</p>';
			return $item_name . $excerpt_html;
		}
		?>

			

<?php 
/*==================================================================================================================
							Mostrar productos de la semana
		===================================================================================================================*/
		add_shortcode('ofertas_semanales', 'ofertas_semanales');

		function ofertas_semanales( $price, $product ) {
			//Obtener todos los post que sean de tipo producto
			$args = array(
				'post_type' => 'product',
			);
			//Crear un na consulta con los agrumentos
			$loop = new WP_Query( $args );
			//Verificar que existan post de tipo productos
			if ( $loop->have_posts() ) :
			?>
			<div class="products_shortcode">
				<div class="woocommerce columns-4">
				<ul class="products columns-4">
			<?php 
			//Mientras existan posts
			while ( $loop->have_posts() ) : 
			$loop->the_post();
			global $product;
			//Obtener el la fecha de inicio de la pormoción
			$sales_price_from = get_post_meta( $product->id, '_sale_price_dates_from', true );
			//Obtener el la fecha de final de la pormoción
			$sales_price_to  = get_post_meta( $product->id, '_sale_price_dates_to', true );

			$weekday = date("w"); //Dia de la semana en numero
			$weekend = (6-$weekday); //Calcular cuantos dias quedan de esta semana
			/*-----Dar formato al dia de hoy -----------*/
				$date=date_create(date("Y-m-j", time()));
				$date = date_add($date,date_interval_create_from_date_string("".$weekend." days"));
				$date = strtotime(date_format($date,"Y-m-d"));
			/*------------------------------------------*/
				//Condiciona que el producto esté en oferta y esté dentro del rango dela semana
				$sales_w_dates = $product->is_on_sale() && $sales_price_from <= $date   && $date <= $sales_price_to;
				//Condiciona que el producto esté en oferta y no se hayan definido períodos de la oferta
				$sales_no_dates = $product->is_on_sale() && $sales_price_from == ''  && $sales_price_to == '';

				if ( $sales_no_dates || $sales_w_dates ) :
					add_action( 'woocommerce_before_single_product', 'woocommerce_template_loop_add_to_cart', 1 );
					wc_get_template_part( 'content', 'product' );						
					add_action( 'woocommerce_after_single_product', 'shop_isle_product_page_wrapper_end', 2);
				endif;
					
				endwhile;
				?>
				</ul>
				</div>
				</div>
		
				<div class="row">
				<div class="col-sm-12 align-center">
				
				<?php if ( function_exists( 'wc_get_page_id' ) ) {
					echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
				} elseif ( function_exists( 'woocommerce_get_page_id' ) ) {
					echo '<a href="' . esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
				}
				?>
				</div>
			
				</div>
	
		<?php else :
	
			echo '<div class="row">';
			echo '<div class="col-sm-6 col-sm-offset-3">';
			echo '<p class="">' . __( 'No products found.', 'shop-isle' ) . '</p>';
			echo '</div>';
			echo '</div>';
	
		endif;
	
		wp_reset_postdata();

			//return apply_filters( 'woocommerce_get_price', $price );
		}
		


		/*==================================================================================================================
							Mostrar ofertas
		===================================================================================================================*/
		add_shortcode('ofertas', 'ofertas');
		function ofertas($atts) {
			//Obtener todos los post que sean de tipo producto
			$args = array(
				'post_type' => 'product',
			);
			$attributos = shortcode_atts( array (
				'categoria' => 'None',
			), $atts );

			//Crear un na consulta con los agrumentos
			$loop = new WP_Query( $args );
			//Verificar que existan post de tipo productos
			if ( $loop->have_posts() ) :
			?>
			<div class="products_shortcode">
				<div class="woocommerce columns-4">
				<ul class="products columns-4">
				
			<?php 
			//Mientras existan posts
			while ( $loop->have_posts() ) : 
			$loop->the_post();
			global $product;

			foreach ($product->category_ids as $key => $cat_id) {
				//Obtener las categorías a las que pertenece el producto.
				$category = get_term_by('id', $cat_id, 'product_cat', 'ARRAY_A');
				//Verificar que coincida con la categoría a filtrar
				if ($category['name'] === $attributos['categoria']){
				//Condiciona que el producto esté en oferta y no se hayan definido períodos de la oferta
				if ( $product->is_on_sale() ) :
					add_action( 'woocommerce_before_single_product', 'woocommerce_template_loop_add_to_cart', 1 );
					wc_get_template_part( 'content', 'product' );						
					add_action( 'woocommerce_after_single_product', 'shop_isle_product_page_wrapper_end', 2);
				endif;
				}
			}	
			
			//Condiciona que el producto esté en oferta y esté dentro del rango dela semana
			
					
				endwhile;
				
				?>
				</ul>
				</div>
				</div>
		
				<div class="row">
				<div class="col-sm-12 align-center">
				
				<?php if ( function_exists( 'wc_get_page_id' ) ) {
					echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
				} elseif ( function_exists( 'woocommerce_get_page_id' ) ) {
					echo '<a href="' . esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) ) . '" class="btn btn-b btn-round">' . apply_filters( 'shop_isle_see_all_products_label', __( 'See all products', 'shop-isle' ) ) . '</a>';
				}
				?>
				</div>
			
				</div>
	
		<?php else :
	
			echo '<div class="row">';
			echo '<div class="col-sm-6 col-sm-offset-3">';
			echo '<p class="">' . __( 'No products found.', 'shop-isle' ) . '</p>';
			echo '</div>';
			echo '</div>';
	
		endif;
	
		wp_reset_postdata();

			//return apply_filters( 'woocommerce_get_price', $price );
		}
		
		?>