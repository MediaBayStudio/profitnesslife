<section class="index-hero container"<?php echo $section_id ?>>
  <picture class="index-hero__pic">
    <source type="image/webp" srcset="<?php echo $template_directory_uri ?>/img/hero-img.mobile.webp">
    <img src="<?php echo $template_directory_uri ?>/img/hero-img.mobile.png" alt="#" class="index-hero__img">
  </picture>
  <h1 class="index-hero__title sect-h1"><?php echo $section['title'] ?></h1>
  <p class="index-hero__descr"><?php echo $section['descr'] ?></p>
  <button type="button" class="btn btn-green index-hero__btn">Хочу начать!</button> <?php
  $start_date = mb_strtoupper( 'старт ' . $section['date'] ) . '&nbsp;&bull;&nbsp;' ?>
  <span class="index-hero__marquee" id="marquee" data-date="<?php echo $start_date ?>">
  </span>
</section>