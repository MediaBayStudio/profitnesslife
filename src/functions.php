<?php
// Глобальные переменные
$template_directory_uri = get_template_directory_uri();
$template_directory = get_template_directory();
$site_url = site_url();

$user = wp_get_current_user();
$user_id = $user->ID;
$user_data = get_fields( 'user_' . $user_id );
$questionnaire_complete = get_field( 'questionnaire_complete', 'user_' . $user_id );

$upload_dir = wp_get_upload_dir();
$upload_basedir = $upload_dir['basedir'];
$upload_baseurl = $upload_dir['baseurl'] . DIRECTORY_SEPARATOR;

$tel = get_option( 'contacts_tel' );
$tel_clean = str_replace( [' ', '–', '-', '(', ')'], '', $tel );
$email = get_option( 'contacts_email' );

$logo_id = get_theme_mod( 'custom_logo' );
$logo_url = wp_get_attachment_url( $logo_id );

/*
  Расчеты времени после прохождения анкеты
*/

// Дата прохождения анкеты (d.m.Y H:i:s)
$questionnaire_date =  $user_data['questionnaire_time'];
// $questionnaire_date =  '12.09.2021 12:10:27'; // 3 неделя
// $questionnaire_date =  '21.09.2021 12:10:27'; // 2 неделя
$questionnaire_dmy_date = substr( $questionnaire_date, 0, -9 );

if ( $questionnaire_date ) {
  // Время прохождения анкеты в мс
  $questionnaire_time =  strtotime( $questionnaire_date );
  $questionnaire_dmy_time =  strtotime( $questionnaire_dmy_date );
  // Время начала марафона в мс (следующий понедельник от даты прохождения анкеты)
  $start_marathon_time = strtotime( 'next monday', $questionnaire_dmy_time );

  // Название дня прохождения анкеты: Sun || Mon || Tue || etc...
  $questionnaire_week_day = date( 'D', $questionnaire_time );

  // Текущее время в мс
  $current_time = strtotime( 'now' );

  // Время открытия анкеты
  /*
    Если анкета пройдена в воскресенье,
    то показывать результат через 3 часа
    Если анкета пройдена не в воскресенье,
    то показывать результат в следующее воскресенье в 10 утра
  */
  if ( $questionnaire_week_day === 'Sun' ) {
    $questionnaire_result_time = strtotime( '+3 hours', $questionnaire_time );
  } else {
    $questionnaire_result_time = strtotime( 'next sunday +10 hours', $questionnaire_time );
  }

  $questionnaire_show = $current_time >= $questionnaire_result_time;

  update_field( 'questionnaire_show', $questionnaire_show, 'user_' . $user_id );

  // Концы каждой недели марафона (только дата, без времени)
  $first_week_end_time = strtotime( '+1 week', $start_marathon_time );
  $second_week_end_time = strtotime( '+2 week', $start_marathon_time );
  $third_week_end_time = strtotime( '+3 week', $start_marathon_time );

  $weeks_end_dates = [ $first_week_end_time, $second_week_end_time, $third_week_end_time ];

  // echo '<p>Старт марафона: ' . date( 'd.m.Y H:i:s', $start_marathon_time ) . '</p>';
  // echo '<p>Сейчас: ' . date( 'd.m.Y H:i:s', $current_time ) . '</p>';
  // echo '<p>first_week_end_time: ' . date( 'd.m.Y H:i:s', $first_week_end_time ) . '</p>';
  // echo '<p>second_week_end_time: ' . date( 'd.m.Y H:i:s', $second_week_end_time ) . '</p>';
  // echo '<p>third_week_end_time: ' . date( 'd.m.Y H:i:s', $third_week_end_time ) . '</p>';

  if ( $current_time < $first_week_end_time ) {
    $current_week_number = 1;
  } else if ( $current_time > $first_week_end_time && $current_time < $third_week_end_time ) {
    $current_week_number = 2;
  } else {
    $current_week_number = 3;
  }

  // echo '<p>Сейчас идет неделя ' . $current_week_number . '</p>';

  // var_dump( date( 'd.m.Y H:i:s', $questionnaire_result_time ) );
}

// Проверка поддержки webp браузером
$webp_support = strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], ' Chrome/' ) !== false;

// Передаем в js некоторые данные о сайте
add_action( 'admin_head', 'print_site_data' );
add_action( 'wp_body_open', 'print_site_data' );

