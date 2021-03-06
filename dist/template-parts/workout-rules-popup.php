<?php
$cnt = [
  [
    'title' => 'Как подобрать рабочий вес?',
    'tags' => [
      'p' => 'Рабочий вес снарядов во всех упражнениях подбирается только опытным путём. Определяется он так: берете такой вес снарядов, чтобы вы смогли выполнить не более того количества повторений, которое указано в первом подходе упражнения. Этот вес и является рабочим в упражнении. Каждые две-три недели во всех упражнениях увеличивайте рабочий вес, это крайне важно и необходимо для прогресса в тренировках. Увеличивайте вес на минимально возможный, т.е. делайте это постепенно, без резких увеличений веса снарядов.'
    ]
  ],
  [
    'title' => 'Кардио-тренировка',
    'tags' => [
      'p' => 'Кардио — тренировки обязательны. Старайтесь уделять им минимум 1–2 тренировки в неделю, дополнительно к силовым нагрузкам. Это может быть любой кардио-тренажёр (беговая дорожка, эллипсоид, степпер и т.д.), либо бег или велосипед в зале или на улице.<br>
Смысл кардио тренировок не в конкретной нагрузке, а в том, чтобы держать пульс в интервале 120–160 ударов в минуту. Пользуйтесь пульсометром, установленным в тренажёре, либо приобретите собственный.<br>
Варианты подключения кардио следующие:<br>
1. Утром (можно натощак) – 20 минут.<br>
2. Сразу после силовой тренировки — 30 минут.<br>
3. Вечером (без силовой тренировки) – 45–60 минут.<br>
Кардио — тренировки длительностью более 60 минут запускают процесс разрушения собственных мышц, поэтому тренироваться более часа не рекомендуется.
В случае, если кардио — тренировку провести не получается, то рекомендуем работать на скакалке: 5 минут до тренировки и 15 минут после.'
    ]
  ],
  [
    'title' => 'Активность в течение дня',
    'tags' => [
      'p' => 'Это крайне важный аспект в здоровом образе жизни.<br>
Ваша задача — совершать 10 000 шагов каждый день. В эту норму активности входит абсолютно все: просто ходьба, тренировка, уборка по дому, поход в магазин и тд, те любое телодвижение, что вы делаете в течение дня. Используйте ваш телефон, там всегда есть шагомер, либо установите соответствующее приложение.<br>
Чередовать тренировки можно — менять их местами, делать отдых в будние дни. Однако, ваша задача — выполнить весь объём тренировочной работы за неделю, в какой бы очерёдности тренировки не были. Вы можете выбирать время и дни для выполнения тренировок самостоятельно.<br>
Всегда тренируйтесь по таймеру — следите за тем, сколько вы отдыхаете между подходами и упражнениями.<br>
Во-первых, это увеличит интенсивность тренировок.<br>
Во-вторых, сократит время занятий — без таймера вы всегда будете отдыхать больше, чем нужно.<br>
В-третьих, отдых более 1,5–2 минут между подходами существенно сокращает эффективность самих тренировок.<br>
Скачайте любое удобное приложение, либо пользуйтесь таймером телефона.'
    ]
  ]
] ?>
<div class="workout-rules-popup popup">
  <div class="workout-rules-popup__cnt popup__cnt">
    <button type="button" class="workout-rules-popup__close popup__close"></button>
    <span class="workout-rules-popup__title popup__title">Правила выполнения тренировок</span>
    <div class="workout-rules-popup__important popup__important">
      <span class="popup__important-title lazy" data-src="#">Важно!</span>
      <span class="popup__important-descr">Перед началом тренировок обязательно ознакомьтесь с информацией в этом блоке и в блоке перед программами тренировок. Это избавит вас от возможных ошибок и вопросов в процессе работы.</span>
    </div>
    <div class="workout-rules-popup__important popup__important">
      <span class="popup__important-title lazy" data-src="#">Важно!</span>
      <span class="popup__important-descr">План тренировок достаточно интенсивный и рассчитан на оптимальную нагрузку, которая позволит вам добиться желаемых результатов. Выполнять более 3-х силовых тренировок в неделю не стоит. В противном случае это приведёт к перетренированности. Если время и силы позволяют вам выполнять больше тренировок, то в оставшиеся дни выполняйте кардио тренировки.</span>
    </div> <?php
    foreach ( $cnt as $c ) {
      popup_content( [
        'title' => $c['title'],
        'tags' => $c['tags']
      ] );
    } ?>
  </div>
</div>