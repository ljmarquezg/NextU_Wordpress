<?php

/**
 * Fired during plugin activation
 *
 * @link       http://multidots.com/
 * @since      1.0.0
 *
 * @package    Woo_Shipping_Display_Mode
 * @subpackage Woo_Shipping_Display_Mode/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Shipping_Display_Mode
 * @subpackage Woo_Shipping_Display_Mode/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Woo_Shipping_Display_Mode_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() { 
		
		global $wpdb;	
		set_transient( '_welcome_screen_shipping_display_mode_activation_redirect_data', true, 30 );
	}
}