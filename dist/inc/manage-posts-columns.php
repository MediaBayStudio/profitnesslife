<?php
add_filter( 'manage_dish_posts_columns', function( $columns ) {
  $num = 1; // после какой по счету колонки вставлять новые

  $new_columns = [
    'title' => 'Название',
    'calories' => 'Калорийность',
    // 'terms' => 'terms',
    'ingredients' => 'Ингредиенты',
    'terms' => 'Категории',
    'type' => 'Тип',
    'date' => 'Дата публикации'
  ];

  return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}, 4 );

// Заполнение колонок нужными данными
add_action( 'manage_dish_posts_custom_column', function( $colname, $post_id ) {
  global $site_url;

  switch ( $colname ) {
    // case 'terms':
    //   $terms = get_the_terms( $post_id, 'dish_ingredients' );

    //   foreach ( $terms as $ingredient ) {
    //     $ingredients_text .= $ingredient->name;
    //     $ingredients_text .= ', ';
    //   }
    //   echo '<p>' . substr( $ingredients_text, 0, -2 ) . '</p>';
    //   break;
    case 'calories':
      echo get_field( 'calories', $post_id );
      break;
    case 'ingredients':
      $ingredients = get_field( 'ingredients', $post_id );
      foreach ( $ingredients as $ingredient ) {
        $ingredients_text .= $ingredient['title']->name;
        if ( $ingredient['number'] ) {
          $ingredients_text .= ' (' . $ingredient['number'] . ' ' .  $ingredient['units']['label'] . ')';
        }
        $ingredients_text .= ', ';
      }
      echo '<p>' . substr( $ingredients_text, 0, -2 ) . '</p>';
      break;
    case 'type':
      $types = get_field( 'type', $post_id );
      if ( $types ) {
        foreach ( $types as $type ) {
          $types_text .= '<a href="' . $site_url . '/wp-admin/edit.php?dish_type=' . $type->slug . '&post_type=dish">' . $type->name . '</a>, ';
        }
        echo '<p>' . substr( $types_text, 0, -2 ) . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'terms':
      $categories = get_field( 'categories', $post_id );
      if ( $categories ) {
        foreach ( $categories as $category ) {
          $categories_text .= '<a href="' . $site_url . '/wp-admin/edit.php?dish_category=' . $category->slug . '&post_type=dish">' . $category->name . '</a>, ';
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

function sort_columns_by_calories( $sortable_columns ) {
  $sortable_columns['calories'] = ['calories_calories', false];
  $sortable_columns['type'] = ['type_type', false];
  return $sortable_columns;
}

// изменяем запрос при сортировке колонки
add_action( 'pre_get_posts', function( $query ) {
  $query_orderby = $query->get( 'orderby' );

  if ( !is_admin() || !$query->is_main_query() 
    || $query_orderby !== 'calories_calories'
    && $query_orderby !== 'type_type') {
    return;
  }

  switch ( $query_orderby ) {
    case 'calories_calories':
      $meta_key = 'calories';
      $meta_value = 'meta_value_num';
      break;
    case 'type_type':
      $meta_key = 'type';
      $meta_value = 'meta_value';
      break;
  }

  $query->set( 'meta_key', $meta_key );
  $query->set( 'orderby', $meta_value );
} );

add_filter( 'manage_workout_posts_columns', function( $columns ) {
  $num = 1; // после какой по счету колонки вставлять новые

  $new_columns = [
    'title' => 'Название',
    'video' => 'Файл',
    'duration' => 'Длительность',
    'type' => 'Тип',
    'category' => 'Категории',
    'inventory' => 'Инвентарь',
    'muscle_groups' => 'Ограничения',
    'date' => 'Дата публикации'
  ];

  return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}, 4 );

// Заполнение колонок нужными данными
add_action( 'manage_workout_posts_custom_column', function( $colname, $post_id ) {
  global $site_url;

  $post_fields = get_fields( $post_id );

  switch ( $colname ) {
    case 'duration':
      if ( $post_fields['video_duration'] ) {
        echo '<p>' . $post_fields['video_duration'] . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'video':
      if ( $post_fields['video'] ) {
        echo '<p>' . $post_fields['video'] . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'type':
      if ( $post_fields['type'] ) {
        foreach ( $post_fields['type'] as $term ) {
          $term = get_term( $term );
          $term_text .= '<a href="' . $site_url . '/wp-admin/edit.php?workout_type=' . $term->slug . '&post_type=workout">' . $term->name . '</a>, ';
        }
        echo '<p>' . substr( $term_text, 0, -2 ) . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'category':
      if ( $post_fields['category'] ) {
        foreach ( $post_fields['category'] as $term ) {
          $term = get_term( $term );
          $term_text .= '<a href="' . $site_url . '/wp-admin/edit.php?workout_category=' . $term->slug . '&post_type=workout">' . $term->name . '</a>, ';
        }
        echo '<p>' . substr( $term_text, 0, -2 ) . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'inventory':
      if ( $post_fields['workout_inventory'] ) {
        foreach ( $post_fields['workout_inventory'] as $term ) {
          $term = get_term( $term );
          $term_text .= '<a href="' . $site_url . '/wp-admin/edit.php?workout_inventory=' . $term->slug . '&post_type=workout">' . $term->name . '</a>, ';
        }
        echo '<p>' . substr( $term_text, 0, -2 ) . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'muscle_groups':
      if ( $post_fields['muscle_groups'] ) {
        foreach ( $post_fields['muscle_groups'] as $term ) {
          $term = get_term( $term );
          $term_text .= '<a href="' . $site_url . '/wp-admin/edit.php?workout_muscle_groups=' . $term->slug . '&post_type=workout">' . $term->name . '</a>, ';
        }
        echo '<p>' . substr( $term_text, 0, -2 ) . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'title':
      echo '<p>' . get_the_title( $post_id ) . '</p>';
      break;
  }
}, 5, 2);