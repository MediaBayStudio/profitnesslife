<?php
// Получить ингриденеты у рецепта
// Получить их соотношение
// Получить их калорийность
function load_recipe( $args ) {

  $defaults = [
    'post_id' => $_POST['id'],
    'calories' => $_POST['calories'],
    'print' => true
  ];

  $parsed_args = wp_parse_args( $args, $defaults );

  $recipe = get_post( $parsed_args['post_id'] );
  $target_calories = $parsed_args['calories'];

  $recipe_fields = get_fields( $recipe );

  $recipe_template = $recipe_fields['text'];
  $ingredients = $recipe_fields['ingredients'];
  $ingredients_ratio_repeater = $recipe_fields['ingredients_ratio'];

  // Считаем вес блюда
  // Считаем % соотношение ингредиентов в нем
  // И по калорийности пересчитываем вес

  // Высчитываем общий вес блюда
  list( $ingredients_weight, $ingredients_ccal ) = get_dish_props( $ingredients, $ingredients_ratio_repeater );

  $i = 1;
  foreach ( $ingredients as $ingredient ) {
    $ccal_per_100_gramms = get_field( 'calories', $ingredient );

    foreach ( $ingredients_ratio_repeater as $ratio_item ) {
      if ( $ratio_item['ingredient']->post_title === $ingredient->post_title ) {
        list( $ratio, $units ) = get_ingredients_ratio( $ratio_item );
        break;
      }
    }

    $ingredient_ccal_in_recipe = $ratio / 100 * $ccal_per_100_gramms;
    $ingredient_percent = $ingredient_ccal_in_recipe / $ingredients_ccal;
    $ingredient_target_ratio = ($ingredient_percent * $target_calories * 100) / $ccal_per_100_gramms;

    if ( $units === 'grams' || $units === 'ml' ) {
      $ingredient_title = get_field( 'gen_case', $ingredient->ID );
      $ingredient_target_ratio = my_round( $ingredient_target_ratio );
    } else {
      $ingredient_target_ratio = round( $ingredient_target_ratio );
      $ingredient_numbers_case = get_field( 'numbers_case', $ingredient->ID );
      $ingredient_title = num2word( $ingredient_target_ratio, [ $ingredient_numbers_case[1], $ingredient_numbers_case[2], $ingredient_numbers_case[5] ] );
    }


    if ( !$ingredient_title ) {
      $ingredient_title = $ingredient->post_title;
    }

    if ( $units === 'grams' ) {
      $ratio_ending = ' гр.';
    } else if ( $units === 'ml' ) {
      $ratio_ending = ' мл.';
    }

    $recipe_template = str_replace(
      [
        '%ratio-' . $i . '%',
        '%ingredient-' . $i . '%'
      ],
      [
        $ingredient_target_ratio . $ratio_ending,
        mb_strtolower( $ingredient_title )
      ],
      $recipe_template
    );

    $i++;
  }

  if ( $parsed_args['print'] ) {
    echo $recipe_template;
  } else {
    return $recipe_template;
  }


  die();
}

function my_round( $num ) {
  $number = ceil( $num );

  $mod_10 = $number % 10;
  $mod_5 = $number % 5;

  if ($mod_10 < 5) {
      return $number + 5 - $mod_5;
  }

  if ($mod_10 > 5) {
      return $number + 10 - $mod_10;
  }

  return $number;
}

function num2word( $num, $words ) {
  $num = $num % 100;

  if ($num > 19 ) {
    $num = $num % 10;
  }

  switch ( $num ) {
    case 1: {
      return $words[0];
    }
    case 2:
    case 3:
    case 4: {
      return $words[1];
    }
    default: {
      return $words[2];
    }
  }
}

function get_dish_props( $ingredients, $ingredients_ratio_repeater ) {
  $ingredients_weight = 0;
  $ingredients_ccal = 0;

  foreach ( $ingredients as $ingredient ) {
    $ccal_per_100_gramms = get_field( 'calories', $ingredient );

    foreach ( $ingredients_ratio_repeater as $ratio_item ) {
      if ( $ratio_item['ingredient']->post_title === $ingredient->post_title ) {
        list( $ratio, $units ) = get_ingredients_ratio( $ratio_item );
        $ingredient_ccal_in_recipe = $ratio / 100 * $ccal_per_100_gramms;
        $ingredients_weight += (int)$ratio;
        $ingredients_ccal += $ingredient_ccal_in_recipe;
        break;
      }
    }
  }

  return [
    $ingredients_weight,
    $ingredients_ccal
  ];
}

function get_ingredients_ratio( $ratio_item ) {
  $ratio = $ratio_item['ratio'];
  $units = $ratio_item['units'];
  if ( $units === 'tablespoon' ) {
    $ratio *= 15;
  } else if ( $units === 'teaspoon' ) {
    $ratio *= 5;
  }

  return [
    $ratio,
    $units
  ];
}

add_action( 'wp_ajax_nopriv_load_recipe', 'load_recipe' ); 
add_action( 'wp_ajax_load_recipe', 'load_recipe' );


// При изменении рецепта, надо перезаписать и другие рецепты
add_action( 'save_post_recipe', function( $post_ID, $post ) {
  $dishes = get_posts( [
    'post_type' => 'dish',
    'numberposts' => -1,
    'meta_key' => 'recipe'
  ] );

  $output = 'Взяли записи: ';

  foreach ( $dishes as $dish ) {
    $dish_recipe = get_field( 'recipe', $dish->ID );

    if ( $dish_recipe && $dish_recipe->ID === $post_ID ) {
      $new_recipe_template = load_recipe( [
        'post_id' => $dish->ID,
        'calories' => get_field( 'calories', $dish->ID ),
        'print' => false
      ] );
      $updated_field = update_field( 'recipe_text', $new_recipe_template, $dish->ID );
      $output = '
      Совпала запись: ' . $dish->post_title . '
      Новый рецепт: ' . $new_recipe_template . '
      Обновили? ' . $updated_field . '
      ';
    }

  }
}, 10, 3 );