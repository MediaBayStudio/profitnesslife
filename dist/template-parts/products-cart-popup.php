<?php 
$products_cart_popup = recalculate_products_cart() ?>
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
    </div>
    <div class="products-cart-popup__thead">
      <span class="products-cart-popup__th">Продукты</span>
      <span class="products-cart-popup__th">Количество</span>
    </div>
    <div class="products-cart-popup__table"> <?php
      foreach ( $products_cart_popup as $array_part => $products ) :
        foreach ( $products as $key => $value ) : ?>
          <div class="products-cart-popup__tr">
            <span class="products-cart-popup__td"><?php echo $key ?></span>
            <span class="products-cart-popup__td"><?php
            if ( $value['number'] ) {
              echo $value['number'] . ' ' . $value['label'];
            } ?></span>
          </div> <?php
        endforeach;
      endforeach ?>
    </div>
  </div>
</div>