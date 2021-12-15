<?php
if ( !$user_data['telegram_chat'] && $user_id != '1' ) {
  echo "<p>Пользователь не прикреплен к чату</p>";
  $users = get_users( [
    'number' => -1,
    'role__in' => ['waiting', 'started'],
    'meta_query' => [
      [
        'key' => 'telegram_chat',
        'value' => ''
      ]
    ]
  ] );

  $users_count = count_users();
  $total_users_count = $users_count['avail_roles']['waiting'] + $users_count['avail_roles']['started'];

  foreach ( $section['chats'] as $chat ) {
    if ( $chat['users'] ) {
      $chats_counts[] = count( $chat['users'] );
    } else {
      $chats_counts[] = 0;
    }
  }

  // Наименьшее кол-во пользователей в массиве
  $target_chat_value = min( $chats_counts );


  // Определяем ключ в массиве
  foreach ( $chats_counts as $key => $value ) {
    if ( $value === $target_chat_value ) {
      $target_chat_index = $key;
      break;
    }
  }


  echo "<p>target_chat_index: {$target_chat_index}</p>";

  if ( is_array( $section['chats'][ $target_chat_index ]['users'] ) ) {
    if ( !in_array( $user_id, $section['chats'][ $target_chat_index ]['users'] ) ) {
      $section['chats'][ $target_chat_index ]['users'][] = $user_id;
      $update = true;
      echo "<p>Пользователя нет в чате</p>";
    } else {
      echo "<p>Этот пользователь уже есть в чате</p>";
    }
  } else {
    $section['chats'][ $target_chat_index ]['users'] = [ $user_id ];
    $update = true;
    echo "<p>Чат пустой</p>";
  }

  if ( $update ) {
    echo "<p>Добавим пользователя в чат</p>";
    $sections = $GLOBALS['sections'];
    $sections[0]['chats'] = $section['chats'];
    update_field( 'sections', $sections, $post->ID );
    update_field( 'telegram_chat', $section['chats'][ $target_chat_index ]['link'], 'user_' . $user_id );
  }
}
 
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
      'href' => $section['chats'][ $target_chat_index ]['link']
    ]
  ]
] ) ?>