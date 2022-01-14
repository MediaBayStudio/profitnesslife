<?php

// echo '<p>Время окончания марафона ' . $finish_marathon_time . '</p>';
// echo '<p>Текущее время ' . $current_time . '</p>';

add_action( 'wp', function() {
  // return;
  global 
    $post,
    $site_url,
    $current_time,
    $show_diet_plan,
    $finish_marathon_time,
    $questionnaire_complete,
    $user;

  switch ( $post->post_name ) {
    case 'questionnaire':
    case 'training-program':
    case 'diet-plan':
    case 'chat':
    case 'account':
      $GLOBALS['is_account_page'] = true;
      break;
  }

  // if ( is_super_admin() ) {
  //   switch ( $post->post_name ) {
  //     case 'questionnaire':
  //     case 'training-program':
  //     case 'diet-plan':
  //     case 'chat':
  //     case 'account':
  //       wp_redirect( $site_url . '/admin' );
  //       exit;
  //   }
  // } 

  // Редирект на страницу входа, если не авторизованный пользователь попал на другие внутренние страницы
  if ( !is_user_logged_in() && !is_super_admin() ) {
    switch ( $post->post_name ) {
      case 'questionnaire':
      case 'training-program':
      case 'diet-plan':
      case 'chat':
        // wp_redirect( $site_url . '/account' );
        // exit;
    }
  } else {
    if ( is_super_admin() ) {
      return;
    }
    // Редирект на страницу заполнения анкеты, если пользовтаель авторизовался и анкета у него не заполнена
    if ( $post->post_name !== 'questionnaire' && !is_front_page() ) {
      $user_id = get_current_user_id();
      if ( !$questionnaire_complete ) {
        wp_redirect( $site_url . '/questionnaire' );
        exit;
      } else {
        /*
          Если анкета заполнена,
          но время показывать не пришло,
          то со страниц тренировок и плана питания
          редиректим на личный кабинет
        */
        if ( !$show_diet_plan ) {
          switch ( $post->post_name) {
            case 'training-program':
            case 'diet-plan':
            case 'chat':
              // wp_redirect( $site_url . '/account' );
              // exit;
          }
        }
      }
    }
  }

  // Если марафон окончен, то редирект на главную
  if ( !is_front_page() && is_user_logged_in() && !is_super_admin() && $current_time && $finish_marathon_time && $current_time >= $finish_marathon_time ) {
    wp_redirect( $site_url . '?completed=true' );
    exit;
  }

} );

add_action( 'wp_logout', function() {
  global $site_url;
  wp_redirect( $site_url );
  exit();
} );