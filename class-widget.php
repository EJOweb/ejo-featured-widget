<?php
/**
 * Class used to implement a Text Widget widget.
 */
final class EJO_Featured_Widget extends WP_Widget
{
	//* Slug of this widget
    const SLUG = EJO_Featured_Widget_Plugin::SLUG;

    const VERSION = EJO_Featured_Widget_Plugin::VERSION;

    public static $uri;

	/**
	 * Sets up a new widget instance.
	 */
	function __construct() 
	{
		//* Set uri
		self::$uri = EJO_Featured_Widget_Plugin::$uri;

		$widget_title = __('Featured Widget', self::SLUG);

		$widget_info = array(
			'classname'   => 'featured-widget',
			'description' => __('Displays a simple widget with title, image/icon, text and button', self::SLUG),
		);

		$widget_control = array( 'width' => 400 );

		parent::__construct( self::SLUG, $widget_title, $widget_info, $widget_control );

		//* Just register... not only when using widget... ?
		add_action( 'admin_enqueue_scripts', array( $this, 'register_ejo_image_picker_files' ) );
	}

	/**
	 * Registers and enqueues admin-specific JavaScript and CSS only on widgets page.
	 */ 
	function register_ejo_image_picker_files($hook) 
	{
		if ($hook != 'widgets.php')
			return;

	    // Image Widget
	    wp_enqueue_media();     
	    wp_enqueue_script( 'ejo-image-picker', self::$uri . 'js/admin-image-picker.js', array('jquery'), self::VERSION, true );
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

		//* Check if Widget Template Loader exists and try to load template
		if ( class_exists( 'EJO_Widget_Template_Loader' ) && EJO_Widget_Template_Loader::load_template( $args, $instance, $this ) ) 
			return;

		//* Allow filtered widget-output
		$filtered_output = apply_filters( 'ejo_featured_widget_output', '', $args, $instance, $this );

		//* Print filtered_output
		echo $filtered_output;

		//* If no filtered output show default widget 
		if ( ! $filtered_output ) {
			$this->render_default_widget($args, $instance);
		}			
	}


	/**
	 * Render default widget
	 */
	public function render_default_widget($args, $instance)
	{
		echo $args['before_widget'];

		$image_size = apply_filters( 'ejo_featured_widget_image_size', 'thumbnail' ); 
		$image_class = apply_filters( 'ejo_featured_widget_image_class', 'featured-image' ); 

		//* Get pattern for linking
		if ($instance['linked_page_id']) {
			$link = get_the_permalink($instance['linked_page_id']);
			$link_title = the_title_attribute( array( 'echo' => false, 'post' => $instance['linked_page_id'] ) );

			$link_pattern = '<a href="'.$link.'" title="'.$link_title.'">%1$s</a>';
		}
		else {
			$link_pattern = '%s';
		}
		?>

		<?php if ($instance['image_id']) : // Check if there is an image_id ?>
				
			<div class="image-container">
				<?php printf( $link_pattern, wp_get_attachment_image( $instance['image_id'], $image_size, false, array('class'=>$image_class) ) ); ?> 
			</div>

		<?php elseif ($instance['icon']) : // Check if there is an icon ?>

			<div class="icon-container">
				<i class="fa <?php echo $instance['icon']; ?>"></i>
			</div>

		<?php endif; // END image_id and icon check ?>

		<div class="entry-header">		
			<?php printf( '<h2 class="entry-title">'.$link_pattern.'</h2>', $instance['title'] ); ?> 
		</div>

		<div class="entry-content">
			<?php echo wpautop($instance['text']); ?>
		</div>

		<?php if ( $instance['linked_page_id'] && $instance['link_text'] ) : ?>

			<div class="entry-footer">
				<?php printf( $link_pattern, $instance['link_text'] ); ?> 
			</div>

		<?php endif; // END link check ?>

		<?php
		echo $args['after_widget'];
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

      	<div class="ejo-image-picker">
	        <label><?php _e( 'Featured Image' ); ?></label>
	        <p class="image-container">
	            <?php if ( $instance['image_id'] ) : ?>

	                <?php echo wp_get_attachment_image( $instance['image_id'], 'thumbnail', false ); ?>

	            <?php endif; ?>
	        </p>

	        <input type="hidden" id="<?php echo $this->get_field_id('image_id'); ?>" name="<?php echo $this->get_field_name('image_id'); ?>" value="<?php echo $instance['image_id']; ?>" class="image-id" />
	        <a class="button picker-button" href="#"><?php _e('Pick an image'); ?></a>
	        <a class="button remove-button" href="#"><?php _e('Remove the image'); ?></a>
	    </div>

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
				<option value="">-- Geen link --</option>
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

