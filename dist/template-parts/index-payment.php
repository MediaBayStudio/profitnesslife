<section class="index-payment container sect" id="payment-methods-sect">
  <h2 class="index-payment__title sect-title"><?php echo $section['title'] ?></h2>
  <ul class="index-payment__list lazy" data-src="#"> <?php
    foreach ( $section['payment_methods'] as $payment_method ) : ?>
      <li class="index-payment__method"> <?php
        if ( $payment_method['img'] ) :
          $alt = $payment_method['img']['alt'] ?: 'image' ?>
          <figure class="index-payment__method-fig">
            <img src="#" alt="<?php echo $alt ?>" data-src="<?php echo $payment_method['img']['url'] ?>" class="index-payment__method-img lazy">
          </figure> <?php
        endif;
        if ( $payment_method['descr'] ) : ?>
          <p class="index-payment__method-text"><?php echo $payment_method['descr'] ?></p> <?php
        endif ?>
      </li> <?php
    endforeach ?>
  </ul>
  <div class="index-payment__nav"></div>
</section>