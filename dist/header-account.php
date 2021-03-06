<?php
  global
    $preload,
    $site_url,
    $logo_url,
    $manager_link_whatsapp,
    $manager_link_viber,
    $user,
    $user_id,
    $user_data,
    $current_template,
    $questionnaire_date,
    $show_diet_plan,
    $template_directory_uri,
    $questionnaire_dmy_time,
    $start_marathon_time,
    $current_time,
    $first_week_end_time,
    $second_week_end_time,
    $third_week_end_time,
    $version;

function add_user_to_chat( $user_data, $user_id ) {
  if ( !is_user_logged_in() ) {
    return;
  }

  if ( !$user_data['questionnaire_complete'] ) {
    // echo "<p>Пользователь не прошел анкету</p>";
    return;
  }

  $user_chat_link = $user_data['telegram_chat'];
  $user_start_marathon_date = date( 'd.m.Y', $user_data['start_marathon_time'] );
  $chats = get_field( 'telegram_chats', 419 );
  $chat_exists = false;

  foreach ( $chats as $chat ) {
    if ( $chat['start_marathon_date'] === $user_start_marathon_date ) {
      $chat_exists = true;
      break;
    }
  }

  if ( !$chat_exists ) {
    return;
  }

  unset( $chat );  

  // echo "<p>user_chat_link: {$user_chat_link}</p>";
  // echo "<p>user_start_marathon_date: {$user_start_marathon_date}</p>";
  // echo "<p>user_id: {$user_id}</p>";

  // var_dump( $chats );

  // if ( $user_chat_link ) {
    // echo "<p>За пользователем закреплен чат: {$user_chat_link}</p>";

    // for ( $i = 0, $len = count( $chats ); $i < $len; $i++ ) { 

    //   if ( in_array( $user_id, $chats[ $i ]['users'] ) ) {
    //     echo "<p>На странице чата пользователь есть в чате {$chats[ $i ]['link']}, нужно его удалить</p>";
    //     $user_key_in_chat = array_search( $user_id, $chats[ $i ]['users'] );
    //     array_splice( $chats[ $i ]['users'], $user_key_in_chat, 1 );
    //   } else {
    //     echo "<p>Пользователя нет в чате {$chats[ $i ]['link']}</p>";
    //   }

    //   if ( $chats[ $i ]['link'] === $user_chat_link ) {
    //     if ( !in_array( $user_id, $chats[ $i ]['users'] ) ) {
    //       echo "<p>На странице чата нет такой информации, добавим пользователя в чат {$chats[ $i ]['link']}</p>";
    //       $chats[ $i ]['users'][] = $user_id;
    //     } else {
    //       echo "<p>На странице чата тоже есть эта информация</p>";
    //     }
    //   }

    // }
  // } else {
    // echo "<p>За пользователем не закреплен чат</p>";

    foreach ( $chats as $chat_data ) {
      if ( in_array( $user_id, $chat_data['users'] ) ) {
        // echo "<p>Пользователь есть на странице чата в чате {$chat_data['link']}, обновим ему поле</p>";
        update_field( 'telegram_chat', $chat_data['link'], 'user_' . $user_id );
        return;
      }
    }

    // echo "<p>Пользователя нет в чатах на странце</p>";

    // Сохраним кол-во пользователей в чатах в отдельный массив
    foreach ( $chats as $chat ) {
      if ( $chat['start_marathon_date'] !== $user_start_marathon_date ) {
        // Если чат не подходит по дате, то поставить максимальное кол-во участников
        $chats_counts[] = 9999;
      } else {
        if ( $chat['users'] ) {
          $chats_counts[] = count( $chat['users'] );
        } else {
          // Чат пустой
          $chats_counts[] = 0;
        }
      }
    }

    // var_dump( $chats_counts );

    $target_chat_key = min( $chats_counts );

    foreach ( $chats_counts as $key => $value ) {
      if ( $value === $target_chat_key && $target_chat_key !== 9999 ) {
        $target_chat_index = $key;
        break;
      }
    }

    // echo "<p>Будем вставлять в чат {$target_chat_index}</p>";

    $chats[ $target_chat_index ]['users'][] = $user_id;

    update_field( 'telegram_chats', $chats, 419 );
    update_field( 'telegram_chat', $chats[ $target_chat_index ]['link'], 'user_' . $user_id );

  // }
}

add_user_to_chat( $user_data, $user_id );
    
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


      // if ( $current_template === 'diet-plan' ) {
      //   $script_name .= '-1';
      //   $style_name .= '-1';
      // }

      // $questionnaire_complete определяется в functions.php

      if ( $current_template === 'questionnaire' ) {

        if ( $show_diet_plan ) {
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
  <meta charset="<?php bloginfo( 'charset' ) ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <!-- styles preload --> <?php
#if ( $current_template === 'training-program' ) : ?>
  <!-- <link href="//vjs.zencdn.net/7.10.2/video-js.min.css" rel="stylesheet"> -->
  <!-- <script defer src="//vjs.zencdn.net/7.10.2/video.min.js"></script> --> <?php
#endif ?>
  <link rel="preload" as="style" href="<?php echo "$template_directory_uri/style.css?ver=$version" ?>" />
	<link rel="preload" as="style" href="<?php echo "$template_directory_uri/css/$style_name.css?ver=$version" ?>" />
	<link rel="preload" as="style" href="<?php echo "$template_directory_uri/css/$style_name.576.css?ver=$version" ?>" media="(min-width:575.98px)" />
	<link rel="preload" as="style" href="<?php echo "$template_directory_uri/css/$style_name.768.css?ver=$version" ?>" media="(min-width:767.98px)" />
	<link rel="preload" as="style" href="<?php echo "$template_directory_uri/css/$style_name.1024.css?ver=$version" ?>" media="(min-width:1023.98px)" />
	<link rel="preload" as="style" href="<?php echo "$template_directory_uri/css/$style_name.1280.css?ver=$version" ?>" media="(min-width:1279.98px)" />
  <link rel="preload" as="style" href="<?php echo "$template_directory_uri/css/hover.css?ver=$version" ?>" media="(hover) and (min-width:1024px)" />
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
  $preload[] = [
    'filepath' => $template_directory_uri . '/img/icon-burger.svg',
    'media' => '(max-width:767.98px)'
  ];

  if ( is_user_logged_in() && $questionnaire_date && $current_time < $start_marathon_time ) {
    $preload[] = $template_directory_uri . '/img/icon-edit.svg';
  }

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
      <a href="<?php echo $site_url ?>" class="hdr__logo"><img src="<?php echo $logo_url ?>" alt="#" class="hdr__logo-img"></a> <?php
      if ( is_user_logged_in() && $post->ID !== 4122 ) : ?>
        <a href="<?php echo wp_logout_url() ?>" class="hdr__logout"><?php echo $user->user_firstname . ' ' . $user->user_lastname ?><picture class="hdr__logout-icon"><source type="image/svg+xml" srcset="<?php echo $template_directory_uri ?>/img/icon-logout.svg" media="(min-width:767.98px)"><img src="#" alt="#"></picture></a> <?php
      endif ?>
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