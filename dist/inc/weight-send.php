<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function weight_send() {
  if ( $_POST['action'] === 'weight_send' ) {
    global $user_id, $user_data;

    $user = 'user_' . $user_id;

    $row = [
      'date' => $_POST['date'],
      'weight' => $_POST['current-weight']
    ];

    $weight_timeline = $user_data['weight_timeline'];
    $weight_timeline[] = $row;

    update_field( 'current_weight', $row['weight'], $user );
    update_field( 'weight_timeline', $weight_timeline, $user );
    // add_row( 'weight_timeline', $row, $user ); // Функция добавялет сразу 2 строки почему-то
    die();
  }
}

add_action( 'wp_ajax_nopriv_weight_send', 'weight_send' ); 
add_action( 'wp_ajax_weight_send', 'weight_send' );