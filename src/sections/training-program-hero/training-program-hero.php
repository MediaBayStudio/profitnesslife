<?php
print_account_hero_section( [
  'title' => $section['title'],
  'descr' => $section['descr'],
  'img' => [
    'url' => $template_directory_uri . '/img/training-hero-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => [
    [
      'title' => 'Общие правила выполнения',
      'class' => 'btn-ol training-rules-popup-open'
    ],
    [
      'title' => 'Инвентарь',
      'class' => 'btn-ol inventory-popup-open'
    ]
  ]
] ) ?>