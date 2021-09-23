<section class="index-chat container sect"<?php echo $section_id ?>>
  <div class="index-chat__text">
    <h2 class="index-chat__title sect-title"><?php echo $section['title'] ?></h2>
    <p class="index-chat__descr"><?php echo $section['descr'] ?></p>
    <ul class="index-chat__list"> <?php
      foreach ( $section['links'] as $link ) : ?>
        <li class="index-chat__list-item">
          <a href="<?php echo $link['link'] ?>" target="_blank" class="index-chat__link">
            <img src="#" alt="#" data-src="<?php echo $link['img']['url'] ?>" class="index-chat__link-img lazy">
          </a>
        </li> <?php
    endforeach ?>
    </ul>
  </div>
  <picture class="index-chat__pic lazy"> <?php
    $webp = get_post_meta( $section['img']['id'], 'webp' )[0];
    if ( $webp ) : ?>
      <source type="image/webp" srcset="#" data-srcset="<?php echo $upload_baseurl . $webp ?>"> <?php
    endif ?>
    <img src="#" alt="#" data-src="<?php echo $section['img']['url'] ?>" class="index-chat__img">
  </picture>
</section>