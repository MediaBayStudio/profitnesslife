<?php
// необходимые поддержки темой
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'custom-logo' );

add_action( 'after_setup_theme', function() {
  $standart_sizes = [
    'medium',
    'medium_large',
    'large',
    '1536x1536',
    '2048x2048',
  ];

  foreach ( $standart_sizes as $standart_size ) {
    remove_image_size( $standart_size );
  }

}, 999 );

// удаление ненужных миниатюр
add_filter( 'intermediate_image_sizes', function ( $sizes ) {
  // размеры которые нужно удалить
  return array_diff( $sizes, [
    'medium',
    'medium_large',
    'large',
    '1536x1536',
    '2048x2048',
  ] );
} );

// add_image_size( 'size_name', 100, 100, true );