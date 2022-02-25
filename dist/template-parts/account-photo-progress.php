<?php
if ( $user_data['target'] !== 'Поддержание веса' ) : ?>
  <section class="photo-progress-sect">
    <h2 class="photo-progress-sect__title">Фото-прогресс</h2>
    <div class="photo-progress-slider" id="photo-progress-slider">
      <form action="<?php echo $site_url ?>/wp-admin/admin-ajax.php" name="photo-progress-form" class="photo-progress-form" title="Добавить фото">
        <input type="file" name="photo" accept="image/jpeg,image/png" class="photo-progress-add">
      </form> <?php
      foreach ( $user_data['photo_progress'] as $img ) : ?>
        <picture class="photo-progress-pic lazy"> <?php
          $webp = image_get_intermediate_size( $img['ID'], 'photo_progress_webp' )['url'];
          $url = image_get_intermediate_size( $img['ID'], 'photo_progress' )['url'];
          if ( !$url ) {
            $url = $img['url'];
          }
          if ( !$webp ) {
            $webp = image_get_intermediate_size( $img['ID'], 'webp' )['url'];
          }
          if ( $webp ) : ?>
            <source type="image/webp" srcset="#" data-srcset="<?php echo $webp ?>"> <?php
          endif ?>
          <img src="#" alt="Фото" data-src="<?php echo $url ?>" class="photo-progress-img">
        </picture> <?php
      endforeach ?>
      <div class="photo-progress-slider__nav"></div>
    </div>
  </section> <?php
endif ?>