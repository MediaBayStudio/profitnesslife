<?php

add_action( 'wp', function() {
  global 
    $post,
    $site_url,
    $questionnaire_show,
    $questionnaire_complete;

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
        wp_redirect( $site_url . '/account' );
        exit;
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
        if ( !$questionnaire_show ) {
          switch ( $post->post_name) {
            case 'training-program':
            case 'diet-plan':
              wp_redirect( $site_url . '/account' );
              exit;
          }
        }
      }
    }
  }
} );

add_action( 'wp_logout', function() {
  global $site_url;
  wp_redirect( $site_url );
  exit();
} );