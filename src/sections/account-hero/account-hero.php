<?php
if ( $user_data['show_msg'] ) {
  print_account_hero_section( [
    'can_be_hidden' => true,
    'title' => 'Привет, ' . $user->user_firstname . '!',
    'descr' => 'Это твой личный кабинет. Здесь ты можешь следить за своими достижениями — вносить вес и загружать свои фотографии.',
    'img' => [
      'url' => $template_directory_uri . '/img/account-hero-img.svg',
      'alt' => 'Изображение'
    ]
  ] );
} ?>

<section class="user-data">
  <div class="user-data-main">
    <form action="<?php echo $site_url ?>/wp-admin/admin-ajax.php" class="user-data__avatar-form" id="user-avatar-form">
      <input type="file" name="photo" accept="image/jpeg,image/png" class="user-data__avatar-input">
      <picture class="user-data__avatar-pic lazy"> <?php
        if ( $user_data['img'] ) {
          $avatar_url = $user_data['img']['url'];
          $avatar_webp = $upload_baseurl . get_post_meta( $user_data['img']['ID'] )['webp'][0];
        } else {
          $avatar_url = $template_directory_uri . '/img/icon-add-avatar.svg';
          $avatar_webp = '#';
        }
        if ( $avatar_webp !== '#' ) : ?>
          <source type="image/webp" srcset="#" data-srcset="<?php echo $avatar_webp ?>"> <?php
        endif ?>
        <img src="#" alt="Фото профиля" data-src="<?php echo $avatar_url ?>" class="user-data__avatar-img">
      </picture>
    </form>
    <h2 class="user-data__name"><?php echo $user->user_firstname . ' ' . $user->user_lastname ?></h2>
    <ul class="user-data__params">
      <li class="user-data__param"><span class="user-data__param-value"><?php echo $user_data['height'] ?> <span class="user-data__param-units">см</span></span> рост</li>
      <li class="user-data__param"><span class="user-data__param-value"><?php echo $user_data['start_weight'] ?> <span class="user-data__param-units">кг</span></span> начальный вес</li> <?php
      if ( $user_data['target'] !== 'Поддержание веса' ) : ?>
        <li class="user-data__param"><span class="user-data__param-value"><?php echo $user_data['target_weight'] ?> <span class="user-data__param-units">кг</span></span> желаемый вес</li> <?php
      endif ?>
    </ul>
    <ul class="user-data__nutrition-list">
      <li class="user-data__nutrition-li">
        <img src="#" alt="Иконка калорий" data-src="<?php echo $template_directory_uri ?>/img/icon-calories.svg" class="user-nutrition__li-img lazy">
        <span class="user-nutrition__li-title"><?php echo round( $user_data['calories'] ) ?></span>
        <span>ккал</span>
      </li>
      <li class="user-data__nutrition-li">
        <img src="#" alt="Иконка белков" data-src="<?php echo $template_directory_uri ?>/img/icon-proteins.svg" class="user-nutrition__li-img lazy">
        <span class="user-nutrition__li-title"><?php echo round( $user_data['proteins'] ) ?> гр</span>
        <span>белки</span>
      </li>
      <li class="user-data__nutrition-li">
        <img src="#" alt="Иконка жиров" data-src="<?php echo $template_directory_uri ?>/img/icon-fats.svg" class="user-nutrition__li-img lazy">
        <span class="user-nutrition__li-title"><?php echo round( $user_data['fats'] ) ?> гр</span>
        <span>жиры</span>
      </li>
      <li class="user-data__nutrition-li">
        <img src="#" alt="Иконка углеводов" data-src="<?php echo $template_directory_uri ?>/img/icon-carbohydrates.svg" class="user-nutrition__li-img lazy">
        <span class="user-nutrition__li-title"><?php echo round( $user_data['carbohydrates'] ) ?> гр</span>
        <span>углеводы</span>
      </li>
    </ul>
  </div> <?php
  if ( $user_data['target'] !== 'Поддержание веса' ) : ?>
    <div class="user-data__current-weight">
      <span>Текущий вес</span> <?php
      if ( $user_data['weight_timeline'] ) {
        $last_weight = end( $user_data['weight_timeline'] );
        $current_weight = $last_weight['weight'];

        if ( $current_time >= strtotime( $last_weight['date'] ) ) {
          $weight_form_class = '';
          $weight_form_descr = '';
          $weight_form_tabinex = '';
          $weight_form_placeholder = 'Введите значение веса';
        } else {
          $available_weight_time = strtotime( '+1 day', $last_weight['date'] );
          $weight_form_class = ' disabled';
          $weight_form_descr = 'Будет доступно с ' . date( 'd.m.Y', $available_weight_time );
          $weight_form_tabinex = ' tabindex="-1"';
          $weight_form_placeholder = $current_weight;
        }
      } else {
        $weight_form_class = ' disabled';
        $weight_form_descr = 'Будет доступно с ' . date( 'd.m.Y', $start_marathon_time );
        $weight_form_tabinex = ' tabindex="-1"';
        $current_weight = $user_data['current_weight'];
        $available_weight_time = $start_marathon_time;
      } ?>
      <span class="user-data__current-weight-date" id="current-weight-date"><?php echo $last_weight['date'] ?></span>
      <span class="user-data__current-weight-number" id="current-weight-number"><?php echo $current_weight ?> <sapn class="user-data__current-weight-units">кг</sapn></span>
    </div>
    <div class="user-data__weight-goal">
      <span class="user-data__weight-goal-title">До цели</span>
      <div class="user-data__weight-goal-chart-block">
        <svg class="user-data__weight-goal-svg" id="weight-goal-svg" width="100" height="100" viewPort="0 0 50 50" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <circle id="weight-goal-svg-bg" r="40" cx="50" cy="50" fill="transparent" stroke-dasharray="251" stroke-dashoffset="0"></circle>
          <circle id="weight-goal-svg-bar" r="40" cx="50" cy="50" fill="transparent" stroke-dasharray="251" stroke-dashoffset="251" stroke-linecap="round"></circle>
        </svg>
        <div class="user-data__weight-goal-chart">
          <div class="user-data__weight-goal-numbers">
            <span class="user-data__weight-goal-current" id="weight-goal-current"><?php echo abs( $current_weight - $user_data['target_weight'] ) ?> /</span>
            <span class="user-data__weight-goal-total" id="weight-goal-total"><?php echo abs( $user_data['start_weight'] - $user_data['target_weight'] ) ?> кг</span>
          </div>
        </div>
      </div>
    </div>
    <form action="<?php echo $site_url ?>/wp-admin/admin-ajax.php" method="POST" class="user-data__weight-form weight-form<?php echo $weight_form_class ?>" data-start-weight="<?php echo $user_data['start_weight'] ?>" data-target-weight="<?php echo $user_data['target_weight'] ?>">
      <span class="weight-form__title">Вес сегодня</span>
      <span class="weight-form__descr"><?php echo $weight_form_descr ?></span>
      <input type="number" name="current-weight" placeholder="<?php echo $weight_form_placeholder ?>"<?php echo $weight_form_tabinex ?> required class="weight-form__input">
      <button name="submit" class="weight-form__btn btn btn-green disabled">Сохранить</button>
    </form> <?php
  endif ?>
</section>