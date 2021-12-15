<?php
function questionnaire_send() {
  if ( $_POST['reset'] ) {

    if ( $_POST['user'] ) {
      $user = new WP_User( $_POST['user'] );
      $user_id = 'user_' . $_POST['user'];
    } else {
      $user = wp_get_current_user();
      $user_id = 'user_' . $user->ID;
    }

    $user_data = get_fields( $user_id );

    if ( $_POST['reset_by_user'] ) {
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

    if ( $user_id !== 'user_1' ) {
      $user->set_role( 'waiting' );
    }

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
    update_field( 'start_marathon_time', '', $user_id );
    update_field( 'finish_marathon_time', '', $user_id );
    update_field( 'start_marathon_date', '', $user_id );
    update_field( 'finish_marathon_date', '', $user_id );
    update_field( 'questionnaire_time', '', $user_id );

    die();
  }

  $user_id = $_POST['user-id'];
  $target = $_POST['target'];
  $sex = $_POST['sex'];
  $current_weight = $_POST['current-weight'];
  $target_weight = $_POST['target-weight'];
  $height = $_POST['height'];
  $age = $_POST['age'];
  // Есть ли дети на грудном вскармливании
  $children = $_POST['children'] ?: 'children-n';
  // Стаж тренировок
  $training_experience = $_POST['training-experience'];
  // Активность
  $activity = $_POST['activity'];
  // Ограничения в тренировках
  $training_restrictions = $_POST['training-restrictions'];
  // Место для занятий (дом/зал)
  $place = $_POST['place'];
  // Инвентарь
  $inventory = $_POST['inventory'];
  // На чем хотите сделать акцент в тренировках
  $body_parts = $_POST['body-parts'];
  $products = $_POST['categories'];
  $cereals_products = $_POST['cereals-products'];
  $milk_products = $_POST['milk-products'];
  $meat_products = $_POST['meat-products'];
  $fish_products = $_POST['fish-products'];

  // Расчет количества необходимых калорий в сутки
  if ( $sex === 'male' ) {
    $bmr = (66.5 + 13.75 * $current_weight + 5.003 * $height) - 6.775 * $age;
  } else if ( $sex === 'female' ) {
    $bmr = (655 + 9.563 * $current_weight + 1.85 * $height) - 4.676 * $age;
    if ( $children === 'y' ) {
      $bmr += 400;
    }
  }

  switch ( $activity ) {
    case 'inactive':
      $bmr *= 1.2;
      break;
    case 'medium-active':
      $bmr *= 1.3;
      break;
    case 'high-active':
      $bmr *= 1.4;
      break;
  }

  switch ( $target ) {
    case 'weight-loss':
      $bmr *= 0.8;
      $proteins = ($bmr * 0.4) / 4;
      $carbohydrates = ($bmr * 0.3) / 4;
      $fats = ($bmr * 0.3) / 9;
      break;
    case 'weight-gain':
      $bmr *= 1.2;
      $proteins = ($bmr * 0.3) / 4;
      $carbohydrates = ($bmr * 0.3) / 4;
      $fats = ($bmr * 0.4) / 9;
      break;
    default:
      $proteins = ($bmr * 0.4) / 4;
      $carbohydrates = ($bmr * 0.2) / 4;
      $fats = ($bmr * 0.4) / 9;
      break;
  }

  // Дневная норма для каждого приема пищи
  $breakfast_norm = 0.25;
  $snack_1_norm = 0.10;
  $luch_norm = 0.35;
  $snack_2_norm = 0.10;
  $dinner_norm = 0.20;

  $breakfast_max_calories = $bmr * $breakfast_norm;
  $lunch_max_calories = $bmr * $luch_norm;
  $dinner_max_calories = $bmr * $dinner_norm;

  $categories = [];
  $exclude_terms = [];
  $exclude_terms_breakfasts = [];

  // Категории для исключения
  // исключение круп
  foreach ( $cereals_products as $cereals_product ) {
    if ( $cereals_product === 'all-cereals' ) {
      $exclude_terms[] = 'cereals';
      $exclude_terms_breakfasts[] = 'cereals';
      $term = get_term_by( 'slug', 'cereals', 'dish_category' );
      $categories[] = $term->term_id;
    } else if ( $cereals_product === 'exclude-breakfast' ) {
      update_field( 'cereals_exclude_breakfast', true, $user_id );
      $exclude_terms_breakfasts[] = 'cereals';
    }
  }

  // $products_array = array_merge( $milk_products, $meat_products, $fish_products, $products );

  foreach ( $milk_products as $product ) {
    $products_array[] = $product;
  }
  foreach ( $meat_products as $product ) {
    $products_array[] = $product;
  }
  foreach ( $fish_products as $product ) {
    $products_array[] = $product;
  }
  foreach ( $products as $product ) {
    $products_array[] = $product;
  }

  foreach ( $products_array as $slug ) {
    $exclude_terms[] = $slug;
    $exclude_terms_breakfasts[] = $slug;
    $term = get_term_by( 'slug', $slug, 'dish_category' );
    $categories[] = $term->term_id;
  }

  $breakfasts = get_posts( [
    'post_type'   => 'dish',
    'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'breakfast',
        'orderby' => 'rand',
      ],
      [
        'operator'  => 'NOT IN',
        'taxonomy'  => 'dish_category',
        'field'     => 'slug',
        'terms'     => $exclude_terms_breakfasts
      ]
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
  ] );

  $lunches = get_posts( [
    'post_type'   => 'dish',
    'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'lunch',
        'orderby' => 'rand'
      ],
      [
        'operator'  => 'NOT IN',
        'taxonomy'  => 'dish_category',
        'field'     => 'slug',
        'terms'     => $exclude_terms
      ]
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
  ] );

  $dinners = get_posts( [
    'post_type'   => 'dish',
    'numberposts' => -1,
    'orderby' => 'rand',
    'tax_query'   => [
      [
        'taxonomy'  => 'dish_type',
        'field'     => 'slug',
        'terms'     => 'dinner',
        'orderby' => 'rand'
      ],
      [
        'operator'  => 'NOT IN',
        'taxonomy'  => 'dish_category',
        'field'     => 'slug',
        'terms'     => $exclude_terms
      ]
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
  ] );

  $initial_breakfasts = $breakfasts;
  $initial_lunches = $lunches;
  $initial_dinners = $dinners;

  if ( count( $breakfasts ) > 21 ) {
    $replacement_breakfasts = array_slice( $breakfasts, 21 );
  }

  if ( count( $lunches ) > 21 ) {
    $replacement_lunches = array_slice( $lunches, 21 );
  }

  if ( count( $dinners ) > 21 ) {
    $replacement_dinners = array_slice( $dinners, 21 );
  }

  $response['data'] = [
    'replacement_breakfasts' => $replacement_breakfasts,
    'replacement_lucnhes' => $replacement_lunches,
    'replacement_dinners' => $replacement_dinners,
    'count_breakfasts' => count( $breakfasts ),
    'count_lucnhes' => count( $lunches ),
    'count_dinners' => count( $dinners )
  ];

  $response['categories'] = $exclude_terms;
  $response['categories_breakfasts'] = $exclude_terms_breakfasts;

  $response['bmr'] = $bmr;
  $response['breakfast_ccal'] = $breakfast_max_calories;
  $response['lunch_ccal'] = $lunch_max_calories;
  $response['dinner_ccal'] = $dinner_max_calories;
  $response['terms'] = $categories;

  // var_dump( $response );

  function fill_array( $array, $max ) {
    $count = count( $array );
    if ( $count < $max ) {
      shuffle( $array );
      for ( $i = 0; $i < $count; $i++ ) {
        if ( $array[ $i ] ) {
          $array[] = $array[ $i ];
        }
      }
      $new_count = count( $array );
      if ( $new_count < $max ) {
        return fill_array( $array, $max );
      } else {
        return array_slice( $array, 0, $max );   
      }
    } else {
      return array_slice( $array, 0, $max );
    }
  }

  if ( count( $breakfasts ) < 21 ) {
    $breakfasts = fill_array( $breakfasts, 21 );  
  }

  if ( count( $lunches ) < 21 ) {
    $lunches = fill_array( $lunches, 21 );
  }

  if ( count( $dinners ) < 21 ) {
    $dinners = fill_array( $dinners, 21 );
  }

  foreach ( $breakfasts as $breakfast ) {
    $breakfasts_ids[] = $breakfast->ID;
  }

  foreach ( $lunches as $lunch ) {
    $lunches_ids[] = $lunch->ID;
  }

  foreach ( $dinners as $dinner ) {
    $dinners_ids[] = $dinner->ID;
  }

  for ( $i = 0; $i < 21; $i++ ) {
    $breakfast_daily_calories = get_field( 'calories', $breakfasts[ $i ] );
    $lunch_daily_calories = get_field( 'calories', $lunches[ $i ] );
    $dinner_daily_calories = get_field( 'calories', $dinners[ $i ] );

    $daily_calories = $breakfast_daily_calories + $lunch_daily_calories + $dinner_daily_calories;

    $snack_calories = ( $bmr - $daily_calories ) / 2;

    $args_snack_1 = [
      'post_type'   => 'dish',
      'numberposts' => 1,
      'orderby' => 'rand',
      'tax_query'   => [
        [
          'taxonomy'  => 'dish_type',
          'field'     => 'slug',
          'terms'     => 'snack_1',
          'orderby' => 'rand'
        ],
        [
          'operator'  => 'NOT IN',
          'taxonomy'  => 'dish_category',
          'field'     => 'slug',
          'terms'     => $exclude_terms
        ]
      ],
      'meta_query' => [
        [
          'key' => 'calories',
          // 'value' => ( $bmr - $daily_calories ) / 2,
          // 'compare' => '<=',
          // 'type' => 'numeric',

          'value' => [$snack_calories - 50, $snack_calories + 50],
          'compare' => 'between',
          'type' => 'numeric',

          'orderby' => 'rand'
        ]
      ]
    ];

    $args_snack_2 = [
      'post_type'   => 'dish',
      'numberposts' => 1,
      'orderby' => 'rand',
      'tax_query'   => [
        'relation' => 'AND',
        [
          'taxonomy'  => 'dish_type',
          'field'     => 'slug',
          'terms'     => 'snack_2',
          'orderby' => 'rand'
        ],
        [
          'operator'  => 'NOT IN',
          'taxonomy'  => 'dish_category',
          'field'     => 'slug',
          'terms'     => $exclude_terms
        ]
      ],
      'meta_query' => [
        [
          'key' => 'calories',
          // 'value' => ( $bmr - $daily_calories ) / 2,
          // 'compare' => '<=',
          // 'type' => 'numeric',

          'value' => [$snack_calories - 50, $snack_calories + 50],
          'compare' => 'between',
          'type' => 'numeric',

          'orderby' => 'rand'
        ]
      ]
    ];
 
    $daily_snack_1 = get_posts( $args_snack_1 );
    $daily_snack_2 = get_posts( $args_snack_2 );

    if ( !$daily_snack_1[0] ) {
      $daily_snack_1 = get_posts( $args_snack_1[
        'meta_query' ] = [
          [
            'key' => 'calories',
            'value' => $snack_calories,
            'compare' => '<=',
            'type' => 'numeric'
          ]
        ] );
    }

    if ( !$daily_snack_2[0] ) {
      $daily_snack_2 = get_posts( $args_snack_2[
        'meta_query' ] = [
          [
            'key' => 'calories',
            'value' => $snack_calories,
            'compare' => '<=',
            'type' => 'numeric'
          ]
        ] );
    }

    $snacks_1_ids[] = $daily_snack_1[0]->ID;
    $snacks_2_ids[] = $daily_snack_2[0]->ID;

    $response['breakfasts'][] = [
      'title' => $breakfasts[ $i ]->post_title,
      'calories' => $breakfast_daily_calories
    ];

    $response['lunches'][] = [
      'title' => $lunches[ $i ]->post_title,
      'calories' => $lunch_daily_calories
    ];

    $response['dinners'][] = [
      'title' => $dinners[ $i ]->post_title,
      'calories' => $dinner_daily_calories
    ];

    $response['snack_1'][] = [
      'title' => $daily_snack_1[0]->post_title,
      'calories' => get_field( 'calories', $daily_snack_1[0] )
    ];

    $response['snack_2'][] = [
      'title' => $daily_snack_2[0]->post_title,
      'calories' => get_field( 'calories', $daily_snack_2[0] )
    ];
  }

  // $snacks_1_ids = fill_array( $snacks_1_ids, 21 );
  // $snacks_2_ids = fill_array( $snacks_2_ids, 21 );

  // $response['snack_1'] = fill_array( $response['snack_1'], 21 );
  // $response['snack_2'] = fill_array( $response['snack_2'], 21 );

  $response['categories'] = $exclude_terms;
  $response['categories_breakfasts'] = $exclude_terms_breakfasts;

  $response['bmr'] = $bmr;
  $response['breakfast_ccal'] = $breakfast_max_calories;
  $response['lunch_ccal'] = $lunch_max_calories;
  $response['dinner_ccal'] = $dinner_max_calories;
  $response['terms'] = $categories;


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
  update_field( 'show_diet_plan', true, $user_id );
  // !!!

  $response['workout'] = $workout;
  $response['data']['cardio_abs_up'] = $cardio_abs_up;
  $response['data']['cardio_abs_down_all'] = $cardio_abs_down_all;
  $response['data']['cardio_abs_down'] = $cardio_abs_down;
  $response['data']['up'] = $up;
  $response['data']['down'] = $down;
  $response['data']['up_down_all_abs'] = $up_down_all_abs;
  $response['data']['inventory_array'] = $inventory_array;
  $response['data']['training_restrictions_array'] = $training_restrictions_array;
  $response['workout_week_1'] = $workout_week_1;
  $response['workout_week_2'] = $workout_week_2;
  $response['workout_week_3'] = $workout_week_3;

  update_field( 'workout_week_1', $workout_week_1, $user_id );
  update_field( 'workout_week_2', $workout_week_2, $user_id );
  update_field( 'workout_week_3', $workout_week_3, $user_id );
  
  update_field( 'replacement_breakfasts', $replacement_breakfasts, $user_id );
  update_field( 'replacement_lunches', $replacement_lunches, $user_id );
  update_field( 'replacement_dinners', $replacement_dinners, $user_id );

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
  update_field( 'categories', $categories, $user_id );


  /* РАСЧЕТЫ ВРЕМЕНИ */
  // Дата прохождения анкеты
  $questionnaire_date =  date( 'd.m.Y H:i:s' );
  $questionnaire_dmy_date = date( 'd.m.Y' );

  // Время прохождения анкеты в мс
  $questionnaire_time =  strtotime( $questionnaire_date );
  $questionnaire_dmy_time =  strtotime( $questionnaire_dmy_date );

  // Время начала марафона в мс (следующий понедельник от даты прохождения анкеты)
  $start_marathon_time = strtotime( 'next monday', $questionnaire_dmy_time );

  // Название дня прохождения анкеты: Sun || Mon || Tue || etc...
  $questionnaire_week_day = date( 'D', $questionnaire_time );

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
    $diet_plan_open_time = strtotime( 'next sunday +10 hours', $questionnaire_time );
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

  update_field( 'first_week_end_time', $first_week_end_time, $user_id );
  update_field( 'second_week_end_time', $second_week_end_time, $user_id );
  update_field( 'third_week_end_time', $third_week_end_time, $user_id );

  update_field( 'first_week_end_date', $first_week_end_date, $user_id );
  update_field( 'second_week_end_date', $second_week_end_date, $user_id );
  update_field( 'third_week_end_date', $third_week_end_date, $user_id );

  update_field( 'diet_plan_open_date', $diet_plan_open_date, $user_id );
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

  update_field( 'diet_plan', $diet_plan, $user_id );
  // update_field( 'show_diet_plan', true, $user_id );

  echo json_encode( $response );

  die();
}

add_action( 'wp_ajax_nopriv_questionnaire_send', 'questionnaire_send' ); 
add_action( 'wp_ajax_questionnaire_send', 'questionnaire_send' );