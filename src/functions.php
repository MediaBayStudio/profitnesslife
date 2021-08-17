<?php
// Глобальные переменные
$template_directory_uri = get_template_directory_uri();
$template_directory = get_template_directory();
$site_url = site_url();

// $address = get_option( 'contacts_address' );
// $tel = get_option( 'contacts_tel' );
// $tel_dry = preg_replace( '/\s/', '', $tel );
// $email = get_option( 'contacts_email' );
// $insta = get_option( 'contacts_insta' );

$logo_id = get_theme_mod( 'custom_logo' );
$logo_url = wp_get_attachment_url( $logo_id );

// Проверка поддержки webp браузером
$webp_support = strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], ' Chrome/' ) !== false;

// Передаем в js некоторые данные о сайте
add_action( 'admin_head', 'print_site_data' );
add_action( 'wp_body_open', 'print_site_data' );

function print_site_data() {
  global $site_url, $template_directory_uri, $template_directory;
  echo '<script id="page-data">var siteUrl = "' . $site_url . '", templateDirectoryUri = "' . $template_directory_uri . '", templateDirectory = "' . $template_directory . '"</script>';
}

// Запрет обновления плагинов
add_filter( 'site_transient_update_plugins', function( $value ) {
  unset(
    $value->response['contact-form-7/wp-contact-form-7.php'],
    $value->response['contact-form-7-honeypot/honeypot.php'],
    $value->response['advanced-custom-fields-pro/acf.php']
  );

  return $value;
} );

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

	// Функция формирования стилей для страницы при сохранении страницы
	require $template_directory . '/inc/build-styles.php';

	// Функция формирования скриптов для страницы при сохранении страницы
	require $template_directory . '/inc/build-scripts.php';

	// Функция создания webp и минификации изображений
	require $template_directory . '/inc/generate-images.php';

	// Формирование файла pages-info.json, для понимания на какой странице какие секции используются
	require $template_directory . '/inc/build-pages-info.php';

	// Удаление лишних пунктов из меню админ-панели и прочие настройки админ-панели
	require $template_directory . '/inc/admin-menu-actions.php';
}