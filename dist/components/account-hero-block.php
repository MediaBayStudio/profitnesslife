<?php

function print_account_hero_section( $args ) {
  global $user_id;
  $defaults = [
    'can_be_hidden' => false,
    'title_tag' => 'h1',
    'class' => ''
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
    //     'href' => '',
    //     'target' => ''
    //   ]
    // ]
  ];

  $parsed_args = wp_parse_args( $args, $defaults ) ?>

  <section class="account-hero<?php echo $parsed_args['class'] ?><?php if ( $parsed_args['can_be_hidden'] ) echo ' account-hero-maybe-close' ?>" data-user-id="<?php echo $user_id ?>">
    <button type="button" class="account-hero__close popup__close" title="Больше не показывать"></button>
    <img src="<?php echo $parsed_args['img']['url'] ?>" alt="<?php echo $parsed_args['img']['alt'] ?>" class="account-hero__img">
    <div class="account-hero__text">
      <<?php echo $parsed_args['title_tag'] ?> class="account-hero__title sect-title"><?php echo $args['title'] ?></<?php echo $parsed_args['title_tag'] ?>>
      <p class="account-hero__descr"><?php echo $args['descr'] ?></p> <?php
      if ( $parsed_args['buttons'] ) : ?>
        <div class="account-hero__btns"> <?php
          foreach ( $parsed_args['buttons'] as $btn ) {
            if ( $btn['href'] ) {
              $btn_tag = 'a';
              $btn_attr_key = 'href';
              $btn_attr_value = $btn['href'];
              $btn_target = $btn['target'] ? ' target="_blank"' : '';
            } else {
              $btn_tag = 'button';
              $btn_attr_key = 'type';
              $btn_attr_value = 'button';
            }
            $callback = $btn['callback'] ? ' onclick=\"' . $btn['callback'] . '\"' : '';

            echo "<{$btn_tag} {$btn_attr_key}=\"{$btn_attr_value}\"{$btn_target} class=\"btn {$btn['class']}\"{$callback}>{$btn['title']}</{$btn_tag}>";
          } ?>
        </div> <?php
      endif ?>
    </div> <?php
    if ( $parsed_args['attention'] ) : ?>
      <p class="account-hero__attention"><?php echo $parsed_args['attention'] ?></p> <?php
    endif ?>
  </section> <?php
}