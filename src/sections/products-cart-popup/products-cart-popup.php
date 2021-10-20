<div class="products-cart-popup popup">
  <div class="products-cart-popup__cnt popup__cnt">
    <button type="button" class="products-cart-popup__close popup__close"></button>
    <span class="products-cart-popup__title popup__title">Продуктовая корзина</span>
    <div class="products-cart-popup__hdr">
      <span>На этой неделе тебе понадобится</span> <?php
      switch ( $current_week_number ) {
        case 1:
          $time_from = $start_marathon_time;
          $time_to = $first_week_end_time;
          break;
        case 2:
          $time_from = $first_week_end_time;
          $time_to = $second_week_end_time;
          break;
        case 3:
          $time_from = $second_week_end_time;
          $time_to = $third_week_end_time;
          break;
      } ?>
      <span class="products-cart-popup__dates">
        c
        <span class="products-cart-popup__date-form"><?php echo date( 'd.m.Y', $time_from ) ?></span>
        по
        <span class="products-cart-popup__date-to"><?php echo date( 'd.m.Y', strtotime( '-1 day', $time_to ) ) ?></span>
      </span>
    </div> <?php
      foreach ( $user_data['week_1'] as $dish_type ) {
        foreach ( $dish_type as $dish ) {
          $ingredients = get_field( 'ingredients', $dish );
          foreach ( $ingredients as $ingredient ) {
            $ingredient_title = $ingredient['title']->name;

            if ( $ingredient_title === 'Овощи' || $ingredient_title === 'Сезонные овощи' ) {
              continue;
            }

            if ( $products_cart_popup[ $ingredient_title ] ) {
              if ( $ingredient['number']['value'] ) {
                switch ( $ingredient['units']['value'] ) {
                  case 'teaspoon':
                    $value = $ingredient['number'] * 5;
                    break;
                  case 'tablespoon':
                    $value = $ingredient['number'] * 10;
                    break;
                  default:
                    $value = $ingredient['number'];
                    break;
                }
                $products_cart_popup[ $ingredient_title ]['number'] += $value;
              }
            } else {
              $products_cart_popup[ $ingredient_title ] = [
                'title' => $ingredient_title
              ];
              $label = $ingredient['units']['label'];
              if ( $ingredient['number'] ) {
                switch ( $ingredient['units']['value'] ) {
                  case 'teaspoon':
                    $value = $ingredient['number'] * 5;
                    $label = 'гр.';
                    break;
                  case 'tablespoon':
                    $value = $ingredient['number'] * 10;
                    $label = 'гр.';
                    break;
                  default:
                    $label = $ingredient['units']['label'];
                    $value = $ingredient['number'];
                    break;
                }
                $products_cart_popup[ $ingredient_title ]['label'] = $label;
                $products_cart_popup[ $ingredient_title ]['number'] = $value;
              }
            }
          }
        }
      } ?>
    <div class="products-cart-popup__thead">
      <span class="products-cart-popup__th">Продукты</span>
      <span class="products-cart-popup__th">Количество</span>
    </div>
    <div class="products-cart-popup__table"> <?php
      foreach ( $products_cart_popup as $product ) : ?>
        <div class="products-cart-popup__tr">
          <span class="products-cart-popup__td"><?php echo $product['title'] ?></span>
          <span class="products-cart-popup__td"><?php
          if ( $product['label'] ) {
            echo $product['number'] . ' ' . $product['label'];
          } ?></span>
        </div> <?php
      endforeach ?>
    </div>
  </div>
</div>