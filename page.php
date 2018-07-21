<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Smores
 * @since Smores 2.0
 */
?>

<?php get_template_part('templates/header'); ?>
<?php   if( have_rows('section') ):
        while ( have_rows('section') ) : the_row(); ?>

<?php if( get_row_layout() == 'large_hero' ) {?>

        <section class="pt180 pb120 hero">
            <div class="section-bg" style="background-image: url(<?php $attachment_id = get_sub_field('hero_image'); $size = "banner"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
            <div class="overlay"></div>

            <div class="container">
                
                <div class="<?php the_sub_field('alignment');?> <?php the_sub_field('text_alignment');?> text-white">
                <h1 class="m0"><?php the_sub_field('headline');?></h1>
                <?php if(get_sub_field('hero_content')) ; the_sub_field('hero_content') ?>
                <?php if(get_sub_field('cta_link')) {?><a href="<?php $link = get_sub_field('cta_link'); the_permalink($link->ID);?>" class="btn btn-filled"><?php $link = get_sub_field('cta_link'); echo get_the_title($link->ID);?></a><?php }?>
                
                </div>
            </div>
        </section>

<?php }?>


 <?php if( get_row_layout() == 'split_image_right' ) {?>

<section>
            <div class="overlay gradient-blue"></div>
    
    <div class="row">
        <div class="col-md-6 match-me text-white">
            <div class="content">
                <h3><?php the_sub_field('headline');?></h3>
                <?php if(get_sub_field('section_content')) ; the_sub_field('section_content') ?>
                <?php if(get_sub_field('cta_link')) {?><a href="<?php $link = get_sub_field('cta_link'); the_permalink($link->ID);?>" class="btn btn-filled"><?php $link = get_sub_field('cta_link'); echo get_the_title($link->ID);?></a><?php }?>

            </div>
        </div>
        <div class="col-md-6 match-me pt-sm-120 pb-sm-120">
            <div class="section-bg" style="background-image: url(<?php $attachment_id = get_sub_field('row_image'); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
        </div>
        



    </div>
</section>



 <?php }?>

 <?php if( get_row_layout() == 'split_image_left' ) {?>
<section>
            <div class="overlay gradient-blue"></div>
    
    <div class="row">
        <div class="col-md-6 match-me pt-sm-120 pb-sm-120">
            <div class="section-bg" style="background-image: url(<?php $attachment_id = get_sub_field('row_image'); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
        </div>


        <div class="col-md-6 match-me text-white">
            <div class="content">
                <h3><?php the_sub_field('headline');?></h3>
                <?php if(get_sub_field('section_content')) ; the_sub_field('section_content') ?>
                <?php if(get_sub_field('cta_link')) {?><a href="<?php $link = get_sub_field('cta_link'); the_permalink($link->ID);?>" class="btn btn-filled"><?php $link = get_sub_field('cta_link'); echo get_the_title($link->ID);?></a><?php }?>

            </div>
        </div>
    </div>
</section>
 <?php }?>

 <?php if( get_row_layout() == 'section_bg' ) {?>

        <section class="section-bg-container pt16 pb16" style="background-image: url(<?php $attachment_id = get_sub_field('section_bg'); $size = "banner"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)">
            <div class="overlay gradient-blue"></div>
            
            <?php if( have_rows('section_row') ):?>
            <?php $counter = 0; while ( have_rows('section_row') ) : the_row(); $counter++;?>
            <?php if( $counter % 2 == 0 ) {?>
            
                    <div class="row pt32 pb32 pt-sm-16 pb-sm-16">
                        
                     <div class="col-md-push-6 col-md-6 match-me pt-sm-120 pb-sm-120">
                            <div class="section-bg shadow-1" style="background-image: url(<?php $attachment_id = get_sub_field('row_image'); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
                        </div>
                        
                        
                        <div class="col-md-pull-6 col-md-6 match-me text-white">
                            <div class="content">
                                <h3><?php the_sub_field('headline');?></h3>
                                <?php the_sub_field('section_content');?>
                <?php if(get_sub_field('cta_link')) {?><a href="<?php $link = get_sub_field('cta_link'); the_permalink($link->ID);?>" class="btn btn-filled"><?php $link = get_sub_field('cta_link'); echo get_the_title($link->ID);?></a><?php }?>

                            </div>
                        </div>

   



                    </div>
            
            <?php } else {?>

                        <div class="row pt32 pb32 pt-sm-16 pb-sm-16">


                            <div class="col-md-6 match-me pt-sm-120 pb-sm-120">
                                <div class="section-bg shadow-1" style="background-image: url(<?php $attachment_id = get_sub_field('row_image'); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
                            </div>

                            <div class="col-md-6 match-me text-white text-right">
                                <div class="content">
                                    <h3><?php the_sub_field('headline');?></h3>
                                    <?php the_sub_field('section_content');?>
                <?php if(get_sub_field('cta_link')) {?><a href="<?php $link = get_sub_field('cta_link'); the_permalink($link->ID);?>" class="btn btn-filled"><?php $link = get_sub_field('cta_link'); echo get_the_title($link->ID);?></a><?php }?>

                                </div>
                            </div>

                        </div>
            <?php }?>

             
             
            <?php endwhile; ?>
            <?php endif; ?>
            
            
        </section>

<?php }?>


 <?php if( get_row_layout() == 'portfolio_gallery' ) {?>

<section class="portfolio-gallery">
            <div class="overlay gradient-blue"></div>

    <?php if(get_sub_field('section_content')){?>
    <div class="container filter-row">
    <div class="row">
    
    <div class="col-sm-8 col-sm-offset-2 text-white mt32 mb32">
        
    <?php the_sub_field('section_content');?>    
        
    </div>
    
    </div>
    </div>
    <?php }?>
    <?php if(is_page('properties')){?>
    <div class="container">
    <div class="row pb-xs-8 pt-xs-8">
    
    <div class="col-sm-12 text-white mt32 mb32 mt-xs-0">
        
                                <ul class="properties-filter">
                                <li>
									<a href="#" id="filter-all">
										All
									</a>
								</li>  
                                    <?php wp_list_categories( array(
                                        'orderby' => 'name',
                                        'title_li' => ''
                                    ) ); ?> 
                                </ul>
        
    </div>
    
    </div>
    </div>
    <?php }?>


    <div class="row m-xs-0">
    
        
        <?php $gallery = get_sub_field('number');?>
        
        <?php if($gallery == -1 ){?>
        
            <?php $args = array(

                'posts_per_page' => $gallery,
                'orderby' => 'title' ,
                'order'   => 'ASC',
                'post_type' => 'properties'
            );

            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post(); $category = get_the_category(); ?>
 
            <div class="col-sm-3 col-xs-6 pt120 pb120 pt-xs-64 pb-xs-64 single-portfolio-item" data-filter="<?php echo strtolower($category[0]->cat_name);?>">
                <a href="<?php the_permalink();?>">
                <div class="section-bg" style="background-image: url(<?php $attachment_id = get_field('main_image'); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
                <div class="over">
                <h5><?php echo get_the_title();?></h5>
                <h6 class="subhead"><?php echo $category[0]->cat_name;?></h6>    
                </div>
                </a>
            </div>
 
            <?php endwhile; wp_reset_postdata();?>
        
        
        <?php }?>
        
        
        <?php if($gallery == 6 ){?>
        
        <?php $features = get_sub_field('featured_properites'); ?>
        <?php foreach( $features as $feature ): ?>
            <div class="col-sm-4 col-xs-6 pt120 pb120 pt-xs-64 pb-xs-64">
                <a href="<?php the_permalink($feature->ID);?>">
                <div class="section-bg" style="background-image: url(<?php $attachment_id = get_field('main_image', $feature->ID); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
                <div class="over">
                <h5><?php echo get_the_title($feature->ID);?></h5>
                <h6 class="subhead"><?php $category = get_the_category($feature->ID); echo $category[0]->cat_name;?></h6>    
                </div>
                </a>
            </div>
        <?php endforeach; ?>
        
        
        <?php }?>
        
        <?php if($gallery == 8 ){?>
        
        <?php $features = get_sub_field('featured_properites'); ?>
        <?php foreach( $features as $feature ): ?>
            <div class="col-sm-3 col-xs-6 pt120 pb120 pt-xs-64 pb-xs-64">
                <a href="<?php the_permalink($feature->ID);?>">
                <div class="section-bg" style="background-image: url(<?php $attachment_id = get_field('main_image', $feature->ID); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
                <div class="over">
                <h5><?php echo get_the_title($feature->ID);?></h5>
                <h6 class="subhead"><?php $category = get_the_category($feature->ID); echo $category[0]->cat_name;?></h6>    
                </div>
                </a>
            </div>
        <?php endforeach; ?>
        
        
        <?php }?>

        </div>
    </section>

<?php }?>
 <?php if( get_row_layout() == 'members_gallery' ) {?>

<section>
    

    <div class="row m-xs-0">
        
        <?php $members = get_sub_field('team_members'); ?>
        <?php foreach( $members as $member ): ?>
 
            <div class="col-sm-3 col-ls-6 pt120 pb120 single-headshot">
                <a href="<?php the_permalink($member->ID);?>">
                <div class="section-bg" style="background-image: url(<?php $attachment_id = get_field('headshot', $member->ID); $size = "large"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>)"></div>
                <div class="over">
                <h5><?php echo get_the_title($member->ID);?></h5>
                <h6 class="subhead"><?php the_field('title', $member->ID);?></h6>    
                </div>
                </a>
            </div>
 
        <?php endforeach; ?>

        </div>
        
    </section>

<?php }?>
 <?php if( get_row_layout() == 'contact_form' ) {?>

<section>
            <div class="overlay gradient-blue"></div>
    
    <div class="row">
        <div class="col-md-6 match-me pt-sm-180 pb-sm-180">
            <div id="map"></div>
        </div>


        <div class="col-md-6 match-me text-white">
            <div class="content">
                <h3><?php the_sub_field('headline');?></h3>
                <?php if(get_sub_field('section_content')) ; the_sub_field('section_content') ?>

                <?php the_sub_field('contact_form');?>

            </div>
        </div>
    </div>
</section>

        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVqVn0srESlbUkaPoDNi7GQJTLfKoUZns"></script>  

                            <script type="text/javascript">
                                function mapinitialize() {
                                    var latlng = new google.maps.LatLng(28.4927777, -81.510067);
                                    var myOptions = {
                                        zoom: 16,
                                        center: latlng,
                                        scrollwheel: false,
                                        scaleControl: false,
                                        disableDefaultUI: false,
                                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                                        // Google Map Color Styles
                                        styles: [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"lightness":20},{"color":"#ececec"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"on"},{"color":"#f0f0ef"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#f0f0ef"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#d4d4d4"}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"visibility":"on"},{"color":"#ececec"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"lightness":21},{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#d4d4d4"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#303030"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"poi.attraction","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.government","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"featureType":"poi.place_of_worship","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.school","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.school","elementType":"geometry.stroke","stylers":[{"lightness":"-61"},{"gamma":"0.00"},{"visibility":"off"}]},{"featureType":"poi.sports_complex","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#dadada"},{"lightness":17}]}]
                                    };
                                    var map = new google.maps.Map(document.getElementById("map"), myOptions);

                                  var image = "/wp-content/themes/eistein-esas/dist/img/pin.png";
                                    var image = new google.maps.MarkerImage("/wp-content/themes/eistein-esas/dist/img/pin.png", null, null, null, new google.maps.Size(100, 99));
                                    var marker = new google.maps.Marker({
                                        map: map,
                                        icon: image,
                                        position: map.getCenter()
                                    });

                                    var contentString = '<div class="text-center"><h2 class="mt8 mb0">Estein USA</h2><p class="mt8">4705 S Apopka Vineland Rd Suite 201 <br>Orlando, FL 32819</p></div>';
                                    var infowindow = new google.maps.InfoWindow({
                                        content: contentString
                                    });
                                    google.maps.event.addDomListener(window, "resize", function() {
                                        var center = map.getCenter();
                                        google.maps.event.trigger(map, "resize");
                                        map.setCenter(center); 
                                    });
                            

                                    setTimeout(function() {
                                        infowindow.open(map, marker);

                                    }, 750)
                                }

                                            mapinitialize();

                            </script>

<?php }?>
 
<?php endwhile; ?>
<?php endif; ?>
<?php get_template_part('templates/footer'); ?>
