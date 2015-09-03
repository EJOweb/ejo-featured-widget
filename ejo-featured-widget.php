<?php
/*
	Plugin Name: Featured Widget by EJOweb
	Description: Flexible widget to create featured content in Widget Areas. Images, icons, titles, info, link and url.
	Version: 0.1.1
	Author: EJOweb
	Author URI: http://www.ejoweb.nl/
	
	GitHub Plugin URI: https://github.com/EJOweb/ejo-featured-widget.git
 */

//* Als ik wil dat features die worden uitgeschakeld nog wel informatie bevatten dan moet ik een variabele 'active' toevoegen waarop gechecked wordt. 

/** 
 * Register Widget
 */
function ejo_featured_widget() {
	register_widget( 'EJO_Featured_Widget' );
}
add_action( 'widgets_init', 'ejo_featured_widget' );

require_once( plugin_dir_path(__FILE__) . '/inc/helpers.php' );

/**
 * EJO Featured Content Class
 *
 * @author       Erik Joling <erik@ejoweb.nl>
 * @copyright    Copyright (c) 2014, Erik Joling
 */
class EJO_Featured_Widget extends WP_Widget {

	var $title = 'EJO Featured Widget';
	var $tag = 'ejo-featured-widget';
	var $db = '0.1.1';
	var $feature_list = array( 'image', 'title', 'subtitle', 'info', 'linktext' );
	var $imagesize = 'home-featured';

	public function __construct() {

		$widget_args = array( 'classname' => $this->tag, 'description' => 'Flexible Featured Content' );
		parent::__construct( $this->tag, $this->title, $widget_args );

		// Register styles and scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts_styles' ) );

	}

	/**
	 * Registers and enqueues admin-specific JavaScript and CSS only on widgets page.
	 */	
	public function register_admin_scripts_styles($hook) {

		wp_enqueue_script( $this->tag .'-admin-script', plugins_url( $this->tag . '/js/admin.js' ), array('jquery'), false, true );
		wp_enqueue_style( $this->tag .'-admin-styles', plugins_url( $this->tag . '/css/admin.css' ) );
		
		// Image Widget
		wp_enqueue_media();		
		wp_enqueue_script( $this->tag .'-image-admin-script', plugins_url( $this->tag . '/js/image-widget.js' ), array( 'jquery', 'media-upload', 'media-views' ), false, true );
		wp_localize_script( $this->tag .'-image-admin-script', 'EjoFeaturedWidget', array(
			'frame_title' => __( 'Select an Image', 'image_widget' ),
			'button_title' => __( 'Insert Into Widget', 'image_widget' ),
		) );

	} 

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		include( plugin_dir_path( __FILE__ ) . '/inc/widget.php' );

		echo $args['after_widget'];
	}

 	public function form( $instance ) {

 		// Extracting defined values and defining default values for variables
	    $instance = wp_parse_args( (array) $instance, array( 
			'title' => '', 
			'features' => array(),
			'url' => ''
		));

		// $title 				= esc_attr( $instance['title'] );
		$url 				= esc_url( $instance['url'] );
		$active_features	= $instance['features'];

		// Store all active features first in (array) $all_features
		$all_features = $active_features;
		
		// Append non-active features to (array) $all_features
		foreach ($this->feature_list as $feature) {
			if ( !in_array_r( $feature, $active_features ) ) {
				$all_features[] = array( 
					'name' => $feature
				);
			}
		}

		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . 'inc/admin-widget.php' );

	}

	public function update( $new_instance, $old_instance ) {

		// Keep previously stored data
		$instance = $old_instance;
		
		// Overwrite with new data
		// $instance['title'] = esc_attr( $new_instance['title'] );
		$instance['title'] = '';
		$instance['url'] = esc_url( $new_instance['url'] );

		$active_features = array();
		// Get all features where 'name' is checked
		foreach ($new_instance['features'] as $single_feature) {
			if (array_key_exists( 'name', $single_feature )) {
				$active_features[] = $single_feature;
			}
		}

		// Store all active features
		$instance['features'] = $active_features;

		return $instance;
	}

	/**
	 * Print features to admin widget
	 */
	public function print_admin_feature( $feature, $active_features, $i ) {
		// Include print feature in admin widget
		include( plugin_dir_path(__FILE__) . '/inc/admin-print-feature.php' );	
	}

	/**
	 * Print features to frontend
	 */
	public function print_feature( $feature ) {
		// Include print features in frontend widget
		include( plugin_dir_path(__FILE__) . '/inc/print-feature.php' );	
	}

}

