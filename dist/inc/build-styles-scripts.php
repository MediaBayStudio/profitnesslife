<?php
add_action( 'save_post_page', function( $post_ID ) {
  if ( !function_exists( 'get_field' ) ) {
    return;
  }
  global $template_directory;

  $screen_widths = ['0', '576', '768', '1024', '1280'];

  $acf = $_REQUEST['acf'];

  $sections = [
    'header',
    'mobile-menu'
  ];

  foreach ( $acf as $fields ) {
    $continue = false;

    if ( is_array( $fields ) ) {
      foreach ( $fields as $field ) {
        if ( $field['acf_fc_layout'] ) {
          $sections[] = $field['acf_fc_layout'];
        } else {
          $continue = true;
          break;
        }
      }
  
      if ( $continue ) {
        continue;
      }
    } else {
      $fields = array_values( get_object_vars( $fields ) )[0];

      foreach ( $fields as $field ) {
        if ( $field->acf_fc_layout ) {
          $sections[] = $field->acf_fc_layout;
        } else {
          $continue = true;
          break;
        }
      }
  
      if ( $continue ) {
        continue;
      }
    }
  }

  $sections[] = 'footer';

  // JavaScript
  // Собираем JS контент и вставляем в файл страницы
  $javascript_content = 'document.addEventListener(\'DOMContentLoaded\', function() {';
  $javascript_content .= PHP_EOL;
  $javascript_sections_content = '';

  // Получаем ярлык шаблона и убираем .php в нем (будет index, about и т.д.)
  // Собираем массив для вставки в pages-info.json
  $template_slug = str_replace( '.php', '', get_page_template_slug( $post_ID ) );
  $page_info_cnt[ $template_slug ] = [];

  foreach ( $sections as $section ) {
    // Делаем массив сразу уникальным
    if ( !in_array( $section, $page_info_cnt[ $template_slug ] ) ) {
      $page_info_cnt[ $template_slug ][] = $section;
    }

    // Заходим в каждый JS файл и собираем его контент
    $section_filename = $section . '.js';
    $section_fielpath = php_path_join( $template_directory, 'sections', $section, $section_filename );
    if ( file_exists( $section_fielpath ) ) {
      $section_content = file_get_contents( $section_fielpath );
      if ( $section_content ) {
        $javascript_sections_content .= "\n// " . $section . "\n" . $section_content;
      }
    }
  } // endforeach $sections

  if ( $javascript_sections_content ) {
    $javascript_destination = php_path_join( $template_directory, 'js', 'script-' . $template_slug . '.js' );

    if ( file_exists( $javascript_destination ) ) {
      unlink( $javascript_destination );
    }

    $javascript_content .= $javascript_sections_content . PHP_EOL . '});';

    file_put_contents( $javascript_destination, $javascript_content );
  }

  // CSS
  // Собираем CSS контент для страницы и размера экрана
  foreach ( $screen_widths as $width ) {
    if ( !$template_slug ) {
      break;
    }

    $css_content = '';

    if ( $width === '0') {
      $slug = $template_slug;
    } else {
      $slug = $template_slug . '.' . $width;
    }

    foreach ( $sections as $section ) {
      if ( $width === '0' ) {
        $filename = $section . '.css';
      } else {
        $filename = $section . '.' . $width . '.css';
      }

      $filepath = php_path_join( $template_directory, 'sections', $section, $filename );

      if ( file_exists( $filepath ) ) {
        $css_content .= file_get_contents( $filepath );
      }
    } // endforeach $sections

    $css_destination = php_path_join( $template_directory, 'css', 'style-' . $slug . '.css' );

    if ( file_exists( $css_destination ) ) {
      unlink( $css_destination );
    }

    file_put_contents( $css_destination, $css_content );
  } // endforeach $screen_widths

  // Создаем файл с информацией для gulp
  build_pages_info( $page_info_cnt );

}, 10, 3 );