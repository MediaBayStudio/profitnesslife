<?php
  global
    $preload,
    $site_url,
    $logo_url,
    $user,
    $user_id,
    $user_data,
    $current_template,
    $questionnaire_complete,
    $template_directory_uri,
    $questionnaire_dmy_time,
    $start_marathon_time,
    $current_time,
    $first_week_end_time,
    $second_week_end_time,
    $third_week_end_time;

  if ( !$preload ) {
    $preload = get_field( 'preload' );
  }

  if ( is_front_page() ) {
    $script_name = 'script-index';
    $style_name = 'style-index';

    // $preload[] = $GLOBALS['sections'][0]
  } else if ( is_404() ) {
    $script_name = 'script-404';
    $style_name = 'style-404';
  } else if ( is_single() ) {
    $script_name = 'script-single';
    $style_name = 'style-single';
  } else {
    if ( $current_template ) {
      $script_name = 'script-' . $GLOBALS['current_template'];
      $style_name = 'style-' . $GLOBALS['current_template'];

      // $questionnaire_complete определяется в functions.php

      if ( $current_template === 'questionnaire' ) {

        if ( $questionnaire_complete ) {
          $preload[] = $template_directory_uri . '/img/questionnaire-hero-img.svg';
        } else {
          $preload[] = $template_directory_uri . '/img/questionnaire-start-img.svg';
        }
      } else if ( $current_template === 'chat' ) {
        $preload[] = $template_directory_uri . '/img/chat-hero-img.svg';
      }

    } else {
      $script_name = '';
      $style_name = '';
    } 
  }

  $GLOBALS['page_script_name'] = $script_name;
  $GLOBALS['page_style_name'] = $style_name ?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=CustomEvent%2CIntersectionObserver%2CIntersectionObserverEntry%2CElement.prototype.closest%2CElement.prototype.dataset%2CHTMLPictureElement"></script>
  <meta charset="<?php bloginfo( 'charset' ) ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- styles preload -->
  <link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/style.css">
	<link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/<?php echo $style_name ?>.css" />
	<link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/<?php echo $style_name ?>.576.css" media="(min-width:575.98px)" />
	<link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/<?php echo $style_name ?>.768.css" media="(min-width:767.98px)" />
	<link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/<?php echo $style_name ?>.1024.css" media="(min-width:1023.98px)" />
	<link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/<?php echo $style_name ?>.1280.css" media="(min-width:1279.98px)" />
  <link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/hover.css" media="(hover) and (min-width:1024px)">
  <!-- fonts preload --> <?php
	$fonts = [
		'NotoSans-Bold.woff' => 'woff',
		'NotoSans-Regular.woff' => 'woff',
		'Roboto-Regular.woff' => 'woff',
		'Roboto-Medium.woff' => 'woff',
		'Roboto-Bold.woff' => 'woff'
	];
	foreach ( $fonts as $font => $font_type ) : ?>

	<link rel="preload" href="<?php echo $template_directory_uri . '/fonts/' . $font ?>" as="font" type="font/<?php echo $font_type ?>" crossorigin="anonymous" /> <?php
	endforeach ?>
  <!-- other preload --> <?php
  echo PHP_EOL;

  $preload[] = $logo_url;
  $preload[] = $template_directory_uri . '/img/icon-burger.svg';

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
    <header class="hdr hdr-account container">
      <a href="<?php echo $site_url ?>" class="hdr__logo"><img src="<?php echo $logo_url ?>" alt="#" class="hdr__logo-img"></a>
      <a href="<?php echo wp_logout_url() ?>" class="hdr__logout">Выход<picture class="hdr__logout-icon"><source type="image/svg+xml" srcset="<?php echo $template_directory_uri ?>/img/icon-logout.svg" media="(min-width:767.98px)"><img src="#" alt="#"></picture></a>
      <button type="button" class="hdr__burger"></button>
    </header> <?php
    // echo '<div style="text-align:center">';
    //   echo '<p>Время прохождения анкеты: ' . date( 'd.m.Y H:i:s', $questionnaire_dmy_time ) . '</p>';
    //   echo '<p>Старт марафона: ' . date( 'd.m.Y H:i:s', $start_marathon_time ) . '</p>';
    //   echo '<p>Сейчас: ' . date( 'd.m.Y H:i:s', $current_time ) . '</p>';
    //   echo '<p>Последний день 1 недели: ' . date( 'd.m.Y H:i:s', $first_week_end_time ) . '</p>';
    //   echo '<p>Последний день 2 недели: ' . date( 'd.m.Y H:i:s', $second_week_end_time ) . '</p>';
    //   echo '<p>Последний день 3 недели: ' . date( 'd.m.Y H:i:s', $third_week_end_time ) . '</p>';
    // echo '</div>';