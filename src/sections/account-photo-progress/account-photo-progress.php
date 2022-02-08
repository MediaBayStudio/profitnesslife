<?php
if ( $user_data['target'] !== 'Поддержание веса' ) : ?>
  <section class="photo-progress-sect">
    <h2 class="photo-progress-sect__title">Фото-прогресс</h2>
    <div class="photo-progress-slider" id="photo-progress-slider">
      <form action="<?php echo $site_url ?>/wp-admin/admin-ajax.php" name="photo-progress-form" class="photo-progress-form" title="Изменить фото">
        <input type="file" name="photo" accept="image/jpeg,image/png" class="photo-progress-add">
      </form> <?php
      foreach ( $user_data['photo_progress'] as $img ) :
        $webp = $upload_baseurl . get_post_meta( $img['ID'], 'webp' )[0] ?>
        <picture class="photo-progress-pic lazy">
          <source type="image/webp" srcset="#" data-srcset="<?php echo $webp ?>">
          <img src="#" alt="Фото" data-src="<?php echo $img['url'] ?>" class="photo-progress-img">
        </picture> <?php
      endforeach ?>
      <div class="photo-progress-slider__nav"></div>
    </div>
  </section> <?php
endif ?>