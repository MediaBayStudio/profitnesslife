<?php
function questionnaire_card( $args ) {

  $defaults = [
    'class' => '',
    'title' => '',
    'descr' => '',
    // 'print' => true,
    'lazyload' => false,
    'img' => [
      // 'url' => '',
      // 'alt' => 'icon',
      // 'width' => 0,
      // 'height' => 0
    ],
    'radio' => [],
    'checkbox' => [
      // 'value' => '',
      // 'name' => '',
      // 'id' => '',
      // 'title' => ''
    ],
    'checkboxes' => [
      // [
        // 'title' => '',
        // 'name' => '',
        // 'value' => ''
      // ]
    ]
  ];

  $parsed_args = wp_parse_args( $args, $defaults );

  $checkbox_without_container = $parsed_args['checkbox'] && $parsed_args['checkbox']['without_container'];

  if ( $checkbox_without_container ) : ?>
    <label class="questionnaire-card <?php echo $parsed_args['class'] ?>"> <?php
  else : ?>
    <div class="questionnaire-card <?php echo $parsed_args['class'] ?>"> <?php
  endif;
    if ( $parsed_args['img'] ) {
      if ( $parsed_args['lazyload'] ) {
        $img_src = 'src="#" data-src="';
        $img_class = ' lazy';
      } else {
        $img_src = 'src="';
        $img_class = '';
      }
      
      $img_src .= $parsed_args['img']['url'] . '"';
      $img_width = $parsed_args['img']['width'] ? ' width="' . $parsed_args['img']['width'] . '"' : '';
      $img_height = $parsed_args['img']['height'] ? ' height="' . $parsed_args['img']['height'] . '"' : '';
      $img_html = '<img ' . $img_src . ' alt="' . $parsed_args['img']['alt'] . '"' . $img_width . $img_height . ' class="questionnaire-card__img' . $img_class . '">';
    }
    if ( $parsed_args['title'] ) {
      $title_html = '<span class="questionnaire-card__title">' . $parsed_args['title'] . '</span>';
    }
    if ( $parsed_args['descr'] ) {
      $descr_html = '<p class="questionnaire-card__descr">' . $parsed_args['descr'] . '</p>';
    }
    if ( $parsed_args['radio'] ) : ?>
      <input type="radio" name="<?php echo $parsed_args['radio']['name'] ?>" value="<?php echo $parsed_args['radio']['value'] ?>" id="<?php echo $parsed_args['radio']['id'] ?>" class="questionnaire-card__radio-input">
      <label for="<?php echo $parsed_args['radio']['id'] ?>" class="questionnaire-card__label"> <?php
        echo $img_html . $title_html . $descr_html ?>
      </label> <?php

    elseif ( $parsed_args['checkbox'] ) :
      $checkbox_class = $parsed_args['checkbox']['class'] ? ' ' . $parsed_args['checkbox']['class'] : '';
      if ( !$parsed_args['checkbox']['checkbox_inside'] ) : ?>
        <input type="checkbox" name="<?php echo $parsed_args['checkbox']['name'] ?>" value="<?php echo $parsed_args['checkbox']['value'] ?>" id="<?php echo $parsed_args['checkbox']['id'] ?>" class="questionnaire-card__checkbox-input<?php echo $checkbox_class ?>"> <?php
      endif;
      if ( !$checkbox_without_container ) : ?>
        <label for="<?php echo $parsed_args['checkbox']['id'] ?>" class="questionnaire-card__label"> <?php
      endif;
        if ( $parsed_args['checkbox']['image_first'] ) {
          echo $img_html;
        }
        if ( $parsed_args['checkbox']['checkbox_inside'] ) : ?>
          <input type="checkbox" name="<?php echo $parsed_args['checkbox']['name'] ?>" value="<?php echo $parsed_args['checkbox']['value'] ?>" id="<?php echo $parsed_args['checkbox']['id'] ?>" class="questionnaire-card__checkbox-input<?php echo $checkbox_class ?>"> <?php
        endif;
        if ( !$parsed_args['checkbox']['image_first'] ) {
          echo $img_html;
        } ?>
        <span class="questionnaire-card__checkbox-pseudo-input checkbox-pseudo-input"></span>
        <span class="questionnaire-card__checkbox-text checkbox-text"><?php echo $parsed_args['checkbox']['title'] ?></span> <?php
        if ( !$checkbox_without_container ) : ?>
          </label> <?php
        endif;

    elseif ( $parsed_args['checkboxes'] ) :
      echo $img_html ?>
      <div class="questionnaire-card__checkboxes"> <?php
      $checkbox_class = $parsed_args['checkboxes_class'] ? ' ' . $parsed_args['checkboxes_class'] : '';
        foreach ( $parsed_args['checkboxes'] as $checkbox ) : ?>
          <label class="questionnaire-card__checkbox">
            <input type="checkbox" name="<?php echo $checkbox['name'] ?>" value="<?php echo $checkbox['value'] ?>" class="questionnaire-card__checkbox-input<?php echo $checkbox_class ?>" required>
            <span class="questionnaire-card__checkbox-pseudo-input checkbox-pseudo-input"></span>
            <span class="questionnaire-card__checkbox-title checkbox-text"><?php echo $checkbox['title'] ?></span>
          </label> <?php
        endforeach ?>
      </div> <?php
    endif;
  if ( $checkbox_without_container ) : ?>
    </label> <?php
  else : ?>
    </div> <?php
  endif;
} ?>