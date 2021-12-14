<?php
foreach ( $user_data['inventory'] as $inventory ) {
  if ( $inventory['value'] === 'with-weight' ) {
    $inventory_popup_cnt[] = [
      'title' => 'Гантели',
      'tags' => [
        'p' => 'Для выполнения тренировок вам понадобятся гантели и коврик',
        'recommend' => 'Рекомендации',
        'p2' => 'Рекомендуем приобрести разборные гантели от 2 до 10 кг. Это позволит вам подобрать нужный рабочий вес для всех упражнений. Разборные гантели намного удобнее и выгоднее, чем гантели с разной нагрузкой.',
        'images' => [
            'alt' => 'Изображение гантелей',
            [
              'url' => '/img/inventory-popup-weight.jpg',
              'webp' => '/img/inventory-popup-weight.webp'
            ]
          ]
        ]
      ];
  } else if ( $inventory['value'] === 'with-elastic' ) {
    $inventory_popup_cnt[] = [
      'title' => 'Резинки',
      'tags' => [
        'p' => 'Для выполнения тренировок вам понадобятся набор резинок, эспандер и коврик по возможности',
        'recommend' => 'Рекомендации',
        'p2' => 'Рекомендуем выбирать набор резинок с разным сопротивлением от 2–3 кг до 15–20 кг. Тканевые или латексные, как вам удобно.',
        'images' => [
            'alt' => 'Изображение резинки',
            [
              'url' => '/img/inventory-popup-elastic-1.jpg',
              'webp' => '/img/inventory-popup-elastic-1.webp'
            ]
          ],
        'p3' => 'Так же понадобится набор ленточных эспандеров с разным сопротивлением (фитнес-петли) или фитнес-ленты от 2–3 до 20 кг',
        'images2' => [
            'alt' => 'Изображение резинки',
            [
              'url' => '/img/inventory-popup-elastic-2.jpg',
              'webp' => '/img/inventory-popup-elastic-2.webp'
            ],
            [
              'url' => '/img/inventory-popup-elastic-3.jpg',
              'webp' => '/img/inventory-popup-elastic-3.webp'
            ]
          ]
      ]
    ];
  }
} ?>
<div class="inventory-popup popup">
  <div class="inventory-popup__cnt popup__cnt">
    <button type="button" class="inventory-popup__close popup__close"></button>
    <span class="inventory-popup__title popup__title">Инвентарь</span> <?php
    foreach ( $inventory_popup_cnt as $c ) {
      popup_content( [
        'title' => $c['title'],
        'tags' => $c['tags']
      ] );
    } ?>
  </div>
</div>