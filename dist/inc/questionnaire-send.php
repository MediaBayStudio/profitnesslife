<?php
// 
function questionnaire_send() {
  $target = $_POST['target'];
  $sex = $_POST['sex'];
  $current_weight = $_POST['current-weight'];
  $target_weight = $_POST['target-weight'];
  $height = $_POST['height'];
  $age = $_POST['age'];
  $children = $_POST['children'];
  $training_experience = $_POST['training-experience'];
  $activity = $_POST['activity'];
  $training_restrictions = $_POST['training-restrictions'];
  $place = $_POST['place'];
  $body_parts = $_POST['body-parts'];
  $products = $_POST['categories'];
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

  // var_dump('breakfast_max_calories');
  // var_dump($breakfast_max_calories);
  // var_dump('lunch_max_calories');
  // var_dump($lunch_max_calories);
  // var_dump('dinner_max_calories');
  // var_dump($dinner_max_calories);

  $breakfasts = get_posts( [
    'post_type'   => 'dish',
    'numberposts' => 5,
    'orderby' => 'rand',
    'tax_query'   => [
    [
      'taxonomy'  => 'dish_type',
      'field'     => 'slug',
      'terms'     => 'breakfast',
      'orderby' => 'rand',
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
    'numberposts' => 5,
    'orderby' => 'rand',
    'tax_query'   => [
    [
      'taxonomy'  => 'dish_type',
      'field'     => 'slug',
      'terms'     => 'lunch',
      'orderby' => 'rand',
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
    'numberposts' => 5,
    'orderby' => 'rand',
    'tax_query'   => [
    [
      'taxonomy'  => 'dish_type',
      'field'     => 'slug',
      'terms'     => 'dinner',
      'orderby' => 'rand',
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

  // $snacks = get_posts( [
  //   'post_type'   => 'dish',
  //   'numberposts' => -1,
  //   'tax_query'   => [
  //   [
  //     'taxonomy'  => 'dish_type',
  //     'field'     => 'slug',
  //     'terms'     => 'snack'
  //     ]
  //   ],
  //   'meta_query' => [
  //     [
  //       'key' => 'calories',
  //       'value' => [$breakfast_max_calories - 100, $breakfast_max_calories],
  //       'compare' => 'between',
  //       'type' => 'numeric'
  //     ]
  //   ]
  // ] );

  // foreach( $breakfasts as $post ) {
    // $calories = get_field( 'calories', $post );
    // var_dump( $post->post_title );
    // var_dump( 'calories ' . $calories );
  // }

  // var_dump('lunches');
  // echo '______________________';
  // var_dump('');

   // foreach( $lunches as $post ) {
    // $calories = get_field( 'calories', $post );
    // var_dump( $post->post_title );
    // var_dump( 'calories ' . $calories );
  // }

  // var_dump('dinners');
  // echo '______________________';
  // var_dump('');

  // foreach( $dinners as $post ) {
    // $calories = get_field( 'calories', $post );
    // var_dump( $post->post_title );
    // var_dump( 'calories ' . $calories );
  // }

  for ( $i = 0; $i < 5; $i++ ) { 
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
          'terms'     => 'snack',
          'orderby' => 'rand'
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
      'exclude' => $daily_snack_1[0]->post_ID,
      'tax_query'   => [
        'relation' => 'AND',
        [
          'taxonomy'  => 'dish_type',
          'field'     => 'slug',
          'terms'     => 'snack',
          'orderby' => 'rand'
        ],
        [
          'taxonomy'  => 'dish_category',
          'field'     => 'slug',
          'terms'     => 'orehi',
          'operator' => 'NOT IN'
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

  $response['bmr'] = $bmr;
  $response['breakfast_ccal'] = $breakfast_max_calories;
  $response['lunch_ccal'] = $lunch_max_calories;
  $response['dinner_ccal'] = $dinner_max_calories;

  echo json_encode( $response );

  // $sum_calories = get_field( 'calories', $breakfasts[0] ) + get_field( 'calories', $lunches[0] )  + get_field( 'calories', $dinners[0] );

  // $snack_max_calories = ( $bmr - $sum_calories ) / 2;

  // $snacks = get_posts( [
  //   'post_type'   => 'dish',
  //   'numberposts' => 2,
  //   'orderby' => 'rand',
  //   'tax_query'   => [
  //   [
  //     'taxonomy'  => 'dish_type',
  //     'field'     => 'slug',
  //     'terms'     => 'snack',
  //     'orderby' => 'rand'
  //     ]
  //   ],
  //   'meta_query' => [
  //     [
  //       'key' => 'calories',
  //       'value' => $snack_max_calories,
  //       'compare' => '<=',
  //       'type' => 'numeric',
  //       'orderby' => 'rand'
  //     ]
  //   ]
  // ] );

  // $response = [
  //   'breakfasts' => $breakfasts,
  //   'lunches' => $lunches,
  //   'dinners' => $dinners,
  //   'snaks' => $snacks
  // ];

  // var_dump($snacks[0]->post_title);
  // var_dump($snacks[0]->post_title);
  // var_dump( get_field( 'calories', $snacks[0] ) );
  // var_dump( get_field( 'calories', $snacks[1] ) );

  // $snack_1 = get_food( $snacks_1, $snack_max_calories );
  // $snack_2 = get_food( $snacks_2, $snack_max_calories );

  // var_dump( $target );
  // var_dump( $products );
  // var_dump( $milk_products );
  // var_dump( $activity );
  // var_dump( $body_parts );

  die();
}

add_action( 'wp_ajax_nopriv_questionnaire_send', 'questionnaire_send' ); 
add_action( 'wp_ajax_questionnaire_send', 'questionnaire_send' );