<?php

// foreach ( $user_data['training_restrictions'] as $r ) {
//   $training_restrictions[] = $r['value'];
// }

// foreach ( $user_data['inventory'] as $i ) {
//   $inventory[] = $i['value'];
// }

// if ( $user_data['place'] === 'Дома' ) {
//   $inventory[] = 'without-inventory';
// }

// $workout = get_posts( [
//   'post_type' => 'workout',
//   'numberposts' => -1,
//   'tax_query' => [
//     [
//       'taxonomy' => 'workout_type',
//       'field' => 'name',
//       'terms' => $user_data['place']
//     ],
//     [
//       'taxonomy' => 'muscle_groups',
//       'field' => 'slug',
//       'terms' => $training_restrictions,
//       'operator' => 'NOT IN'
//     ],
//     [
//       'taxonomy' => 'workout_inventory',
//       'field' => 'slug',
//       'terms' => $inventory
//     ]
//   ],
//   'meta_query' => [
//     [
//       'compare' => '!=',
//       'key' => 'stretching',
//       'value' => true
//     ]
//   ]
// ] );

// /*

// Всего 3 недели по 3 тренировки (3 дня:
// 1 день любой недели: 
// 4 упражнения на верх, 1 упражнение на кардио/пресс/верх

// 2 день любой недели: 
// 4 упражнения на низ, 1 упражнение на кардио/пресс/низ/все тело

// 3 день любой недели: 
// 4 упражнения пресс/верх/низ/все тело (в любом порядке) и 1 кардио/преес/низ

// */

// // Сначала сделаем одну неделю

// $up = []; // верх 1 дня
// $cardio_abs_up = []; // кардио 1 дня

// $down = []; // низ 2 дня
// $cardio_abs_down_all = []; // кардио 2 дня

// $up_down_all_abs = []; // упражнения 3 дня
// $cardio_abs_down = []; // кардио 3 дня

// $all = []; // Запасной вариант, упражнения на все тело

// $i = 0;
// foreach ( $workout as $w ) {
//   $terms = wp_get_post_terms( $w->ID, 'workout_category', $args );

//   foreach ( $terms as $term ) {
//     switch ( $term->name ) {
//       case 'Пресс': 
//         $cardio_abs_up[] = $w->post_title;
//         $cardio_abs_down[] = $w->post_title;
//         $cardio_abs_down_all[] = $w->post_title;
//         $up_down_all_abs[] = $w->post_title;
//         break;
//       case 'Кардио':
//         $cardio_abs_up[] = $w->post_title;
//         $cardio_abs_down[] = $w->post_title;
//         $cardio_abs_down_all[] = $w->post_title;
//         break;
//       case 'Руки':
//         $cardio_abs_up[] = $w->post_title;
//         $up[] = $w->post_title;
//         $up_down_all_abs[] = $w->post_title;
//         break;
//       case 'Ноги':
//         $down[] = $w->post_title;
//         $up_down_all_abs[] = $w->post_title;
//         $cardio_abs_down[] = $w->post_title;
//         $cardio_abs_down_all[] = $w->post_title;
//         break;
//       case 'Все тело':
//         $all[] = $w->post_title;
//         $cardio_abs_down_all[] = $w->post_title;
//         $up_down_all_abs[] = $w->post_title;
//         break;
//     }
//   }

//   $i++;
// }

// $cardio_abs_up = array_flip( $cardio_abs_up );
// $cardio_abs_down_all = array_flip( $cardio_abs_down_all );
// $cardio_abs_down = array_flip( $cardio_abs_down );
// $up = array_flip( $up );
// $down = array_flip( $down );
// $up_down_all_abs = array_flip( $up_down_all_abs );


// function create_workout_on_week () {
//   global
//     $cardio_abs_up,
//     $cardio_abs_down_all,
//     $cardio_abs_down,
//     $up,
//     $down,
//     $up_down_all_abs;

//   // Формируем массив тренировок на неделю (4 случайных упражнения)
//   $week = [
//     'day_1' => array_rand( $up, 4 ),
//     'day_2' => array_rand( $down, 4 ),
//     'day_3' => array_rand( $up_down_all_abs, 4 )
//   ];

//   // Доплняем первую неделю
//   $day_1_count = count( $week['day_1'] );
//   if ( $day_1_count < 4 ) {
//     $week['day_1'] = array_rand( $down, 4 - $day_1_count );
//   }

//   // Убираем повторения в самом последнем упражнении
//   foreach ( $week['day_1'] as $key => $value ) {
//     foreach ( $cardio_abs_up as $c_key => $c_value ) {
//       if ( $c_key === $value ) {
//         unset( $cardio_abs_up[ $c_key ] );
//       }
//     }
//   }
//   foreach ( $week['day_2'] as $key => $value ) {
//     foreach ( $cardio_abs_down_all as $c_key => $c_value ) {
//       if ( $c_key === $value ) {
//         unset( $cardio_abs_down_all[ $c_key ] );
//       }
//     }
//   }
//   foreach ( $week['day_3'] as $key => $value ) {
//     foreach ( $cardio_abs_down as $c_key => $c_value ) {
//       if ( $c_key === $value ) {
//         unset( $cardio_abs_down[ $c_key ] );
//       }
//     }
//   }

