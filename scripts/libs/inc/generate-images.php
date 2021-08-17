<?php

$image_sizes = [
  'desktop' => 1200,
  'laptop' => 980,
  'tablet' => 740,
  'mobile' => 576,
  // 'author_articles' => [300, 400],
  'thumb' => 400,
];

foreach ( $image_sizes as $size_name => $width ) {
  if ( is_array( $width ) ) {
    $height = $width[1];
    $width = $width[0];
  } else {
    $height = 0;
  }
  add_image_size( $size_name, $width, $height, true );
}

add_action( 'wp_generate_attachment_metadata', function ( $image_meta, $img_id ) {
  global $image_sizes;

  $img_path = get_attached_file( $img_id );
  $img_pathinfo = pathinfo( $img_path );
  $dirname = $img_pathinfo['dirname'];

  $upload_dir = preg_replace( '/.*uploads/', '', $dirname );

  foreach ( $image_sizes as $size_name => $width ) {
    $file = image_get_intermediate_size( $img_id, $size_name );
    $file_webp = str_replace( ['.jpg', '.jpeg', '.png'], '', $file['file'] );
    $file_webp_name = $file_webp . '.webp';
    $webp_path = $upload_dir . DIRECTORY_SEPARATOR . $file_webp_name;

    $cwebp = '/usr/local/bin/cwebp -q 90 ' . $file['file'] . ' -o ' . $file_webp_name;

    update_post_meta( $img_id, $size_name . '_webp', $webp_path );

    chdir( $dirname );
    exec( $cwebp );
  }

  $webp_name = $img_pathinfo['filename'] . '.webp';
  $webp_path = $upload_dir . '/' . $webp_name;

  $cwebp = '/usr/local/bin/cwebp -q 90 ' . $img_pathinfo['basename'] . ' -o ' . $webp_name;

  chdir( $dirname );
  exec( $cwebp );
  minifyImg( $img_path );
  update_post_meta( $img_id, 'webp', $webp_path );

  return $image_meta;
}, 10, 3 );

add_action( 'delete_attachment', function( $img_id, $img ) {
  global $upload_basedir;

  $img_path = get_attached_file( $img_id );
  $img_pathinfo = pathinfo( $img_path );
  $dirname = $img_pathinfo['dirname'];
  $img_meta = get_post_meta( $img_id );

  foreach ( $img_meta as $size_name => $filename ) {
    $webp_path = $upload_basedir . '/' . $filename[0];
    $webp_path = wp_normalize_path( $webp_path );
    if ( file_exists( $webp_path ) ) {
      unlink( $webp_path );
    }
  }

}, 10, 2 );


function minifyImg( $src, $dest = null, $quality = 90 ) {
  if ( is_null( $dest ) ) {
    $dest = $src;
  }

  $info = getimagesize( $src );

  if ( $info['mime'] === 'image/jpeg' ) {
    $image = imagecreatefromjpeg( $src );
    $is_jpg = true;
  } elseif ( $info['mime'] === 'image/gif' ) {
    $image = imagecreatefromgif( $src );
  } elseif ( $info['mime'] === 'image/png' ) {
    $is_png = true;
    $image = imagecreatefrompng( $src );
  }

  if ( $is_jpg ) {
    imagejpeg( $image, $dest, $quality );
  } else if ( $is_png ) {
    imagepng( $image, $dest, $quality );
  }

  return $dest;
}
