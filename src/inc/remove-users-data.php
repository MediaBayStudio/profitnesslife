<?php

// function remove_users_data() {
//   $current_time = strtotime( 'now' );

//   $users = get_users( [
//     'number' => -1,
//     'role__in' => ['completed'],
//     'meta_query' => []
//   ] );

//   $_POST['reset'] = 'true';

//   foreach ( $users as $user ) {
//     $user_data = get_fields( 'user_' . $user->ID );

//     $finish_marathon_time = $user_data['finish_marathon_time'];
//     $target_time = strtotime( '+1 days', $finish_marathon_time );

//     if ( $target_time <= $current_time ) {
//       echo "<p>Данные пользователя {$user->ID} надо удалять</p>";
//       // $_POST['user'] = $user->ID;
//       // questionnaire_send()
//     }
//   }

//   // var_dump( $users );
// }

// if ( is_super_admin() ) {
//   remove_users_data();
// }

// Описываем функцию деактивации
// function wplb_deactivate() {
//     // Убираем задачу 'wplb_cron'
//     wp_clear_scheduled_hook( 'wplb_cron' );
// }
// Добавляем основное событие.
