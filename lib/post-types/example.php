<?php

 $properties = new CPT(
     array(
         'post_type_name' => 'clients',
         'singular'       => 'Client',
         'plural'         => 'Clients',
         'slug'           => 'clients',
     ),
     array(
         'supports' => array(
             'title'
         ),
         'public' => true,
         'show_ui' => true,
         'taxonomies'          => array( 'category' ),

     )
 );

 $properties->menu_icon("dashicons-grid-view");
