<?php
// Создание новых колонок для dish
// add_filter( 'manage_dish_posts_columns', function( $columns ) {
//   $num = 1; // после какой по счету колонки вставлять новые

//   $new_columns = [
//     'title' => 'Название',
//     'calories' => 'Калорийность',
//     'text' => 'Текст рецепта',
//     'products' => 'Продукты',
//     'terms' => 'Категории',
//     'date' => 'Дата публикации'
//   ];

//   return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
// }, 4 );

// // Заполнение колонок нужными данными
// add_action( 'manage_dish_posts_custom_column', function( $colname, $post_id ) {
//   switch ( $colname ) {
//     case 'calories':
//       echo get_field( 'calories', $post_id );
//       break;
//     case 'products':
//       $recipe = get_field( 'recipe', $post_id );
//       $ingredients = get_field( 'ingredients', $recipe->ID );
//       $ingredients_text = '';
//       foreach ( $ingredients as $ingredient ) {
//         $ingredients_text .= $ingredient->post_title . ', ';
//       }
//       echo '<p>' . substr( $ingredients_text, 0, -2 ) . '</p>';
//       break;
//     case 'terms':
//       $categories = get_field( 'categories', $post_id );
//       if ( $categories ) {
//         foreach ( $categories as $category ) {
//           $categories_text .= $category->name . ', ';
//         }
//         echo '<p>' . substr( $categories_text, 0, -2 ) . '</p>';
//       } else {
//         echo '<p>&mdash;</p>';
//       }
//       break;
//     case 'title':
//       echo '<p>' . get_the_title( $post_id ) . '</p>';
//       break;
//     case 'text':
//       echo '<p>' . get_field( 'recipe_text', $post_id ) . '</p>';
//       break;
//   }
// }, 5, 2);

// Создание новых колонок для recipe
add_filter( 'manage_dish_posts_columns', function( $columns ) {
  $num = 1; // после какой по счету колонки вставлять новые

  $new_columns = [
    'title' => 'Название',
    'calories' => 'Калорийность',
    'terms' => 'Категории',
    'type' => 'Тип',
    'date' => 'Дата публикации'
  ];

  return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}, 4 );

// Заполнение колонок нужными данными
add_action( 'manage_dish_posts_custom_column', function( $colname, $post_id ) {
  switch ( $colname ) {
    case 'calories':
      echo get_field( 'calories', $post_id );
      break;
    case 'type':
      $type = get_field( 'type', $post_id );
      if ( $type ) {
        echo $type->name;
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'terms':
      $categories = get_field( 'categories', $post_id );
      if ( $categories ) {
        foreach ( $categories as $category ) {
          $categories_text .= $category->name . ', ';
        }
        echo '<p>' . substr( $categories_text, 0, -2 ) . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'title':
      echo '<p>' . get_the_title( $post_id ) . '</p>';
      break;
  }
}, 5, 2);


// добавляем возможность сортировать колонку
add_filter( 'manage_edit-dish_sortable_columns', 'sort_columns_by_calories' );
add_filter( 'manage_edit-recipe_sortable_columns', 'sort_columns_by_calories' );

function sort_columns_by_calories( $sortable_columns ) {
  $sortable_columns['calories'] = ['calories_calories', false];
  return $sortable_columns;
}

// изменяем запрос при сортировке колонки
add_action( 'pre_get_posts', function( $query ) {
  if( ! is_admin() 
    || ! $query->is_main_query() 
    || $query->get( 'orderby' ) !== 'calories_calories' 
    // || get_current_screen()->id !== 'edit-post'
  ) {
    return;
  }

  $query->set( 'meta_key', 'calories' );
  $query->set( 'orderby', 'meta_value_num' );
} );
