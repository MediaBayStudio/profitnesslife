<?php
$weight_timeline_count = count( $user_data['weight_timeline'] );
if ( $weight_timeline_count > 1 ) : ?>
  <section class="weight-chart-sect lazy" data-week="<?php echo $current_week_number ?>" data-src="#">
    <h2 class="weight-chart-sect__title">График изменения веса</h2>
    <div class="weight-chart">
      <div class="weight-chart__tabs"> <?php
      // echo( '<p>weight_timeline_count ' . $weight_timeline_count . '</p>' );
      // echo( '<p>current_week_number ' . $current_week_number . '</p>' );
        /*
          Заполняем массив недель данными,
          чтобы поставить их в дата-атрибут как json
        */
        for ( $i = 0; $i < $weight_timeline_count; $i++ ) { 
          $weight_datetime = strtotime( $user_data['weight_timeline'][ $i ]['date'] );
          if ( $first_week_end_time > $weight_datetime ) {
            $num = 0;
          } else if ( $second_week_end_time > $weight_datetime ) {
            $num = 1;
          } else if ( $third_week_end_time > $weight_datetime ) {
            $num = 2;
          }
          $weeks[ $num ][] = $user_data['weight_timeline'][ $i ];
        }
        /*
          Для каждой кнопки опередяем класс,
          в зависимости от того, какая сейчас неделя,
          кнопка будет активной, обычной или заблокированной
        */
        // $current_week_number объявлена в functions.php
        for ( $i = 1; $i < 4; $i++ ) {
          // Добавляем класс для каждой кнопки
          if ( $weight_timeline_count < 8 ) {
            switch ( $i ) {
              case 1:
                $btn_class = ' active';
                break;
              case 2:
              case 3:
                $btn_class = ' disabled';
                break;
            }
          } else if ( $weight_timeline_count >= 8 && $weight_timeline_count < 15 ) {
            switch ( $i ) {
              case 1:
                $btn_class = '';
                break;
              case 2:
                $btn_class = ' active';
                break;
              case 3:
                $btn_class = ' disabled';
                break;
            }
          } else if ( $weight_timeline_count >= 15 ) {
            switch ( $i ) {
              case 1:
              case 2:
                $btn_class = '';
                break;
              case 3:
                $btn_class = ' active';
                break;
            }
          }
          // Добавляем дата-атрибут с данными для графика
          if ( $weeks[ $i - 1 ] ) {
            $data_attr = ' data-chart="' . htmlspecialchars( json_encode( $weeks[ $i - 1 ] ) ) . '"';
          } else {
            $data_attr = '';  
          }
          echo '<button type="button"' . $data_attr . ' class="weight-chart__tab' . $btn_class . '">Неделя ' . $i . '</button>';
        } ?>
      </div>
      <canvas id="weight-chart" width="280" height="250" data-initial-weight="<?php echo $user_data['start_weight'] ?>" data-target-weight="<?php echo $user_data['target_weight'] ?>"></canvas>
    </div>
  </section> <?php
endif ?>