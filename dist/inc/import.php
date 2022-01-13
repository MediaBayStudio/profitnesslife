<?php

function csv_to_array( $filename='', $delimiter=',' ) {
  if( !file_exists($filename) || !is_readable($filename) )
      return false;

  $header = null;
  $data = [];

  if ( ($handle = fopen( $filename, 'r' )) !== false ) {
    while ( ($row = fgetcsv( $handle, 1000, $delimiter )) !== false ) {
      if ( !$header ) {
        $header = $row;
      } else {
        $data[] = array_combine( $header, $row );
      }
    }
    fclose( $handle );
  }
  return $data;
}

// Первая буква заглавная
function my_mb_ucfirst( $str ) {
  $fc = mb_strtoupper( mb_substr( $str, 0, 1 ) );
  return $fc . mb_substr( $str, 1 );
}

function insert_terms( $terms_array, $taxonomy ) {
  if ( is_string( $terms_array ) ) {
    $terms_array = [$terms_array];
  }

  foreach ( $terms_array as $term ) {
    $term_title = my_mb_ucfirst( $term );
    $term_object = get_term_by( 'name', $term_title, $taxonomy );

    if ( $term_object ) {
      echo "<p>$term_title уже существует, надо прикрепить к нему запись</p>";
    } else {
      echo "<p>$term_title не существует, добавим</p>";

      $insert_term = wp_insert_term( $term_title, $taxonomy );

      if ( is_wp_error( $insert_term ) ) {
        echo $insert_term->get_error_message();
      } else {
        echo "<p>Вставлено успешно, id: {$insert_term['term_id']}</p>";
      }
    }
  }
}

