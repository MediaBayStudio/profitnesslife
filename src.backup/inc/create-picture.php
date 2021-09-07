<?php

function cretae_picture( $section_class, $img_field, $lazy = true, $alt = '', $print = true ) {

  $pic_class = $section_class . '__pic';
  $img_class = $section_class . '__img';

  $image_array = [
    'img' => $img_field,
    'media' => 0
  ];
  
  $img_field = get_fields( $img_field['ID'] );
  $img_field[] = $image_array;


  if ( $lazy ) {
    $pic_class .= ' lazy';
    $img_attr = 'src="#" data-src="';
    $source_attr = 'srcset="#" data-srcset="';
  } else {
    $img_attr = 'src="';
    $source_attr = 'srcset="';
  }

  $tag = '<picture class="' . $pic_class . '">';

  foreach ( $img_field as $img ) {
    if ( !$img ) {
      continue;
    }
    $img_url = $img['img']['url'];

    if ( $img['media'] ) {
      $media = ' media="' . $img['media'] . '"';
    } else {
      $media = '';
    }

    if ( $alt ) {
      $img_alt = $alt;
    } else {
      if ( $img['img']['alt'] ) {
        $img_alt = $img['img']['alt'];
      } else {
        $img_alt = '#';
      }
    }

    if ( $img['media'] == '0' ) {
      $tag .= '<img ' . $img_attr . $img_url . '" alt="' . $img_alt . '"' . $media . ' class="' . $img_class . '">';
    } else {
      $tag .= '<source ' . $source_attr . $img_url . '" type="' . $img['img']['mime_type'] . '"' . $media . '>';
    }
    
    unset( $img_url, $media, $img_alt );
  }

  $tag .= '</picture>';

  if ( $print ) {
    echo $tag;
  } else {
    return $tag;
  }

}