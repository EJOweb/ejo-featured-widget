<?php
/**
 * Plugin Name: EJO Featured Widget
 * Description: A widget to show title, image or icon, text and a button
 * Version: 	0.5
 * Author: 		EJOweb
 * Author URI: 	http://www.ejoweb.nl/
 * 
 * GitHub Plugin URI: https://github.com/EJOweb/ejo-featured-widget
 * GitHub Branch:     basiswebsite
 */

/**
 *
 */
final class EJO_Featured_Widget_Plugin
{
	//* Slug of this plugin
    const SLUG = 'ejo-featured-widget';

    //* Version number of this plugin
    const VERSION = '0.5';

    //* Stores the directory path for this plugin.
    public static $dir;

    //* Stores the directory URI for this plugin.
    public static $uri;

    //* Holds the instance of this class.
    protected static $_instance = null;

    //* Only instantiate once
    public static function init() 
    {
        if ( !self::$_instance )
            self::$_instance = new self;
        return self::$_instance;
    }

    //* No clones please!
    protected function __clone() {}

    //* Plugin setup.
    protected function __construct() 
    {
		//* Setup
        add_action( 'plugins_loaded', array( $this, 'setup' ), 1 );

        // Include required files
        include_once( self::$dir . 'includes/class-widget.php' );
		// include_once( self::$dir . 'includes/class-template-loader.php' );

		//* Add custom styles & scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles_and_scripts' ) );

        //* Register widget
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

    //* Defines the directory path and URI for the plugin.
    public function setup() 
    {
    	//* Set plugin dir and uri
        self::$dir = plugin_dir_path( __FILE__ ); // with trailing slash
        self::$uri = plugin_dir_url( __FILE__ ); // with trailing slash

        //* Load the translation for the plugin
        load_plugin_textdomain( self::SLUG, false, self::SLUG.'/languages' );
    }

    //* Admin Styles & Scripts
	function admin_styles_and_scripts( $hook )
	{
		if ($hook != 'widgets.php')
			return;

		//* Widget Style
		wp_enqueue_style( self::SLUG.'-admin', self::$uri.'css/admin-widget.css' );

		//* Widget Script
		wp_enqueue_script( self::SLUG.'-admin', self::$uri.'js/admin-widget.js', array( 'jquery' ) );
	}

    //* Register widgets
    public function widgets_init() 
    {
		register_widget( 'EJO_Featured_Widget' );
	}

	//* Check cross-plugin requirements
	public static function check_requirements()
	{
		$output = '';

		if ( ! function_exists( 'ejo_image_select' ) ) {
        	$output .= '<li>- <strong>ejo_image_select</strong>-function not available</li>';
		}

    	if ( ! class_exists( 'Better_Font_Awesome_Library' ) ) {
    		$output .= '<li>- <strong>Better_Font_Awesome_Library</strong>-class not available</li>';
    	}

    	//* Proces output
    	$output = !empty($output) ? "<ul>$output</ul>" : '';

    	return $output;
	}
}

/* Call the wrapper class */
EJO_Featured_Widget_Plugin::init();
