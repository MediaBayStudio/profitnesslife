<?php
// Глобальные переменные
$version = '1.0.4'; // Версия стилей и скриптов
$template_directory_uri = get_template_directory_uri();
$template_directory = get_template_directory();
$site_url = site_url();

$user = wp_get_current_user();
$user_id = $user->ID;
$user_data = get_fields( 'user_' . $user_id );
$questionnaire_complete = get_field( 'questionnaire_complete', 'user_' . $user_id );

// echo '<script>var userData = \'' . json_encode( $user_data ) . '\'</script>';

$upload_dir = wp_get_upload_dir();
$upload_basedir = $upload_dir['basedir'];
$upload_baseurl = $upload_dir['baseurl'];

$tel = get_option( 'contacts_tel' );
$tel_clean = str_replace( [' ', '–', '-', '(', ')'], '', $tel );
$email = get_option( 'contacts_email' );
$manager_link_whatsapp = get_option( 'contacts_manager_link_whatsapp' );
$manager_link_viber = get_option( 'contacts_manager_link_viber' );

$logo_id = get_theme_mod( 'custom_logo' );
$logo_url = wp_get_attachment_url( $logo_id );

// Кнопка "запустить марафон еще раз" на стр. пользователя
add_action( 'admin_color_scheme_picker', function() {
  echo '<button type="button" class="button" onclick="resetQuestionnaire()" id="clear-user-data-btn" data-user="' . $_GET['user_id'] . '">Запустить марафон еще раз</button>';
} );

add_action('init', function() {
    // Создаём событие нашего планировщика
    add_action( 'wplb_cron', 'wplb_run_cron' );
    
    // Регистрируем событе на случай дективации

    // Добавляем наше событие в WP-Cron 
    if ( ! wp_next_scheduled ( 'wplb_cron' ) ) {
      wp_schedule_event( time(), 'weekly', 'wplb_cron' );
    }
});

add_filter( 'big_image_size_threshold', function(){
	return 1200;
} );

// Описываем функцию для планировщика
function wplb_run_cron() {
  $users = get_users( [
    'role__in' => 'completed',
    'number' => -1
  ] );

  foreach ( $users as $user ) {
    questionnaire_send( [
      'reset' => true,
      'user' => $user->ID,
      'cron' => true // не менять роль и не удалять дату начала и окончания марафона
    ] );
  }
}

/*
  Расчеты времени после прохождения анкеты
*/

  function set_user_role_started( $user_object ) {
    if ( in_array( 'waiting', (array)$user_object->roles ) ) {
      $user_object->set_role( 'started' );
      update_field( 'role', 1, 'user_' . $user_object->ID );
    }
  }

  function set_user_role_completed( $user_object ) {
    if ( in_array( 'started', (array)$user_object->roles ) ) {
      $user_object->set_role( 'completed' );
      $user_id = 'user_' . $user_object->ID;
      $marathons_count = get_field( 'marathons_count', $user_id );
      update_field( 'role', 2, $user_id );
      update_field( 'marathons_count', $marathons_count + 1, $user_id );
    }
  }

// Текущее время в мс
$current_time = strtotime( 'now' );
// $current_time = strtotime( '30.12.2021 16:20:13' );

// Дата прохождения анкеты (d.m.Y H:i:s)
$questionnaire_date =  $user_data['questionnaire_time'];
$questionnaire_dmy_date = date( 'd.m.Y', strtotime( $questionnaire_date ) );
$questionnaire_dmy_time = strtotime( $questionnaire_dmy_date );
// $questionnaire_date =  '12.09.2021 12:10:27'; // 3 неделя
// $questionnaire_date =  '21.09.2021 12:10:27'; // 2 неделя

// Если анкета пройдена, то считаем время
if ( $questionnaire_date ) {

  $start_marathon_time = $user_data['start_marathon_time'];
  $finish_marathon_time = $user_data['finish_marathon_time'];

  // Пришло время показывать план питания или нет
  if ( !$user_data['show_diet_plan'] ) {
    $show_diet_plan = $user_data['diet_plan_open_time'] <= $current_time;
    update_field( 'show_diet_plan', $show_diet_plan, 'user_' . $user_id );
  } else {
    $show_diet_plan = true;
  }

  // Марафон начался, меняем роль пользователя
  if ( $start_marathon_time <= $current_time && !is_super_admin() && $user_id != 13 ) {
    set_user_role_started( $user );
  }

  // Марафон закончился, меняем роль пользователя
  if ( $finish_marathon_time <= $current_time && !is_super_admin() && $user_id != 13 ) {
    set_user_role_completed( $user );
  }

  // Концы каждой недели марафона (только дата, без времени)
  $first_week_end_time = $user_data['first_week_end_time'];
  $second_week_end_time = $user_data['second_week_end_time'];
  $third_week_end_time = $user_data['third_week_end_time'];

  // Переменная используется в пострении графиков веса и объемов тела
  $weeks_end_dates = [
    $first_week_end_time,
    $second_week_end_time,
    $third_week_end_time
  ];

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

// Авторизация пользователя
require $template_directory . '/inc/auth.php';

// Запись успешного платежа в базу данных
require $template_directory . '/inc/create-payment.php';

// Редиректы пользователей
require $template_directory . '/inc/redirects.php';

// Удаление данных пользователей, которые завершили марафон
require $template_directory . '/inc/remove-users-data.php';

// Роли пользователей
require $template_directory . '/inc/users-roles.php';

// Функция создания календаря для стр. план питания
require $template_directory . '/inc/calendar.php';

// Отправка в бд записи о закрытии приветственного блока
require $template_directory . '/inc/close-welcome-block.php';

// Подгрузка плана питания (при клике по календарю)
require $template_directory . '/inc/load-diet-plan.php';

// Отправка данных о весе пользователя
require $template_directory . '/inc/weight-send.php';

// Отправка данных для фото-прогресса
require $template_directory . '/inc/photo-send.php';

// Отправка данных о замерах объемов тела пользователя
require $template_directory . '/inc/measure-send.php';

// Расчет продуктовой корзины
require $template_directory . '/inc/recalculate-products-cart.php';

// Отправка данных о замене приемов пищи (пользователь кликнул на "заменить блюдо")
require $template_directory . '/inc/replace-dish.php';

// Импорт приемов пищи из csv в бд
require $template_directory . '/inc/import.php';

// Создание карточек в анкете
require $template_directory . '/components/questionnaire-card.php';

// Создание главной секции на внутренних страницах личного кабинета
require $template_directory . '/components/account-hero-block.php';

// Контент внутри попапа
require $template_directory . '/components/popup-content.php';

// Отправка и получение данных анкеты
require $template_directory . '/inc/questionnaire-send.php';

// Получение и обновление инстаграм постов
require $template_directory . '/inc/instagram-posts.php';

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

  // Смена ролей пользователям (когда марафон для пользователя закончен или начат)
  require $template_directory . '/inc/change-users-roles.php';

  // Создание новых колонок в админке
  require $template_directory . '/inc/manage-posts-columns.php';

	// Формирование файла pages-info.json, для понимания на какой странице какие секции используются
	require $template_directory . '/inc/build-pages-info.php';

	// Функция формирования стилей и скриптов для страницы при сохранении страницы
	require $template_directory . '/inc/build-styles-scripts.php';

	// Функция создания webp и минификации изображений
	require $template_directory . '/inc/generate-images.php';

	// Удаление лишних пунктов из меню админ-панели и прочие настройки админ-панели
	require $template_directory . '/inc/admin-menu-actions.php';
}