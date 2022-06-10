<?php
function questionnaire_send( $args ) {

  if ( !$args ) {
    $args = $_POST;
  }

  if ( $args['reset'] ) {

    if ( $args['user'] ) {
      $user = new WP_User( $args['user'] );
      $user_id = 'user_' . $args['user'];
    } else {
      $user = wp_get_current_user();
      $user_id = 'user_' . $user->ID;
    }

    $user_data = get_fields( $user_id );

    if ( $args['reset_by_user'] ) {
      update_field( 'reset', true, $user_id );
    } else {
      update_field( 'reset', false, $user_id );
    }

    update_field( 'questionnaire_complete', false, $user_id );
    update_field( 'show_diet_plan', false, $user_id );
    update_field( 'weight_timeline', [], $user_id );
    update_field( 'measure_timeline', [], $user_id );
    update_field( 'workout_week_1', [], $user_id );
    update_field( 'workout_week_2', [], $user_id );
    update_field( 'workout_week_3', [], $user_id );
    
    update_field( 'replacement_breakfasts', [], $user_id );
    update_field( 'replacement_lunches', [], $user_id );
    update_field( 'replacement_dinners', [], $user_id );

    update_field( 'diet_plan', [], $user_id );

    if ( $user_data['photo_progress'] ) {
      foreach ( $user_data['photo_progress'] as $photo_progress_image ) {
        wp_delete_attachment( $photo_progress_image['ID'], true );
      }
      update_field( 'photo_progress', [], $user_id );
    }

    if ( $user_id !== 'user_1' && $user_id !== 'user_13' ) {
      if ( $args['cron'] ) {
        $user->set_role( 'completed' );
      } else {
        $user->set_role( 'waiting' );
      }
    }

    // Удаление пользователя из телеграм чата
    $chats = get_field( 'telegram_chats', 419 );
    for ( $i = 0, $len = count( $chats ); $i < $len; $i++ ) { 
      if ( in_array( $user->ID, $chats[ $i ]['users'] ) ) {
        $user_key_in_chat = array_search( $user->ID, $chats[ $i ]['users'] );
        array_splice( $chats[ $i ]['users'], $user_key_in_chat, 1 );
      }
    }
    
    update_field( 'telegram_chats', $chats, 419 );

    update_field( 'age', '', $user_id );
    update_field( 'telegram_chat', '', $user_id );
    update_field( 'start_weight', '', $user_id );
    update_field( 'current_weight', '', $user_id );
    update_field( 'target_weight', '', $user_id );
    update_field( 'training_restrictions', [], $user_id );
    update_field( 'inventory', [], $user_id );
    update_field( 'body_parts', [], $user_id );
    update_field( 'height', '', $user_id );
    update_field( 'calories', '', $user_id );
    update_field( 'place', '', $user_id );
    update_field( 'carbohydrates', '', $user_id );
    update_field( 'proteins', '', $user_id );
    update_field( 'fats', '', $user_id );
    update_field( 'categories', [], $user_id );
    update_field( 'first_week_end_time', '', $user_id );
    update_field( 'second_week_end_time', '', $user_id );
    update_field( 'third_week_end_time', '', $user_id );

    update_field( 'first_week_end_date', '', $user_id );
    update_field( 'second_week_end_date', '', $user_id );
    update_field( 'third_week_end_date', '', $user_id );

    update_field( 'diet_plan_open_date', '', $user_id );
    update_field( 'diet_plan_open_time', '', $user_id );
    update_field( 'start_marathon_time', '', $user_id );
    update_field( 'finish_marathon_time', '', $user_id );
    if ( !$agrs['cron'] ) {
      update_field( 'start_marathon_date', '', $user_id );
      update_field( 'finish_marathon_date', '', $user_id );
    }
    update_field( 'questionnaire_time', '', $user_id );

    return 0;
  }


  $is_test = $_POST['method'] === 'test';

  $user_id = $args['user-id'];
  $target = $args['target'];
  $sex = $args['sex'];
  $current_weight = $args['current-weight'];
  $target_weight = $args['target-weight'];
  $height = $args['height'];
  $age = $args['age'];
  // Есть ли дети на грудном вскармливании
  $children = $args['children'] ?: 'n';
  // Стаж тренировок
  $training_experience = $args['training-experience'];
  // Активность
  $activity = $args['activity'];
  // Ограничения в тренировках
  $training_restrictions = $args['training-restrictions'];
  // Место для занятий (дом/зал)
  $place = $args['place'];
  // Инвентарь
  $inventory = $args['inventory'];
  // На чем хотите сделать акцент в тренировках
  $body_parts = $args['body-parts'];
  $products = $args['categories'];
  $milk_products = $args['milk-products'];
  $fish_products = $args['fish-products'];
  $meat_products = $args['meat-products'];
  $bmr_text = '';

  $categories = [];

  foreach ( $products as $product_slug ) {
    if ( $product_slug !== 'molochnye-produkty' && $product_slug !== 'ryba' && $product_slug !== 'myaso' ) {
      $categories[] = $product_slug;
    }
  }

  if ( count( $milk_products ) >= 1 ) {
    foreach ( $milk_products as $milk_product ) {
      if ( $milk_product === 'all' ) {
        $categories[] = 'molochnye-produkty';
      } else {
        $categories[] = $milk_product;
      }
    }
  }
  if ( count( $fish_products ) >= 1 ) {
    foreach ( $fish_products as $fish_product ) {
      if ( $fish_product === 'all' ) {
        $categories[] = 'ryba';
      } else {
        $categories[] = $fish_product;
      }
    }
  }
  if ( count( $meat_products ) >= 1 ) {
    foreach ( $meat_products as $meat_product ) {
      if ( $meat_product === 'all' ) {
        $categories[] = 'myaso';
      } else {
        $categories[] = $meat_product;
      }
    }
  }
  
  if ( $categories ) {
    $terms = get_terms( [
      'taxonomy' => 'dish_category',
      'hide_empty' => false,
      'slug' => $categories
    ] );

    $response['terms'] = $terms;
    $response['terms_slugs'] = $categories;
  }

  $terms_counts = [];
  $categories_ids = [];

  foreach ( $terms as $term ) {
    $categories_ids[] = $term->term_id;
    switch ( $term->term_id ) {
      case 223: // milk
      case 391: // meat
      case 230: // fish
        $childs = get_term_children( $term->term_id, 'dish_category' );
        $terms_counts[ $term->term_id ] = count( $childs );
        break;
    }
  }

  // Расчет количества необходимых калорий в сутки
  if ( $sex === 'male' ) {
    $bmr = (66.5 + 13.75 * $current_weight + 5.003 * $height) - 6.775 * $age;
    $initial_bmr = $bmr;
  } else if ( $sex === 'female' ) {
    $bmr = (655 + 9.563 * $current_weight + 1.85 * $height) - 4.676 * $age;
    $initial_bmr = $bmr;
    if ( $children === 'y' ) {
      $bmr_text .= "<p>Дети на грудном вскармливании: <b>BMR +250 ккал</b></p>";
      $bmr += 250;
    }
  }

  switch ( $target ) {
    case 'weight-loss':
      $bmr *= 0.8;
      $proteins = ($bmr * 0.4) / 4;
      $carbohydrates = ($bmr * 0.3) / 4;
      $fats = ($bmr * 0.3) / 9;
      $target_slug = 'pohudenie';
      $target_rus = 'Потеря веса';
      $bmr_text .= "<p>Влияние цели на BMR: <b>-20%</b></p>";
      switch ( $activity ) {
        case 'inactive':
          $bmr *= 1.2;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+20%</b></p>";
          break;
        case 'medium-active':
          $bmr *= 1.25;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+25%</b></p>";
          break;
        case 'high-active':
          $bmr *= 1.3;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+30%</b></p>";
          break;
      }
      break;
    case 'weight-gain':
      $bmr *= 1.2;
      $proteins = ($bmr * 0.3) / 4;
      $carbohydrates = ($bmr * 0.3) / 4;
      $fats = ($bmr * 0.4) / 9;
      $target_slug = 'nabor';
      $target_rus = 'Набор веса';
      $bmr_text .= "<p>Влияние цели на BMR: <b>+20%</b></p>";
      switch ( $activity ) {
        case 'inactive':
          $bmr *= 1.35;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+35%</b></p>";
          break;
        case 'medium-active':
          $bmr *= 1.4;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+40%</b></p>";
          break;
        case 'high-active':
          $bmr *= 1.5;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+50%</b></p>";
          break;
      }
      break;
    default:
      $proteins = ($bmr * 0.4) / 4;
      $carbohydrates = ($bmr * 0.2) / 4;
      $fats = ($bmr * 0.4) / 9;
      $target_slug = 'podderzhanie';
      $target_rus = 'Поддержание веса';
      $bmr_text .= "<p>Влияние цели на BMR: <b>не влияет</b></p>";
      switch ( $activity ) {
        case 'inactive':
          $bmr *= 1.25;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+25%</b></p>";
          break;
        case 'medium-active':
          $bmr *= 1.3;
          $bmr_text .= "<p>Влияние активности на BMR: <b>+30%</b></p>";
          break;
        case 'high-active':
        $bmr_text .= "<p>Влияние активности на BMR: <b>+35%</b></p>";
          $bmr *= 1.35;
          break;
      }
      break;
  }

  if ( $target === 'weight-gain' ) {
    $breakfast_norm = 0.20;
    $snack_1_norm = 0.10;
    $luch_norm = 0.30;
    $snack_2_norm = 0.20;
    $dinner_norm = 0.20;
  } else {
    $breakfast_norm = 0.25;
    $snack_1_norm = 0.10;
    $luch_norm = 0.35;
    $snack_2_norm = 0.10;
    $dinner_norm = 0.20;
  }

  $breakfast_max_calories = $bmr * $breakfast_norm;
  $lunch_max_calories = $bmr * $luch_norm;
  $dinner_max_calories = $bmr * $dinner_norm;
  $snack_1_max_calories = $bmr * $snack_1_norm;
  $snack_2_max_calories = $bmr * $snack_2_norm;

  if ( $target === 'weight-gain' ) {
    $calories_on_breakfast = "<p>Калорий на завтрак: <b>{$breakfast_max_calories}</b> (20% от BMR)</p>";
    $calories_on_lunch = "<p>Калорий на обед: <b>{$lunch_max_calories}</b> (30% от BMR)</p>";
    $calories_on_dinner = "<p>Калорий на ужин: <b>{$dinner_max_calories}</b> (20% от BMR)</p>";
    $calories_on_snack_1 = "<p>Калорий на перекус 1: <b>{$snack_1_max_calories}</b> (10% от BMR)</p>";
    $calories_on_snack_2 = "<p>Калорий на перекус 2: <b>{$snack_2_max_calories}</b> (20% от BMR)</p>";
  } else {
    $calories_on_breakfast = "<p>Калорий на завтрак: <b>{$breakfast_max_calories}</b> (25% от BMR)</p>";
    $calories_on_lunch = "<p>Калорий на обед: <b>{$lunch_max_calories}</b> (35% от BMR)</p>";
    $calories_on_dinner = "<p>Калорий на ужин: <b>{$dinner_max_calories}</b> (20% от BMR)</p>";
    $calories_on_snack_1 = "<p>Калорий на перекус 1: <b>{$snack_1_max_calories}</b> (10% от BMR)</p>";
    $calories_on_snack_2 = "<p>Калорий на перекус 2: <b>{$snack_2_max_calories}</b> (10% от BMR)</p>";
  }

  $terms = [];
  $exclude_terms = [];

  // foreach ( $products as $product ) {
  //   $products_array[] = $product;
  // }

  // foreach ( $products_array as $slug ) {
  foreach ( $categories as $slug ) {
    $exclude_terms[] = $slug;
    $products_array[] = $slug;
    $term = get_term_by( 'slug', $slug, 'dish_category' );
    $terms[] = $term->term_id;
    $exclude_terms_rus[] = $term->name === 'Крупа' ? 'Каши на завтрак' : $term->name;
  }
  
  if ( $is_test ) {
    echo "<div class=\"container\">";
    echo "<h2>Данные анкеты:</h2>";
    echo "<p>Цель: <b>{$target_rus}</b></p>";
    echo "<p>Пол: <b>{$args['sex']}</b></p>";
    echo "<p>Дети: <b>" . ($args['children'] === 'y' ? 'Есть' : 'Нет')  . "</b></p>";
    echo "<p>Текущий вес: <b>{$args['current-weight']}</b></p>";
    echo "<p>Рост: <b>{$args['height']}</b></p>";
    echo "<p>Возраст: <b>{$args['age']}</b></p>";
    echo "<p>Первоначальный BMR: <b>{$initial_bmr}</b></p>";
    echo $bmr_text;
    echo "<p>BMR: <b>{$bmr}</b></p>";
    echo "<br>";
    echo $calories_on_breakfast;
    echo "<p>Поиск в интервале калорий от <i>" . ($breakfast_max_calories - 50) . "</i> до <i>" . ($breakfast_max_calories + 50) . "</i></p>";
    echo $calories_on_lunch;
    echo "<p>Поиск в интервале калорий от <i>" . ($lunch_max_calories - 50) . "</i> до <i>" . ($lunch_max_calories + 50) . "</i></p>";
    echo $calories_on_dinner;
    echo "<p>Поиск в интервале калорий от <i>" . ($dinner_max_calories - 50) . "</i> до <i>" . ($dinner_max_calories + 50) . "</i></p>";
    echo $calories_on_snack_1;
    echo "<p>Поиск в интервале калорий от <i>" . ($snack_1_max_calories - 100) . "</i> до <i>" . ($snack_1_max_calories + 100) . "</i></p>";
    echo $calories_on_snack_2;
    echo "<p>Поиск в интервале калорий от <i>" . ($snack_2_max_calories - 100) . "</i> до <i>" . ($snack_2_max_calories + 100) . "</i></p>";
    echo "<br>";
    echo "<p>Исключения: <b>" . ($exclude_terms_rus ? implode( ', ', $exclude_terms_rus ) : 'нет') . "</b></p>";
    echo "<br>";
  }

  /*

  ОШИБКИ ПО ИСКЛЮЧЕНИЯМ

  */

  if ( $target === 'weight-gain' ) {
    // Набор веса + грудное вскармливание
    if ( $children === 'y' ) {
      if ( $is_test ) {
        echo '<p><b>ОШИБКА:</b> Набор веса + грудное вскармливание</p>';
      }
      return;
    }

    // Набор веса + исключение всех белков
    if ( in_array( 'molochnye-produkty', $products_array ) && in_array( 'myaso', $products_array ) && in_array( 'ryba', $products_array ) && in_array( 'yajcza', $products_array ) ) {
      if ( $is_test ) {
        echo '<p><b>ОШИБКА:</b> Набор веса + исключение всех белков</p>';
      }
      return;
    }
    $products_slugs = [
      'molochnye-produkty',
      'myaso',
      'ryba'
    ];
    $count = 0;
    foreach ( $products_slugs as $products_slug ) {
      $count += in_array( $products_slug, $products_array );
    }
    // Набор веса + Выбрано 2 из "рыба, мясо, молоко"
    if ( $count >= 2 ) {
      if ( $is_test ) {
        echo "<p><b>ОШИБКА:</b> Набор веса + Выбрано 2 из \"рыба, мясо, молоко\"</p>";
      }
      return;
    }
  }

  // Исключено 5 и более категорий продуктов
  if ( count( $products ) >= 5 ) {
    if ( $is_test ) {
      echo "<p><b>ОШИБКА:</b> Исключено 5 и более категорий продуктов</p>";
    }
    return;
  }

  // Грудное вскармливание + исключено все белковое
  if ( $children === 'y' && in_array( 'ryba', $products_array ) && in_array( 'molochnye-produkty', $products_array ) && in_array( 'myaso', $products_array ) && in_array( 'yajcza', $products_array ) ) {
    if ( $is_test ) {
      echo "<p><b>ОШИБКА:</b> Грудное вскармливание + исключено все белковое</p>";
    }
    return;
  }

  if ( $target === 'weight-loss' || $target === 'weight-maintaining' ) {
    $products_slugs = [
      'molochnye-produkty',
      'myaso',
      'ryba',
      'yajcza'
    ];

    $count = 0;
    foreach ( $products_slugs as $products_slug ) {
      $count += in_array( $products_slug, $products_array );
    }

    if ( $count >= 4 ) {
      $exclude_terms = [
        'molochnye-produkty',
        'jogurt',
        'kefir',
        'korove-moloko',
        'tvorog',
        'moreprodukty',
        'myaso',
        'govyadina-telyatina',
        'indejka',
        'kuricza',
        'ryba',
        'ryba-belaya',
        'ryba-krasnaya',
        'subprodukty',
        'yajcza'
      ];
      $exclude_terms_rus = [
        'Молочные продукты',
        'Йогурт',
        'Кефир',
        'Коровье молоко',
        'Творог',
        'Морепродукты',
        'Мясо',
        'Говядина/телятина',
        'Индейка',
        'Курица',
        'Рыба',
        'Рыба белая',
        'Рыба красная',
        'Субпродукты',
        'Яйца'
      ];
      $vegan = true;
      if ( $is_test ) {
        echo '<p>Исключено много белкового, добавляем исключения: ' . implode( ', ', $exclude_terms_rus ) . '</p>';
      }
    }

    $products_slugs = [
      'myaso',
      'ryba',
      'yajcza'
    ];

    $count = 0;
    foreach ( $products_slugs as $products_slug ) {
      $count += in_array( $products_slug, $products_array );
    }
    if ( $count === 3 ) {
      $vegan = true;
      if ( $is_test ) {
        echo '<p>Исключено много белкового</p>';
      }
    }
  }

  function fill_array( $array ) {
    $array_count = count( $array );

    if ( $array_count < 21 ) {
      $array = array_merge( $array, array_slice( $array, 0, 21 - $array_count ) );
      shuffle( $array );
      $array = fill_array( $array );
    } else {
      return $array;
    }

    return $array;
  }

  if ( !$vegan ) {
    $excluded_ingredients = [];
    if ( $target === 'weight-loss' || $target === 'weight-maintaining' ) {

      if ( in_array( 'ryba', $products_array ) && in_array( 'myaso', $products_array )) {
        if ( $is_test ) {
          echo '<p>Исключены рыба и мясо, в приемах пищи может быть тофу</p>';
        }
        $excluded_ingredients[] = 'soevyj-protein-pure-protein';
      } else if ( in_array( 'yajcza', $products_array ) && in_array( 'myaso', $products_array ) && in_array( 'ryba', $products_array ) ) {
        if ( $is_test ) {
          echo '<p>Исключены рыба, мясо и яйца, в приемах пищи может быть тофу и соевый протеин</p>';
        }
      } else {
        if ( $is_test ) {
          echo '<p>Приемы пищи с соевым протеином и тофу исключены</p>';
        }
        $excluded_ingredients = ['soevyj-protein-pure-protein', 'tofu'];  
      }

    } else {
      if ( $is_test ) {
        echo '<p>Приемы пищи с соевым протеином и тофу исключены</p>';
      }
      $excluded_ingredients = ['soevyj-protein-pure-protein', 'tofu'];
    }

    if ( $excluded_ingredients ) {
      $tax_query_ingredients = [
        'operator' => 'NOT IN',
        'taxonomy' => 'dish_ingredients',
        'field' => 'slug',
        'terms' => $excluded_ingredients
      ];
    }

  } else {
    if ( $is_test ) {
      echo '<p>Исключено много белкового, добавлены приемы пищи с соевым протеином и тофу</p>';
    }
  }

  $breakfasts_args = [
    'post_type'   => 'dish',
    'numberposts' => 21,
    // 'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy' => 'dish_target',
        'field' => 'slug',
        'terms' => $target_slug
      ],
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'breakfast',
        'orderby' => 'rand'
      ],
      [
        'operator'  => 'NOT IN',
        'taxonomy'  => 'dish_category',
        'field'     => 'slug',
        'terms'     => $exclude_terms
      ],
      $tax_query_ingredients
    ],
    'meta_query' => [
      [
        'key' => 'calories',
        'value' => [$breakfast_max_calories - 50, $breakfast_max_calories + 50],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand',
      ]
    ]
  ];

  $lunches_args = [
    'post_type'   => 'dish',
    'numberposts' => 21,
    // 'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy' => 'dish_target',
        'field' => 'slug',
        'terms' => $target_slug
      ],
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'lunch',
        'orderby' => 'rand'
      ],
      $tax_query_ingredients
    ],
    'meta_query' => [
      [
        'key' => 'calories',
        'value' => [$lunch_max_calories - 50, $lunch_max_calories + 50],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand',
      ]
    ]
  ];

  if ( $exclude_terms ) {
    $carelas_index = array_search( 'krupa', $exclude_terms );

    if ( $carelas_index ) {
      $carelas_exclude_terms = array_splice( $exclude_terms, $carelas_index, 1);
    }

    $lunches_args['tax_query'][] = [
      'operator'  => 'NOT IN',
      'taxonomy'  => 'dish_category',
      'field'     => 'slug',
      'terms'     => $exclude_terms
    ];
  }
  
  $breakfasts = get_posts( $breakfasts_args );
  $lunches = get_posts( $lunches_args );

  if ( $breakfasts ) {
    $breakfasts_count = count( $breakfasts );
    $breakfasts = fill_array( $breakfasts );
    if ( $is_test ) {
      echo "<h3>Завтраки" . ( $breakfasts_count < 21 ? ' (уникальных подобралось ' . $breakfasts_count . ' шт, остальные копии)' : ' (копий не было)' ) . "</h3>";
      echo "<table style=\"width:100%\">";
      echo '<tr style="text-align:left"><th>№</th><th>Название</th><th>Ккал</th><th>Цель</th><th>Категории</th><th>Ингредиенты</th><th>Рецепт</th></tr>';
      $i = 1;
      foreach ( $breakfasts as $breakfast ) {
        $ccal = get_field( 'calories', $breakfast->ID );
        $targets = get_field( 'target', $breakfast->ID );
        $categories = get_the_terms( $breakfast->ID, 'dish_category' );
        $ingredients = get_field( 'ingredients', $breakfast->ID );
        $recipe = get_field( 'text', $breakfast->ID );
        $ingredients_str = [];
        $targets_str = [];
        foreach ( $targets as $t ) {
          if ( $t->slug === $target_slug ) {
            $targets_str[] = "<b>{$t->name}</b>";
          } else {
            $targets_str[] = $t->name;
          }
        }
        $categories_str = [];
        foreach ( $categories as $c ) {
          $categories_str[] = $c->name;
        }
        foreach ( $ingredients as $ingredient ) {
          $ingredient_text = $ingredient['title']->name;
          if ( $ingredient['number'] ) {
            $ingredient_text .= ' (' . $ingredient['number'] . ' ' .  $ingredient['units']['label'] . ')';
          }
          $ingredients_str[] = $ingredient_text;
        }
        echo "<tr>";
        echo "<td style=\"font-size:0.75em\">{$i}</td>";
        echo "<td style=\"font-size:0.9em\">{$breakfast->post_title}</td>";
        echo "<td style=\"font-size:0.75em\">{$ccal}</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $targets_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $categories_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $ingredients_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">{$recipe}</td>";
        echo "</tr>";
        $i++;
      }
      echo "</table>";
    }
  } else {
    if ( $is_test ) {
      echo "<p>Не удалось подобрать завтраки</p>";
      echo "<p>Цель: {$target_rus}</p>";
    }
    return;
  }

  if ( $is_test ) {
    echo "<br>";
  }
  
  if ( $lunches ) {
    $lunches_count = count( $lunches );
    $lunches = fill_array( $lunches );
    if ( $is_test ) {
      echo "<h3>Обеды" . ( $lunches_count < 21 ? ' (уникальных подобралось ' . $lunches_count . ' шт, остальные копии)' : ' (копий не было)' ) . "</h3>";
      echo "<table style=\"width:100%\">";
      echo '<tr style="text-align:left"><th>№</th><th>Название</th><th>Ккал</th><th>Цель</th><th>Категории</th><th>Ингредиенты</th><th>Рецепт</th></tr>';
      $i = 1;
      foreach ( $lunches as $lunch ) {
        $ccal = get_field( 'calories', $lunch->ID );
        $targets = get_field( 'target', $lunch->ID );
        $categories = get_the_terms( $lunch->ID, 'dish_category' );
        $ingredients = get_field( 'ingredients', $lunch->ID );
        $recipe = get_field( 'text', $lunch->ID );
        $ingredients_str = [];
        $targets_str = [];
        foreach ( $targets as $t ) {
          if ( $t->slug === $target_slug ) {
            $targets_str[] = "<b>{$t->name}</b>";
          } else {
            $targets_str[] = $t->name;
          }
        }
        $categories_str = [];
        foreach ( $categories as $c ) {
          $categories_str[] = $c->name;
        }
        foreach ( $ingredients as $ingredient ) {
          $ingredient_text = $ingredient['title']->name;
          if ( $ingredient['number'] ) {
            $ingredient_text .= ' (' . $ingredient['number'] . ' ' .  $ingredient['units']['label'] . ')';
          }
          $ingredients_str[] = $ingredient_text;
        }
        echo "<tr>";
        echo "<td style=\"font-size:0.75em\">{$i}</td>";
        echo "<td style=\"font-size:0.9em\">{$lunch->post_title}</td>";
        echo "<td style=\"font-size:0.75em\">{$ccal}</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $targets_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $categories_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $ingredients_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">{$recipe}</td>";
        echo "</tr>";
        $i++;
      }
      echo "</table>";
    }
  } else {
    // Не удалось подобрать обеды
    if ( $is_test ) {
      echo "<p>Не удалось подобрать обеды</p>";
      echo "<p>Цель: {$target_rus}</p>";
    }
    return;
  }
  if ( $is_test ) {
    echo "<br>";
  }

  $dinners_args = [
    'post_type'   => 'dish',
    'numberposts' => 21,
    // 'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy' => 'dish_target',
        'field' => 'slug',
        'terms' => $target_slug
      ],
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'dinner',
        'orderby' => 'rand'
      ],
      $tax_query_ingredients
    ],
    'meta_query' => [
      [
        'key' => 'calories',
        'value' => [$dinner_max_calories - 50, $dinner_max_calories + 50],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand',
      ]
    ]
  ];

  if ( $exclude_terms ) {
    $dinners_args['tax_query'][] = [
      'operator'  => 'NOT IN',
      'taxonomy'  => 'dish_category',
      'field'     => 'slug',
      'terms'     => $exclude_terms
    ];
  }

  if ( $target === 'weight-gain' ) {
    $dinners_args['tax_query'][] = [
      'taxonomy'  => 'dish_category',
      'field'     => 'slug',
      'terms'     => ['krupa']
    ];
    if ( $is_test ) {
      echo '<p>Все ужины только из категории <b>крупа</b></p>';
    }
  }

  $dinners = get_posts( $dinners_args );

  if ( $dinners ) {
    $dinners_count = count( $dinners );
    $dinners = fill_array( $dinners );
    if ( $is_test ) {
      echo "<h3>Ужины" . ( $dinners_count < 21 ? ' (уникальных подобралось ' . $dinners_count . ' шт, остальные копии)' : ' (копий не было)' ) . "</h3>";
      echo "<table style=\"width:100%\">";
      $i = 1;
      echo '<tr style="text-align:left"><th>№</th><th>Название</th><th>Ккал</th><th>Цель</th><th>Категории</th><th>Ингредиенты</th><th>Рецепт</th></tr>';
      foreach ( $dinners as $dinner ) {
        $ccal = get_field( 'calories', $dinner->ID );
        $targets = get_field( 'target', $dinner->ID );
        // $categories = get_field( 'categories', $dinner->ID );
        $categories = get_the_terms( $dinner->ID, 'dish_category' );
        $ingredients = get_field( 'ingredients', $dinner->ID );
        $recipe = get_field( 'text', $dinner->ID );
        $ingredients_str = [];
        $targets_str = [];
        foreach ( $targets as $t ) {
          if ( $t->slug === $target_slug ) {
            $targets_str[] = "<b>{$t->name}</b>";
          } else {
            $targets_str[] = $t->name;
          }
        }
        $categories_str = [];
        foreach ( $categories as $c ) {
          if ( $target === 'weight-gain' && $c->slug === 'krupa' ) {
            $categories_str[] = "<span style=\"color:green\"><b>$c->name</b></span>";
          } else {
            $categories_str[] = $c->name;
          }
        }
        foreach ( $ingredients as $ingredient ) {
          $ingredient_text = $ingredient['title']->name;
          if ( $ingredient['number'] ) {
            $ingredient_text .= ' (' . $ingredient['number'] . ' ' .  $ingredient['units']['label'] . ')';
          }
          $ingredients_str[] = $ingredient_text;
        }
        echo "<tr>";
        echo "<td style=\"font-size:0.75em\">{$i}</td>";
        echo "<td style=\"font-size:0.9em\">{$dinner->post_title}</td>";
        echo "<td style=\"font-size:0.75em\">{$ccal}</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $targets_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $categories_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $ingredients_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">{$recipe}</td>";
        echo "</tr>";
        $i++;
      }
      echo "</table>";
    }
  } else {
    // Не удалось подобрать ужины
    if ( $is_test ) {
      echo "<p>Не удалось подобрать ужины</p>";
      echo "<p>Цель: {$target_rus}</p>";
      echo "<p>Разбег калорий от " . ($dinner_max_calories - 50) . " до " . ($dinner_max_calories + 50) . "</p>";
    }
    return;
  }

  if ( $is_test ) {
    echo "<br>";
  }

  $args_snack_1 = [
    'post_type'   => 'dish',
    'numberposts' => 21,
    // 'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy' => 'dish_target',
        'field' => 'slug',
        'terms' => $target_slug
      ],
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'snack_1',
        'orderby' => 'rand'
      ],
      $tax_query_ingredients
    ],
    'meta_query' => [
      [
        'key' => 'calories',
        'value' => [$snack_1_max_calories - 100, $snack_1_max_calories + 100],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand'
      ]
    ]
  ];

  if ( $exclude_terms ) {
    $args_snack_1['tax_query'][] = [
      'operator'  => 'NOT IN',
      'taxonomy'  => 'dish_category',
      'field'     => 'slug',
      'terms'     => $exclude_terms
    ];
  }

  $snacks_1 = get_posts( $args_snack_1 );

  if ( $snacks_1 ) {
    $snacks_1_count = count( $snacks_1 );
    $snacks_1 = fill_array( $snacks_1 );
    if ( $is_test ) {
      echo "<h3>Перекусы 1" . ( $snacks_1_count < 21 ? ' (уникальных подобралось ' . $snacks_1_count . ' шт, остальные копии)' : ' (копий не было)' ) . "</h3>";
      echo "<table style=\"width:100%\">";
      $i = 1;
      echo '<tr style="text-align:left"><th>№</th><th>Название</th><th>Ккал</th><th>Цель</th><th>Категории</th><th>Ингредиенты</th><th>Рецепт</th></tr>';
      foreach ( $snacks_1 as $snack_1 ) {
        $ccal = get_field( 'calories', $snack_1->ID );
        $targets = get_field( 'target', $snack_1->ID );
        $categories = get_field( 'categories', $snack_1->ID );
        $ingredients = get_field( 'ingredients', $snack_1->ID );
        $recipe = get_field( 'text', $snack_1->ID );
        $ingredients_str = [];
        $targets_str = [];
        foreach ( $targets as $t ) {
          if ( $t->slug === $target_slug ) {
            $targets_str[] = "<b>{$t->name}</b>";
          } else {
            $targets_str[] = $t->name;
          }
        }
        $categories_str = [];
        foreach ( $categories as $c ) {
          if ( $target === 'weight-gain' && $c->slug === 'krupa' ) {
            $categories_str[] = "<span style=\"color:green\"><b>$c->name</b></span>";
          } else {
            $categories_str[] = $c->name;
          }
        }
        foreach ( $ingredients as $ingredient ) {
          $ingredient_text = $ingredient['title']->name;
          if ( $ingredient['number'] ) {
            $ingredient_text .= ' (' . $ingredient['number'] . ' ' .  $ingredient['units']['label'] . ')';
          }
          $ingredients_str[] = $ingredient_text;
        }
        echo "<tr>";
        echo "<td style=\"font-size:0.75em\">{$i}</td>";
        echo "<td style=\"font-size:0.9em\">{$snack_1->post_title}</td>";
        echo "<td style=\"font-size:0.75em\">{$ccal}</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $targets_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $categories_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $ingredients_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">{$recipe}</td>";
        echo "</tr>";
        $i++;
      }
      echo "</table>";
    }
  } else {
    // Не удалось подобрать перекусы 1
    if ( $is_test ) {
      echo "<p>Не удалось подобрать перекусы 1</p>";
      echo "<p>Цель: {$target_rus}</p>";
      echo "<p>Разбег калорий от " . ($dinner_max_calories - 100) . " до " . ($dinner_max_calories + 100) . "</p>";
    }
    return;
  }

  $args_snack_2 = [
    'post_type'   => 'dish',
    'numberposts' => 21,
    // 'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy' => 'dish_target',
        'field' => 'slug',
        'terms' => $target_slug
      ],
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'snack_2',
        'orderby' => 'rand'
      ],
      $tax_query_ingredients
    ],
    'meta_query' => [
      [
        'key' => 'calories',
        'value' => [$snack_2_max_calories - 100, $snack_2_max_calories + 100],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand'
      ]
    ]
  ];

  if ( $exclude_terms ) {
    $args_snack_2['tax_query'][] = [
      'operator'  => 'NOT IN',
      'taxonomy'  => 'dish_category',
      'field'     => 'slug',
      'terms'     => $exclude_terms
    ];
  }

  if ( $target === 'weight-gain' ) {
    $args_snack_2['tax_query'][] = [
      'taxonomy' => 'dish_ingredients',
      'field' => 'slug',
      'terms' => ['gejner-serious-mass']
    ];
    if ( $is_test ) {
      echo '<p>Цель набор веса, добавялем на второй перекус только гейнер</p>';
    }
  }

  $snacks_2 = get_posts( $args_snack_2 );

  if ( $snacks_2 ) {
    $snacks_2_count = count( $snacks_2 );
    $snacks_2 = fill_array( $snacks_2 );
    if ( $is_test ) {
      echo "<h3>Перекусы 2" . ( $snacks_2_count < 21 ? ' (уникальных подобралось ' . $snacks_2_count . ' шт, остальные копии)' : ' (копий не было)' ) . "</h3>";
      echo "<table style=\"width:100%\">";
      $i = 1;
      echo '<tr style="text-align:left"><th>№</th><th>Название</th><th>Ккал</th><th>Цель</th><th>Категории</th><th>Ингредиенты</th><th>Рецепт</th></tr>';
      foreach ( $snacks_2 as $snack_2 ) {
        $ccal = get_field( 'calories', $snack_2->ID );
        $targets = get_field( 'target', $snack_2->ID );
        $categories = get_field( 'categories', $snack_2->ID );
        $ingredients = get_field( 'ingredients', $snack_2->ID );
        $recipe = get_field( 'text', $snack_2->ID );
        $ingredients_str = [];
        $targets_str = [];
        foreach ( $targets as $t ) {
          if ( $t->slug === $target_slug ) {
            $targets_str[] = "<b>{$t->name}</b>";
          } else {
            $targets_str[] = $t->name;
          }
        }
        $categories_str = [];
        foreach ( $categories as $c ) {
          if ( $target === 'weight-gain' && $c->slug === 'krupa' ) {
            $categories_str[] = "<span style=\"color:green\"><b>$c->name</b></span>";
          } else {
            $categories_str[] = $c->name;
          }
        }
        foreach ( $ingredients as $ingredient ) {
          $ingredient_text = $ingredient['title']->name;
          if ( $ingredient['number'] ) {
            $ingredient_text .= ' (' . $ingredient['number'] . ' ' .  $ingredient['units']['label'] . ')';
          }
          $ingredients_str[] = $ingredient_text;
        }
        echo "<tr>";
        echo "<td style=\"font-size:0.75em\">{$i}</td>";
        echo "<td style=\"font-size:0.9em\">{$snack_2->post_title}</td>";
        echo "<td style=\"font-size:0.75em\">{$ccal}</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $targets_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $categories_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">" . implode( ', ', $ingredients_str ) . "</td>";
        echo "<td style=\"font-size:0.75em\">{$recipe}</td>";
        echo "</tr>";
        $i++;
      }
      echo "</table>";
    }
  } else {
    // Не удалось подобрать перекусы 2
    if ( $is_test ) {
      echo "<p>Не удалось подобрать перекусы 2</p>";
      echo "<p>Цель: {$target_rus}</p>";
      echo "<p>Разбег калорий от " . ($dinner_max_calories - 100) . " до " . ($dinner_max_calories + 100) . "</p>";
    }
    return;
  }
  if ( $is_test ) {
    echo "<br>";
    echo "</div>";
  }

  if ( $carelas_index ) {
    $exclude_terms = array_merge( $exclude_terms, $carelas_exclude_terms );
  }

  if ( $is_test ) {
    return;
  }

  // $response['categories'] = $exclude_terms;
  // $response['categories_breakfasts'] = $exclude_terms_breakfasts;

  // $response['bmr'] = $bmr;
  // $response['breakfast_ccal'] = $breakfast_max_calories;
  // $response['lunch_ccal'] = $lunch_max_calories;
  // $response['dinner_ccal'] = $dinner_max_calories;
  // $response['terms'] = $categories;

  // var_dump( $response );

  foreach ( $breakfasts as $breakfast ) {
    $breakfasts_ids[] = $breakfast->ID;
  }

  foreach ( $lunches as $lunch ) {
    $lunches_ids[] = $lunch->ID;
  }

  foreach ( $dinners as $dinner ) {
    $dinners_ids[] = $dinner->ID;
  }

  foreach ( $snacks_1 as $snack_1 ) {
    $snacks_1_ids[] = $snack_1->ID;
  }

  foreach ( $snacks_2 as $snack_2 ) {
    $snacks_2_ids[] = $snack_2->ID;
  }

  

  // $response['categories'] = $exclude_terms;

  // $response['bmr'] = $bmr;
  // $response['breakfast_ccal'] = $breakfast_max_calories;
  // $response['lunch_ccal'] = $lunch_max_calories;
  // $response['dinner_ccal'] = $dinner_max_calories;
  // $response['terms'] = $terms;


  $tax_query = [
    [
      'taxonomy' => 'workout_type',
      'field' => 'slug',
      'terms' => $place
    ]
  ];

  foreach ( $training_restrictions as $r ) {
    $training_restrictions_array[] = $r;
  }

  foreach ( $inventory as $inv ) {
    $inventory_array[] = $inv;
  }

  if ( $place === 'at-home' ) {
    $inventory_array[] = 'without-inventory';
    $inventory[] = 'without-inventory';
  }

  if ( $training_restrictions_array ) {
    $tax_query[] = [
      'taxonomy' => 'muscle_groups',
      'field' => 'slug',
      'terms' => $training_restrictions_array,
      'operator' => 'NOT IN'
    ];
  }

  if ( $inventory_array ) {
    $tax_query[] = [
      'taxonomy' => 'workout_inventory',
      'field' => 'slug',
      'terms' => $inventory_array
    ];
  }

  $workout = get_posts( [
    'post_type' => 'workout',
    'numberposts' => -1,
    'tax_query' => $tax_query,
    'orderby' => 'rand',
    'meta_query' => [
      [
        'compare' => '!=',
        'key' => 'stretching',
        'value' => true
      ]
    ]
  ] );

  /*

  Всего 3 недели по 3 тренировки (3 дня:
  1 день любой недели: 
  4 упражнения на верх, 1 упражнение на кардио/пресс/верх

  2 день любой недели: 
  4 упражнения на низ, 1 упражнение на кардио/пресс/низ/все тело

  3 день любой недели: 
  4 упражнения пресс/верх/низ/все тело (в любом порядке) и 1 кардио/преес/низ

  */

  // Сначала сделаем одну неделю

  $up = []; // верх 1 дня
  $cardio_abs_up = []; // кардио 1 дня

  $down = []; // низ 2 дня
  $cardio_abs_down_all = []; // кардио 2 дня

  $up_down_all_abs = []; // упражнения 3 дня
  $cardio_abs_down = []; // кардио 3 дня

  $all = []; // Запасной вариант, упражнения на все тело

  $i = 0;
  foreach ( $workout as $w ) {
    $terms = wp_get_post_terms( $w->ID, 'workout_category', $args );

    foreach ( $terms as $term ) {
      switch ( $term->name ) {
        case 'Пресс': 
          $cardio_abs_up[] = $w->ID;
          $cardio_abs_down[] = $w->ID;
          $cardio_abs_down_all[] = $w->ID;
          $up_down_all_abs[] = $w->ID;
          break;
        case 'Кардио':
          $cardio_abs_up[] = $w->ID;
          $cardio_abs_down[] = $w->ID;
          $cardio_abs_down_all[] = $w->ID;
          break;
        case 'Руки':
          $cardio_abs_up[] = $w->ID;
          $up[] = $w->ID;
          $up_down_all_abs[] = $w->ID;
          break;
        case 'Ноги':
          $down[] = $w->ID;
          $up_down_all_abs[] = $w->ID;
          $cardio_abs_down[] = $w->ID;
          $cardio_abs_down_all[] = $w->ID;
          break;
        case 'Все тело':
          $all[] = $w->ID;
          $cardio_abs_down_all[] = $w->ID;
          $up_down_all_abs[] = $w->ID;
          break;
      }
    }

    $i++;
  }

  $cardio_abs_up = array_flip( $cardio_abs_up );
  $cardio_abs_down_all = array_flip( $cardio_abs_down_all );
  $cardio_abs_down = array_flip( $cardio_abs_down );
  $up = array_flip( $up );
  $down = array_flip( $down );
  $up_down_all_abs = array_flip( $up_down_all_abs );

  $warmup = get_post( 2149 );

  function create_workout_on_week ( $cardio_abs_up, $cardio_abs_down_all, $cardio_abs_down, $up, $down, $up_down_all_abs, $warmup ) {

    // Формируем массив тренировок на неделю (4 случайных упражнения)
    $week = [
      'day_1' => array_rand( $up, 4 ),
      'day_2' => array_rand( $down, 4 ),
      'day_3' => array_rand( $up_down_all_abs, 4 )
    ];

    array_unshift( $week['day_1'], $warmup );
    array_unshift( $week['day_2'], $warmup );
    array_unshift( $week['day_3'], $warmup );

    // Доплняем первую неделю
    $day_1_count = count( $week['day_1'] );
    if ( $day_1_count < 4 ) {
      $week['day_1'] = array_rand( $down, 4 - $day_1_count );
    }

    // Убираем повторения в самом последнем упражнении
    foreach ( $week['day_1'] as $key => $value ) {
      foreach ( $cardio_abs_up as $c_key => $c_value ) {
        if ( $c_key === $value ) {
          unset( $cardio_abs_up[ $c_key ] );
        }
      }
    }
    foreach ( $week['day_2'] as $key => $value ) {
      foreach ( $cardio_abs_down_all as $c_key => $c_value ) {
        if ( $c_key === $value ) {
          unset( $cardio_abs_down_all[ $c_key ] );
        }
      }
    }
    foreach ( $week['day_3'] as $key => $value ) {
      foreach ( $cardio_abs_down as $c_key => $c_value ) {
        if ( $c_key === $value ) {
          unset( $cardio_abs_down[ $c_key ] );
        }
      }
    }

    // Добавляем последнее упражнение
    $week['day_1'][] = array_rand( $cardio_abs_up );
    $week['day_2'][] = array_rand( $cardio_abs_down_all );
    $week['day_3'][] = array_rand( $cardio_abs_down );

    return $week;
  }

  $workout_week_1 = create_workout_on_week( $cardio_abs_up, $cardio_abs_down_all, $cardio_abs_down, $up, $down, $up_down_all_abs, $warmup );
  $workout_week_2 = create_workout_on_week( $cardio_abs_up, $cardio_abs_down_all, $cardio_abs_down, $up, $down, $up_down_all_abs, $warmup );
  $workout_week_3 = create_workout_on_week( $cardio_abs_up, $cardio_abs_down_all, $cardio_abs_down, $up, $down, $up_down_all_abs, $warmup );


  // !!! Убрать после тестов
  // update_field( 'show_diet_plan', true, $user_id );
  // !!!

  // $response['user_id'] = $user_id;
  // $response['workout'] = $workout;
  // $response['data']['cardio_abs_up'] = $cardio_abs_up;
  // $response['data']['cardio_abs_down_all'] = $cardio_abs_down_all;
  // $response['data']['cardio_abs_down'] = $cardio_abs_down;
  // $response['data']['up'] = $up;
  // $response['data']['down'] = $down;
  // $response['data']['up_down_all_abs'] = $up_down_all_abs;
  // $response['data']['inventory_array'] = $inventory_array;
  // $response['data']['training_restrictions_array'] = $training_restrictions_array;
  // $response['workout_week_1'] = $workout_week_1;
  // $response['workout_week_2'] = $workout_week_2;
  // $response['workout_week_3'] = $workout_week_3;

  // echo "<script data-json=\"" . htmlspecialchars( json_encode( $response ) ) . "\"></script>";
  // echo $user_id;
  // return;

  update_field( 'workout_week_1', $workout_week_1, $user_id );
  update_field( 'workout_week_2', $workout_week_2, $user_id );
  update_field( 'workout_week_3', $workout_week_3, $user_id );
  
  // update_field( 'replacement_breakfasts', $replacement_breakfasts, $user_id );
  // update_field( 'replacement_lunches', $replacement_lunches, $user_id );
  // update_field( 'replacement_dinners', $replacement_dinners, $user_id );

  update_field( 'target', $target, $user_id );
  update_field( 'age', $age, $user_id );
  update_field( 'sex', $sex, $user_id );
  update_field( 'start_weight', $current_weight, $user_id );
  update_field( 'current_weight', $current_weight, $user_id );
  update_field( 'target_weight', $target_weight, $user_id );
  update_field( 'questionnaire_complete', true, $user_id ); // Анкета была пройдена
  update_field( 'training_experience', $training_experience, $user_id );
  update_field( 'training_restrictions', $training_restrictions, $user_id );
  update_field( 'place', $place, $user_id );
  update_field( 'inventory', $inventory, $user_id );
  update_field( 'body_parts', $body_parts, $user_id );
  update_field( 'activity', $activity, $user_id );
  update_field( 'height', $height, $user_id );
  update_field( 'calories', $bmr, $user_id );
  update_field( 'carbohydrates', $carbohydrates, $user_id );
  update_field( 'proteins', $proteins, $user_id );
  update_field( 'fats', $fats, $user_id );
  update_field( 'children', $children, $user_id );
  update_field( 'categories', $categories_ids, $user_id );


  /* РАСЧЕТЫ ВРЕМЕНИ */
  // Дата прохождения анкеты
  $questionnaire_date =  date( 'd.m.Y H:i:s' );
  $questionnaire_dmy_date = date( 'd.m.Y' );

  // Время прохождения анкеты в мс
  $questionnaire_time =  strtotime( $questionnaire_date );
  $questionnaire_dmy_time =  strtotime( $questionnaire_dmy_date );

  // Название дня прохождения анкеты: Sun || Mon || Tue || etc...
  $questionnaire_week_day = date( 'D', $questionnaire_time );

  // Время начала марафона в мс (следующий понедельник от даты прохождения анкеты)
  // или сегодня, если анкета пройдена в понедельник
  if ( $questionnaire_week_day === 'Mon' ) {
    $start_marathon_time = $questionnaire_dmy_time;
  } else {
    $start_marathon_time = strtotime( 'next monday', $questionnaire_dmy_time );
  }
  
  // Время открытия анкеты
  /*
    Если анкета пройдена в воскресенье или понедельник,
    то показывать результат через 3 часа
    Если анкета пройдена в любой другой день,
    то показывать результат в следующее воскресенье в 10 утра
  */
  if ( $questionnaire_week_day === 'Sun' || $questionnaire_week_day === 'Mon' ) {
    $diet_plan_open_time = strtotime( '+3 hours', $questionnaire_time );
  } else {
    $diet_plan_open_time = strtotime( 'next sunday +7 hours', $questionnaire_time );
  }

  $diet_plan_open_date = date( 'd.m.Y H:i:s', $diet_plan_open_time );

  $first_week_end_time = strtotime( '+1 week', $start_marathon_time );
  $second_week_end_time = strtotime( '+2 week', $start_marathon_time );
  $third_week_end_time = strtotime( '+3 week', $start_marathon_time );

  $first_week_end_date = date( 'd.m.Y H:i:s', $first_week_end_time );
  $second_week_end_date = date( 'd.m.Y H:i:s', $second_week_end_time );
  $third_week_end_date = date( 'd.m.Y H:i:s', $third_week_end_time );

  $start_marathon_date = date( 'd.m.Y H:i:s', $start_marathon_time );

  $finish_marathon_time = $third_week_end_time;
  $finish_marathon_date = $third_week_end_date;

  // var_dump( "questionnaire_date: {$questionnaire_date}" );
  // var_dump( "questionnaire_dmy_date: {$questionnaire_dmy_date}" );
  // var_dump( "questionnaire_time: {$questionnaire_time}" );
  // var_dump( "questionnaire_dmy_time: {$questionnaire_dmy_time}" );
  // var_dump( "questionnaire_week_day: {$questionnaire_week_day}" );
  // var_dump( "start_marathon_time: {$start_marathon_time}" );
  // var_dump( "diet_plan_open_time: {$diet_plan_open_time}" );
  // var_dump( "diet_plan_open_date: {$diet_plan_open_date}" );
  // var_dump( "first_week_end_time: {$first_week_end_time}" );
  // var_dump( "second_week_end_time: {$second_week_end_time}" );
  // var_dump( "third_week_end_time: {$third_week_end_time}" );
  // var_dump( "first_week_end_date: {$first_week_end_date}" );
  // var_dump( "second_week_end_date: {$second_week_end_date}" );
  // var_dump( "third_week_end_date: {$third_week_end_date}" );
  // var_dump( "start_marathon_date: {$start_marathon_date}" );
  // var_dump( "finish_marathon_time: {$finish_marathon_time}" );
  // var_dump( "finish_marathon_date: {$finish_marathon_date}" );

  update_field( 'first_week_end_time', $first_week_end_time, $user_id );
  update_field( 'second_week_end_time', $second_week_end_time, $user_id );
  update_field( 'third_week_end_time', $third_week_end_time, $user_id );

  update_field( 'first_week_end_date', $first_week_end_date, $user_id );
  update_field( 'second_week_end_date', $second_week_end_date, $user_id );
  update_field( 'third_week_end_date', $third_week_end_date, $user_id );

  update_field( 'diet_plan_open_date', $diet_plan_open_date, $user_id );
  update_field( 'diet_plan_open_time', $diet_plan_open_time, $user_id );
  update_field( 'start_marathon_time', $start_marathon_time, $user_id );
  update_field( 'finish_marathon_time', $finish_marathon_time, $user_id );
  update_field( 'start_marathon_date', $start_marathon_date, $user_id );
  update_field( 'finish_marathon_date', $finish_marathon_date, $user_id );
  update_field( 'questionnaire_time', $questionnaire_date, $user_id );


  for ( $i = 0, $len = count( $breakfasts_ids ); $i < $len; $i++ ) {
    $diet_plan[] = [
      'breakfast' => $breakfasts_ids[ $i ],
      'snack_1' => $snacks_1_ids[ $i ],
      'lunch' => $lunches_ids[ $i ],
      'snack_2' => $snacks_2_ids[ $i ],
      'dinner' => $dinners_ids[ $i ]
    ];
  }

  $response['diet_plan'] = $diet_plan;
  $response['sended_products'] = $categories;
  $response['categories_ids'] = $categories_ids;

  if ( $milk_products ) {
    $response['milk_products'] = $milk_products;
  }
  if ( $fish_products ) {
    $response['fish_products'] = $fish_products;
  }
  if ( $meat_products ) {
    $response['meat_products'] = $meat_products;
  }

  // wp_set_object_terms( $user_id, $categories_ids, 'dish_category' );

  update_field( 'diet_plan', $diet_plan, $user_id );
  // update_field( 'show_diet_plan', true, $user_id );

  // echo "<script data-json=\"" . htmlspecialchars( json_encode( $response ) ) . "\"></script>";

  echo json_encode( $response );
  // return;


  die();
}

add_action( 'wp_ajax_nopriv_questionnaire_send', 'questionnaire_send' ); 
add_action( 'wp_ajax_questionnaire_send', 'questionnaire_send' );