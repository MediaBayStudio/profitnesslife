<?php 
print_account_hero_section( [
  'title' => $section['title'],
  'descr' => $section['descr'],
  'img' => [
    'url' => $template_directory_uri . '/img/diet-plan-hero-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => [
    [
      'title' => 'Разрешённые продукты',
      'class' => 'btn-ol'
    ],
    [
      'title' => 'Правила питания',
      'class' => 'btn-ol'
    ]
  ]
] ) ?>