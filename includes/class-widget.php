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
		echo $args['before_widget'];

		//* Combine $instance data with defaults
        $instance = wp_parse_args( $instance, array( 
            'image_id' => '',
            'icon' => '',
            'title' => '',
            'text' => '',
            'linked_page_id' => '',
            'link_text' => __('Lees meer', self::SLUG),
        ));

        //* Run $instance['text'] through filter
		$instance['text'] = apply_filters( 'widget_text', $instance['text'], $instance, $this );

        //* Allow widget output to be filtered
        $filtered = apply_filters( 'ejo_featured_widget_output', '', $args, $instance, $this->id_base );

        //* If $inside is filtered, add it to output
        //* Else load template loader or default widget
        if ( $filtered ) {
        	echo $filtered;
        }
        else {
			$template_file_names = $this->get_template_file_names( $this->id_base, $args['id'] );
			$template_directories = $this->get_template_directories();

			$template_file = $this->get_template_location( $template_file_names, $template_directories );

			if ($template_file) {
				$this->load_template( $template_file, $args, $instance);
			}
			else {
				$this->render_default_widget($args, $instance);			
			}
        }   
		

		echo $args['after_widget'];
	}

	/**
	 * Load a template file.
	 *
	 * @since 4.0.0
	 *
	 * @param string $template_file Absolute path to a file or list of template parts.
	 * @param array  $data          Optional. List of variables to extract into the template scope.
	 */
	public function load_template( $template_file, $args, $instance ) {
		// global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		// if ( is_array( $data ) && ! empty( $data ) ) {
		// 	extract( $data, EXTR_SKIP );
		// 	unset( $data );
		// }

		if ( file_exists( $template_file ) ) {
			require( $template_file );
		}
	}
	
	/**
	 * Create the file names of templates.
	 *
	 * @return array
	 */
	protected function get_template_file_names( $widget_slug, $sidebar_slug ) 
	{
		$template_file_names = array();
		$template_file_names[] = $sidebar_slug . '_' . $widget_slug . '.php';
		$template_file_names[] = $widget_slug . '.php';

		return $template_file_names;
	}

	/**
	 * Return a list of paths to check for template locations.
	 *
	 * Default is to check in a child theme (if relevant) before a parent theme, so that themes which inherit from a
	 * parent theme can just overload one file. If the template is not found in either of those, it looks in the
	 * theme-compat folder last.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	protected function get_template_directories() 
	{
		$theme_template_directory = 'widget/';

		//* Prioritized order of template locations
		$template_directories = array();

		//* Start by setting theme location high in order
		$template_directories[10] = trailingslashit( get_template_directory() ) . $theme_template_directory;
		$template_directories[11] = trailingslashit( get_template_directory() );

		//* Set child theme location first if active
		if ( is_child_theme() ) {
			$template_directories[1] = trailingslashit( get_stylesheet_directory() ) . $theme_template_directory;
			$template_directories[2] = trailingslashit( get_stylesheet_directory() );
		}

		// Sort the file paths based on priority.
		ksort( $template_directories, SORT_NUMERIC );

		return array_map( 'trailingslashit', $template_directories );
	}


	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
	 * inherit from a parent theme can just overload one file. If the template is
	 * not found in either of those, it looks in the theme-compat folder last.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $template_file_names Template file(s) to search for, in order.
	 * @param bool         $load           If true the template file will be loaded if it is found.
	 * @param bool         $require_once   Whether to require_once or require. Default true.
	 *                                     Has no effect if $load is false.
	 *
	 * @return string The template filename if one is located.
	 */
	public function get_template_location( $template_file_names, $template_directories ) 
	{
		$located = false;

		//* Try to find a template file.
		foreach ( $template_file_names as $template_file_name ) {

			//* Trim off any slashes from the template name.
			$template_file_name = ltrim( $template_file_name, '/' );

			//* Try locating this template file by looping through the template paths.
			foreach ( $template_directories as $template_directory ) {

				if ( file_exists( $template_directory . $template_file_name ) ) {

					$located = $template_directory . $template_file_name;

					//* Break out both loops
					break 2;
				}
			}
		}

		return $located;
	}










	/**
	 * Render default widget
	 */
	public function render_default_widget($args, $instance)
	{
		$image_size = apply_filters( 'ejo_featured_widget_image_size', 'thumbnail' ); ?>

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

