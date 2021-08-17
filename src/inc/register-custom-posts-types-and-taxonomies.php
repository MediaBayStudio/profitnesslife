<?php

add_action( 'init', function() {
  return;
  register_post_type( 'slug', [
    'label'  => null,
    'labels' => [
      'name'               => 'Оборудование',
      'singular_name'      => 'Оборудование',
      'add_new'            => 'Добавить',
      'add_new_item'       => 'Добавление',
      'edit_item'          => 'Редактирование',
      'new_item'           => 'Новое ',
      'view_item'          => 'Смотреть',
      'search_items'       => 'Искать',
      'not_found'          => 'Не найдено',
      'not_found_in_trash' => 'Не найдено в корзине',
      'parent_item_colon'  => '',
      'menu_name'          => 'Оборудование',
    ],
    'description'         => '',
    'public'              => true,
    'show_in_menu'        => null,
    'show_in_rest'        => null,
    'rest_base'           => null,
    'menu_position'       => null,
    'menu_icon'           => null,
    'hierarchical'        => false,
    'supports'            => [ 'title', 'thumbnail' ],
    'taxonomies'          => [ 'tax_slug' ],
    'exclude_from_search' => false,
    'has_archive' => true,
    'rewrite' => [
      // 'slug' => 'equipments/%equipments%',
      'with_front' => true,
      'pages' => false
    ],
    'query_var' => false
  ] );


  register_taxonomy( 'slug', ['slug_for'], [
    'label'                 => '',
    'labels'                => [
      'name'              => 'Категории',
      'singular_name'     => 'Категория',
      'search_items'      => 'Найти',
      'all_items'         => 'Все',
      'view_item '        => 'Показать',
      'parent_item'       => 'Родитель',
      'parent_item_colon' => 'Родитель:',
      'edit_item'         => 'Изменить',
      'update_item'       => 'Обносить',
      'add_new_item'      => 'Добавить',
      'new_item_name'     => 'Добавить',
      'menu_name'         => 'Категории',
    ],
    'hierarchical'          => true,
    'meta_box_cb'           => false
  ] );

});

