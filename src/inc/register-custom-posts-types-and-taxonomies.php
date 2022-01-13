<?php

add_action( 'init', function () {

	register_post_type( 'review', [
		'label' => null,
		'labels' => [
			'name' => 'Отзывы',
			'singular_name' => 'Отзыв',
			'add_new' => 'Добавить',
			'add_new_item' => 'Добавление',
			'edit_item' => 'Редактирование',
			'new_item' => 'Новый ',
			'view_item' => 'Смотреть',
			'search_items' => 'Искать',
			'not_found' => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon' => '',
			'menu_name' => 'Отзывы'
		],
		'description' => '',
		'public' => true,
		'show_in_menu' => null,
		'menu_position' => null,
		'menu_icon' => null,
		'hierarchical' => false,
		'supports' => ['title'],
		'menu_icon' => 'dashicons-admin-comments',
		'taxonomies' => [],
	] );

	register_post_type( 'instagram_post', [
		'label' => null,
		'labels' => [
			'name' => 'Instagram посты',
			'singular_name' => 'Instagram пост',
			'add_new' => 'Добавить',
			'add_new_item' => 'Добавление',
			'edit_item' => 'Редактирование',
			'new_item' => 'Новый ',
			'view_item' => 'Смотреть',
			'search_items' => 'Искать',
			'not_found' => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon' => '',
			'menu_name' => 'Instagram посты'
		],
		'description' => '',
		'public' => true,
		'show_in_menu' => null,
		'menu_position' => null,
		'menu_icon' => null,
		'hierarchical' => false,
		'supports' => ['title'],
		'taxonomies' => [],
		'menu_icon' => 'dashicons-instagram'
	] );

	register_post_type( 'dish', [
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
			'menu_name' => 'Приемы пищи'
		],
		'description' => '',
		'public' => true,
		'show_in_menu' => null,
		'menu_position' => null,
		'menu_icon' => null,
		'hierarchical' => false,
		'supports' => ['title'],
		'taxonomies' => ['dish_category', 'dish_type', 'dish_ingredients', 'dish_target'],
		'menu_icon' => 'dashicons-food'
	] );

	register_post_type( 'workout', [
		'label' => null,
		'labels' => [
			'name' => 'Тренировки',
			'singular_name' => 'Тренировка',
			'add_new' => 'Добавить',
			'add_new_item' => 'Добавление',
			'edit_item' => 'Редактирование',
			'new_item' => 'Новый ',
			'view_item' => 'Смотреть',
			'search_items' => 'Искать',
			'not_found' => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon' => '',
			'menu_name' => 'Тренировки'
		],
		'description' => '',
		'public' => true,
		'show_in_menu' => null,
		'menu_position' => null,
		'menu_icon' => null,
		'hierarchical' => false,
		'supports' => ['title'],
		'taxonomies' => ['workout_category', 'workout_type', 'workout_inventory', 'muscle_groups'],
		'menu_icon' => 'dashicons-video-alt2'
	] );

	register_taxonomy( 'workout_category', ['workout'], [
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
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Категории'
		],
		'hierarchical' => true,
		'meta_box_cb' => false
	] );

	register_taxonomy( 'workout_type', ['workout'], [
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
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Типы'
		],
		'hierarchical' => false,
		'meta_box_cb' => false
	] );


	register_taxonomy( 'workout_inventory', ['workout'], [
		'label' => '',
		'labels' => [
			'name' => 'Инвентарь',
			'singular_name' => 'Инвентарь',
			'search_items' => 'Найти',
			'all_items' => 'Все',
			'view_item ' => 'Показать',
			'parent_item' => 'Родитель',
			'parent_item_colon' => 'Родитель:',
			'edit_item' => 'Изменить',
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Инвентарь'
		],
		'hierarchical' => false,
		'meta_box_cb' => false
	] );

	register_taxonomy( 'muscle_groups', ['workout'], [
		'label' => '',
		'labels' => [
			'name' => 'Группы мышц',
			'singular_name' => 'Группа мышц',
			'search_items' => 'Найти',
			'all_items' => 'Все',
			'view_item ' => 'Показать',
			'parent_item' => 'Родитель',
			'parent_item_colon' => 'Родитель:',
			'edit_item' => 'Изменить',
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Группы мышц (ограничения)'
		],
		'hierarchical' => false,
		'meta_box_cb' => false
	] );

	register_taxonomy( 'dish_category', ['dish'], [
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
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Категории'
		],
		'hierarchical' => true,
		'meta_box_cb' => false
	] );

	register_taxonomy( 'dish_type', ['dish'], [
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
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Типы'
		],
		'hierarchical' => false,
		'meta_box_cb' => false
	] );

	register_taxonomy( 'dish_target', ['dish'], [
		'label' => '',
		'labels' => [
			'name' => 'Цели',
			'singular_name' => 'Цель',
			'search_items' => 'Найти',
			'all_items' => 'Все',
			'view_item ' => 'Показать',
			'parent_item' => 'Родитель',
			'parent_item_colon' => 'Родитель:',
			'edit_item' => 'Изменить',
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Цели'
		],
		'hierarchical' => false,
		'meta_box_cb' => false
	] );

	register_taxonomy( 'dish_ingredients', ['dish'], [
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
			'update_item' => 'Обновить',
			'add_new_item' => 'Добавить',
			'new_item_name' => 'Добавить',
			'menu_name' => 'Ингредиенты'
		],
		'hierarchical' => true,
		'meta_box_cb' => false
	] );

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

} );
