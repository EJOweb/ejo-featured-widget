<?php		
	// outputs the content of the widget
	// $title = apply_filters( 'widget_title', $instance['title'] );

	// if ( !empty( $title ) )
	// 	echo $args['before_title'] . $title . $args['after_title'];

	$url = $instance['url'];

	foreach ($instance['features'] as $feature) 
	{
		// $this->print_feature($feature);
		?>
		<?php /* <div class="ejo-feature ejo-feature-<?php echo $feature['name']; ?>"> */ ?>
			<?php /*<div class="feature-container">*/ ?>
		<?php
			switch($feature['name']) 
			{
				case 'title' :
					// echo (!empty($url)) ? '<a href="'.$url.'">' : '';
					echo '<h2>'.$feature['title'].'</h2>';
					// echo (!empty($url)) ? '</a>' : '';
		 			break;

		 		case 'subtitle' :
					// echo (!empty($url)) ? '<a href="'.$url.'">' : '';
					echo '<h4>'.$feature['subtitle'].'</h4>';
					// echo (!empty($url)) ? '</a>' : '';
		 			break;

		 		case 'image' :
		 			$attachment_id = (isset($feature['attachment_id'])) ? abs($feature['attachment_id']) : '';

		 			if (!empty($attachment_id)) {
		 				echo '<div class="ejo-feature-image">';
			 			echo (!empty($url)) ? '<a href="'.$url.'" class="ejo-feature-'.$feature['name'].'-link">' : '';
						echo wp_get_attachment_image( $attachment_id, $this->imagesize );
						echo (!empty($url)) ? '</a>' : '';
		 				echo '</div>';
					}
		 			break;

		 		case 'linktext' :
					echo (!empty($url)) ? '<a href="'.$url.'" class="more-link">' : '';
					echo $feature['linktext'];
					echo (!empty($url)) ? '</a>' : '';
		 			break;

		 		case 'info' :
		 			echo '<div class="ejo-feature-info">';
					echo $feature['info'];
					echo '</div>';
		 			break;

		 	default:
				# code...
				break;
			}
		?>
			<?php /*</div>*/ ?>
		<?php /* </div> */ ?>
		<?php
	} //end foreach
	?>