function import() {

  $csv_array = csv_to_array( get_template_directory() . '/db.csv' );

  $count = count( $csv_array );

  $i = 0;
  foreach ( $csv_array as $dish ) {
    $title = my_mb_ucfirst( $dish['Название блюда'] );
    $recipe = $dish['Рецепт'];
    $ingredients = str_replace( ['Соль Перец', 'Соль перец'], "Соль\nПерец", $dish['Ингредиенты'] );
    $ingredients = preg_replace( '/\s(?=$)/', '', $ingredients );
    $ingredients = str_replace( '', "\n", $ingredients );
    $ingredients = str_replace( 'c', 'с', $ingredients );
    $ingredients = str_replace( "&nbsp;", "", htmlentities( $ingredients ) );
    $ingredients = str_replace( 'Лук репчптый', 'Лук репчатый', $ingredients );
    $ingredients = preg_split( '/\n/', $ingredients );
    $calories = $dish['ККАЛ'];
    $types = [my_mb_ucfirst( $dish['Тип'] )];
    $targets = explode( ',', $dish['Цель'] );

    if ( stripos( $dish['Категории'], 'рыба белая' ) !== false || stripos( $dish['Категории'], 'рыба красная' ) !== false ) {
      $dish['Категории'] .= ',рыба';
    }

    if ( stripos( $dish['Категории'], 'курица' ) !== false || stripos( $dish['Категории'], 'индейка' ) !== false || stripos( $dish['Категории'], 'говядина' ) !== false ) {
      $dish['Категории'] .= ',мясо';
    }

    if ( stripos( $dish['Категории'], 'йогурт' ) !== false || stripos( $dish['Категории'], 'кефир' ) !== false || stripos( $dish['Категории'], 'коровье молоко' ) !== false || stripos( $dish['Категории'], 'творог' ) !== false ) {
      $dish['Категории'] .= ',молочные продукты';
    }

    $categories = explode( ',', $dish['Категории'] );

    $categories_array = [];
    foreach ( $categories as $category ) {
      $category_title = my_mb_ucfirst( trim( $category ) );
      $category_object = get_term_by( 'name', $category_title, 'dish_category' );
      if ( !in_array( $category_object->term_id, $categories_array ) ) {
        $categories_array[] = $category_object->term_id; 
      }
      $categories_array[] = $category_object->term_id;
    }

    // echo "<h2 style=\"margin: 15px 0 0\">{$title} <i>({$calories} ккал)</i></h2>";
    // echo "<p>{$dish['Тип']}</p>";
    // echo "<p>{$dish['Цель']}</p>";

    $ingredients_array = [];
    foreach ( $ingredients as $key => $value ) {
      $ingredient_data = explode( '/', $value );
      $ingredient_title = my_mb_ucfirst( trim( $ingredient_data[0] ) );
      $ingredient_count = trim( $ingredient_data[1] );
      $ingredient_units = trim( $ingredient_data[2] );

      if ( $ingredient_title === 'Яйца' || $ingredient_title === 'Яйцо куриное' ) {
        $ingredient_title = 'Яйцо';
      }

      if ( $ingredient_title === 'Горький шокодад 80%' ) {
        $ingredient_title = 'Горький шоколад 80%';
      }

      if ( $ingredient_title === 'Филе куриной грудки' ) {
        $ingredient_title = 'Филе грудки куриной';
      }

      if ( $ingredient_title === 'Форель слабосоленная' ) {
        $ingredient_title = 'Форель слабосоленая';
      }

      // insert_terms( $ingredient_title, 'dish_ingredients' );

      $ingredient_object = get_term_by( 'name', $ingredient_title, 'dish_ingredients' );

      if ( $ingredient_object === false ) {
        // echo "<p>{$ingredient_title}</p>";
      }

      $ingredients_array[] = [
        'title' => $ingredient_object->term_id,
        'number' => $ingredient_count,
        'units' => $ingredient_units
      ];

      $ingredients_ids = $ingredient_object->term_id;

      // echo "<p>{$ingredient_title} $ingredient_count $ingredient_units</p>";
    }

    $types_array = [];
    foreach ( $types as $type ) {
      $type_title = my_mb_ucfirst( trim( $type ) );
      $type_object = get_term_by( 'name', $type_title, 'dish_type' );
      $types_array[] = $type_object->term_id;
    }

    $targets_array = [];
    foreach ( $targets as $target ) {
      $target_title = my_mb_ucfirst( trim( $target ) );
      $target_object = get_term_by( 'name', $target_title, 'dish_target' );
      if ( !in_array( $target_object->term_id, $targets_array ) ) {
        $targets_array[] = $target_object->term_id; 
      }
    }

    // var_dump( $dish['Категории'] );
    // echo "<br>";


    $post_data = [
      'post_title' => $title,
      'post_type' => 'dish',
      'post_content' => '',
      'post_author' => 1,
      'post_status' => 'publish'
    ];


    $post_id = wp_insert_post( $post_data );
    $text = update_field( 'text', $recipe, $post_id );
    $calories = update_field( 'calories', $calories, $post_id );
    $target = update_field( 'target', $targets_array, $post_id );
    $types = update_field( 'type', $types_array, $post_id );
    $ingredients = update_field( 'ingredients', $ingredients_array, $post_id );
    $categories = update_field( 'categories', $categories_array, $post_id );

    wp_set_post_terms( $post_id, $categories_array, 'dish_category' );
    wp_set_post_terms( $post_id, $types_array, 'dish_type' );
    wp_set_post_terms( $post_id, $targets_array, 'dish_target' );
    wp_set_post_terms( $post_id, $ingredients_ids, 'dish_ingredients' );


    if ( $text ) {
      echo "<p>text обновлено</p>";
    } else {
      echo "<p>text НЕ обновлено</p>";
    }

    if ( $calories ) {
      echo "<p>calories обновлено</p>";
    } else {
      echo "<p>calories НЕ обновлено</p>";
    }

    if ( $target ) {
      echo "<p>target обновлено</p>";
    } else {
      echo "<p>target НЕ обновлено</p>";
    }

    if ( $types ) {
      echo "<p>types обновлено</p>";
    } else {
      echo "<p>types НЕ обновлено</p>";
    }

    if ( $ingredients ) {
      echo "<p>ingredients обновлено</p>";
    } else {
      echo "<p>ingredients НЕ обновлено</p>";
    }

    if ( $categories ) {
      echo "<p>categories обновлено</p>";
    } else {
      echo "<p>categories НЕ обновлено</p>";
    }


    // if ( $i === 5 )
    // break;
    $i++;
    if ( $i === $count ) {
      break;
    }
  }
  die();
}

add_action( 'wp_ajax_nopriv_import', 'import' ); 
add_action( 'wp_ajax_import', 'import' );