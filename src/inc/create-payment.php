<?php

function create_payment() {

  global $site_url;

  $user_name = $_POST['name'];
  $user_surname = $_POST['surname'];
  $user_email = $_POST['email'];
  $user_tel = $_POST['tel'];

  // Получаем последний платеж
  $query = new WP_Query( [
    'post_status' => 'any',
    'orderby'     => 'post_date',
    'numberposts' => -1,
    // 'order'       => 'DESC',
    'post_type'   => 'payment'
  ] );

  $payments = $query->posts;

  // Если есть, то берем его номер и прибавляем 1
  if ( $payments ) {
    $last_payment = $payments[0];
    $last_payment_numbet = preg_replace( '/[^0-9]/', '', $last_payment->post_title );
    $payment_number = 1 + (int)$last_payment_numbet;
  } else {
    // если заказов нет, то номер 1
    $payment_number = '1';
  }

  $payment_data = array(
    'post_title'    => sanitize_text_field( 'Заказ #' . $payment_number ),
    'post_type'     => 'payment',
    'post_content'  => '',
    'post_status'   => 'pending', // статус "ожидает подтверждения"
    'post_author'   => 1
  );

  // создаем заказ
  $payment_id = wp_insert_post( $payment_data );

  // обновялем поля заказа
  update_field( 'name', $user_name, $payment_id );
  update_field( 'surname', $user_surname, $payment_id );
  update_field( 'email', $user_email, $payment_id );
  update_field( 'tel', $user_tel, $payment_id );

  $subject = 'Оплата марафона стройности';
  $payment_url = $site_url . '/wp-admin/post.php?post=' . $payment_id . '&action=edit';

  $admin_message = 'PROFITNESSLIFE.RU' . PHP_EOL . PHP_EOL .
  'Здравствуйте!' . PHP_EOL . PHP_EOL .
  'На сайте profitnesslife.ru была совершена оплата.' . PHP_EOL . PHP_EOL .
  'Информация об оплате:' . PHP_EOL . PHP_EOL .
  'Имя пользователя: ' . $user_name . PHP_EOL .
  'Фамилия пользователя: ' . $user_surname . PHP_EOL .
  'E-mail пользователя: ' . $user_email . PHP_EOL .
  'Телефон пользователя: ' . $user_tel . PHP_EOL .
  'Данные о заказе записаны в базу данных сайта и доступны по ссылке: ' . $payment_url;


  $client_message = 'PROFITNESSLIFE.RU' . PHP_EOL . PHP_EOL .
  'Здравствуйте!' . PHP_EOL . PHP_EOL .
  'Благодарим вас за оплату марафона стройности!' . PHP_EOL . PHP_EOL .
  'Мы свяжемся с вами в ближайшее время, и вы получите логин и пароль для входа в личный кабинет.' . PHP_EOL .
  'С наилучшими пожеланиями, команда profitnesslife.ru';

  $admin_email = '89224714290a@gmail.com';
  $client_email = '89224714290a@gmail.com';

  // отправка на почту
  wp_mail( $admin_email, $subject, $admin_message );
  wp_mail( $client_email, $subject, $client_message );

}

add_action( 'wp_ajax_nopriv_create_payment', 'create_payment' ); 
add_action( 'wp_ajax_create_payment', 'create_payment' );