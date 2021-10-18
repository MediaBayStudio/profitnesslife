<?php
function questionnaire_send() {

  if ( $_POST['reset'] ) {
    global $user_id;
    echo update_field( 'questionnaire_complete', false, 'user_' . $user_id );
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
    $bmr = (66 + 13.7 * $current_weight + 5 * $height) - 6.76 * $age;
  } else if ( $sex === 'female' ) {
    $bmr = (655 + 9.6 * $current_weight + 1.8 * $height) - 4.7 * $age;
    if ( $children === 'y' ) {
      $bmr += 400;
    }
  }

  switch ( $activity ) {
    case 'inactive':
      $bmr *= 1.2;
      break;
    case 'medium-active':
      $bmr *= 1.375;
      break;
    case 'high-active':
      $bmr *= 1.55;
      break;
  }

  switch ( $target ) {
    case 'weight-loss':
      $bmr *= 0.8;
      break;
    case 'weight-gain':
      $bmr *= 1.2;
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

  // Категории для исключения
  // исключение круп
  foreach ( $cereals_products as $cereals_product ) {
    if ( $cereals_product === 'all-cereals' ) {
      $exclude_terms[] = 'cereals';
      $exclude_terms_breakfasts[] = 'cereals';
    } else if ( $cereals_product === 'exclude-breakfast' ) {
      $exclude_terms_breakfasts[] = 'cereals';
    }
  }

  // исключения молочных продуктов
  foreach ( $milk_products as $milk_product ) {
    if ( $milk_product === 'all' ) {
      $exclude_terms[] = 'milk-products';
      $exclude_terms_breakfasts[] = 'milk-products';
    } else {
      $exclude_terms[] = $milk_product;
      $exclude_terms_breakfasts[] = $milk_product;
    }
  }

  // исключения мясные продуктов
  foreach ( $meat_products as $meat_product ) {
    if ( $meat_product === 'all' ) {
      $exclude_terms[] = 'meat-products';
      $exclude_terms_breakfasts[] = 'meat-products';
    } else {
      $exclude_terms[] = $meat_product;
      $exclude_terms_breakfasts[] = $meat_product;
    }
  }

  // исключения рыбные продуктов
  foreach ( $fish_products as $fish_product ) {
    if ( $fish_product === 'all' ) {
      $exclude_terms[] = 'fish-products';
      $exclude_terms_breakfasts[] = 'fish-products';
    } else {
      $exclude_terms[] = $fish_product;
      $exclude_terms_breakfasts[] = $fish_product;
    }
  }

  foreach ( $products as $product ) {
    if ( $product !== 'cereals' && $product !== 'fish-products'
    && $product !== 'milk-products' && $product !== 'meat-products' ) {
      $exclude_terms[] = $product;
      $exclude_terms_breakfasts[] = $product;
    }
  }

  $breakfasts = get_posts( [
    'post_type'   => 'dish',
    'numberposts' => 7,
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
        'value' => [$breakfast_max_calories - 100, $breakfast_max_calories],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand',
      ]
    ]
  ] );

  $lunches = get_posts( [
    'post_type'   => 'dish',
    'numberposts' => 7,
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
        'value' => [$lunch_max_calories - 100, $lunch_max_calories],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand',
      ]
    ]
  ] );

  $dinners = get_posts( [
    'post_type'   => 'dish',
    'numberposts' => 7,
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
        'value' => [$dinner_max_calories - 100, $dinner_max_calories],
        'compare' => 'between',
        'type' => 'numeric',
        'orderby' => 'rand',
      ]
    ]
  ] );

  foreach ( $breakfasts as $breakfast ) {
    $breakfasts_ids[] = $breakfast->ID;
  }

  foreach ( $lunches as $lunch ) {
    $lunches_ids[] = $lunch->ID;
  }

  foreach ( $dinners as $dinner ) {
    $dinners_ids[] = $dinner->ID;
  }

  for ( $i = 0; $i < 7; $i++ ) {
    $breakfast_daily_calories = get_field( 'calories', $breakfasts[ $i ] );
    $lunch_daily_calories = get_field( 'calories', $lunches[ $i ] );
    $dinner_daily_calories = get_field( 'calories', $dinners[ $i ] );

    $daily_calories = $breakfast_daily_calories + $lunch_daily_calories + $dinner_daily_calories;
 
    $daily_snack_1 = get_posts( [
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
          'value' => ( $bmr - $daily_calories ) / 2,
          'compare' => '<=',
          'type' => 'numeric',
          'orderby' => 'rand'
        ]
      ]
    ] );

    $daily_snack_2 = get_posts( [
      'post_type'   => 'dish',
      'numberposts' => 1,
      'orderby' => 'rand',
      // 'exclude' => $daily_snack_1[0]->post_ID,
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
          'value' => ( $bmr - $daily_calories ) / 2,
          'compare' => '<=',
          'type' => 'numeric',
          'orderby' => 'rand'
        ]
      ]
    ] );

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

  $response['categories'] = $exclude_terms;
  $response['categories_breakfasts'] = $exclude_terms_breakfasts;

  $response['bmr'] = $bmr;
  $response['breakfast_ccal'] = $breakfast_max_calories;
  $response['lunch_ccal'] = $lunch_max_calories;
  $response['dinner_ccal'] = $dinner_max_calories;

  $carbohydrates = ($bmr * 0.5) / 4;
  $proteins = ($bmr * 0.3) / 4;
  $fats = ($bmr * 0.2) / 9;

  update_field( 'age', $age, $user_id );
  update_field( 'sex', $sex, $user_id );
  update_field( 'start_weight', $current_weight, $user_id );
  update_field( 'target_weight', $target_weight, $user_id );
  update_field( 'questionnaire_complete', true, $user_id );
  update_field( 'questionnaire_time', date( 'd.m.Y H:i:s' ), $user_id );
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

  update_field( 'week_1', [
    'breakfasts' => $breakfasts_ids,
    'snack_1' => $snacks_1_ids,
    'lunches' => $lunches_ids,
    'snack_2' => $snacks_2_ids,
    'dinners' => $dinners_ids
  ], $user_id );

  update_field( 'questionnaire_show', true, 'user_' . $user_id );

  echo json_encode( $response );

  die();
}

add_action( 'wp_ajax_nopriv_questionnaire_send', 'questionnaire_send' ); 
add_action( 'wp_ajax_questionnaire_send', 'questionnaire_send' );