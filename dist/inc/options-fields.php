<?php
 /* Настройка контактов в панели настройки->общее */
// Функции вывода нужных полей
  function options_inp_html ( $id ) {
    echo "<input type='text' name='{$id}' value='" . esc_attr( get_option( $id ) ) . "'>";
  }

  add_action( 'admin_init', function() {
    $options = [
      'tel'     =>  'Телефон (в подвале сайта)',
      'email'   =>  'E-mail (в подвале сайта)',
      // 'instagram_last_update'     =>  'Последнее обновление отзывов Instagram',
      'manager_link_whatsapp' =>  'Ссылка на WhatsApp (гл. страница и при ошибке оплаты)',
      'manager_link_viber' =>  'Ссылка на Viber (гл. страница и при ошибке оплаты)',
      // 'coords'  =>  'Координаты маркера на карте',
      // 'zoom'    =>  'Увеличение карты'
    ];

    foreach ($options as $id => $name) {
      $my_id = "contacts_{$id}";

      add_settings_field( $id, $name, 'options_inp_html', 'general', 'default', $my_id );
      register_setting( 'general', $my_id );
    }
  } );
