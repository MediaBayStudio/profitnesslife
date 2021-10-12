<?php
if ( $questionnaire_complete ) {
  $questionnaire_show = get_field( 'questionnaire_show', 'user_' . $user_id );
  $questionnaire_time = get_field( 'questionnaire_time', 'user_' . $user_id );
  $current_time = date( 'd.m.Y H:i:s' );
  // $next_time = strtotime( $questionnaire_time ) + 3 * 3600;
  // $next_time = strtotime( $questionnaire_time ) + 60;

  if ( $questionnaire_show ) {

    $next_time = strtotime( $questionnaire_time ) + 24 * 3600;

    // Прошло 24 часа, убираем кнопку "пройти анкету заново"
    if ( $next_time <= strtotime( $current_time ) ) {

    }

    // update_field( 'body_parts', ['belly', 'hands'], 'user_' . $user_id );

    // var_dump('анкета обработана');

    $user_data = get_fields( 'user_' . $user_id );

    // var_dump( $user_data );

    print_account_hero_section( [
      'title' => 'Анкета участника',
      'descr' => 'В течение 24 часов вы можете скорректировать данные анкеты на этой странице. Если вы захотите изменить что-то позже — обратитесь к менеджеру',
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
    ] ) ?>

    <section class="questionnaire-complete-sect">
      <h3 class="questionnaire-complete-sect__title">Общие вопросы</h3>
      <ul class="questionnaire-complete-sect__list">
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Ваша цель:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['target'] ?></span>
        </li>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">В текущий вес:</span>
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
          <span class="questionnaire-complete-sect__li-left">Ваша цель:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['target'] ?></span>
        </li>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Ваш возраст:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['age'] ?></span>
        </li>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Ваш пол:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['sex'] ?></span>
        </li>
      </ul>
      <h3 class="questionnaire-complete-sect__title">Физическая активность</h3>
      <ul class="questionnaire-complete-sect__list">
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Стаж тренировок:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['training_experience'] ?></span>
        </li>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Ежедневная физическая активность:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['activity'] ?></span>
        </li>
      </ul>
      <h3 class="questionnaire-complete-sect__title">Режим питания</h3>
      <ul class="questionnaire-complete-sect__list">
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Продукты, которые необходимо исключить из вашего меню:</span>
          <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['training_experience'] ?></span>
        </li> <?php
        if ( $user_data['sex'] === 'female' ) : ?>
          <li class="questionnaire-complete-sect__li">
            <span class="questionnaire-complete-sect__li-left">Есть ли у вас дети на грудном вскармливании:</span>
            <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['children'] ?></span>
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
        </li> <?php
        if ( $user_data['sex'] === 'female' ) : ?>
          <li class="questionnaire-complete-sect__li">
            <span class="questionnaire-complete-sect__li-left">Есть ли у вас дети на грудном вскармливании:</span>
            <span class="questionnaire-complete-sect__li-right"><?php echo $user_data['children'] ?></span>
          </li> <?php
        endif ?>
        <li class="questionnaire-complete-sect__li">
          <span class="questionnaire-complete-sect__li-left">Где вы планируете заниматься:</span>
          <span class="questionnaire-complete-sect__li-right"><?php
            echo $user_data['place'];
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
    </section>

    <?php
  } else {
    // Прошло 3 часа, показываем всю анкету
    $next_time = strtotime( $questionnaire_time ) + 3 * 3600;

    if ( $next_time <= strtotime( $current_time ) ) {
      update_field( 'questionnaire_show', true, 'user_' . $user_id );
    }
  }
}