<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function measure_send() {
  if ( $_POST['action'] === 'measure_send' ) {
    global $user_id, $user_data;

    $user = 'user_' . $user_id;

    $initial_date = $user_data['initial_measure_date'];
    $initial_chest = $user_data['initial_measure_chest'];
    $initial_waist = $user_data['initial_measure_waist'];
    $initial_hip = $user_data['initial_measure_hip'];

    if ( $initial_date && $initial_chest && $initial_waist && $initial_hip ) {
      $row = [
        'date' => $_POST['date'],
        'chest' => $_POST['chest'],
        'waist' => $_POST['waist'],
        'hip' => $_POST['hip']
      ];

      $measure_timeline = $user_data['measure_timeline'];
      $measure_timeline[] = $row;

      update_field( 'measure_timeline', $measure_timeline, $user );

      $field = get_field( 'measure_timeline', $user );

      $response['initial_measure'] = false;
      $response['chart_data'] = $field;
    } else {
      update_field( 'initial_measure_date', $_POST['date'], $user );
      update_field( 'initial_measure_chest', $_POST['chest'], $user );
      update_field( 'initial_measure_waist', $_POST['waist'], $user );
      update_field( 'initial_measure_hip', $_POST['hip'], $user );

      $response['initial_measure'] = true;
    }

    echo json_encode( $response );

    die();
  }
}

add_action( 'wp_ajax_nopriv_measure_send', 'measure_send' ); 
add_action( 'wp_ajax_measure_send', 'measure_send' );