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

add_action( 'user_register', function( $user_id ) {
  update_field( 'role', 0, 'user_' . $user_id );
} );

// remove_role( 'completed' );
// remove_role( 'started' );
// remove_role( 'waiting' );

// $roles = [
//   'completed' => 'Завершил марафон',
//   'started' => 'В процессе прохождения',
//   'waiting' => 'Ожидает начала'
// ];

// foreach ( $roles as $key => $value ) {
//   $result = add_role( $key, $value );
//   var_dump( $result );
// }

/*

КОЛОНКИ В manage-posts-columns.php

*/