<?php
 
print_account_hero_section( [
  'title' => $section['title'],
  'descr' => $section['descr'],
  'class' => ' chat-hero',
  'img' => [
    'url' => $template_directory_uri . '/img/chat-hero-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => [
    [
      'title' => 'Перейти в чат',
      'class' => 'btn-green',
      'href' => $section['link']
    ]
  ]
] );

// $posts = get_posts( [
//   'post_type' => 'dish',
//   'numberposts' => -1
// ] );


// var_dump( $fileds['ingredients'] );
// var_dump( $terms );


// $bug_count = 0;
// foreach ( $posts as $post ) {
//   $ingredients = get_field( 'ingredients', $post->ID );
//   $terms = get_the_terms( $post->ID, 'dish_ingredients' );

//   foreach ( $ingredients as $ingredient ) {
//     if ( !$ingredient['title'] ) {
//       $bug_count++;
//     }
  // }

  // foreach ( $ingredients as $ingredient ) {
  //   if ( $ingredient['title'] ) {
  //     foreach ( $terms as $key => $term ) {
  //       // echo "<p>Сравниваю {$term->name} с {$ingredient['title']->name} </p>";
  //       if ( $term->name === $ingredient['title']->name ) {
  //         unset( $terms[ $key ] );
  //       }
  //     }
  //   }
  // }
  // if ( $terms ) {
  //   $terms = array_values( $terms );
  //   for ( $i = 0, $len = count( $ingredients ); $i < $len; $i++ ) { 
  //     if ( !$ingredients[ $i ]['title'] ) {
  //       $ingredients[ $i ]['title'] = $terms[0];
  //     }
  //   }
  //   echo "<p>У записи {$post->post_title} остался ингредиент: </p>";
  //   var_dump( $ingredients );
  //   echo '<br><br>';
  //   var_dump( update_field( 'ingredients', $ingredients, $post->ID ) );
  //   // break;
  // }
// }

// var_dump( $bug_count );

?>
