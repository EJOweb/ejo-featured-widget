<?php
/*
	Plugin Name: Featured Widget
	Description: A widget to show title, image or icon, text and a button
	Version: 0.2
	Author: EJOweb
	Author URI: http://www.ejoweb.nl/
	
	GitHub Plugin URI: https://github.com/EJOweb/ejo-featured-widget.git
	GitHub Branch:     basiswebsite
 */

//* Register Widget
add_action( 'widgets_init', function() { 
    register_widget( 'EJO_Featured_Widget' ); 
} );

//* Add custom styles & scripts
add_action( 'admin_enqueue_scripts', 'ejo_featured_widget_styles_and_scripts', 99 );

/**
 *
 */
function ejo_featured_widget_styles_and_scripts($hook)
{
	if ($hook == 'widgets.php') {
		/**
		 * Scripts
		 */
		wp_enqueue_script( 'ejo-featured-widget-admin', plugins_url( 'js/admin-widget.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'ejo-image-select', plugins_url( 'js/admin-image-select.js', __FILE__ ), array( 'jquery' ) );


		/**
		 * Styles
		 */
    	wp_enqueue_media();     
		wp_enqueue_style( 'ejo-featured-widget-admin', plugins_url( 'css/admin-widget.css', __FILE__ ) );
		wp_enqueue_style( 'ejo-image-select',  plugins_url( 'css/admin-image-select.css', __FILE__ ) );
	}
}

/**
 * Class used to implement a Text Widget widget.
 */
final class EJO_Featured_Widget extends WP_Widget
{
	/**
	 * Sets up a new widget instance.
	 */
	function __construct() 
	{
		$widget_title = __('Featured Widget', 'ejoweb');

		$widget_info = array(
			'classname'   => 'featured-widget',
			'description' => __('Displays a simple widget with title, image/icon, text and button', 'ejoweb'),
		);

		$widget_control = array( 'width' => 600 );

		parent::__construct( 'ejo-featured-widget', $widget_title, $widget_info, $widget_control );
	}

	/**
	 * Outputs the content for the current widget instance.
	 */
	public function widget( $args, $instance ) 
	{
		/** 
		 * Combine $instance data with defaults
		 * Then extract variables of this array
		 */
        extract( wp_parse_args( $instance, array( 
            'image_id' => '',
            'icon' => '',
            'title' => '',
            'text' => '',
            'linked_page_id' => '',
            'link_text' => __('Lees meer', 'ejo-featured-widget'),
        )));

        /* Run $text through filter */
		$text = apply_filters( 'widget_text', $text, $instance, $this );
		?>

		<?php echo $args['before_widget']; ?>

		<?php if (!empty($image_id)) : // Check if there is an image_id ?>
			
			<div class="featured-image-container">
				<?php echo wp_get_attachment_image( $image_id, 'featured', false, array('class'=>'featured-image') ); ?>
			</div>

		<?php endif; // END image_id check ?>

		<?php if (!empty($icon)) : // Check if there is an icon ?>

			<div class="icon-container">
				<i class="fa <?php echo $icon; ?>"></i>
			</div>

		<?php endif; // END icon check ?>

		<?php 
		if (!empty($title)) { 
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>

		<?php if (!empty($text)) : // Check if there is text ?>

			<div class="textwidget"><?php echo wpautop($text); ?></div>

		<?php endif; // END text check ?>

		<?php if (!empty($linked_page_id)) : ?>
			<a href="<?php echo get_the_permalink($linked_page_id); ?>" class="button"><?php echo $link_text; ?></a>
		<?php endif; // Show button ?>

		<?php echo $args['after_widget']; ?>

		<?php
	}

	/**
	 * Outputs the widget settings form.
	 */
 	public function form( $instance ) 
 	{
		/** 
		 * Combine $instance data with defaults
		 * Then extract variables of this array
		 */
        extract( wp_parse_args( $instance, array( 
            'image_id' => '',
            'icon' => '',
            'title' => '',
            'text' => '',
            'linked_page_id' => '',
            'link_text' => '',
        )));

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>

		<div class="ejo-image-upload">
            <label>Uitgelichte afbeelding</label>
            <p class="image-container">
                <?php if ( $image_id ) : ?>

                    <?php echo wp_get_attachment_image( $image_id, 'thumbnail', false ); ?>

                <?php endif; ?>
            </p>

            <input type="hidden" id="<?php echo $this->get_field_id('image_id'); ?>" name="<?php echo $this->get_field_name('image_id'); ?>" value="<?php echo $image_id; ?>" class="image-id" />
            <a class="button upload-button" href="#">Kies een afbeelding</a>
            <a class="button remove-button" href="#">Verwijder</a>
        </div>

        <p>
			<label for="<?php echo $this->get_field_id('icon'); ?>"><?php _e('Icon:') ?></label>
			<input type="text" class="widefat ejo-icon-picker" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" value="<?php echo $icon; ?>" />
			<?php //<span class="input-group-addon"><i class="fa fa-archive"></i></span> ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:') ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="5"><?php echo $text; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('linked_page_id'); ?>"><?php _e('Linken naar pagina:') ?></label>
			<select id="<?php echo $this->get_field_id('linked_page_id'); ?>" name="<?php echo $this->get_field_name('linked_page_id'); ?>" class="widefat">
				<?php
					$all_pages = get_pages();

					foreach ($all_pages as $page) {
						$selected = selected($linked_page_id, $page->ID, false);
						echo "<option value='".$page->ID."' ".$selected.">".$page->post_title."</option>";
					}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Link tekst:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" value="<?php echo $link_text; ?>" />
		</p>

		<?php
	}

	/**
	 * Handles updating settings for the current widget instance.
	 */
	public function update( $new_instance, $old_instance ) 
	{
		/* Store old instance as defaults */
		$instance = $old_instance;

		/* Store new title */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* Store image id */
		$instance['image_id'] = $new_instance['image_id'];

		/* Store icon */
		$instance['icon'] = $new_instance['icon'];

		/* Store text */
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = wp_kses_post( stripslashes( $new_instance['text'] ) );

		/* Store link */
		$instance['linked_page_id'] = $new_instance['linked_page_id'];

		/* Store link_text */
		$instance['link_text'] = $new_instance['link_text'];

		/* Return updated instance */
		return $instance;
	}
}

