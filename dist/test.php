<?php
/*
  Template name: test
*/
# get_header();

  /* РАСЧЕТЫ ВРЕМЕНИ */
  // Дата прохождения анкеты
  // $questionnaire_date =  '29.11.2021 10:12:13';
  // $questionnaire_dmy_date = '29.11.2021';

  // // Время прохождения анкеты в мс
  // $questionnaire_time =  strtotime( $questionnaire_date );
  // $questionnaire_dmy_time =  strtotime( $questionnaire_dmy_date );

  // // Название дня прохождения анкеты: Sun || Mon || Tue || etc...
  // $questionnaire_week_day = date( 'D', $questionnaire_time );

  // // Время начала марафона в мс (следующий понедельник от даты прохождения анкеты)
  // // или сегодня, если анкета пройдена в понедельник
  // if ( $questionnaire_week_day === 'Mon' ) {
  //   $start_marathon_time = $questionnaire_dmy_time;
  // } else {
  //   $start_marathon_time = strtotime( 'next monday', $questionnaire_dmy_time );
  // }
  
  // // Время открытия анкеты
  // /*
  //   Если анкета пройдена в воскресенье или понедельник,
  //   то показывать результат через 3 часа
  //   Если анкета пройдена в любой другой день,
  //   то показывать результат в следующее воскресенье в 10 утра
  // */
  // if ( $questionnaire_week_day === 'Sun' || $questionnaire_week_day === 'Mon' ) {
  //   $diet_plan_open_time = strtotime( '+3 hours', $questionnaire_time );
  // } else {
  //   $diet_plan_open_time = strtotime( 'next sunday +10 hours', $questionnaire_time );
  // }

  // $diet_plan_open_date = date( 'd.m.Y H:i:s', $diet_plan_open_time );

  // $first_week_end_time = strtotime( '+1 week', $start_marathon_time );
  // $second_week_end_time = strtotime( '+2 week', $start_marathon_time );
  // $third_week_end_time = strtotime( '+3 week', $start_marathon_time );

  // $first_week_end_date = date( 'd.m.Y H:i:s', $first_week_end_time );
  // $second_week_end_date = date( 'd.m.Y H:i:s', $second_week_end_time );
  // $third_week_end_date = date( 'd.m.Y H:i:s', $third_week_end_time );

  // $start_marathon_date = date( 'd.m.Y H:i:s', $start_marathon_time );

  // $finish_marathon_time = $third_week_end_time;
  // $finish_marathon_date = $third_week_end_date;

  // update_field( 'first_week_end_time', $first_week_end_time, 'user_14' );
  // update_field( 'second_week_end_time', $second_week_end_time, 'user_14' );
  // update_field( 'third_week_end_time', $third_week_end_time, 'user_14' );

  // update_field( 'first_week_end_date', $first_week_end_date, 'user_14' );
  // update_field( 'second_week_end_date', $second_week_end_date, 'user_14' );
  // update_field( 'third_week_end_date', $third_week_end_date, 'user_14' );

  // update_field( 'diet_plan_open_date', $diet_plan_open_date, 'user_14' );
  // update_field( 'diet_plan_open_time', $diet_plan_open_time, 'user_14' );
  // update_field( 'start_marathon_time', $start_marathon_time, 'user_14' );
  // update_field( 'finish_marathon_time', $finish_marathon_time, 'user_14' );
  // update_field( 'start_marathon_date', $start_marathon_date, 'user_14' );
  // update_field( 'finish_marathon_date', $finish_marathon_date, 'user_14' );
  // update_field( 'questionnaire_time', $questionnaire_date, 'user_14' );
  

  // $users = get_users( [
  //   'role__in' => 'completed',
  //   'number' => -1
  // ] );

  // foreach ( $users as $u ) {
  //   echo "<p>{$u->data->display_name}</p>";
  //   echo "<p>{$u->data->user_registered}</p>";
  //   echo "<p>" . strtotime( $u->data->user_registered ) . "</p>";
  //   echo "<p>" . strtotime( '01.05.2022' ) . "</p>";

  //   echo "<p><a style='text-decoration:underline;color:lightblue' href=\"" . $site_url . '/wp-admin/user-edit.php?user_id=' . $u->ID . "\" target=\"_blank\">" . $site_url . '/wp-admin/user-edit.php?user_id=' . $u->ID . "</a></p>";

  //   if ( strtotime( '01.05.2022' ) > strtotime( $u->data->user_registered ) ) {
  //     $childrens = get_posts( [
  //       'author' => $u->ID,
  //       'post_type'   => 'attachment',
  //       'numberposts' => -1,
  //       'post_status' => 'any'
  //     ] );

  //     if( $childrens ){
  //       foreach( $childrens as $children ){
  //         echo "<p>{$children->post_type}</p>";
  //         echo "<p><a style='text-decoration:underline;color:lightblue' href=\"" . wp_get_attachment_url( $children->ID ) . "\" target=\"_blank\">" . wp_get_attachment_url( $children->ID ) . "</a></p>";
  //       }
  //     }
  //   }
  // }

