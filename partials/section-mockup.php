<?php ;?>


	<div class="slide image">

		<img src="<?php $attachment_id = get_sub_field('image'); $size = " large "; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>">
		<img src="<?php global $post; $attachment_id = get_field('client_logo', $slide->ID); $size = " large "; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>">
		<div class="caption">
			<p>
				<?php the_sub_field('caption');?>
			</p>
		</div>


	</div>
