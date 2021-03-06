<?php 
print_account_hero_section( [
  'title' => $section['title'],
  'title_tag' => 'h2',
  'descr' => $section['descr'],
  'class' => ' products-cart-hero',
  'img' => [
    'url' => $template_directory_uri . '/img/products-cart-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => [
    [
      'title' => 'Посмотреть',
      'class' => 'btn-ol products-cart-popup-open'
    ]
  ],
  'attention' => 'Внимание, после замены блюда в продуктовой корзине произошли изменения'
] ) ?>