?>
<header>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/style.css">
</header>
<form class="container" id="form" style="display: flex;flex-flow: column;align-items: flex-start;">
  <input type="hidden" name="test" value="1">
  <select name="target" style="margin: 10px 0">
    <option value="weight-loss">Потеря веса</option>
    <option value="weight-maintaining">Поддержание веса</option>
    <option value="weight-gain">Набор веса</option>
  </select>
  <select name="activity" style="margin: 10px 0">
    <option value="inactive">Сидячий, малоподвижный</option>
    <option value="medium-active">Тренируюсь регулярно</option>
    <option value="high-active">Высокая активность</option>
  </select>
  <select name="sex" style="margin: 10px 0">
    <option value="male">Мужской</option>
    <option value="female">Женский</option>
  </select>
  <select name="children" style="margin: 10px 0">
    <option value="y">Есть дети на гв</option>
    <option value="n" selected>Нет детей на гв</option>
  </select>
  Текущий вес:<input type="number" name="current-weight" placeholder="Текущий вес" value="70" style="margin: 10px 0">
  <!-- Пользователь:<input type="text" name="user-id" value="user_12" style="margin: 10px 0"> -->
  Рост:<input type="number" name="height" placeholder="Рост" value="160" style="margin: 10px 0">
  Возраст:<input type="number" name="age" placeholder="Возраст" value="35" style="margin: 10px 0">
  <div style="display: flex;flex-flow: column;margin: 10px 0;">
  <b style="margin: 0 0 5px;">Исключить категории:</b> <?php
    $categories = get_terms( [
      'taxonomy' => 'dish_category'
    ] );
    foreach ( $categories as $category ) : ?>
      <label style="display: flex;align-items: center;margin: 0 0 5px;"> <?php
        switch ( $category->name ) :
          case 'Мясо':
          case 'Рыба':
          case 'Молочные продукты': ?>
            <span><b style="font-size: 1.2em;"><?php echo $category->name ?></b></span> <?php
            break;
          default: ?>
            <span><?php echo $category->name . ( $category->name === 'Крупа' ? ' (только на завтрак)' : '' ) ?></span> <?php
            break;
        endswitch ?>
        <input type="checkbox" name="categories[]" value="<?php echo $category->slug ?>">
      </label> <?php
     endforeach ?>
  </div>
  <button class="btn btn-green" name="submit" style="width: 150px;height: 40px;">Проверить</button>
  <style>
    [name="submit"].loading {
      opacity: 0.5;
      pointer-events: none;
    }
  </style>
</form>
<br>
<script>
  var j;
  document.addEventListener('DOMContentLoaded', function() {
    let form = document.forms.form,
      result = document.querySelector('#result');

    form.addEventListener('submit', function() {
      event.preventDefault();
      form.submit.classList.add('loading');

      let data = new FormData(form),
        url = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';

      data.append('action', 'questionnaire_send');
      data.append('method', 'test');

      fetch(url, {
        method: 'POST',
        body: data
      })
      .then(function(response) {
        if (response.ok) {
          return response.text();
        } else {
          console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
          return '';
        }
      })
      .then(function(response) {
        // response = JSON.parse(response);
        // console.log(response);
        form.submit.classList.remove('loading');
        result.innerHTML = response;
   
      })
      .catch(function(err) {
        errorPopup.openPopup();
        console.log(err);
      });
    });
  });
</script>
<div id="result"></div>
<?php

# get_footer();