<?php

add_action('init', function () {
	// return;
	// register_post_type( 'product', [
	//   'label'  => null,
	//   'labels' => [
	//     'name'               => 'Продукты',
	//     'singular_name'      => 'Продукты',
	//     'add_new'            => 'Добавить',
	//     'add_new_item'       => 'Добавление',
	//     'edit_item'          => 'Редактирование',
	//     'new_item'           => 'Новый ',
	//     'view_item'          => 'Смотреть',
	//     'search_items'       => 'Искать',
	//     'not_found'          => 'Не найдено',
	//     'not_found_in_trash' => 'Не найдено в корзине',
	//     'parent_item_colon'  => '',
	//     'menu_name'          => 'Продукты',
	//   ],
	//   'description'         => '',
	//   'public'              => true,
	//   'show_in_menu'        => null,
	//   'show_in_rest'        => true,
	//   'rest_controller_class' => 'WP_REST_Posts_Controller',
	//   'menu_position'       => null,
	//   'menu_icon'           => null,
	//   'hierarchical'        => false,
	//   'supports'            => [ 'title', 'thumbnail' ],
	//   'taxonomies'          => [ 'product_category' ]
	// ] );

	register_post_type('dish', [
		'label' => null,
		'labels' => [
			'name' => 'Приемы пищи',
			'singular_name' => 'Прием пищи',
			'add_new' => 'Добавить',
			'add_new_item' => 'Добавление',
			'edit_item' => 'Редактирование',
			'new_item' => 'Новый ',
			'view_item' => 'Смотреть',
			'search_items' => 'Искать',
			'not_found' => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon' => '',
			'menu_name' => 'Приемы пищи',
		],
		'description' => '',
		'public' => true,
		'show_in_menu' => null,
		'show_in_rest' => true,
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'menu_position' => null,
		'menu_icon' => null,
		'hierarchical' => false,
		'supports' => ['title'],
		'taxonomies' => ['dish_category', 'dish_type', 'dish_ingredients'],
	]);

	register_taxonomy('dish_category', ['dish'], [
		'label' => '',
		'labels' => [
			'name' => 'Категории',
			'singular_name' => 'Категория',
			'search_items' => 'Найти',
			'all_items' => 'Все',
			'view_item ' => 'Показать',
			'parent_item' => 'Родитель',
			'parent_item_colon' => 'Родитель:',
			'edit_item' => 'Изменить',
			'update_item' => 'Обносить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Категории',
		],
		'hierarchical' => true,
		'meta_box_cb' => false,
		'show_in_rest' => true,
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	]);

	register_taxonomy('dish_type', ['dish'], [
		'label' => '',
		'labels' => [
			'name' => 'Типы',
			'singular_name' => 'Тип',
			'search_items' => 'Найти',
			'all_items' => 'Все',
			'view_item ' => 'Показать',
			'parent_item' => 'Родитель',
			'parent_item_colon' => 'Родитель:',
			'edit_item' => 'Изменить',
			'update_item' => 'Обносить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Типы',
		],
		'hierarchical' => false,
		'meta_box_cb' => false,
		'show_in_rest' => true,
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	]);

	register_taxonomy('dish_ingredients', ['dish'], [
		'label' => '',
		'labels' => [
			'name' => 'Ингредиенты',
			'singular_name' => 'Ингредиент',
			'search_items' => 'Найти',
			'all_items' => 'Все',
			'view_item ' => 'Показать',
			'parent_item' => 'Родитель',
			'parent_item_colon' => 'Родитель:',
			'edit_item' => 'Изменить',
			'update_item' => 'Обносить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Ингредиенты',
		],
		'hierarchical' => true,
		'meta_box_cb' => false,
		'show_in_rest' => true,
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	]);

	// register_post_type( 'recipe', [
	//   'label'  => null,
	//   'labels' => [
	//     'name'               => 'Рецепты',
	//     'singular_name'      => 'Рецепт',
	//     'add_new'            => 'Добавить',
	//     'add_new_item'       => 'Добавление',
	//     'edit_item'          => 'Редактирование',
	//     'new_item'           => 'Новый ',
	//     'view_item'          => 'Смотреть',
	//     'search_items'       => 'Искать',
	//     'not_found'          => 'Не найдено',
	//     'not_found_in_trash' => 'Не найдено в корзине',
	//     'parent_item_colon'  => '',
	//     'menu_name'          => 'Рецепты',
	//   ],
	//   'description'         => '',
	//   'public'              => true,
	//   'show_in_menu'        => null,
	//   'show_in_rest'        => true,
	//   'rest_controller_class' => 'WP_REST_Posts_Controller',
	//   'menu_position'       => null,
	//   'menu_icon'           => null,
	//   'hierarchical'        => false,
	//   'supports'            => [ 'title' ],
	//   'taxonomies'          => []
	// ] );

});
