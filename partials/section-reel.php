<?php ;?>


	<div class="slide web screenshot reel">
		<div class="imac">
			<div class="screen">
				<video autoplay muted id="reel">
					<source src="<?php the_sub_field('video');?>" type="video/mp4">
				</video>
			</div>
		</div>
	<img src="<?php $attachment_id = get_field('client_logo', $slide->ID); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>">
		<div class="caption">
			<p>
				<?php the_sub_field('caption');?>
			</p>
		</div>

	</div>
