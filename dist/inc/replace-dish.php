<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function replace_dish() {
  if ( $_POST['action'] === 'replace_dish' ) {
    global $user_id, $user_data;

    // $initial_id = $_POST['initial_id'];

    // die();

    // if ( $initial_id ) {
    //   // если это самая первая замена, то добавим текущий id в массив замен
    //   $user_data[ $_POST['type'] ][] = get_post( $initial_id );
    //   update_field( $_POST['type'], $user_data[ $_POST['type'] ], 'user_' . $user_id );
    // }

    $replacement_item = get_post( $_POST['replacement_id'] );

    $fields = get_fields( $replacement_item->ID );

    $item = [
      'title' => $replacement_item->post_title,
      'id' => $replacement_item->ID,
      'calories' => $fields['calories'],
      'text' => $fields['text']
    ];

    foreach ( $fields['ingredients'] as $ingredient ) {
      $item['ingredients'][] = [
        'title' => $ingredient['title']->name,
        'number' => $ingredient['number'],
        'units' => $ingredient['units']['label']
      ];
    }

    // foreach ( $user_data[ $_POST['type'] ] as $dish ) {
    //   $replacement_ids[] = $dish->ID;
    // }

    // Также нужно заменить поле с блюдом в бд

    $user_data['diet_plan'][ $_POST['today'] - 1 ][ $_POST['dish_type'] ] = $replacement_item;
    update_field( 'diet_plan', $user_data['diet_plan'], 'user_' . $user_id );

    $response = [
      'item' => $item,
      // 'replacement_ids' => $replacement_ids,
      'cart' => recalculate_products_cart()
    ];

    echo json_encode( $response );

    die();
  }
}

add_action( 'wp_ajax_nopriv_replace_dish', 'replace_dish' ); 
add_action( 'wp_ajax_replace_dish', 'replace_dish' );