<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function photo_send() {
  if ( $_POST['action'] === 'photo_send' ) {
    global $user_id, $user_data, $upload_baseurl,
    $upload_basedir, $template_directory;

    $user = 'user_' . $user_id;

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    $file = & $_FILES['photo'];
    $overrides = [ 'test_form' => false ];

    $moved_file = wp_handle_upload( $file, $overrides );

    $file_pathinfo = pathinfo( $moved_file['file'] );

    // Подготовим массив с необходимыми данными для вставки в бд
    $attachment = [
      'guid'           => $moved_file['url'], 
      'post_mime_type' => $moved_file['type'],
      'post_title'     => $file_pathinfo['filename'],
      'post_content'   => '',
      'post_status'    => 'publish'
    ];


    $attachment_id = wp_insert_attachment( $attachment, $moved_file['file'] );
    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $moved_file['file'] );
    wp_update_attachment_metadata( $attachment_id, $attachment_data );
  
    if ( is_wp_error( $attachment_id ) ) {
      echo 0;
    } else {
      // Медиа файл загружен
      // обновляем поле галереи
      // или поле аватара
      if ( $_POST['type'] === 'avatar' ) {
        $field_name = 'img';
        $field_value = $attachment_id;
      } else {
        $images_ids[] = $attachment_id;
        if ( $user_data['photo_progress'] ) {
          foreach ( $user_data['photo_progress'] as $img ) {
            $images_ids[] = $img['ID'];
          }
        }

        $field_name = 'photo_progress';
        $field_value = $images_ids;
      }

      $updated = update_field( $field_name, $field_value, $user );

      if ( $updated ) {
        $response = [];
        
        $webp = image_get_intermediate_size( $attachment_id, 'photo_progress_webp' )['url'];
        $url = image_get_intermediate_size( $attachment_id, 'photo_progress' )['url'];

        if ( !$webp ) {
          $webp = image_get_intermediate_size( $attachment_id, 'webp' )['url'];
        }
        
        if ( !$url ) {
          $url = wp_get_attachment_url( $attachment_id );
        }

        if ( $webp ) {
          $response['img_webp'] = $webp;
        }

        if ( $url ) {
          $response['img'] = $url;
        }

        echo json_encode( $response );
      } else {
        // Ошибка обновления поля
        // удалить вложение и вернуть 0
        wp_delete_attachment( $attachment_id, true );
        echo 0;
      }
    }

    die();
  }
}

add_action( 'wp_ajax_nopriv_photo_send', 'photo_send' ); 
add_action( 'wp_ajax_photo_send', 'photo_send' );