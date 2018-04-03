<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://multidots.com/
 * @since             1.0.0
 * @package           Woo_Shipping_Display_Mode
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Shipping Method Display Style
 * Plugin URI:        http://multidots.com/
 * Description:       This plugin provides a configuration to display shipping methods as Radio button or select box on the checkout page.
 * Version:           1.9
 * Author:            Multidots
 * Author URI:        http://multidots.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-shipping-display-mode
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-shipping-display-mode-activator.php
 */
function activate_woo_shipping_display_mode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-shipping-display-mode-activator.php';
	Woo_Shipping_Display_Mode_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-shipping-display-mode-deactivator.php
 */
function deactivate_woo_shipping_display_mode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-shipping-display-mode-deactivator.php';
	Woo_Shipping_Display_Mode_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_shipping_display_mode' );
register_deactivation_hook( __FILE__, 'deactivate_woo_shipping_display_mode' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-shipping-display-mode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_shipping_display_mode() {

	$plugin = new Woo_Shipping_Display_Mode();
	$plugin->run();

}
run_woo_shipping_display_mode();

function myplugin_plugin_path() {

  return untrailingslashit( plugin_dir_path( __FILE__ ) );
}