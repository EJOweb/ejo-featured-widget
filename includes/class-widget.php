<?php
/**
 * Class used to implement a Text Widget widget.
 */
final class EJO_Featured_Widget extends WP_Widget
{
	//* Slug of this widget
    const SLUG = EJO_Featured_Widget_Plugin::SLUG;

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

		$widget_control = array( 'width' => 400 );

		parent::__construct( self::SLUG, $widget_title, $widget_info, $widget_control );
	}

	/**
	 * Outputs the content for the current widget instance.
	 */
	public function widget( $args, $instance ) 
	{
		//* Combine $instance data with defaults
        $instance = wp_parse_args( $instance, array( 
            'image_id' => '',
            'icon' => '',
            'title' => '',
            'text' => '',
            'linked_page_id' => '',
            'link_text' => __('Lees meer', self::SLUG),
        ));

        //* Allow theme to override image size
        $image_size = apply_filters( 'ejo_featured_widget_image_size', 'thumbnail' );

        //* Run $instance['text'] through filter
		$instance['text'] = apply_filters( 'widget_text', $instance['text'], $instance, $this );
		?>

		<?php echo $args['before_widget']; ?>

		<?php if (!empty($instance['image_id'])) : // Check if there is an image_id ?>
			
			<div class="featured-image-container">
				<?php echo wp_get_attachment_image( $instance['image_id'], $image_size, false, array('class'=>'featured-image') ); ?>
			</div>

		<?php endif; // END image_id check ?>

		<?php if (!empty($instance['icon'])) : // Check if there is an icon ?>

			<div class="icon-container">
				<i class="fa <?php echo $instance['icon']; ?>"></i>
			</div>

		<?php endif; // END icon check ?>

		<?php 
		if (!empty($instance['title'])) { 
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}
		?>

		<?php if (!empty($instance['text'])) : // Check if there is text ?>

			<div class="textwidget"><?php echo wpautop($instance['text']); ?></div>

		<?php endif; // END text check ?>

		<?php if (!empty($instance['linked_page_id'])) : ?>
			<a href="<?php echo get_the_permalink($instance['linked_page_id']); ?>" class="button"><?php echo $instance['link_text']; ?></a>
		<?php endif; // Show button ?>

		<?php echo $args['after_widget']; ?>

		<?php
	}

	/**
	 * Outputs the widget settings form.
	 */
 	public function form( $instance ) 
 	{
		//* Combine $instance data with defaults
        $instance = wp_parse_args( $instance, array( 
            'image_id' => '',
            'icon' => '',
            'title' => '',
            'text' => '',
            'linked_page_id' => '',
            'link_text' => __('Lees meer', self::SLUG),
        ));

        echo EJO_Featured_Widget_Plugin::check_requirements();
        ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<?php 

		//* Add image
		if ( function_exists( 'ejo_image_select' ) ) {
			ejo_image_select( $instance['image_id'], $this->get_field_id('image_id'), $this->get_field_name('image_id') );
		}

      	?>

        <div class="form-group ejo-iconpicker-container">
			<label for="<?php echo $this->get_field_id('icon'); ?>"><?php _e('Icon:') ?></label>
			<div class="input-group">
				<input type="text" class="widefat form-control ejo-iconpicker" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" value="<?php echo $instance['icon']; ?>" />
				<span class="ejo-iconpicker-component">
					<?php echo !empty($instance['icon']) ? '<i class="fa '.$instance['icon'].'"></i>' : ''; ?>
				</span>
			</div>
		</div>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:') ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="5"><?php echo $instance['text']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('linked_page_id'); ?>"><?php _e('Linken naar pagina:') ?></label>
			<select id="<?php echo $this->get_field_id('linked_page_id'); ?>" name="<?php echo $this->get_field_name('linked_page_id'); ?>" class="widefat">
				<?php
					$all_pages = get_pages();

					foreach ($all_pages as $page) {
						$selected = selected($instance['linked_page_id'], $page->ID, false);
						echo "<option value='".$page->ID."' ".$selected.">".$page->post_title."</option>";
					}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Link tekst:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" value="<?php echo $instance['link_text']; ?>" />
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

