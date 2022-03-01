<?php
  $today_day = $start_marathon_time ? date( 'j', $current_time - $start_marathon_time ) : 1;
  // $today_day = 2;
  // echo '<p>Время прохождения анкеты: ' . date( 'd.m.Y H:i:s', $questionnaire_dmy_time ) . '</p>';
  // echo '<p>Старт марафона: ' . date( 'd.m.Y H:i:s', $start_marathon_time ) . '</p>';
  // echo '<p>Сейчас: ' . date( 'd.m.Y H:i:s', $current_time ) . '</p>';
  // echo '<p>first_week_end_time: ' . date( 'd.m.Y H:i:s', $first_week_end_time ) . '</p>';
  // echo '<p>second_week_end_time: ' . date( 'd.m.Y H:i:s', $second_week_end_time ) . '</p>';
  // echo '<p>third_week_end_time: ' . date( 'd.m.Y H:i:s', $third_week_end_time ) . '</p>';
  if ( $today_day > 21 ) {
    $today_day = 1;
  }
 ?>

<section class="diet-plan">
  <header class="diet-plan__hdr">
    <span class="diet-plan__day">День <?php echo $today_day ?></span>
    <div class="diet-plan__hdr-date">
      <span class="diet-plan__date"><span class="diet-plan__today">сегодня, </span><?php echo date( 'd.m.Y' ) ?></span>
      <div class="diet-plan__calendar-btn lazy" data-src="#"></div>
      <div class="diet-plan__calendar" data-today="<?php echo $today_day ?>">
        <div class="diet-plan__calendar-nav hide">
          <button type="button" class="diet-plan__calendar-prev"></button>
          <button type="button" class="diet-plan__calendar-next"></button>
        </div> <?php
        if ( $start_marathon_time ) {
          echo Calendar::getInterval(
            date( 'n.Y', $start_marathon_time ),
            date( 'n.Y', strtotime( '-1day', $finish_marathon_time )
          ), [
            date( 'd.m', $start_marathon_time ) => 'Начало марафона',
            date( 'd.m', strtotime( '-1day', $finish_marathon_time ) ) => 'Окончание марафона'
          ] );
        } ?>
      </div>
    </div>
  </header>
  <ul class="diet-plan__list"> <?php
    $types = [
      'breakfast',
      'snack_1',
      'lunch',
      'snack_2',
      'dinner'
    ];

    foreach ( $types as $type ) :
      // $item = $user_data['week_' . $current_week_number][ $type ][ $today_day - 1 ];
      // $item = $user_data['week_1'][ $type ][ $today_day - 1 ];
      $item = $user_data['diet_plan'][ $today_day - 1 ][ $type ];

      if ( !$item ) {
        continue;
      }

      $item_fields = get_fields( $item->ID );
      switch ( $type ) {
        case 'breakfast':
          $item_type = 'Завтрак';
          $replacement_selector = 'breakfasts';
          break;
        case 'snack_1':
          $item_type = 'Перекус 1';
          $replacement_selector = '';
          break;
        case 'lunch':
          $item_type = 'Обед';
          $replacement_selector = 'lunches';
          break;
        case 'snack_2':
          $item_type = 'Перекус 2';
          $replacement_selector = '';
          break;
        case 'dinner':
          $item_type = 'Ужин';
          $replacement_selector = 'dinners';
          break;
      }

      $dishes = [];

      for ( $i = 0, $len = count( $user_data['diet_plan'] ); $i < $len; $i++ ) {
        $replacement_item_id = $user_data['diet_plan'][ $i ][ $type ]->ID;
        if (
             // $i === $today_day - 1 ||
             !$replacement_item_id || 
             $replacement_item_id == $item->ID ||
             // $type === 'snack_1' ||
             // $type === 'snack_2' ||
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

      // $dishes = [];
      // if ( $user_data['replacement_' . $replacement_selector] ) {
      //   foreach ( $user_data['replacement_' . $replacement_selector] as $dish ) {
      //     $dishes[] = $dish->ID;
      //   }
      //   $replacement_data_attr = ' data-replacement="' .  htmlspecialchars( json_encode( $dishes ) ) . '"';
      //   $replacement_selector_attr = ' data-replacement-selector="replacement_' . $replacement_selector . '"';
      // } else {
      //   $replacement_data_attr = '';
      //   $replacement_selector_attr = '';
      // } 
      if ( $item_fields['text'] ) {
        $no_recipe_class = '';
      } else {
        $no_recipe_class = ' no-recipe';
      }
      ?>
      <li class="diet-plan__item<?php echo $no_recipe_class ?>" data-id="<?php echo $item->ID ?>" data-type="<?php echo $type ?>">
        <span class="diet-plan__item-type"><?php echo $item_type ?></span>
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
                      $ingredient_text .= ' (' . $ingredient['number'] . ' ' . ( is_array( $ingredient['units'] ) ? $ingredient['units']['label'] : $ingredient['units'] ) . ')';
                    }
                    echo $ingredient_text ?>
                  </li> <?php
                endforeach ?>
              </ul>
            </div>
            <div class="diet-plan__item-recipe">
              <span class="diet-plan__item-recipe-title">Рецепт</span>
              <p class="diet-plan__item-recipe-text"><?php echo $item_fields['text'] ?></p>
            </div>
          </div>
          <div class="loader loader-coral">
            <div class="loader__circle"></div>
          </div>
          <button type="button" class="diet-plan__item-change lazy" data-src="#"<?php echo $replacement_data_attr . $replacement_selector_attr ?>>Заменить блюдо</button>
        </div>
      </li> <?php
    endforeach ?>
  </ul>
</section>