<?php
// $user_id объявлена в functions.php
// $user_data объявлена в functions.php
// $current_week_number объявлениа в functions.php
function recalculate_products_cart() {
  global $user_data, $current_week_number;

  $repasts = [
    'breakfast',
    'snack_1',
    'lunch',
    'snack_2',
    'dinner'
  ];

  $data = array_slice( $user_data['diet_plan'], ($current_week_number - 1) * 7, 7 );

  // $posts = get_posts( [
  //   'numberposts' => -1,
  //   'post_type' => 'dish'
  // ] );

  foreach ( $data as $day ) {
    foreach ( $repasts as $repast ) {
      $ingredients = get_field( 'ingredients', $day[ $repast ]->ID );
    // foreach ( $posts as $post ) {
      // $ingredients = get_field( 'ingredients', $post->ID );
      foreach ( $ingredients as $ingredient ) {
        if ( $ingredient['title'] ) {

          $ingredient_key = $ingredient['title']->name;
          $ingredient_label = $ingredient['units']['label'];

          if ( $ingredient_key === 'Овощи' || $ingredient_key === 'Сезонные овощи' || $ingredient_key === 'Салат из свежих овощей' ) {
            continue;
          }

          switch ( $ingredient['units']['value'] ) {
            case 'teaspoon':
              $ingredient_value = $ingredient['number'] * 5;
              $ingredient_label = 'гр.';
              break;
            case 'tablespoon':
              $ingredient_value = $ingredient['number'] * 10;
              $ingredient_label = 'гр.';
              break;
            default:
              $ingredient_value = $ingredient['number'];
              $ingredient_label = $ingredient['units']['label'];
              break;
          }

          switch ( $ingredient_key ) {
            case 'Лук-порей':
            case 'Банан':
            case 'Апельсин':
            case 'Приправа карри':
            case 'Яблоко':
            case 'Груша':
            case 'Фрукт с низким ГИ':
            case 'Тыква':
            case 'Цветная капуста':
            case 'Авокадо':
            case 'Лимон':
            case 'Клетчатка':
            case 'Подсластитель FitParad':
            case 'Имбирь':
            case 'Морковь':
            case 'Огурцы':
            case 'Помидоры':
            case 'Кабачок':
            case 'Баклажан':
            case 'Зелень':
            case 'Цукинни':
            case 'Чеснок':
            case 'Базилик':
            case 'Фрукты':
            case 'Сода':
            case 'Картофель':
            case 'Зеленый лук':
            case 'Оливки':
            case 'Брокколи':
            case 'Капуста белокачанная':
            case 'Свекла':
            case 'Лимонный сок':
            case 'Сок лимона':
            case 'Зеленый горошек':
            case 'Стручковая фасоль':
            case 'Томатный сок':
            case 'Болгарский перец':
            case 'Сладкий перец':
            case 'Оливковое масло':
            case 'Лук репчатый':
              $ingredient_value = '';
              $ingredient_label = '';
              break;
          }

          if ( $ingredient_value === '' && $ingredient_label === '' ) {
            $array_part = 'bottom';
          } else {
            $array_part = 'top';
          }

          if ( $products_cart_popup[ $array_part ][ $ingredient_key ] ) {
            $products_cart_popup[ $array_part ][ $ingredient_key ]['number'] += $ingredient_value;
          } else {
            $products_cart_popup[ $array_part ][ $ingredient_key ]['label'] = $ingredient_label;
            $products_cart_popup[ $array_part ][ $ingredient_key ]['number'] = $ingredient_value;
          }

      
        }
      }
    }
  }
    ksort( $products_cart_popup['top'] );
    ksort( $products_cart_popup['bottom'] );

  return $products_cart_popup;
}