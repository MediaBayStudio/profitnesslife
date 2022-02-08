<?php

// $chats = get_field( 'telegram_chats', 419 );

// for ( $i = 0, $len = count( $chats ); $i < $len; $i++ ) { 
//   if ( $user_data['telegram_chat'] === $chats[ $i ]['link'] ) {
//     $target_chat_index = $i;
//   }
// }

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
      'class' => 'btn-green',
      'href' => $user_data['telegram_chat']
    ]
  ]
] ) ?>