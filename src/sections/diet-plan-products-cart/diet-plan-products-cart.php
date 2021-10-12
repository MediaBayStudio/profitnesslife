<?php 
print_account_hero_section( [
  'title' => $section['title'],
  'title_tag' => 'h2',
  'descr' => $section['descr'],
  'img' => [
    'url' => $template_directory_uri . '/img/products-cart-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => [
    [
      'title' => 'Посмотреть',
      'class' => 'btn-ol'
    ]
  ]
] ) ?>