//   // Добавляем последнее упражнение
//   $week['day_1'][] = array_rand( $cardio_abs_up );
//   $week['day_2'][] = array_rand( $cardio_abs_down_all );
//   $week['day_3'][] = array_rand( $cardio_abs_down );

//   return $week;
// }

// $workout_week_1 = create_workout_on_week();
// $workout_week_2 = create_workout_on_week();
// $workout_week_3 = create_workout_on_week();

// $i = 1;
// $j = 1;
// foreach ( $workout_week_1 as $w ) {
//   echo '<p>Неделя 1, день: ' . $i . '</p>';
//   foreach ( $w as $d => $v ) {
//     echo '<p>' . $j . '. ' . $v . '</p>';
//     $j++;
//   }
//   $j = 1;
//   $i++;
// }

// echo '<br><br>';

// $i = 1;
// $j = 1;
// foreach ( $workout_week_2 as $w ) {
//   echo '<p>Неделя 2, день: ' . $i . '</p>';
//   foreach ( $w as $d => $v ) {
//     echo '<p>' . $j . '. ' . $v . '</p>';
//     $j++;
//   }
//   $j = 1;
//   $i++;
// }

// echo '<br><br>';

// $i = 1;
// $j = 1;
// foreach ( $workout_week_3 as $w ) {
//   echo '<p>Неделя 3, день: ' . $i . '</p>';
//   foreach ( $w as $d => $v ) {
//     echo '<p>' . $j . '. ' . $v . '</p>';
//     $j++;
//   }
//   $j = 1;
//   $i++;
// }


// $workout_array = csv_to_array( $template_directory . '/workout.csv' );

// function csv_to_array( $filename='', $delimiter=',' ) {
//   if( !file_exists($filename) || !is_readable($filename) )
//       return false;

//   $header = null;
//   $data = [];

//   if ( ($handle = fopen( $filename, 'r' )) !== false ) {
//     while ( ($row = fgetcsv( $handle, 1000, $delimiter )) !== false ) {
//       if ( !$header ) {
//         $header = $row;
//       } else {
//         $data[] = array_combine( $header, $row );
//       }
//     }
//     fclose( $handle );
//   }
//   return $data;
// }

// $i = 0;
// foreach ( $csv as $c ) {
//   // break;
//   $type = explode( '/', mb_strtolower( $c['type'] ) );
//   $inventory = explode( '/', mb_strtolower( $c['inventory'] ) );
//   $category = explode( '/', mb_strtolower( $c['category'] ) );
//   $muscle_groups = explode( '/', mb_strtolower( $c['muscle_groups'] ) );

//   $reps = $c['reps'] ?: 'reps';

//   $reps_group = [
//     'newbie_number' => $c['newbie'],
//     'newbie_what' => $reps,
//     'middle_number' => $c['middle'],
//     'middle_what' => $reps,
//     'expert_number' => $c['expert'],
//     'expert_what' => $reps,
//   ];

//   $post_id = wp_insert_post( [
//     'post_title'    => $c['title'],
//     'post_type' => 'workout',
//     'post_status'   => 'publish',
//     'post_author'   => 1
//   ] );

//   if ( stripos( $c['title'], 'растяжка' ) === false ) {
//     $stretching = false;
//   } else {
//     $stretching = true;
//   }

//   // var_dump( $inventory );

//   var_dump( update_field( 'type', $type, $post_id ) );
//   var_dump( update_field( 'category', $category, $post_id ) );
//   var_dump( update_field( 'workout_inventory', $inventory, $post_id ) );
//   // var_dump( wp_set_post_terms( $post_id, $inventory, 'workout_inventory' ) );
//   var_dump( update_field( 'muscle_groups', $muscle_groups, $post_id ) );
//   var_dump( update_field( 'reps', $reps_group, $post_id ) );
//   var_dump( update_field( 'stretching', $stretching, $post_id ) );

//   // if ( $i === 5 ) {
//   //   break;
//   // }
//   // $i++;
//   // break;
// }

$workout_hero_buttons = [
  [
    'title' => 'Общие правила выполнения',
    'class' => 'btn-ol workout-rules-popup-open'
  ]
];

if ( count( $user_data['inventory'] ) > 1 ) {
  $workout_hero_buttons[] = [
    'title' => 'Инвентарь',
    'class' => 'btn-ol inventory-popup-open'
  ];
}

print_account_hero_section( [
  'title' => $section['title'],
  'descr' => $section['descr'],
  'img' => [
    'url' => $template_directory_uri . '/img/training-hero-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => $workout_hero_buttons
] ) ?>