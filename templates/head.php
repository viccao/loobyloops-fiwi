<?php
/**
 * The template for displaying the header
 *
 * @package Smores
 * @since Smores 2.0
 */
?>
<!doctype html>

<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?> id="returnTop"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. -->

<head>

  <meta charset="<?php bloginfo( 'charset' ); ?>">

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=1920">
  <meta name="description" content="<?php bloginfo('description') ?>" />
  <meta name="author" content="Findsome &amp; Winmore" />
  <!-- Google will often use this as its description of your page/site. Make it good. -->

  <meta name="google-site-verification" content="" />
  <!-- Speaking of Google, don't forget to set your site up: http://google.com/webmasters -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">


  <meta name="Copyright" content="<?php echo date('Y'); ?>" />
   <?php get_template_part('partials/meta', 'favicons'); ?>

    <?php

        /*
         * Wordpress Head
         */

        wp_head();

    ?>


  <script src="https://cdn.jsdelivr.net/npm/emoji-js@3.4.1/lib/emoji.min.js" type="text/javascript"></script>
  <script src="https://unicodey.com/js-emoji/lib/jquery.emoji.js" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.js" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js" type="text/javascript"></script>
</head>

<body <?php body_class() ?>>
<!--[if lt IE 8]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

