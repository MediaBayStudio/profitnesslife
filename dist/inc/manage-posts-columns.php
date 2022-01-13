<?php
add_filter( 'manage_dish_posts_columns', function( $columns ) {
  $num = 1; // после какой по счету колонки вставлять новые

  $new_columns = [
    'title' => 'Название',
    'calories' => 'Калории',
    'ingredients' => 'Ингредиенты',
    'terms' => 'Категории',
    'type' => 'Тип',
    'target' => 'Цель',
    'date' => 'Дата публикации'
  ];

  return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}, 4 );

// Заполнение колонок нужными данными
add_action( 'manage_dish_posts_custom_column', function( $colname, $post_id ) {
  global $site_url;

  switch ( $colname ) {
    case 'target':
      $terms = get_the_terms( $post_id, 'dish_target' );

      foreach ( $terms as $target ) {
        $target_text .= '<a href="' . $site_url . '/wp-admin/edit.php?dish_target=' . $target->slug . '&post_type=dish" title="Показать все приемы пищи с ' . $target->name . '">' . $target->name . '</a>, ';
      }
      echo '<p>' . substr( $target_text, 0, -2 ) . '</p>';
      break;
    case 'calories':
      echo get_field( 'calories', $post_id );
      break;
    case 'ingredients':
      $ingredients = get_field( 'ingredients', $post_id );
      foreach ( $ingredients as $ingredient ) {
        $ingredients_text .= '<a href="' . $site_url . '/wp-admin/edit.php?dish_ingredients=' . $ingredient['title']->slug . '&post_type=dish" title="Показать все приемы пищи с ' . $ingredient['title']->name . '">' . $ingredient['title']->name . '</a>';
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
          if ( is_numeric( $type ) ) {
            $type = get_term( $type, 'dish_type' );
          }
          $types_text .= '<a href="' . $site_url . '/wp-admin/edit.php?dish_type=' . $type->slug . '&post_type=dish">' . $type->name . '</a>, ';
        }
        echo '<p>' . substr( $types_text, 0, -2 ) . '</p>';
      } else {
        echo '<p>&mdash;</p>';
      }
      break;
    case 'terms':
      $categories = get_the_terms( $post_id, 'dish_category' );
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
add_filter( 'manage_edit-dish_sortable_columns', function( $sortable_columns ) {
  $sortable_columns['calories'] = ['calories_calories', false];
  $sortable_columns['type'] = ['type_type', false];
  return $sortable_columns;
} );

add_filter( 'manage_users_columns', function( $columns ) {
  $num = 1; // после какой по счету колонки вставлять новые

  // var_dump( $columns );

  // return $columns;

  $new_columns = [
    'username' => 'Имя пользователя',
    'name' => 'Имя',
    'email' => 'E-mail',
    'user_role' => 'Роль',
    'start_date' => 'Дата начала марафона',
    'end_date' => 'Дата завершения марафона',
    'marathons_count' => 'Кол-во прохождений марафона'
  ];

  return array_slice( $columns, 0, $num ) + $new_columns;
}, 4 );

// Заполнение колонок нужными данными
add_action( 'manage_users_custom_column', function( $output, $column_name, $user_id ) {
  global $site_url;

  $user_data = get_fields( 'user_' . $user_id );
  $user = new WP_User( $user_id );

  $get_params = '';

  // Массив гет параметров для сортируемых колонок
  if ( $_GET['role'] ) {
    $get_params .= 'role=waiting&';
  }

  switch ( $column_name ) {
    case 'start_date':
      $start_date = date( 'd.m.Y', $user_data['start_marathon_time'] );
      $output = "<p><a href=\"{$site_url}/wp-admin/users.php?filter=true&meta_key=start_marathon_time&meta_value={$user_data['start_marathon_time']}\">{$start_date}</a></p>";
      break;
    case 'end_date':
      $finish_date = date( 'd.m.Y', $user_data['finish_marathon_time'] );
      $output = "<p><a href=\"{$site_url}/wp-admin/users.php?filter=true&meta_key=finish_marathon_time&meta_value={$user_data['finish_marathon_time']}\">{$finish_date}</a></p>";
      break;
    case 'user_role':
      global $wp_roles;

      $all_roles = $wp_roles->roles; 

      foreach ( $user->roles as $user_role_key ) {
        $user_role = $user_role_key;
      }

      foreach ( $all_roles as $role_key => $role_details ) {
        if ( $role_key == $user_role_key ) {
          $user_role_name = $role_details['name'];
        }
      }
      $output = "<p><a href=\"{$site_url}/wp-admin/users.php?role={$user_role_key}\">{$user_role_name}</a></p>";
      break;
    case 'marathons_count':
      $output = "<p><a href=\"{$site_url}/wp-admin/users.php?filter=true&meta_key=marathons_count&meta_value={$user_data['marathons_count']}\">{$user_data['marathons_count']}</a></p>";
      break;
  }

  return $output;
}, 25, 3 );

// добавляем возможность сортировать колонку
add_filter( 'manage_users_sortable_columns', function( $sortable_columns ) {
  $sortable_columns['start_date'] = ['start-date_start-date', false];
  $sortable_columns['end_date'] = ['end-date_end-date', false];
  $sortable_columns['user_role'] = ['role_role', false];
  $sortable_columns['marathons_count'] = ['role_role', false];
  return $sortable_columns;
} );


add_action( 'pre_get_users', function( $query ) {
  if ( !is_admin() ) {
    return;
  }

  $query_orderby = $query->get( 'orderby' );

  if ( $_GET['filter'] && $_GET['meta_value'] && $_GET['meta_key'] ) {
    $query->set( 'meta_key', $_GET['meta_key'] );
    $query->set( 'meta_value', $_GET['meta_value'] );
  } else if ( $query_orderby === 'start-date_start-date' || $query_orderby === 'end-date_end-date' || $query_orderby === 'role_role' ) {
    switch ( $query_orderby ) {
      case 'start-date_start-date':
        $meta_key = 'start_marathon_time';
        break;
      case 'end-date_end-date':
        $meta_key = 'finish_marathon_time';
        break;
      case 'role_role':
        $meta_key = 'role';
        break;
    }

    $query->set( 'meta_key', $meta_key );
    $query->set( 'orderby', 'meta_value_num' );
  }

} );

// изменяем запрос при сортировке колонки
add_action( 'pre_get_posts', function( $query ) {
  $query_orderby = $query->get( 'orderby' );

  if ( !is_admin()
    || !$query->is_main_query()
    || $query_orderby !== 'calories_calories'
    && $query_orderby !== 'type_type' ) {
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