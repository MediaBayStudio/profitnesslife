<section class="index-hero container"<?php echo $section_id ?>>
  <picture class="index-hero__pic">
    <source type="image/svg+xml" media="(max-width:575.98px)" srcset="<?php echo $template_directory_uri ?>/img/hero-img.mobile.svg">
    <source type="image/svg+xml" media="(min-width:575.98px) and (max-width:1023.98px)" srcset="<?php echo $template_directory_uri ?>/img/hero-img.tablet.svg">
    <source type="image/svg+xml" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="<?php echo $template_directory_uri ?>/img/hero-img.laptop.svg">
    <img src="<?php echo $template_directory_uri ?>/img/hero-img.svg" alt="Марафон стройности" class="index-hero__img">
  </picture>
  <h1 class="index-hero__title sect-h1"><?php echo $section['title'] ?></h1>
  <p class="index-hero__descr"><?php echo $section['descr'] ?></p>
  <button type="button" class="btn btn-green index-hero__btn" data-scroll-target=".index-form-sect__title">Хочу начать!</button> <?php
  $start_date = mb_strtoupper( 'старт ' . $section['date'] ) . '&nbsp;&bull;&nbsp;' ?>
  <span class="index-hero__marquee" id="elementId" data-date="<?php echo $start_date ?>"><?php echo $start_date ?></span>
</section>