<?php

function create_link_preload( $item ) {
  global $template_directory, $webp_support, $media_queries, $site_url;

  $media = '';

  // $type = '';

  if ( is_string( $item ) ) {
    $filepath = $item;
  } else {
    if ( $item['filepath'] ) {

      // var_dump( $item );

      if ( $item['upload'] ) {
        $filepath = $item['filepath'];
      } else {
        $filepath = $item['filepath'];
      } // endif $item['upload']

      // if ( $item['file'] ) {
      //   $type = $item['file']['mime_type'] . '"';
      // } else if ( strpos( $item['filepath'], '.svg' ) !== false ) {
      //   $type = 'image/svg+xml';
      // } else if ( strpos( $item['filepath'], '.webp' ) !== false ) {
      //   $type = 'image/webp';
      // }

      $media = $item['media'] ? ' media="' . $item['media'] . '"' : '';
    } else if ( $item['file'] ) {
      $fields = get_fields( $item['file']['ID'] );

      $fields = [
        '2x_webp' => $fields['2x_webp_i'],
        'webp' => $fields['webp_i'],
        '2x' => $fields['2x_i']
      ];

      if ( $webp_support ) {
        $filepaths[] = [
          'path' => php_path_join( $site_url, $fields['2x_webp'] ),
          'media' => $media_queries['2x'],
          'type' => $item['file']['mime_type']
        ];

        $filepaths[] = [
          'path' => php_path_join( $site_url, $fields['webp'] ),
          'media' => $media_queries['1x'],
          'type' => $item['file']['mime_type']
        ];
      } else {
        $filepaths[] = [
          'path' => php_path_join( $site_url, $fields['2x'] ),
          'media' => $media_queries['2x'],
          'type' => $item['file']['mime_type']
        ];

        $filepaths[] = [
          'path' => $item['file']['url'],
          'media' => $media_queries['1x'],
          'type' => $item['file']['mime_type']
        ]; 
      } // endif $webp_support
    } // endif $item['file']
  } // endif is_string( $item )

  if ( $filepaths ) {
    foreach ( $filepaths as $path ) {
      echo '<link rel="preload" as="image" href="' . $path['path'] . '" media="' . $path['media'] . '" />' . PHP_EOL;
    }
  } else {
    echo '<link rel="preload" as="image" href="' . $filepath . '"' . $media . ' />' . PHP_EOL;
  } // endif $filepaths

}