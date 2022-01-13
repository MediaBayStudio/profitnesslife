<?php
add_action( 'admin_menu', function() {
  global $user_id, $menu;

  if ( $user_id == 13 ) {
    remove_menu_page( 'plugins.php' ); // Плагины
    remove_menu_page( 'edit.php' ); // Записи
    remove_menu_page( 'edit-comments.php' ); // Комментарии
    remove_menu_page( 'themes.php' ); // Внешний вид
    remove_menu_page( 'flamingo' );
    remove_menu_page( 'wpcf7' );
    remove_menu_page( 'pb_backupbuddy_backup' );
    remove_menu_page( 'edit.php?post_type=acf-field-group' );
    remove_menu_page( 'wpshapere-options' );
  }
  // remove_menu_page( 'options-general.php' ); // Настройки  
  // remove_menu_page( 'tools.php' ); // Инструменты
  // remove_menu_page( 'users.php' ); // Пользователи
  // remove_menu_page( 'plugins.php' ); // Плагины
  // remove_menu_page( 'themes.php' ); // Внешний вид  
  // remove_menu_page( 'edit.php' ); // Записи
  // remove_menu_page( 'upload.php' ); // Медиабиблиотека
  // remove_menu_page( 'edit.php?post_type=page' ); // Страницы
  // remove_menu_page( 'edit-comments.php' ); // Комментарии 
  // remove_menu_page( 'link-manager.php' ); // Ссылки
} );