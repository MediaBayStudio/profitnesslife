<?php

function print_breadcrumbs( $that ) {
  global $site_url;

  $breadcrumbs = [
    0 => [
      'title' => 'Главная',
      'url' => $site_url . '/'
    ]
  ];

  $breadcrumbs[] = [
    'title' => $that->post_title,
    'url' => '#'
  ] ?>

  <div class="breadcrumbs container">
    <ul class="breadcrumbs__ul"> <?php
      foreach ( $breadcrumbs as $breadcrumb ) : ?>
        <li class="breadcrumbs__li"><a class="breadcrumbs__link" href="<?php echo $breadcrumb['url'] ?>"><?php echo $breadcrumb['title'] ?></a></li> <?php
      endforeach ?>
    </ul>
  </div> <?php
}