<?php
// update_field( 'measure_timeline', [], 'user_1' );
// update_field( 'measure_timeline', [
//   [
//     'date' => '02.11.2021',
//     'chest' => 98,
//     'waist' => 69,
//     'hip' => 96
//   ]
// ], 'user_1' );
if ( $user_data['target'] !== 'Поддержание веса' ) :
  $measure_timeline_count = $user_data['measure_timeline'] ? count( $user_data['measure_timeline'] ) : 0;

  if ( $measure_timeline_count > 0 ) {
    $measure_chart_class = '';
  } else {
    $measure_chart_class = ' hide';
  } ?>
  <section class="measure-sect">
    <h2 class="measure-sect__title">Замеры обьёмов тела</h2>
    <p class="measure-sect__descr">Не забывай еженедельно измерять свои параметры</p>
    <div class="measure-sect__user-data"> <?php
      if ( $user_data['initial_measure_chest'] && $user_data['initial_measure_waist'] && $user_data['initial_measure_hip'] ) {
        // за 2-3 дня до конца недели нужно включать доступность полей
        // switch ( $measure_timeline_count ) {
        //   case 1:
        //     $week_text = 'второй';
        //     break;
        //   case 2:
        //     $week_text = 'третьей';
        //     break;
        //   default:
        //     $week_text = 'первой';
        //     break;
        // }
        // $measure_form_title = 'Мои параметры в конце ' . $week_text . ' недели';
        $measure_form_title = 'Мои параметры';
        $measure_current_class = '';
      } else {
        $measure_form_title = 'Мои начальные параметры';
        $measure_current_class = ' no-data';
      }

      if ( $measure_timeline_count === 0 ) {
        // Первое измерение
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
        // Измерения на неделях
        $chest = $user_data['measure_timeline'][ $measure_timeline_count - 1 ]['chest'];
        $waist = $user_data['measure_timeline'][ $measure_timeline_count - 1 ]['waist'];
        $hip = $user_data['measure_timeline'][ $measure_timeline_count - 1 ]['hip'];
        $measure_date = $user_data['measure_timeline'][ $measure_timeline_count - 1 ]['date'];

        foreach ( $user_data['measure_timeline'] as $measure_data ) {
          $measure_chart_data[] = [
            'chest' => $measure_data['chest'],
            'waist' => $measure_data['waist'],
            'hip' => $measure_data['hip'],
            'date' => $measure_data['date']
          ];
        }
      } ?>
      <div class="measure-sect__user-current-data measure-current<?php echo $measure_current_class ?>">
        <span class="measure-current__title">Текущие параметры</span>
        <span class="measure-current__date" id="measure-date"><?php echo $measure_date ?></span>
        <div class="measure-current__no-data-msg">
          <span class="measure-current__no-data-emoji">&#58;&#40;</span>тут пока пусто, сначала введи свои начальные параметры
        </div>
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
      // if ( $measure_timeline_count !== 0 ) {
      //   if ( $user_data['measure_timeline'][ $measure_timeline_count - 1 ] ) {
      //     $measure_timeline_week_count = $current_week_number;
      //   }
      // } else {
      //   $measure_timeline_week_count = $current_week_number - 1;
      // }

      // Определяем дату, когда будет доступно внесение объемов тела
      if ( $user_data['initial_measure_chest'] && $user_data['initial_measure_waist'] && $user_data['initial_measure_hip'] ) {
        if ( $measure_timeline_count === 0 ) {
          if ( strtotime( $user_data['initial_measure_date'] ) <= $curent_time ) {
            var_dump('here-1');
            $available_measure_time = $curent_time - 1000;
          } else {
            $available_measure_time = strtotime( '+1 day', strtotime( $user_data['initial_measure_date'] ) );
          }
        } else {
          $available_measure_time = strtotime( '+1 day', strtotime( $user_data['measure_timeline'][ $measure_timeline_count - 1 ]['date'] ) );
        }
        // $available_measure_time = strtotime( '-3 days', $weeks_end_dates[ $measure_timeline_week_count - 1 ] );
      } else {
        $available_measure_time = $start_marathon_time;
      }
      
      if ( $current_time >= $available_measure_time ) {
        $form_class = '';
        $form_descr = '';
        $tabinex_attr = '';
      } else {
        $form_class = ' disabled';
        $form_descr = 'Будет доступно с ' . date( 'd.m.Y', $available_measure_time );
        $tabinex_attr = ' tabindex="-1"';
        // $form_descr = 'Будет доступно завтра';
      } ?>
      
      <form action="<?php echo $site_url ?>/wp-admin/admin-ajax.php" name="measure-form" class="measure-sect__form measure-form<?php echo $form_class ?>">
        <span class="measure-form__title"><?php echo $measure_form_title ?></span>
        <span class="measure-form__descr"><?php echo $form_descr ?></span>
        <div class="measure-form__data"> <?php
          $img_prefix = $user_data['sex'] === 'Мужской' ? 'male' : 'female' ?>
          <img src="<?php echo $template_directory_uri ?>/img/icon-measure-<?php echo $img_prefix ?>.svg" alt="Изображение мест обхватов" class="measure-form__img">
          <table class="measure-form__data-table">
            <tr>
              <td>
                <input type="number" name="chest" placeholder="Обхват груди" class="measure-form__data-input" <?php echo $tabinex_attr ?>>
              </td>
              <td>см</td>
            </tr>
            <tr>
              <td>
                <input type="number" name="waist" placeholder="Обхват талии" class="measure-form__data-input" <?php echo $tabinex_attr ?>>
              </td>
              <td>см</td>
            </tr>
            <tr>
              <td>
                <input type="number" name="hip" placeholder="Обхват бёдер" class="measure-form__data-input" <?php echo $tabinex_attr ?>>
              </td>
              <td>см</td>
            </tr>
          </table>
        </div>
        <button name="submit" class="measure-form__btn btn btn-green disabled">Сохранить</button>
      </form>
      <canvas class="measure-chart<?php echo $measure_chart_class ?>" id="measure-chart" height="260" data-initial-chest="<?php echo $user_data['initial_measure_chest'] ?>" data-initial-waist="<?php echo $user_data['initial_measure_waist'] ?>" data-initial-hip="<?php echo $user_data['initial_measure_hip'] ?>" data-initial-date="<?php echo $user_data['initial_measure_date'] ?>" data-measure-chart="<?php echo htmlspecialchars( json_encode( $measure_chart_data ) ) ?>"></canvas>
    </div>
  </section> <?php
endif ?>