function print_site_data() {
  global $site_url, $template_directory_uri, $template_directory;
  echo '<script id="page-data">var siteUrl = "' . $site_url . '", templateDirectoryUri = "' . $template_directory_uri . '", templateDirectory = "' . $template_directory . '"</script>';
}

// add_shortcode( 'mytag', function() {
//   return '<div>asjldnaskjdnajksndm</div>';
// } );

// echo do_shortcode( '[mytag]' );

// Запрет обновления плагинов
add_filter( 'site_transient_update_plugins', function( $value ) {
  unset(
    $value->response['contact-form-7/wp-contact-form-7.php'],
    $value->response['contact-form-7-honeypot/honeypot.php'],
    $value->response['advanced-custom-fields-pro/acf.php']
  );

  return $value;
} );


// Редиректы
add_action( 'wp', function() {
  global 
    $post,
    $site_url,
    $questionnaire_show,
    $questionnaire_complete;

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

// Enable the option show in rest
// add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );

// Enable the option edit in rest
// add_filter( 'acf/rest_api/field_settings/edit_in_rest', '__return_true' );

// Роли для пользователей
add_filter( 'editable_roles', function( $all_roles ) {
  unset(
    $all_roles['subscriber'],
    $all_roles['author'],
    $all_roles['editor']
  );
  return $all_roles;
} );



// Определение slug шаблона, нужно для подключения нужных стилей и скриптов к страницам
add_filter( 'template_include', function( $template ) {
  global $post;
  
  $GLOBALS['current_template'] = pathinfo( $template )['filename'];

  if ( $post->post_type === 'page' ) {
    $page_template_id = $post->ID;
  } else {
    $page = get_pages( [
      'meta_key' => '_wp_page_template',
      'meta_value' => $GLOBALS['current_template'] . '.php'
    ] )[0];

    $page_template_id = $page->ID;
  }

  $GLOBALS['sections'] = get_field( 'sections', $page_template_id );
  return $template;
} );

// Создание главной секции на внутренних страницах личного кабинета
require $template_directory . '/components/account-hero-block.php';

require $template_directory . '/inc/close-welcome-block.php';
require $template_directory . '/inc/weight-send.php';
require $template_directory . '/inc/measure-send.php';

// Создание карточек в анкете
require $template_directory . '/components/questionnaire-card.php';

// Отправка и получение данных анкеты
require $template_directory . '/inc/questionnaire-send.php';

// Получение и обновление инстаграм постов
require $template_directory . '/inc/instagram-posts.php';

// Создание <picture> для img
require $template_directory . '/inc/create-picture.php';

// Создание <link rel="preload" /> для img
require $template_directory . '/inc/create-link-preload.php';

// Активация SVG и WebP в админке
require $template_directory . '/inc/enable-svg-and-webp.php';

// Регистрация стилей и скриптов для страниц и прочие манипуляции с ними
require $template_directory . '/inc/enqueue-styles-and-scripts.php';

// Отключение стандартных скриптов и стилей, гутенберга, emoji и т.п.
require $template_directory . '/inc/disable-wp-scripts-and-styles.php';

// Регистрация меню на сайте
require $template_directory . '/inc/menus.php';

// Регистрация доп. полей в меню Настройки->Общее
require $template_directory . '/inc/options-fields.php';

// Регистрация и изменение записей и таксономий
require $template_directory . '/inc/register-custom-posts-types-and-taxonomies.php';

// Нужные поддержки темой, рамзеры для нарезки изображений
require $template_directory . '/inc/theme-support-and-thumbnails.php';

// Склеивание путей с правильным сепаратором
require $template_directory . '/inc/php-path-join.php';


if ( is_super_admin() || is_admin_bar_showing() ) {

  // Создание новых колонок в админке
  require $template_directory . '/inc/manage-posts-columns.php';

	// Функция формирования стилей для страницы при сохранении страницы
	require $template_directory . '/inc/build-styles.php';

  // require $template_directory . '/inc/ajax-recipe.php';

	// Функция формирования скриптов для страницы при сохранении страницы
	require $template_directory . '/inc/build-scripts.php';

	// Функция создания webp и минификации изображений
	require $template_directory . '/inc/generate-images.php';

	// Формирование файла pages-info.json, для понимания на какой странице какие секции используются
	require $template_directory . '/inc/build-pages-info.php';

	// Удаление лишних пунктов из меню админ-панели и прочие настройки админ-панели
	require $template_directory . '/inc/admin-menu-actions.php';
}