<?php

// Расширяем поиск по ACF полям
add_filter( 'posts_clauses', function( $clauses ){
  global $wpdb;

  if ( !$_POST['term'] ) {
    return $clauses;
  }

  $clauses['join'] .= " LEFT JOIN $wpdb->postmeta kmpm ON (ID = kmpm.post_id)";

  $clauses['where'] = preg_replace(
    "/OR +\( *$wpdb->posts.post_content +LIKE +('[^']+')/",
    "OR (kmpm.meta_value LIKE $1) $0",
    $clauses['where']
  );

  // Ищем в полях full_title и card_descr
  $clauses['where'] .= $wpdb->prepare(' AND kmpm.meta_key = %s', ['full_title', 'card_descr'] );

  $clauses['distinct'] = 'DISTINCT';

  0 && add_filter( 'posts_request', function( $sql ){   die( $sql );  } );

  return $clauses;
} );

function ajax_search() {
  global $template_directory_uri;
  // Параметры поискового запроса
  $query = new WP_Query( [
    's' => $_POST['term'],
    'posts_per_page' => -1,
    'suppress_filters' => false
  ] );
  if ( $query->have_posts() ) :
    while ( $query->have_posts() ) :
      $query->the_post() ?>
      <!-- Вывод постов -->
       <?php
    endwhile;
  else : ?>
  <span class="search-result__not-found">Ничего не найдено</span> <?php
  endif;
  exit;
}

add_action( 'wp_ajax_nopriv_ajax_search', 'ajax_search' );
add_action( 'wp_ajax_ajax_search', 'ajax_search' );