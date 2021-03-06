<section class="index-instagram container sect"<?php echo $section_id ?>> <?php
  $last_update_time = get_option( 'contacts_instagram_last_update' );
  $main_insta = $section['main_insta'] ?>
  <h2 class="index-instagram__title sect-title"><?php echo $section['title'] ?></h2>
  <a href="https://www.instagram.com/<?php echo $main_insta ?>" target="_blank" class="index-instagram__main-link">&#64;<?php echo $main_insta ?></a>
  <div class="index-instagram__posts instagram-posts lazy" data-src="#"> <?php

    // $current_time_ms = time();

    // Раз в 3 дня
    // $update_period_ms = 3 * 24 * 60 * 60;

    // if ( !$last_update_time ) {
      // $instagram_posts = get_instagram_posts( $main_insta );
      // refresh_intsagram_update_date( $current_time_ms );
    // } else {
      // if ( strtotime( $last_update_time ) + $update_period_ms <= $current_time_ms ) {
        // $instagram_posts = get_instagram_posts( $main_insta );
        // refresh_intsagram_update_date( $current_time_ms );
      // } else {
        $instagram_posts = get_posts( [
          'post_type' => 'instagram_post',
          'numberposts' => 4,
          'order' => 'ASC'
        ] );
      // }
    // }

    if ( $instagram_posts ) {
      foreach ( $instagram_posts as $instagram_post ) :
        $instagram_post_link = get_field( 'link', $instagram_post->ID );
        if ( $instagram_post_link ) {
          $start_tag = 'a href="' . $instagram_post_link . '" target="_blank"';
          $end_tag = 'a';
        } else {
          $start_tag = 'div';
          $end_tag = 'div';
        }
        ?>
        <<?php echo $start_tag ?> class="index-instagram__post instagram-post">
        <img src="#" alt="instagram post image" data-src="<?php the_field( 'img_link', $instagram_post->ID ) ?>" class="instagram-post__img lazy">
        </<?php echo $end_tag ?>> <?php
      endforeach;
    } ?>
  </div>
  <span class="index-instagram__subtitle"><?php echo $section['title_2'] ?></span>
  <div class="index-instagram__links"> <?php
    foreach ( $section['other_insta'] as $insta ) : ?>
      <a href="https://www.instagram.com/<?php echo $insta['link'] ?>" target="_blank" class="index-instagram__link lazy" data-src="#">&#64;<?php echo $insta['link'] ?></a> <?php
    endforeach ?>
  </div>
</section>