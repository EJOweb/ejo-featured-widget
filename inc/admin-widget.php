<div>
	<?php
	/*
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</p>
	*/
	?>

	<ul class="ejo-features-list-admin">
	<?php 
		$i = 0; // Order of features
		foreach( $all_features as $feature )
		{
			// Print feature
			$this->print_admin_feature($feature, $active_features, $i);
			$i++; 			
		}
	?> 
	</ul>

	<p>
		<label for="<?php echo $this->get_field_id('url'); ?>">URL</label>
		<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
	</p>

</div>