<?php
  $today_day = date( 'j', $current_time - $start_marathon_time );
  // $today_day = 2;
  // echo '<p>Время прохождения анкеты: ' . date( 'd.m.Y H:i:s', $questionnaire_dmy_time ) . '</p>';
  // echo '<p>Старт марафона: ' . date( 'd.m.Y H:i:s', $start_marathon_time ) . '</p>';
  // echo '<p>Сейчас: ' . date( 'd.m.Y H:i:s', $current_time ) . '</p>';
  // echo '<p>first_week_end_time: ' . date( 'd.m.Y H:i:s', $first_week_end_time ) . '</p>';
  // echo '<p>second_week_end_time: ' . date( 'd.m.Y H:i:s', $second_week_end_time ) . '</p>';
  // echo '<p>third_week_end_time: ' . date( 'd.m.Y H:i:s', $third_week_end_time ) . '</p>';
 ?>

<section class="diet-plan">
  <header class="diet-plan__hdr">
    <span class="diet-plan__day">День <?php echo $today_day ?></span>
    <div class="diet-plan__hdr-date">
      <span class="diet-plan__date"><span class="diet-plan__today">сегодня, </span><?php echo date( 'd.m.Y' ) ?></span>
      <div class="diet-plan__calendar-btn lazy" data-src="#">
        <div class="diet-plan__calendar"> <?php
          echo Calendar::getInterval( date( 'n.Y', $start_marathon_time ), date( 'n.Y', strtotime( '-1day', $finish_marathon_time ) ), [
            date( 'd.m', $start_marathon_time ) => 'Начало марафона',
            date( 'd.m', strtotime( '-1day', $finish_marathon_time ) ) => 'Окончание марафона'
          ] ) ?>
        </div>
      </div>
    </div>
  </header>
  <ul class="diet-plan__list"> <?php
    $types = [
      'breakfasts',
      'snack_1',
      'lunches',
      'snack_2',
      'dinners'
    ];
    foreach ( $types as $type ) :
      // $item = $user_data['week_' . $current_week_number][ $type ][ $today_day - 1 ];
      $item = $user_data['week_1'][ $type ][ $today_day - 1 ];
      if ( !$item ) {
        continue;
      }
      $item_fields = get_fields( $item->ID );
      $item_type = get_the_terms( $item->ID, 'dish_type' )[0] ?>
      <li class="diet-plan__item">
        <span class="diet-plan__item-type"><?php echo $item_type->name ?></span>
        <div class="diet-plan__item-body">
          <span class="diet-plan__item-title"><?php echo $item->post_title ?></span>
          <span class="diet-plan__item-calories">Калорийность <?php echo $item_fields['calories'] ?> ккал</span>
          <div class="diet-plan__item-descr">
            <div class="diet-plan__item-igredietns">
              <span class="diet-plan__item-igredietns-title">Ингредиенты</span>
              <ul class="diet-plan__item-igredietns-list"> <?php
                foreach ( $item_fields['ingredients'] as $ingredient ) : ?>
                  <li class="diet-plan__item-igredietns-li"> <?php
                    $ingredient_text = $ingredient['title']->name;
                    if ( $ingredient['number'] ) {
                      $ingredient_text .= ' (' . $ingredient['number'] . ' ' . $ingredient['units']['label'] . ')';
                    }
                    echo $ingredient_text ?>
                  </li> <?php
                endforeach ?>
              </ul>
            </div> <?php
            if ( $item_fields['text'] ) : ?>
              <div class="diet-plan__item-recipe">
                <span class="diet-plan__item-recipe-title">Рецепт</span>
                <p class="diet-plan__item-recipe-text"><?php echo $item_fields['text'] ?></p>
              </div> <?php
            endif ?>
          </div>
          <button type="button" class="diet-plan__item-change lazy" data-src="#">Заменить блюдо</button>
        </div>
      </li> <?php
    endforeach ?>
  </ul>
</section>
<?php 
var_dump( $products_cart_popup );
 ?>