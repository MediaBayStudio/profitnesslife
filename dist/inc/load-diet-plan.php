<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
function load_diet_plan() {
  if ( $_POST['action'] === 'load_diet_plan' ) {
    global $user_id, $user_data, $upload_baseurl;

    $user = 'user_' . $user_id;
    $week = 'week_' . $_POST['week'];
    $day_index = $_POST['day_index'];

    $weekly_diet_plan = get_field( $week, $user );

    $breakfast_post = $weekly_diet_plan['breakfasts'][ $day_index ];
    $snack_1_post = $weekly_diet_plan['snack_1'][ $day_index ];
    $lunch_post = $weekly_diet_plan['lunches'][ $day_index ];
    $snack_2_post = $weekly_diet_plan['snack_2'][ $day_index ];
    $dinner_post = $weekly_diet_plan['dinners'][ $day_index ];

    $items = [ $breakfast_post, $snack_1_post, $lunch_post, $snack_2_post, $dinner_post ];

    foreach ( $items as $item ) {
      if ( !$item ) {
        continue;
      }
      $fields = get_fields( $item->ID );
      $type = get_the_terms( $item->ID, 'dish_type' )[0];

      $response .= '
        <li class="diet-plan__item">
          <span class="diet-plan__item-type">' . $type->name . '</span>
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
            <button type="button" class="diet-plan__item-change lazyloaded">Заменить блюдо</button>
          </div>
        </li>';
    }
    echo $response;
    die();
  }
}

add_action( 'wp_ajax_nopriv_load_diet_plan', 'load_diet_plan' ); 
add_action( 'wp_ajax_load_diet_plan', 'load_diet_plan' );