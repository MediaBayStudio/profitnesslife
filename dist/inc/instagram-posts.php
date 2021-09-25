<?php
function get_instagram_posts( $account, $refresh=null ) {
  require './vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
  $instagram = new \InstagramScraper\Instagram( new \GuzzleHttp\Client() );
  $account = $instagram->getAccount( $account );

  $medias = $account->getMedias();

  $instagram_posts = [];

  $exists_posts = get_posts( [
    'post_type' => 'instagram_post',
    'numberposts' => 4
  ] );

  foreach ( $exists_posts as $exists_post ) {
    wp_delete_post( $exists_post->ID, true );
  }

  for ( $i = 0; $i < 4; $i++) {
    $post_id = wp_insert_post( [
      'post_type'     => 'instagram_post',
      'post_title'    => sanitize_text_field( $i ),
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1
    ] );

    $link = $medias[ $i ]->getLink();
    $img_link = base64_encode( file_get_contents( $medias[ $i ]->getImageHighResolutionUrl() ) );
    $likes = $medias[ $i ]->getLikesCount();
    $comments = $medias[ $i ]->getCommentsCount();

    $instagram_posts[] = (object)[
      'ID' => $post_id,
      'link' => $link,
      'img_link' => $img_link,
      'likes' => $likes,
      'comments' => $comments
    ];

    update_field( 'link', $link, $post_id );
    update_field( 'img_link', $img_link, $post_id );
    update_field( 'likes', $likes, $post_id );
    update_field( 'comments', $comments, $post_id );
  }

  return $instagram_posts;
}

function refresh_intsagram_update_date( $date ) {
  update_option( 'contacts_instagram_last_update', date('d.m.Y h:i:s', $date ) );
}