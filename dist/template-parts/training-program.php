<?php 

$stretching = get_posts( [
  'post_type' => 'workout',
  'meta_key' => 'stretching'
] );

// По умолчанию доступна всегда 1 неделя тренировок
$workout = [
  [
    'day_1' => $user_data['workout_week_1']['day_1'],
    'day_2' => $user_data['workout_week_1']['day_2'],
    'day_3' => $user_data['workout_week_1']['day_3']
  ]
];


// В зависимости от времени делаем доступными другие недели
#if ( $current_time > $first_week_end_time ) {
  $workout[] = [
    'day_1' => $user_data['workout_week_2']['day_1'],
    'day_2' => $user_data['workout_week_2']['day_2'],
    'day_3' => $user_data['workout_week_2']['day_3']
  ];
#}

#if ( $current_time > $second_week_end_time ) {
  $workout[] = [
    'day_1' => $user_data['workout_week_3']['day_1'],
    'day_2' => $user_data['workout_week_3']['day_2'],
    'day_3' => $user_data['workout_week_3']['day_3']
  ];


#} ?>

<section class="workout-sect"> <?php
  $stretching_count = 0;
  $week_count = 1;
  foreach ( $workout as $weeks ) :
    $workout_count = 1 ?>
    <div class="workout-block">
      <span class="workout-block__title">Неделя <?php echo $week_count ?></span> <?php
      foreach ( $weeks as $week ) :
        $duration = 0; // длительность для каждого видео
        $data = []; // массив в data-атрибут
        $i = 0;
        if ( $stretching_count === 4 ) {
          $stretching_count = 0;
        }
        $week[] = $stretching[ $stretching_count ];
        $stretching_count++;
        foreach ( $week as $daily_workout ) {
          $fields = get_fields( $daily_workout->ID );
          if ( $i === 1 ) {
            $type = get_term( $fields['category'][0] )->name;
            $src = $template_directory_uri . '/video/' . $fields['video'];
            $poster = $template_directory_uri . '/video/' . str_replace( '.mp4', '.jpg', $fields['video'] );
          }
          switch ( $user_data['training_experience'] ) {
            case 'Новичок':
              $reps = [
                'number' => $fields['reps']['newbie_number'],
                'units' => $fields['reps']['newbie_what']['label']
              ];
              break;
            case 'Иногда тренируюсь':
              $reps = [
                'number' => $fields['reps']['middle_number'],
                'units' => $fields['reps']['middle_what']['label']
              ];
              break;
            case 'Тренируюсь регулярно':
              $reps = [
                'number' => $fields['reps']['expert_number'],
                'units' => $fields['reps']['expert_what']['label']
              ];
              break;
          }
          $duration += (int)$fields['video_duration'];
          $data[] = [
            'duration' => gmdate( 'i:s', $fields['video_duration'] ),
            'src' => $template_directory_uri . '/video/' . $fields['video'],
            'title' => $daily_workout->post_title,
            'reps' => $reps
          ];
          $i++;
        } ?>
        <div class="workout" data-videos="<?php echo htmlspecialchars( json_encode( $data ) ) ?>">
          <span class="workout__title">Тренировка <?php echo $workout_count ?></span>
          <div class="workout__body">
            <span class="workout__play"></span>
            <video class="workout__preview" preload="none" poster="<?php echo $poster ?>">
              <source src="<?php echo $src ?>" type="video/mp4">
            </video>
            <span class="workout__duration" style="display:none;"><?php echo round( $duration / 60 ) ?> мин</span>
            <span class="workout__name"> <?php
            switch ( $type ) {
              case 'Руки':
                echo 'Тренировка на верхнюю группу мышц';
                break;
              case 'Ноги':
                echo 'Тренировка на нижнюю группу мышц';
                break;
              default:
                echo 'Тренировка на все группы мышц';
                break;
            } ?> </span>
          </div>
        </div> <?php
        $workout_count++;
      endforeach ?>
    </div> <?php
    $week_count++;
  endforeach ?>
</section>