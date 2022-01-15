<?php 
  
  switch ( $post->post_name ) {
    case 'questionnaire':
      $error_popup_class = ' questionnaire-error-popup';
      $img_url = "{$template_directory_uri}/img/questionnaire-error-img.svg";
      $title = 'План питания не подобран';
      $buttons = [
        [
          'class' => 'btn btn-green',
          'title' => 'Написать менеджеру',
          'href' => $manager_link_whatsapp
        ],
        [
          'class' => 'btn btn-ol',
          'title' => 'Пройти анкету заново',
          'callback' => 'resetQuestionnaire()'
        ]
      ];
      $text = 'К сожалению, мы не можем подобрать для вас план питания. Вы можете попробовать пройти анкету заново или обратиться к менеджеру';
      break;
    
    default:
      $img_url = '';
      $title = '';
      $buttons = [];
      $text = $section['text'];
      break;
  }

 ?>
<div class="error-popup popup<?= $error_popup_class ?>">
  <div class="error-popup__cnt popup__cnt">
    <button type="button" class="error-popup__close popup__close"></button> <?php
    if ( $img_url ) : ?>
      <img src="#" alt="image" class="error-popup__img lazy" data-src="<?= $img_url ?>"> <?php
    endif;
    if ( $title ) : ?>
      <p class="error-popup__title popup__title"><?= $title ?></p> <?php
    endif;
    if ( $text ) : ?>
      <p class="error-popup__descr popup__descr"><?= $text ?></p> <?php
    endif;
    if ( $buttons ) : ?>
      <div class="error-popup__buttons"> <?php
        foreach ( $buttons as $btn ) {
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
          $callback = $btn['callback'] ? ' onclick="' . $btn['callback'] . '"' : '';

          echo "<{$btn_tag} {$btn_attr_key}=\"{$btn_attr_value}\"{$btn_target} data-user=\"{$user_id}\" class=\"btn {$btn['class']}\"{$callback}>{$btn['title']}</{$btn_tag}>";
        } ?>
      </div> <?php
    endif ?>
  </div>
</div>