<?php
  global
    $preload,
    $site_url,
    $logo_url,
    $template_directory_uri;
    $page_style = $GLOBALS['page_script_name'] ?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=CustomEvent%2CIntersectionObserver%2CIntersectionObserverEntry%2CElement.prototype.closest%2CElement.prototype.dataset%2CHTMLPictureElement"></script>
  <meta charset="<?php bloginfo( 'charset' ) ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- styles preload -->
  <link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/style.css"><!-- page styles preload -->
  <link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/hover.css" media="(hover) and (min-width:1024px)">
  <!-- fonts preload -->
  <!-- other preload --> <?php
  echo PHP_EOL;
  if ( !$preload ) {
    $preload = get_field( 'preload' );
  }

  $preload[] = $logo_url;

  if ( $preload ) {
    foreach ( $preload as $item ) {
      create_link_preload( $item );
    }
    unset( $item );
    echo PHP_EOL;
  } ?>
  <!-- favicons --> <?php
  echo PHP_EOL;
  wp_head() ?>
</head>

<body <?php body_class() ?>> <?php
  wp_body_open() ?>
  <noscript>
    <!-- <noindex> -->Для полноценного использования сайта включите JavaScript в настройках вашего браузера.<!-- </noindex> -->
  </noscript>
  <div id="page-wrapper">
  <header class="hdr container"> <?php 
  wp_nav_menu( [
    'theme_location'  => 'header_menu',
    'container'       => 'nav',
    'container_class' => 'hdr__nav',
    'menu_class'      => 'hdr__nav-list',
    'items_wrap'      => '<ul class="%2$s">%3$s</ul>'
  ] ) ?> <?php
    require 'template-parts/mobile-menu.php' ?>
  </header>