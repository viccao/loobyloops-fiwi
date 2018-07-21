<?php
/**
* Template Name: Schedule
*/
;?>

<?php get_template_part('templates/head'); ?>

<div class="slide schedule">
                <video autoplay="" loop="" muted="" id="bgvid" playsinline style="z-index: 0;">

                    <source src="<?php echo get_template_directory_uri(); ?>/src/video/clouds-small.mp4" type="video/mp4">
                </video>
                    <div class="container-fluid" style="position: relative">
                        <div class="row schedule-header">
                            <div class="col-md-12">
                            <h2>Today's Meetings</h2>
                            </div>
                        </div>
                    <?php while ( have_rows('client_schedule', 'options') ) : the_row(); ?>
                    <?php $time = strtotime(get_sub_field('client_time', 'options'));
                          $currentTime = strtotime(current_time( 'H:i:s'));
                            if($time < $currentTime): echo '<script>location.reload();</script>';endif;?>

                    <div class="row">
                        <div class="col-md-10 client-name">
                        <?php the_sub_field('client_name');?>
                        </div>
                        <div class="col-md-2 client-time">
                        <h2><?php $meetingtime = strtotime(get_sub_field('client_time')); echo date('g:i a', $meetingtime); ?></h2>
                        </div>

                    </div>
                    <?php endwhile; ?>
                        </div>
                        </div>
<?php get_template_part('templates/footer'); ?>
