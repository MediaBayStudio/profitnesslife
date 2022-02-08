<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function auth() {
  if ( $_POST['action'] === 'auth' ) {
    $response = [];

    $user_password = $_POST['user_pass'];
    $user_login = $_POST['user_login'];

    $user_query_field = is_email( $user_login ) ? 'email' : 'login';

    $user = get_user_by( $user_query_field, $user_login );

    $response['error'] = true;

    if ( $user ) {
      $hash = $user->user_pass;

      if ( wp_check_password( $user_password, $hash ) ) {
        $response['error'] = false;
        wp_signon( [
          'user_login' => $user_login,
          'user_password' => $user_password,
          'remember' => true
        ] );
      } else {
        $response['error'] = true;
      }
    } else {
      $response['error'] = true;
    }

    echo json_encode( $response );

    die();
  }
}

add_action( 'wp_ajax_nopriv_auth', 'auth' ); 
add_action( 'wp_ajax_auth', 'auth' );