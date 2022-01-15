<?php
$waiting_users = get_users( [
  'number' => -1,
  'role' => 'waiting',
  'meta_query' => [
    [
      'key' => 'start_marathon_time',
      'value' => '',
      'compare' => '!='
    ],
    [
      'key' => 'start_marathon_time',
      'value' => $current_time,
      'compare' => '<='
    ]
  ]
] );

foreach ( $waiting_users as $waiting_user ) {
  set_user_role_started( $waiting_user );
}

// Смена ролей пользователям
$completed_users = get_users( [
  'number' => -1,
  'role' => 'started',
  'meta_query' => [
    [
      'key' => 'finish_marathon_time',
      'value' => '',
      'compare' => '!='
    ],
    [
      'key' => 'finish_marathon_time',
      'value' => $current_time,
      'compare' => '<='
    ]
  ]
] );

foreach ( $completed_users as $completed_user ) {
  set_user_role_completed( $completed_user );
}