<?php 
print_account_hero_section( [
  'title' => $section['title'],
  'descr' => $section['descr'],
  'class' => ' diet-plan-hero',
  'img' => [
    'url' => $template_directory_uri . '/img/diet-plan-hero-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => [
    [
      'title' => 'Разрешённые продукты',
      'class' => 'btn-ol allowed-popup-open'
    ],
    [
      'title' => 'Правила питания',
      'class' => 'btn-ol'
    ]
  ]
] ) ?>