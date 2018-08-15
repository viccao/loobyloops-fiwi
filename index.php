<?php
/**
* Template Name: Slides
*
* This is the most generic template file in a WordPress theme and one
* of the two required files for a theme (the other being style.css).
* It is used to display a page when nothing more specific matches a query,
* e.g., it puts together the home page when no home.php file exists.
*
* @link http://codex.wordpress.org/Template_Hierarchy
*
* @package Smores
* @since Smores 2.0
*/
?>
<?php get_template_part('templates/head'); ?>
        <?php // Directy turn on Welcome Mode
                $welcome = get_field('override_slide_mode','options');
                if($welcome == 'Yes'){?>
            <div id="wrapper" class="welcome">
                <?php  // Check if Schedule Meetings are present
                                if( have_rows('client_schedule', 'options') ) { ?>
                    <?php $counter == 0; while ( have_rows('client_schedule', 'options') ) : the_row();?>
                        <?php $counter++; // Compare Scheduled Meeting Times against current time
                              $time = strtotime(get_sub_field('client_time', 'options')) + 300;
                              $currentTime = strtotime(current_time( 'H:i:s'));
                                if($time > $currentTime || $welcome == 'Yes') {?>
                            <?php $welcomeType = get_sub_field('video_or_image');?>
                            <?php if($welcomeType == 'video' && get_sub_field('client_video')) {?>
                            <video autoplay="" loop="" muted="" id="bgvid">
                                <source src="<?php the_sub_field('client_video');?>">
                            </video>
                            <?php } elseif ($welcomeType == 'image') {?>
                            <img class="bg" src="<?php $attachment_id = get_sub_field('client_image'); $size = "full"; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>">
                            <?php } else { ?>
                            <video autoplay="" loop="" muted="" id="bgvid">
                                <source src="<?php echo get_template_directory_uri(); ?>/src/video/clouds-small.mp4" type="video/mp4">
                            </video>
                            <?php } ?>
                            <?php $color = get_sub_field('bg_color'); $opacity = get_sub_field('background_opacity');?>
                            <div id="overlay" <?php if(get_sub_field('bg_color') || get_sub_field('background_opacity')): echo 'style="background-color: ' . $color . '; opacity: ' . $opacity . '"'; endif;?>></div>
                            <div id="content" class="table-me">
                                <div class="align-me align-top">
                                <h1 class="large blinking"><?php if(get_sub_field('welcome_message')): echo get_sub_field('welcome_message'); else: echo 'Now Boarding'; endif;?> </h1>
                                <div class="medium">
                                  <p><em><?php the_sub_field('individual_names');?></em></p>
                                  <p><strong><?php the_sub_field('client_name');?></strong></p>
                            </div>
                            </div>
                            <script>
                                // Live time refresh against first upcoming meeting to switch back to Slide Mode or to Next Meeting
                                function checkTime(i) {
                                    if (i < 10) {
                                        i = "0" + i;
                                    }
                                    return i;
                                }
                                function startTime() {
                                    var today = new Date();
                                    var h = today.getHours();
                                    var m = today.getMinutes();
                                    var s = today.getSeconds();
                                    // add a zero in front of numbers<10
                                    m = checkTime(m);
                                    s = checkTime(s);
                                    var currentTime = h + ":" + m + ":" + s;
                                    t = setTimeout(function() {
                                        startTime()
                                    }, 500);
                          <?php $meeting = strtotime(get_sub_field('client_time', 'options')) + 900;
                                $lesstime = date('H:i:s', $meeting);?>
                                    console.log('15 Min past meeting: <?php echo $lesstime;?>');
                                    console.log('Current Time:' + currentTime);
                                    if ('<?php echo $lesstime;?>' == currentTime) {
                                        console.log('meeting time');
                                        jQuery('.welcome').addClass('transition-to');

                                        setTimeout(function() {
                                        location.reload();
                                        }, 500);



                                    }
                                }
                                startTime();
                            </script>
                              <?php if($currentTime > $lesstime):
                               delete_row('client_schedule', 1, 'options');
                               update_field( 'override_slide_mode', 'No', 'options' ); ?>
                              <script>
                              jQuery('.welcome').addClass('transition-to');
                              setTimeout(function() {
                              location.reload();
                              }, 500);
                            </script>
                            <?php endif;?>


                            <?php } else { ?>
                                    <?php //update_field( 'override_slide_mode', 'No', 'options' ); delete_row('client_schedule', 1, 'options'); ?>
                                    <script>
//                                        location.reload();
                                    </script>
                                    <?php }?>
                                        <?php endwhile ; wp_reset_postdata();
                                               $mode = get_field('override_slide_mode', 'options');
                                                if(($mode == 'Yes') && ($lesstime < $currentTime)):
                                                delete_row('client_schedule', 1, 'options'); update_field( 'override_slide_mode', 'No', 'options' );
                                                echo '<script>location.reload();</script>';
                                                elseif($mode == 'No'):
                                                update_field( 'override_slide_mode', 'No', 'options' );
                                                else:

                                                endif; ?>
                                            <?php // if no other upcoming meetings switch back to Slide Mode
                                                            } else { ?>
                                                <?php //update_field( 'override_slide_mode', 'No', 'options' );
                                                    //echo '<script>location.reload();</script>'; ?>
                                                    <?php }?>
            </div>
            <?php } else {?>
                    <div class="fade">
                <?php if( have_rows('client_schedule', 'options') ): ?>
                    <div class="slide schedule schedule client board" data-attr="6000">
                    <video autoplay="" loop="" muted="" id="bgvid">
                        <source src="<?php echo get_template_directory_uri(); ?>/src/video/clouds-small.mp4" type="video/mp4">
                    </video>
                    <div class="container-fluid">
                        <div class="row schedule-header">
                            <div class="col-md-12">
                            <h2>Arrivals</h2>
                            </div>
                        </div>
                    <?php while ( have_rows('client_schedule', 'options') ) : the_row(); ?>
                    <?php $time = strtotime(get_sub_field('client_time', 'options'));
                          $currentTime = strtotime(current_time( 'H:i:s'));
                            if($time < $currentTime): echo '<script>location.reload();</script>';endif;?>

                                      <div class="row">
                                          <div class="col-md-9 client-name">
                                          <input class="hero dark XXL title" data-src="<?php remove_filter ('acf_the_content', 'wpautop'); echo get_sub_field('client_name', false, false);?>">
                                          </div>
                                          <div class="col-md-3 client-time">
                                            <input class="hero dark XXL time" data-src="<?php $meetingtime = strtotime(get_sub_field('client_time')); echo date('g:i a', $meetingtime); ?>">
                                          </div>
                                      </div>
                    <?php endwhile; ?>
                        </div>
                      </div>
                        <?php endif; ?>
                    <?php $slides = get_field('slide_order', 'options'); ?>
                    <?php $noslides = 0; foreach( $slides as $slide ): $noslides++;?>
                    <?php if($noslides % 3 == 0) {

                    $calendarId = 'findsomewinmore.com_393732373830313435@resource.calendar.google.com'; //NOT primary!! , but the email of calendar creator that you want to view
                    $optParams = array(
                    'maxResults' => 99,
                    'singleEvents' => TRUE,
                    'orderBy' => 'startTime',
                    'timeMax' => date("c", strtotime(date("c") . ' +12 hours')),
                    'timeMin' => date("c", mktime(0,0,0))
                    );
                    $events = $service->events->listEvents($calendarId, $optParams);
                    if(!empty($events->getItems())): ?>
                    <div class="slide schedule schedule client board" data-attr="20000" style="background: none;">

                      <div class="container-fluid">
                          <div class="row schedule-header">
                              <div class="col-md-12">
                              <h2>Arrivals: Altitude</h2>
                              </div>
                          </div>

                                      <?php
                                      foreach ($events->getItems() as $event):?>
                                      <div class="row">
                                          <div class="col-md-9 client-name">
                                          <input class="hero dark XXL title" data-src="<?php echo $event->getSummary();?>">
                                          </div>
                                          <div class="col-md-3 client-time">
                                            <input class="hero dark XXL time" data-src="<?php $meetingtime = $event->getStart()->getDateTime(); echo date('g:i a', strtotime($meetingtime)); ?>">
                                          </div>
                                      </div>
                                      <?php endforeach;?>
                          </div>
                        </div>
                    <?php endif; } elseif($noslides % 5 == 0){

                      $calendarId = 'findsomewinmore.com_343531313332303332@resource.calendar.google.com'; //NOT primary!! , but the email of calendar creator that you want to view
                      $optParams = array(
                      'maxResults' => 99,
                      'singleEvents' => TRUE,
                      'orderBy' => 'startTime',
                      'timeMax' => date("c", strtotime(date("c") . ' +12 hours')),
                      'timeMin' => date("c", mktime(0,0,0))
                      );
                      $events = $service->events->listEvents($calendarId, $optParams);


                    if(!empty($events->getItems())): ?>
                    <div class="slide schedule schedule client board" data-attr="20000" style="background: none;">

                      <div class="container-fluid">
                          <div class="row schedule-header">
                              <div class="col-md-12">
                              <h2>Arrivals: Runway</h2>
                              </div>
                          </div>
                                  <?php

                                      foreach ($events->getItems() as $event):?>
                                      <div class="row">
                                          <div class="col-md-10 client-name">
                                          <input class="hero dark XXL title" data-src="<?php echo $event->getSummary();?>">
                                          </div>
                                          <div class="col-md-2 client-time">
                                            <input class="hero dark XXL time" data-src="<?php $meetingtime = $event->getStart()->getDateTime(); echo date('g:i a', strtotime($meetingtime)); ?>">
                                          </div>
                                      </div>
                                      <?php endforeach;?>
                          </div>
                        </div>
                    <?php endif;
                            }
                      elseif($noslides % 4 == 0){
                      $json = file_get_contents('https://slack.com/api/users.list?token=xoxp-3921626273-114425188213-401339713923-9e939340d027352b450c1e6fdf421ce6&presence=true&pretty=true');
                      $obj = json_decode($json);
                      ?>
                    <div class="slide schedule schedule client board" data-attr="20000" id="flight-status" style="background: none;">

                      <div class="">
                        <div class="status">

                              <h2>Flight Status</h2>

                          <ul class="members-status">
                                  <?php foreach($obj->members as $member):
                                        if(($member->deleted == false) && ($member->is_bot == false) && ($member->real_name != 'slackbot')):

                                        $status = $member->profile->status_text;
                                        $presence = $member->presence;
                                        $display_name = $member->profile->display_name;
                                        $name = $member->name;
                                        ?>
                                            <li id="<?php echo $member->name;?>" class="<?php echo $presence;?>"><img src="<?php echo $member->profile->image_72;?>"><?php if($display_name == ''): echo $name; else: echo $display_name; endif;?>

                                                <?php

                                                echo '<span class="' . $presence . '">';
                                                if($status == ''):
                                                echo '<span class="emoji" data-src="' . $member->profile->status_emoji . '">' . $member->profile->status_emoji . '</span>';
                                                echo '<span class="user-status"> ' . $presence .'</span>';
                                                else:
                                                echo '<span class="emoji" data-src="' . $member->profile->status_emoji . '">' . $member->profile->status_emoji . '</span>';
                                                echo '<span class="user-status"> ' . $status . '</span>';
                                                endif;
                                                echo '</span>';

                                                ?>

                                            </li>

                                      <?php endif; endforeach;?>
                                  </ul>
                                </div>
                          </div>

                        </div>
                    <?php } elseif($noslides % 6 == 0){?>
                    <?php if( have_rows('client_schedule', 'options') ): ?>
                        <div class="slide schedule client video" data-attr="6000">

                        <div class="container-fluid">
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
                              <input class="hero dark XXL title" data-src="<?php the_sub_field('client_name');?>">

                            </div>
                            <div class="col-md-2 client-time">
                              <input class="hero dark XXL time" data-src="<?php $meetingtime = strtotime(get_sub_field('client_time')); echo date('g:i a', $meetingtime); ?>">
                            </div>
                        </div>
                        <?php endwhile; ?>
                            </div>
                                                            <script>
                                                jQuery('.fade').on('afterChange', function (event, slick, currentSlide, nextSlide) {
                                                                var hasVideo = $('.slick-active').hasClass('video');
                                                                var video = $('.slick-active.video').find('video');
                                                                var speed = $('.slick-active').attr('data-attr') - 1000;
                                                                var VideoFull = $('.slick-active.video').hasClass('video-full');
                                                                if (hasVideo == true) {
                                                                    console.log('has video');
                                                                    var slickSlide = $('.slick-active').attr('data-slick-index');
                                                                }
                                                                if(slick.currentSlide == slickSlide) {
                                                                    console.log('current has video');
                                                                $(video).get(0).play();
                                                                if(VideoFull == true) {
                                                                setTimeout(function () {
                                                                    slick.slickPause();
                                                                }, 500);
                                                                $(video).on('ended', function () {
                                                                    slick.slickPlay();
                                                                })
                                                                } else {
                                                                setTimeout(function () {
                                                                    slick.slickPause();
                                                                }, 500);
                                                                setTimeout(function () {
                                                                    slick.slickPlay();
                                                                }, speed);
                                                                }
                                                    }
                                                });
                                                </script>
                            </div>
                    <?php endif; ?>
                    <?php } else {?>

                    <?php if(get_field('client_logo', $slide->ID)):?>
                        <div class="slide logo" data-attr="10000">
                            <img src="<?php $attachment_id = get_field('client_logo', $slide->ID); $size = " large "; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>">
                        </div>
                    <?php endif;?>

                    <?php 	// flexible layouts of Client Slides
                    if( have_rows('slides', $slide->ID) ):
                    // loop through the rows of data
                    while ( have_rows('slides', $slide->ID) ) : the_row();
                    if( get_row_layout() == 'image' || get_row_layout() == 'website_screenshot' ){?>
                        <div class="slide image" data-attr="30000">
                            <img src="<?php $attachment_id = get_sub_field('image'); $size = " large "; $image = wp_get_attachment_image_src( $attachment_id, $size ); echo $image[0];?>">
                        </div>
                    <?php } elseif ( get_row_layout() == 'client_video' || get_row_layout() == 'video' ){?>
                        <div <?php /** If get Video Slide length **/ $videoLength = get_sub_field('video_length'); if($videoLength == '30000' || $videoLength == '60000'){?> data-attr='<?php echo $videoLength;?>' <?php }?> class="slide video client <?php if(get_sub_field('contain_video')): echo 'contain'; endif;?><?php if($videoLength == 'Full Video') { ?> video-full<?php }?>" <?php if(get_sub_field('video_bg_color')): echo 'style="background-color:' . get_sub_field('video_bg_color') . '"'; endif;?>>
                                                                <video muted preload="none" src="<?php the_sub_field('video');?>">
                                                                </video>
                                            <?php /** If get Video Slide length videoLength = get_sub_field('video_length'); if($videoLength == 'Full Video'){*/?>
                                            <script>
//                                            jQuery('.fade').on('afterChange', function (event, slick, currentSlide, nextSlide) {
//                                                            var hasVideo = $('.slick-active').hasClass('video');
//                                                            var video = $('.slick-active.video').find('video');
//                                                            var speed = $('.slick-active').attr('data-attr');
//                                                            var VideoFull = $('.slick-active.video').hasClass('video-full');
//                                                            if (hasVideo == true) {
//                                                                console.log('has video');
//                                                                var slickSlide = $('.slick-active').attr('data-slick-index');
//                                                            }
//                                                            if(slick.currentSlide == slickSlide) {
//                                                                console.log('current has video');
//                                                            $(video).get(0).play();
//                                                            if(VideoFull == true) {
//                                                            setTimeout(function () {
//                                                                slick.slickPause();
//                                                            }, 500);
//                                                            $(video).on('ended', function () {
//                                                                slick.slickPlay();
//                                                            })
//                                                            } else {
//                                                            setTimeout(function () {
//                                                                slick.slickPause();
//                                                            }, 500);
//                                                            setTimeout(function () {
//                                                                slick.slickPlay();
//                                                            }, speed);
//                                                            }
//                                                }
//                                            });
                                            </script>
                                                            </div>
                    <?php } // Endif Layout == Client Video
                            // End of Client Slide repeater
                          endwhile; endif; }
                            // End foreach of selected clients for Slide Mode rotation
                          endforeach; ?>
                  </div>

                  <video autoplay="" loop="" muted="" id="bgvid">
                      <source src="https://s3.amazonaws.com/fw-devtools/fiwi-internal/assets/video/building.mp4" type="video/mp4">
                  </video>

               <?php  // in Slide Mode: storing the first upcoming meeting time for live check refresh
                          if( have_rows('client_schedule', 'options') ): ?>
                    <?php $counter == 0; while ( have_rows('client_schedule', 'options') ) : the_row(); ?>
                            <script>
                                // Live time refresh against first upcoming meeting to switch back to Slide Mode or to Next Meeting
                                function checkTime(i) {
                                    if (i < 10) {
                                        i = "0" + i;
                                    }
                                    return i;
                                }
                                function startTime() {
                                    var today = new Date();
                                    var h = today.getHours();
                                    var m = today.getMinutes();
                                    var s = today.getSeconds();
                                    // add a zero in front of numbers<10
                                    h = checkTime(h);
                                    m = checkTime(m);
                                    s = checkTime(s);
                                    var currentTime = h + ":" + m + ":" + s;
                                    t = setTimeout(function() {
                                        startTime()
                                    }, 1000);
                          <?php $meeting = strtotime(get_sub_field('client_time', 'options')) - 900;
                                $lesstime = date('H:i:s', $meeting);?>
                                    console.log('Meeting (15 Min Prior): <?php echo $lesstime;?>');
                                    console.log('Current:' + currentTime);
                                    if ('<?php echo $lesstime;?>' == currentTime) {
                                        console.log('15 min before meeting...');
                                        <?php update_field( 'override_slide_mode', 'Yes', 'options' );?>
                                        jQuery('.welcome').addClass('transition-to');
                                        setTimeout(function() {
                                        location.reload();
                                        }, 500);
                                    }
                                }
                                $(window).load(function(){
                                    startTime();
                                })
                            </script>
                    <?php break; endwhile;  endif; ?>
                <?php // Endif not in Welcome Mode - in Slide Mode
                }?>
                    <?php get_template_part('templates/footer'); ?>
