<?php
$measure_timeline_count = $user_data['measure_timeline'] ? count( $user_data['measure_timeline'] ) : 0 ?>
<section class="measure-sect">
  <h2 class="measure-sect__title">Замеры обьёмов тела</h2>
  <p class="measure-sect__descr">Не забывай еженедельно измерять свои параметры</p>
  <div class="measure-sect__user-data"> <?php
    if ( $measure_timeline_count === 0 ) {
      if ( $user_data['initial_measure_chest'] && $user_data['initial_measure_waist'] && $user_data['initial_measure_hip'] ) {
        $chest = $user_data['initial_measure_chest'];
        $waist = $user_data['initial_measure_waist'];
        $hip = $user_data['initial_measure_hip'];
        $measure_date = $user_data['initial_measure_date'];
      } else {
        $chest = 0;
        $waist = 0;
        $hip = 0;
        $measure_date = '';
      }
    } else {
      $chest = $user_data['measure_timeline'][ $current_week_number - 1 ]['chest'];
      $waist = $user_data['measure_timeline'][ $current_week_number - 1 ]['waist'];
      $hip = $user_data['measure_timeline'][ $current_week_number - 1 ]['hip'];
      $measure_date = $user_data['measure_timeline'][ $current_week_number - 1 ]['date'];
    } ?>
    <div class="measure-sect__user-current-data measure-current">
      <span class="measure-current__title">Текущие параметры</span>
      <span class="measure-current__date" id="measure-date"><?php echo $measure_date ?></span>
      <table class="measure-current__table">
        <tr class="measure-current__table-row">
          <td class="measure-current__table-left">грудь (см)</td>
          <td class="measure-current__table-right" id="measure-chest-value"><?php echo $chest ?></td>
        </tr>
        <tr class="measure-current__table-row">
          <td class="measure-current__table-left">талия (см)</td>
          <td class="measure-current__table-right" id="measure-waist-value"><?php echo $waist ?></td>
        </tr>
        <tr class="measure-current__table-row">
          <td class="measure-current__table-left">бёдра (см)</td>
          <td class="measure-current__table-right" id="measure-hip-value"><?php echo $hip ?></td>
        </tr>
      </table>
    </div> <?php
    if ( $measure_timeline_count !== 0 ) {
      if ( $user_data['measure_timeline'][ $current_week_number - 1] ) {
        $measure_timeline_week_count = $current_week_number;
      }
    } else {
      $measure_timeline_week_count = $current_week_number - 1;
    }

    if ( $user_data['initial_measure_chest'] && $user_data['initial_measure_waist'] && $user_data['initial_measure_hip'] ) {
      $available_measure_time = strtotime( '-3 days', $weeks_end_dates[ $measure_timeline_week_count ] );
    } else {
      $available_measure_time = $start_marathon_time;
    }
    
    if ( $current_time >= $available_measure_time ) {
      $form_class = '';
      $form_descr = '';
    } else {
      $form_class = ' disabled';
      $form_descr = 'Будет доступно с ' . date( 'd.m.Y', $available_measure_time );
    } ?>
    
    <form action="<?php echo $site_url ?>/wp-admin/admin-ajax.php" name="measure-form" class="measure-sect__form measure-form<?php echo $form_class ?>"> <?php
      // $current_week_number объявлена в functions.php
      // за 2-3 дня до конца недели нужно включать доступность полей
      switch ( $current_week_number ) {
        case 2:
          $week_text = 'второй';
          break;
        case 3:
          $week_text = 'третьей';
          break;
        default:
          $week_text = 'первой';
          break;
      } ?>
      <span class="measure-form__title">Мои параметры в конце <?php echo $week_text ?> недели</span>
      <span class="measure-form__descr"><?php echo $form_descr ?></span>
      <div class="measure-form__data"> <?php
        $img_prefix = $user_data['sex'] === 'Мужской' ? 'male' : 'female' ?>
        <img src="<?php echo $template_directory_uri ?>/img/icon-measure-<?php echo $img_prefix ?>.svg" alt="Изображение мест обхватов" class="measure-form__img">
        <table class="measure-form__data-table">
          <tr>
            <td>
              <input type="number" name="chest" placeholder="Обхват груди" class="measure-form__data-input">
            </td>
            <td>см</td>
          </tr>
          <tr>
            <td>
              <input type="number" name="waist" placeholder="Обхват талии" class="measure-form__data-input">
            </td>
            <td>см</td>
          </tr>
          <tr>
            <td>
              <input type="number" name="hip" placeholder="Обхват бёдер" class="measure-form__data-input">
            </td>
            <td>см</td>
          </tr>
        </table>
      </div>
      <button name="submit" class="measure-form__btn btn btn-green disabled">Сохранить</button>
    </form>
    <canvas id="measure-chart" height="260"></canvas>
  </div>
</section>