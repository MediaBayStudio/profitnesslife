<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function photo_send() {
  if ( $_POST['action'] === 'photo_send' ) {
    global $user_id, $user_data, $upload_baseurl;

    $user = 'user_' . $user_id;

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    $attachment_id = media_handle_upload( 'photo', $user );

    if ( is_wp_error( $attachment_id ) ) {
      echo 0;
    } else {
      // Медиа файл загружен
      // обновляем поле галереи

      $images_ids[] = $attachment_id;
      if ( $user_data['photo_progress'] ) {
        foreach ( $user_data['photo_progress'] as $img ) {
          $images_ids[] = $img['ID'];
        }
      }

      $updated = update_field( 'photo_progress', $images_ids, $user );

      if ( $updated ) {
        // Поле обновлено
        echo json_encode( [
          'img' => wp_get_attachment_url( $attachment_id ),
          'img_webp' => $upload_baseurl . get_post_meta( $attachment_id, 'webp' )[0]
          ] );
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