<?php
// Скрываем лишние роли пользователей
add_filter( 'editable_roles', function( $all_roles ) {
  unset(
    $all_roles['subscriber'],
    $all_roles['contributor'],
    $all_roles['waiting_contributor'],
    $all_roles['need-confirm'],
    $all_roles['banned'],
    $all_roles['author'],
    $all_roles['editor']
  );
  return $all_roles;
} );

// remove_role( 'completed' );
// remove_role( 'started' );
// remove_role( 'waiting' );

// $roles = [
//   'completed' => 'Завершил марафон',
//   'started' => 'В процессе прохождения',
//   'waiting' => 'Ождиает начала'
// ];

// foreach ( $roles as $key => $value ) {
//   $result = add_role( $key, $value );
//   var_dump( $result );
// }

add_filter( 'manage_users_columns', function( $columns ) {
  $num = 1; // после какой по счету колонки вставлять новые

  // var_dump( $columns );

  // return $columns;

  $new_columns = [
    'username' => 'Имя пользователя',
    'name' => 'Имя',
    'email' => 'E-mail',
    'role' => 'Роль',
    'start_date' => 'Дата начала марафона',
    'end_date' => 'Дата завершения марафона',
    'count' => 'Кол-во прохождений марафона'
  ];

  return array_slice( $columns, 0, $num ) + $new_columns;
}, 4 );

// Заполнение колонок нужными данными
add_action( 'manage_users_custom_column', function( $output, $column_name, $user_id ) {
  global $site_url;

  $user_data = get_fields( 'user_' . $user_id );

  // var_dump( $user_data );

  switch ( $column_name ) {
    case 'start_date':
      $output = "<p>{$start_marathon_time}</p>";
      break;
    case 'end_date':
      $output = "<p>{$start_marathon_time}</p>";
      break;
  }

  return $output;
}, 25, 3 );