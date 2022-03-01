<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function load_diet_plan() {
  if ( $_POST['action'] === 'load_diet_plan' ) {
    global $user_id, $user_data, $upload_baseurl;

    $user = 'user_' . $user_id;
    $week = $_POST['week'];
    $day_index = $_POST['day_index'];

    $diet_plan = get_field( 'diet_plan', $user );

    // var_dump(($week - 1) * 7 + $day_index);

    // var_dump( $_POST );
    // var_dump( $diet_plan );

    // $breakfast_post = $diet_plan[ ($week - 1) * 7 + $day_index ]['breakfast'];
    // $snack_1_post = $diet_plan[ ($week - 1) * 7 + $day_index ]['snack_1'];
    // $lunch_post = $diet_plan[ ($week - 1) * 7 + $day_index ]['lunch'];
    // $snack_2_post = $diet_plan[ ($week - 1) * 7 + $day_index ]['snack_2'];
    // $dinner_post = $diet_plan[ ($week - 1) * 7 + $day_index ]['dinner'];

    $breakfast_post = $diet_plan[ $day_index ]['breakfast'];
    $snack_1_post = $diet_plan[ $day_index ]['snack_1'];
    $lunch_post = $diet_plan[ $day_index ]['lunch'];
    $snack_2_post = $diet_plan[ $day_index ]['snack_2'];
    $dinner_post = $diet_plan[ $day_index ]['dinner'];

    $types = [
      'breakfast',
      'snack_1',
      'lunch',
      'snack_2',
      'dinner'
    ];

    $items = [ $breakfast_post, $snack_1_post, $lunch_post, $snack_2_post, $dinner_post ];

    $i = 0;
    foreach ( $items as $item ) {
      if ( !$item ) {
        continue;
      }
      $fields = get_fields( $item->ID );
      // $type = get_the_terms( $item->ID, 'dish_type' )[0];

      switch ( $types[ $i ] ) {
        case 'breakfast':
          $type_name = 'Завтрак';
          break;
        case 'snack_1':
          $type_name = 'Перекус 1';
          break;
        case 'lunch':
          $type_name = 'Обед';
          break;
        case 'snack_2':
          $type_name = 'Перекус 2';
          break;
        case 'dinner':
          $type_name = 'Ужин';
          break;
      }

      $dishes = [];

      for ( $j = 0, $len = count( $user_data['diet_plan'] ); $j < $len; $j++ ) {
        $replacement_item_id = $user_data['diet_plan'][ $j ][ $types[ $i ] ]->ID;
        if (
             // $j === $today_day - 1 ||
             !$replacement_item_id ||
             $replacement_item_id == $item->ID ||
             // $types[ $i ] === 'snack_1' ||
             // $types[ $i ] === 'snack_2' ||
             in_array( $replacement_item_id, $dishes )
           ) {
          continue;
        }
        $dishes[] = $replacement_item_id;
      }

      if ( $dishes ) {
        array_unshift( $dishes, $item->ID );
        $replacement_data_attr = ' data-replacement="' .  htmlspecialchars( json_encode( $dishes ) ) . '"';
        // $replacement_selector_attr = ' data-replacement-selector="replacement_' . $replacement_selector . '"';
      } else {
        $replacement_data_attr = '';
        // $replacement_selector_attr = '';
      }

      // if ( $user_data['replacement_' . $replacement_selector] ) {
      //   foreach ( $user_data['replacement_' . $replacement_selector] as $dish ) {
      //     $dishes[] = $dish->ID;
      //   }
      //   $replacement_data_attr = ' data-replacement="' .  htmlspecialchars( json_encode( $dishes ) ) . '"';
      //   $replacement_selector_attr = ' data-replacement-selector="replacement_' . $replacement_selector . '"';
      // } else {
      //   $replacement_data_attr = '';
      //   $replacement_selector_attr = '';
      // };

      $response .= '
        <li class="diet-plan__item" data-id="' . $item->ID . '">
          <span class="diet-plan__item-type">' . $type_name . '</span>
          <div class="diet-plan__item-body">
            <span class="diet-plan__item-title">' . $item->post_title . '</span>
            <span class="diet-plan__item-calories">Калорийность ' . $fields['calories'] . ' ккал</span>
            <div class="diet-plan__item-descr">
              <div class="diet-plan__item-igredietns">
                <span class="diet-plan__item-igredietns-title">Ингредиенты</span>
                <ul class="diet-plan__item-igredietns-list">';

                  foreach ( $fields['ingredients'] as $ingredient ) {
                   $response .= '<li class="diet-plan__item-igredietns-li">';
                      $ingredient_text = $ingredient['title']->name;
                      if ( $ingredient['number'] ) {
                        $ingredient_text .= ' (' . $ingredient['number'] . ' ' . $ingredient['units']['label'] . ')';
                      }
                      $response .= $ingredient_text . '</li>';
                  }
                $response .= '</ul>
              </div>';
              if ( $fields['text'] ) {
                $response .= '<div class="diet-plan__item-recipe">
                  <span class="diet-plan__item-recipe-title">Рецепт</span>
                  <p class="diet-plan__item-recipe-text">' . $fields['text'] . '</p>
                </div>';
              }
            $response .= '</div>
            <div class="loader loader-coral"><div class="loader__circle"></div></div>
            <button type="button" class="diet-plan__item-change"';
            $response .= $replacement_data_attr;
            $response .= '>Заменить блюдо</button>
          </div>
        </li>';
      $i++;
    }
    echo $response;
    die();
  }
}

add_action( 'wp_ajax_nopriv_load_diet_plan', 'load_diet_plan' ); 
add_action( 'wp_ajax_load_diet_plan', 'load_diet_plan' );