<?php
// $questionnaire_complete объявляется в functions.php
if ( $questionnaire_complete ) {

  print_account_hero_section( [
    'title' => 'Анкета участника',
    'descr' => 'Ты можешь скорректировать данные анкеты на этой странице, для этого необходимо заново ее пройти. Это ты можешь сделать только до начала марафона.',
    'buttons' => [
      [
        'title' => 'Перейти в чат',
        'class' => 'btn-green',
        'href' => $manager_link_whatsapp,
        'target' => '_blank'
      ]
    ],
    'img' => [
      'url' => $template_directory_uri . '/img/questionnaire-hero-img.svg',
      'alt' => 'Изображение'
    ]
  ] )

  // $user_data объявляется в functions.php
  // $start_marathon_time объявляется в functions.php
  // $current_ti,e объявляется в functions.php ?>

  <section class="questionnaire-complete-sect">
    <div class="questionnaire-complete-sect__title-block"> <?php
      if ( $current_time <= $start_marathon_time && !$user_data['reset'] ) : ?>
        <button type="button" class="questionnaire-complete-sect__reset-btn" data-user="<?php echo $user_id ?>" onclick="resetQuestionnaire(true)">Пройти анкету заново</button> <?php
      endif ?>
      <h3 class="questionnaire-complete-sect__title">Общие вопросы</h3>
    </div>
    <ul class="questionnaire-complete-sect__list">
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваша цель:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo mb_strtolower( $user_data['target'] ) ?></span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваш текущий вес:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['start_weight'] ?> кг</span>
      </li> <?php
      if ( $user_data['target_weight'] ) : ?>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Ваш желаемый вес:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['target_weight'] ?> кг</span>
        </li> <?php
      endif ?>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваш рост:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['height'] ?> см</span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваш возраст:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['age'] ?></span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваш пол:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo mb_strtolower( $user_data['sex'] ) ?></span>
      </li>
    </ul>
    <h3 class="questionnaire-complete-sect__title">Физическая активность</h3>
    <ul class="questionnaire-complete-sect__list">
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Стаж тренировок:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo mb_strtolower( $user_data['training_experience'] ) ?></span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ежедневная физическая активность:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo mb_strtolower( $user_data['activity'] ) ?></span>
      </li>
    </ul>
    <h3 class="questionnaire-complete-sect__title">Режим питания</h3>
    <ul class="questionnaire-complete-sect__list">
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Продукты, которые необходимо исключить из вашего меню:</span>
        <span class="questionnaire-complete-sect__li-right"><?php
        if ( $user_data['categories'] ) {
          $terms = get_terms( [
            'taxonomy' => 'dish_category',
            'include' => $user_data['categories']
          ] );

          $milk_products = [];
          $meat_products = [];
          $fish_products = [];

          $terms_counts = [];

          $terms_count = count( $terms );

          foreach ( $terms as $term ) {
            switch ( $term->parent ) {
              // milk-products
              case 223:
                $milk_products[] = $term;
                break;
              // meat-products
              case 391:
                $meat_products[] = $term;
                break;
              // fish-products
              case 230:
                $fish_products[] = $term;
                break;
              default:
                $products[] = $term;
                break;
            }

            switch ( $term->term_id ) {
              case 223: // milk
              case 391: // meat
              case 230: // fish
                $childs = get_term_children( $term->term_id, 'dish_category' );
                $terms_counts[ $term->term_id ] = count( $childs );
                break;
            }
          } // endforeach $terms as $term

          if ( count( $milk_products ) === $terms_counts[223] ) {
            // $categories_text .= 'молочные продукты полностью, ';
          } else {
            foreach ( $milk_products as $term ) {
              $categories_text .= $term->name . ', ';
            }
          }

          if ( count( $meat_products ) === $terms_counts[391] ) {
            // $categories_text .= 'мясо полностью, ';
          } else {
            foreach ( $meat_products as $term ) {
              $categories_text .= $term->name . ', ';
            }
          }

          if ( count( $fish_products ) === $terms_counts[230] ) {
            // $categories_text .= 'рыба полностью, ';
          } else {
            foreach ( $fish_products as $term ) {
              $categories_text .= $term->name . ', ';
            }
          }

          foreach ( $products as $product ) {
            $categories_text .= ( $product->description ?: $product->name ) . ', ';
          }

          if ( $categories_text ) {
            echo mb_strtolower( substr( $categories_text, 0, -2) );
          }
        } else {
          echo 'нет ограничений';
        } ?></span>
      </li> <?php
      if ( $user_data['sex'] === 'Женский' ) : ?>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Есть ли у вас дети на грудном вскармливании:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['children'] === 'n' ? 'нет' : 'есть'  ?></span>
        </li> <?php
      endif ?>
    </ul>
    <h3 class="questionnaire-complete-sect__title">Тренировки</h3>
    <ul class="questionnaire-complete-sect__list">
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Какие физические ограничения у вас есть:</span>
        <span class="questionnaire-complete-sect__li-right"><?php
          if ( $user_data['training_restrictions'] ) {
            foreach ( $user_data['training_restrictions'] as $training_restrictions ) {
              $training_restrictions_text .= $training_restrictions['label'] . ', ';
            }
            if ( $training_restrictions_text ) {
              echo mb_strtolower( substr( $training_restrictions_text, 0, -2) );
            }
          } else {
            echo 'нет';
          }
         ?></span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Где вы планируете заниматься:</span>
        <span class="questionnaire-complete-sect__li-right"><?php
          echo mb_strtolower( $user_data['place'] );
          if ( $user_data['place'] === 'Дома' ) {
            foreach ( $user_data['inventory'] as $inventory ) {
              if ( $inventory['value'] === 'without-inventory' ) {
                continue;
              }
              $inventory_text .= $inventory['label'] . ', ';
            }
            if ( $inventory_text ) {
              echo ', ' . mb_strtolower( substr( $inventory_text, 0, -2) );
            }
          } ?></span>
      </li> <?php
      if ( $user_data['body_parts'] ) : ?>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Каким проблемным местам вы хотите уделить внимание:</span>
          <span class="questionnaire-complete-sect__li-right"><?php
              echo mb_strtolower( implode( ', ', $user_data['body_parts'] ) ) ?>      
          </span>
        </li> <?php
      endif ?>
    </ul>
  </section> <?php
}