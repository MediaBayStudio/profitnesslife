<?php
function close_welcome_block() {
  if ( $_POST['action'] === 'close_welcome_block' ) {
    echo update_field( 'show_msg', false, 'user_' . $_POST['user-id'] );
    die();
  }
}

add_action( 'wp_ajax_nopriv_close_welcome_block', 'close_welcome_block' ); 
add_action( 'wp_ajax_close_welcome_block', 'close_welcome_block' );