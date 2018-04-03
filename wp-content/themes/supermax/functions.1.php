<?php
	/*==================================================================================
						Agregar Child Theme
	==================================================================================*/

	 add_action( 'wp_enqueue_scripts', 'shop_isle_supermercados_max_enqueue_styles' );
	 function shop_isle_supermercados_max_enqueue_styles() {
		   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
		   wp_enqueue_script( 'supermax', get_stylesheet_directory_uri() . '/js/supermax.js', array( 'jquery' ), '1.0', true );
		   } 
		   /*require_once dirname( __FILE__ ).'/geolocation.php';*/
	

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
?>
<?php
		/*=================================================================================================================
						Personalizar formulario de registro
		==================================================================================================================*/
/*		
add_action( 'show_user_profile', 'agregar_campos_perfil' );
add_action( 'edit_user_profile', 'agregar_campos_perfil' );
add_action( 'personal_options_update', 'guardar_campos_registro' );
add_action( 'edit_user_profile_update', 'guardar_campos_registro' );
 
function agregar_campos_perfil( $user ) {
	$user_edad = esc_attr( get_the_author_meta( 'user_edad', $user->ID ) );
	$user_sexo = esc_attr( get_the_author_meta( 'user_sexo', $user->ID ) );
?>
	<h3>Campos adicionales</h3>
	 
	<table class="form-table">
		<tr>
			<th><label for="direccion">Edad</label></th>
			<td>
				<input type="text" name="user_edad" id="user_edad" class="input" value="<?php echo $user_edad; ?>" size="20" />
				<span class="description">Inserta tu edad</span>
			</td>
		</tr>
		<tr>
			<th><label for="ciudad">Sexo</label></th>
			<td>
				<input type="radio" name="user_sexo" class="radio" value="h" <?php if($user_sexo=='h') { echo ' checked'; } ?> /> Hombre
				<input type="radio" name="user_sexo" class="radio" value="m" style="margin-left:10px" <?php if($user_sexo=='m') { echo ' checked'; } ?> /> Mujer<br />
			</td>
		</tr>
	</table>
<?php } */?>




	

<?php
/*========================================================================================
				Forzar inicio de sesión en frontend
=========================================================================================*/
// function redirect_login_page()
// {
// 	// The URL to your login page
// 	$login_page  = site_url("/account/inloggen/");
// 	$page_viewed = basename($_SERVER["REQUEST_URI"]);
 
// 	if($page_viewed == "wp-login.php" AND $_SERVER["REQUEST_METHOD"] == "GET")
// 	{
// 		wp_redirect($login_page);
// 		exit;
// 	}
// }
// add_action("init","redirect_login_page");

// ?>

 <?php
//    $imagenes = "imagen1.pngimagen2.pngimagen3.png";
?> 

<script type="text/javascript">
	var img = '<?php echo $imagenes;?>'
	//alert(img);
</script> -->

<?php 
	// global $wpdb;
	// global $woocommerce;
	// $result = $wpdb->get_results("SELECT `address`, `lat`,`lng` FROM `wp_wpgmza`");

	// foreach ($result as $key => $resultado) {
	// 	// echo '<li>'. $resultado->address .' -> '. calcularDistancia(10.646762599999999,  -61.49128589999999, $resultado->lat, $resultado->long) .'</li>';
	// }

	
	// function calcularDistancia($lat1, $long1, $lat2, $long2) {
    //     $earth = 6371; //km change accordingly

    //     //Point 1 cords
    //     $lat1 = deg2rad($lat1);
    //     $long1 = deg2rad($long1);

    //     //Point 2 cords
    //     $lat2 = deg2rad($lat2);
    //     $long2 = deg2rad($long2);

    //     //Haversine Formula
    //     $dlong = $long2 - $long1;
    //     $dlat = $lat2 - $lat1;

    //     $sinlat = sin($dlat / 2);
    //     $sinlong = sin($dlong / 2);

    //     $a = ($sinlat * $sinlat) + cos($lat1) * cos($lat2) * ($sinlong * $sinlong);

    //     $c = 2 * asin(min(1, sqrt($a)));

    //     $d = round($earth * $c);

    //    return ($d);
	// }
	
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
		?>