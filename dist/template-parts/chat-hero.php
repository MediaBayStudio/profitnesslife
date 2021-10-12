<?php
print_account_hero_section( [
  'title' => $section['title'],
  'descr' => $section['descr'],
  'class' => ' chat-hero',
  'img' => [
    'url' => $template_directory_uri . '/img/chat-hero-img.svg',
    'alt' => 'Изображение'
  ],
  'buttons' => [
    [
      'title' => 'Перейти в чат',
      'class' => 'btn-green'
    ]
  ]
] );
?>
