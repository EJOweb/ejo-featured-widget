<div class="ejo-feature ejo-feature-<?php echo $feature['name']; ?>">
 	
 	<div class="feature-container">

<?php
 	switch($feature['name']) 
 	{
 		case 'title' :
 			echo '<h4>'.$feature['title'].'</h4>';
	 		break;

	 	default:
			# code...
			break;
 	}
 ?>
 	</div>
 </div>
