<?php 
	// Base for form field name
	$feature_form_namebase = $this->get_field_name('features') . '[' . $i . ']';

	// Check if feature is active
	$featureIsActive = in_array_r($feature['name'], $active_features);

	// Active feature class name
	$featureActiveClass = ($featureIsActive) ? 'feature-active' : '';
?>

	<li class="ejo-feature ejo-feature-<?php echo $feature['name']; ?> <?php echo $featureActiveClass; ?>">
		<div class="ejo-feature-header">
			<input type="checkbox" name="<?php echo $feature_form_namebase; ?>[name]" value="<?php echo $feature['name']; ?>" <?php checked(in_array_r($feature['name'], $active_features)); ?> >
			<?php /*<input type="checkbox" name="<?php echo $feature_form_namebase; ?>[active]" value="<?php echo $feature['active']; ?>" <?php checked($feature['active']); ?> > */ ?>
			<div class="ejo-feature-name"><?php echo $feature['name']; ?></div>
		</div>
		<div class="ejo-feature-content">
		
<?php
		switch ($feature['name']) {
			case 'image':
				$id_prefix = $this->get_field_id('');
				$attachment_id = (isset($feature['attachment_id'])) ? abs($feature['attachment_id']) : '';
				$imageurl = (isset($feature['imageurl'])) ? $feature['imageurl'] : '';
			?>
				<div class="uploader">
					<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="Select an Image" onclick="EJO_Featured_Widget.uploader( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' ); return false;" />
					<div class="ejo-image-preview" id="<?php echo $this->get_field_id('preview'); ?>">
						<?php echo wp_get_attachment_image( $attachment_id, $this->imagesize ); ?>
					</div>

					<!-- onderstaande 2 instances kent hij niet -->
					<input type="hidden" id="<?php echo $this->get_field_id('attachment_id'); ?>" name="<?php echo $feature_form_namebase; ?>[attachment_id]" value="<?php echo $attachment_id; ?>" />
					<input type="hidden" id="<?php echo $this->get_field_id('imageurl'); ?>" name="<?php echo $feature_form_namebase; ?>[imageurl]" value="<?php echo $imageurl; ?>" />
				</div>
			<?php
				break;
			
			case 'title':
				$feature_title = !empty($feature['title']) ? esc_attr($feature['title']) : '';
				echo '<input type="text" name="'.$feature_form_namebase.'[title]" value="'.$feature_title.'" placeholder="Your Featured Title">';
				break;

			case 'subtitle':
				$feature_subtitle = !empty($feature['subtitle']) ? esc_attr($feature['subtitle']) : '';
				echo '<input type="text" name="'.$feature_form_namebase.'[subtitle]" value="'.$feature_subtitle.'" placeholder="Your Featured Subtitle">';
				break;
			
			case 'info':
				$feature_info = !empty($feature['info']) ? esc_textarea($feature['info']) : '';
				echo '<textarea name="'.$feature_form_namebase.'[info]" placeholder="Some space to fill in Information">' . $feature_info . '</textarea>';
				break;
			
			case 'linktext':
				$feature_linktext = !empty($feature['linktext']) ? esc_attr($feature['linktext']) : '';
				echo '<input type="text" name="'.$feature_form_namebase.'[linktext]" value="'.$feature_linktext.'" placeholder="Read more linktext">';
				break;

			default:
				# code...
				break;
		}
		
?>
		<!--<input type="hidden" name="<?php echo $feature_form_namebase; ?>[name]" value="<?php echo $feature['name']; ?>">-->		
		</div>
	</li>