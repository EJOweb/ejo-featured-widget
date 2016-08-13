<?php 
	//* Run $instance['text'] through filter
	$instance['text'] = apply_filters( 'widget_text', $instance['text'], $instance, $this );
	?>


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
	