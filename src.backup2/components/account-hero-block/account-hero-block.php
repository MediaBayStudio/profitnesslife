<?php

function print_account_hero_section( $args ) {

  $defaults = [
    'can_be_hidden' => false,
    // 'title' => '',
    // 'descr' => '',
    // 'img' => [
    //   'url' => '',
    //   'alt' => ''
    // ],
    // 'buttons' => [
    //   [
    //     'title' => '',
    //     'class' => '',
    //     'callback' => '',
    //     'id' => '',
    //     'href' => ''
    //   ]
    // ]
  ];

  $parsed_args = wp_parse_args( $args, $defaults ) ?>

  <section class="account-hero">
    <img src="<?php echo $parsed_args['img']['url'] ?>" alt="<?php echo $parsed_args['img']['alt'] ?>" width="225" height="166" class="account-hero__img">
    <div class="account-hero__text">
      <h1 class="account-hero__title sect-title"><?php echo $args['title'] ?></h1>
      <p class="account-hero__descr"><?php echo $args['descr'] ?></p> <?php
      if ( $parsed_args['buttons'] ) : ?>
        <div class="account-hero__btns"> <?php
          foreach ( $parsed_args['buttons'] as $btn ) {
            if ( $btn['href'] ) {
              $btn_tag = 'a';
              $btn_attr_key = 'href';
              $btn_attr_value = $btn['href'];
            } else {
              $btn_tag = 'button';
              $btn_attr_key = 'type';
              $btn_attr_value = 'button';
            }
            $callback = $btn['callback'] ? ' onclick=\"' . $btn['callback'] . '\"' : '';

            echo "<{$btn_tag} {$btn_attr_key}=\"{$btn_attr_value}\" class=\"btn {$btn['class']}\"{$callback}>{$btn['title']}</{$btn_tag}>";
          } ?>
        </div> <?php
      endif ?>
    </div>
  </section> <?php
}