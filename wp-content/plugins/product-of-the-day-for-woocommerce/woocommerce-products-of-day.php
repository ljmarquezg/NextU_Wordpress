<?php
/**
 * Plugin Name: Product of the Day for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/product-of-the-day-for-woocommerce/
 * Description: Promote products from your shop to your customers on each day of the week
 * Version: 1.0.9
 * Author: BeRocket
 * Requires at least: 4.0
 * Author URI: http://berocket.com
 * Text Domain: BeRocket_products_of_day_domain
 * Domain Path: /languages/
 */
define( "BeRocket_products_of_day_version", '1.0.9' );
define( "BeRocket_products_of_day_domain", 'BeRocket_products_of_day_domain'); 
define( "products_of_day_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('BeRocket_products_of_day_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'includes/admin_notices.php');
require_once(plugin_dir_path( __FILE__ ).'includes/functions.php');
require_once(plugin_dir_path( __FILE__ ).'includes/widget.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class BeRocket_products_of_day {

    public static $info = array( 
        'id'        => 16,
        'version'   => BeRocket_products_of_day_version,
        'plugin'    => '',
        'slug'      => '',
        'key'       => '',
        'name'      => ''
    );

    /**
     * Defaults values
     */
    public static $defaults = array(
        'products'          => array(
            '0'                 => array(),
            '1'                 => array(),
            '2'                 => array(),
            '3'                 => array(),
            '4'                 => array(),
            '5'                 => array(),
            '6'                 => array(),
            '7'                 => array(),
        ),
        'buttons'           => array(),
        'custom_css'        => '',
        'script'            => array(
            'js_page_load'      => '',
        ),
    );
    public static $values = array(
        'settings_name' => 'br-products_of_day-options',
        'option_page'   => 'br-products_of_day',
        'premium_slug'  => 'woocommerce-products-of-day',
    );
    
    function __construct () {
        register_uninstall_hook(__FILE__, array( __CLASS__, 'deactivation' ) );

        if ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) && 
            br_get_woocommerce_version() >= 2.1 ) {
            $options = self::get_option();
            
            add_action ( 'init', array( __CLASS__, 'init' ) );
            add_action ( 'wp_head', array( __CLASS__, 'set_styles' ) );
            add_action ( 'admin_init', array( __CLASS__, 'admin_init' ) );
            add_action ( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
            add_action ( 'admin_menu', array( __CLASS__, 'options' ) );
            add_action( 'current_screen', array( __CLASS__, 'current_screen' ) );
            add_action( "wp_ajax_br_products_of_day_settings_save", array ( __CLASS__, 'save_settings' ) );
            add_action ( "widgets_init", array ( __CLASS__, 'widgets_init' ) );
            add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
            $plugin_base_slug = plugin_basename( __FILE__ );
            add_filter( 'plugin_action_links_' . $plugin_base_slug, array( __CLASS__, 'plugin_action_links' ) );
            add_filter( 'is_berocket_settings_page', array( __CLASS__, 'is_settings_page' ) );
        }
        add_filter('berocket_admin_notices_subscribe_plugins', array(__CLASS__, 'admin_notices_subscribe_plugins'));
    }
    public static function admin_notices_subscribe_plugins($plugins) {
        $plugins[] = self::$info['id'];
        return $plugins;
    }
    public static function is_settings_page($settings_page) {
        if( ! empty($_GET['page']) && $_GET['page'] == self::$values[ 'option_page' ] ) {
            $settings_page = true;
        }
        return $settings_page;
    }
    public static function plugin_action_links($links) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page='.self::$values['option_page'] ) . '" title="' . __( 'View Plugin Settings', 'BeRocket_products_label_domain' ) . '">' . __( 'Settings', 'BeRocket_products_label_domain' ) . '</a>',
		);
		return array_merge( $action_links, $links );
    }
    public static function plugin_row_meta($links, $file) {
        $plugin_base_slug = plugin_basename( __FILE__ );
        if ( $file == $plugin_base_slug ) {
			$row_meta = array(
				'docs'    => '<a href="http://berocket.com/docs/plugin/'.self::$values['premium_slug'].'" title="' . __( 'View Plugin Documentation', 'BeRocket_products_label_domain' ) . '" target="_blank">' . __( 'Docs', 'BeRocket_products_label_domain' ) . '</a>',
				'premium'    => '<a href="http://berocket.com/product/'.self::$values['premium_slug'].'" title="' . __( 'View Premium Version Page', 'BeRocket_products_label_domain' ) . '" target="_blank">' . __( 'Premium Version', 'BeRocket_products_label_domain' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
    }
    public static function widgets_init() {
        register_widget("berocket_products_of_day_widget");
    }

    public static function init () {
        $options = self::get_option();
        wp_enqueue_script("jquery");
        wp_register_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );
        wp_enqueue_style( 'font-awesome' );
        wp_enqueue_script( 'berocket_products_of_day_main', 
            plugins_url( 'js/frontend.js', __FILE__ ), 
            array( 'jquery' ), 
            BeRocket_products_of_day_version );
        wp_register_style( 'berocket_products_of_day_style', 
            plugins_url( 'css/frontend.css', __FILE__ ), 
            "", 
            BeRocket_products_of_day_version );
        wp_enqueue_style( 'berocket_products_of_day_style' );

        wp_localize_script(
            'berocket_products_of_day_main',
            'the_products_of_day_js_data',
            array(
                'script' => apply_filters( 'berocket_products_of_day_user_func', $options['script'] ),
            )
        );
    }
    /**
     * Function set styles in wp_head WordPress action
     *
     * @return void
     */
    public static function set_styles () {
        $options = self::get_option();
        echo '<style>'.$options['custom_css'].'</style>';
    }
    /**
     * Load template
     *
     * @access public
     *
     * @param string $name template name
     *
     * @return void
     */
    public static function br_get_template_part( $name = '' ) {
        $template = '';

        // Look in your_child_theme/woocommerce-products_of_day/name.php
        if ( $name ) {
            $template = locate_template( "woocommerce-products_of_day/{$name}.php" );
        }

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( products_of_day_TEMPLATE_PATH . "{$name}.php" ) ) {
            $template = products_of_day_TEMPLATE_PATH . "{$name}.php";
        }

        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( 'products_of_day_get_template_part', $template, $name );

        if ( $template ) {
            load_template( $template, false );
        }
    }

    public static function admin_enqueue_scripts() {
        if ( function_exists( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
        }
    }

    /**
     * Function adding styles/scripts and settings to admin_init WordPress action
     *
     * @access public
     *
     * @return void
     */
    public static function admin_init () {
        wp_enqueue_script( 'berocket_products_of_day_admin', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), BeRocket_products_of_day_version );
        wp_register_style( 'berocket_products_of_day_admin_style', plugins_url( 'css/admin.css', __FILE__ ), "", BeRocket_products_of_day_version );
        wp_enqueue_style( 'berocket_products_of_day_admin_style' );
        wp_enqueue_script( 'berocket_global_admin', plugins_url( 'js/admin_global.js', __FILE__ ), array( 'jquery' ) );
        wp_localize_script( 'berocket_global_admin', 'berocket_global_admin', array(
            'security' => wp_create_nonce("search-products")
        ) );
    }
    /**
     * Function add options button to admin panel
     *
     * @access public
     *
     * @return void
     */
    public static function options() {
        add_submenu_page( 'woocommerce', __('Products of Day settings', 'BeRocket_products_of_day_domain'), __('Products of Day', 'BeRocket_products_of_day_domain'), 'manage_options', 'br-products_of_day', array(
            __CLASS__,
            'option_form'
        ) );
    }
    /**
     * Function add options form to settings page
     *
     * @access public
     *
     * @return void
     */
    public static function option_form() {
        $plugin_info = get_plugin_data(__FILE__, false, true);
        $paid_plugin_info = self::$info;
        include products_of_day_TEMPLATE_PATH . "settings.php";
    }
    /**
     * Function remove settings from database
     *
     * @return void
     */
    public static function deactivation () {
        delete_option( self::$values['settings_name'] );
    }
    public static function save_settings () {
        if( current_user_can( 'manage_options' ) ) {
            if( isset($_POST[self::$values['settings_name']]) ) {
                update_option( self::$values['settings_name'], self::sanitize_option($_POST[self::$values['settings_name']]) );
                echo json_encode($_POST[self::$values['settings_name']]);
            }
        }
        wp_die();
    }

    public static function current_screen() {
        $screen = get_current_screen();
        if(strpos($screen->id, 'br-products_of_day') !== FALSE) {
            wp_enqueue_script( 'jquery-ui-core', 'jquery' );
            wp_enqueue_script( 'jquery-ui-sortable', 'jquery' );
        }
    }

    public static function sanitize_option( $input ) {
        $default = self::$defaults;
        $result = self::recursive_array_set( $default, $input );
        return $result;
    }
    public static function recursive_array_set( $default, $options ) {
        $result = array();
        foreach( $default as $key => $value ) {
            if( array_key_exists( $key, $options ) ) {
                if( is_array( $value ) ) {
                    if( is_array( $options[$key] ) ) {
                        $result[$key] = self::recursive_array_set( $value, $options[$key] );
                    } else {
                        $result[$key] = self::recursive_array_set( $value, array() );
                    }
                } else {
                    $result[$key] = $options[$key];
                }
            } else {
                if( is_array( $value ) ) {
                    $result[$key] = self::recursive_array_set( $value, array() );
                } else {
                    $result[$key] = '';
                }
            }
        }
        foreach( $options as $key => $value ) {
            if( ! array_key_exists( $key, $result ) ) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    public static function get_option() {
        $options = get_option( self::$values['settings_name'] );
        if ( @ $options && is_array ( $options ) ) {
            $options = array_merge( self::$defaults, $options );
        } else {
            $options = self::$defaults;
        }
        return $options;
    }
}

new BeRocket_products_of_day;

berocket_admin_notices::generate_subscribe_notice();

/**
 * Creating admin notice if it not added already
 */
new berocket_admin_notices(array(
    'start' => 1505100638, // timestamp when notice start
    'end'   => 1506816001, // timestamp when notice end
    'name'  => 'SALE_LABELS', //notice name must be unique for this time period
    'html'  => 'Only <strong>$9.6</strong> for <strong>Premium</strong> WooCommerce Advanced Product Labels!
            <a class="berocket_button" href="http://berocket.com/product/woocommerce-advanced-product-labels" target="_blank">Buy Now</a>
             &nbsp; <span>Get your <strong class="red">60% discount</strong> and save <strong>$14.4</strong> today</span>
            ', //text or html code as content of notice
    'righthtml'  => '<a class="berocket_no_thanks">No thanks</a>', //content in the right block, this is default value. This html code must be added to all notices
    'rightwidth'  => 80, //width of right content is static and will be as this value. berocket_no_thanks block is 60px and 20px is additional
    'nothankswidth'  => 60, //berocket_no_thanks width. set to 0 if block doesn't uses. Or set to any other value if uses other text inside berocket_no_thanks
    'contentwidth'  => 910, //width that uses for mediaquery is image + contentwidth + rightwidth + 210 other elements
    'subscribe'  => false, //add subscribe form to the righthtml
    'priority'  => 7, //priority of notice. 1-5 is main priority and displays on settings page always
    'height'  => 50, //height of notice. image will be scaled
    'repeat'  => '+2 week', //repeat notice after some time. time can use any values that accept function strtotime
    'repeatcount'  => 2, //repeat count. how many times notice will be displayed after close
    'image'  => array(
        'local' => plugin_dir_url( __FILE__ ) . 'images/ad_white_on_orange.png', //notice will be used this image directly
    ),
));
