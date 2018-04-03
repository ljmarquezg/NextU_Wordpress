<?php
	/*=========================================================================================================
						Verificar Usuario Logueado
	============================================================================================================*/

	add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
	
	function add_loginout_link( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'primary') {
		$cu = wp_get_current_user();
		$address1 = '';
		$address2 = '';
		$id = $cu->ID;
		$nombre =  $cu->user_firstname;
		$telefono = $cu->phone;
		$address1 = get_user_meta( $current_user->ID, 'billing_address_1', true );
		$address2 = get_user_meta( $current_user->ID, 'billing_address_2', true );
		if ($address1 == ''){
			$address1 = get_user_meta( $current_user->ID, 'shipping_address_1', true );
			$address2 = get_user_meta( $current_user->ID, 'shipping_address_2', true );
		}
		$edad = '18';
		$sexo = 'Masculino';
		$almacen = 'Bariloche';

		$items .= 
		'<li class="menu-item  menu-item-has-children  has_children"><a href="#">Mi Cuenta</a><p class="dropdownmenu"></p>
			<ul class="sub-menu">
				<li class="menu-item"><a href="#">Hola, '.$nombre.'</a></p>
				<li class="menu-item small">
					<p>Dirección<small>'.descripcion_corta(50, $address1).'</small></p>
					<p>Telefono:'.$telefono.'</p>
					<p>Edad:'.$edad.'</p>
					<p>Sexo:'.$sexo.'<p>
				</li>
				<li class="menu-item"><p id="almacen-cercano">Almacén recomendado:</p></li>		
				<li class="menu-item"><a href="'.wp_logout_url().'">Cerrar Sesión</a></li>
			</ul>
		</li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
        $items .= '<fb:login-button 
		scope="public_profile,email"
		onlogin="checkLoginState();">
					  </fb:login-button>
					  <li><a href="'. site_url('wp-login.php') .'">Iniciar Sesión</a></li>';
	}
	
	
	function shipping_zones_shortcode() {

		$delivery_zones = WC_Shipping_Zones::get_zones();
	
		foreach ( (array) $delivery_zones as $key => $the_zone ) {
		  echo ''.$the_zone['zone_name'].', ';
		  //print_r($delivery_zones);
		}
	}
	 do_shortcode('shipping_zones_shortcode');
	//add_shortcode( 'list_shipping_zones', 'shipping_zones_shortcode', 10 );

 $location = WC_Geolocation::geolocate_ip();
 $country = $location['country'];
echo $country;
 // Lets use the country to e.g. echo greetings
  
 switch ($country) {
     case "IE":
         $hello = "Howya!";
         break;
     case "IN":
         $hello = "Namaste!";
         break;
     default:
         $hello = "Hello!";
 }
  
 echo $hello;

    return $items;
}

/*========================================================================================
				Forzar inicio de sesión en frontend
=========================================================================================*/
function redirect_login_page()
{
	// The URL to your login page
	$login_page  = site_url("/mi-cuenta");
	$page_viewed = basename($_SERVER["REQUEST_URI"]);
 
	if($page_viewed == "wp-login.php" AND $_SERVER["REQUEST_METHOD"] == "GET")
	{
		wp_redirect($login_page);
		exit;
	}
}
add_action("init","redirect_login_page");


?>