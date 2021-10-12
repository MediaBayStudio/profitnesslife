<?php
// $questionnaire_complete определяется в functions.php
if ( $questionnaire_complete ) {

  print_account_hero_section( [
    'title' => 'Анкета участника',
    'descr' => 'В течение 24 часов ты можешь скорректировать данные анкеты на этой странице. Если ты захочешь изменить что-то позже — обратись к менеджеру',
    'buttons' => [
      [
        'title' => 'Перейти в чат',
        'class' => 'btn-green'
      ]
    ],
    'img' => [
      'url' => $template_directory_uri . '/img/questionnaire-hero-img.svg',
      'alt' => 'Изображение'
    ]
  ] )

  // $user_data определяется в functions.php ?>

  <section class="questionnaire-complete-sect">
    <h3 class="questionnaire-complete-sect__title">Общие вопросы</h3>
    <ul class="questionnaire-complete-sect__list">
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваша цель:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo mb_strtolower( $user_data['target'] ) ?></span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваш текущий вес:</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['start_weight'] ?> кг</span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Ваш желаемый вес</span>
        <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['target_weight'] ?> кг</span>
      </li>
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
        if ( $user_data['products'] ) {

        } else {
          echo 'нет ограничений';
        } ?></span>
      </li> <?php
      if ( $user_data['sex'] === 'Женский' ) : ?>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Есть ли у вас дети на грудном вскармливании:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo mb_strtolower( $user_data['children'] ) ?></span>
        </li> <?php
      endif ?>
    </ul>
    <h3 class="questionnaire-complete-sect__title">Тренировки</h3>
    <ul class="questionnaire-complete-sect__list">
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Какие физические ограничения у вас есть:</span>
        <span class="questionnaire-complete-sect__li-right"><?php
          if ( $user_data['training_restrictions'] ) {
            echo implode( ', ', $user_data['training_restrictions'] );
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
            echo ', ' . mb_strtolower( implode( ', ', $user_data['inventory'] ) );
          } ?></span>
      </li>
      <li class="questionnaire-complete-sect__li">
        <span class="questionnaire-complete-sect__li-left">Каким проблемным местам вы хотите уделить внимание:</span>
        <span class="questionnaire-complete-sect__li-right"><?php
            echo mb_strtolower( implode( ', ', $user_data['body_parts'] ) ) ?>      
        </span>
      </li>
    </ul>
  </section> <?php
}