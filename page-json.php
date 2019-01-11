<?php


header('Content-Type: application/json');


function str_replace_first($from, $to, $content)
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}

function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

global $wp_query;

    $gif_data = Array();

    $args = array(
        'post_type'      => 'clients',
        'posts_per_page' => -1,
    );
    $gif_query = new WP_Query( $args );


//    $videos = get_field('slide_order', 'options');
//
//    foreach($videos as $post):
//    setup_postdata($post);


    if ( $gif_query->have_posts() ) : while ($gif_query->have_posts()) : $gif_query->the_post();



//    if ( $video->have_posts() ) : while ( $video->have_posts() ) : $video->the_post();


    if( have_rows('slides') ):
    while ( have_rows('slides') ) : the_row();


    $i = 0; if(get_row_layout() == 'client_video' || get_row_layout() == 'video'):

    $gif_data[] = get_sub_field('video');

    endif;

//
    endwhile;
    endif;

    endwhile;
//    wp_reset_postdata();
    endif;
//    endforeach;

//    var_dump($gif_data);
//    echo $gif_data;
//      $vids = str_replace('[','', json_encode($gif_data, JSON_UNESCAPED_SLASHES));
//      $vidsArray2 = str_replace(']','', $vids);
//      $vidsArray = str_replace('"','', $gif_data);
//      $data2 = str_replace_first('"','', $gif_data);
//      $data1 = str_lreplace('"','', $data2);
//      $data = str_lreplace(', ','', $gif_data);
//      echo $data;
//     wp_send_json( $gif_data );
      echo '{"videos": ' . json_encode($gif_data, JSON_UNESCAPED_SLASHES) . '}';

?>
