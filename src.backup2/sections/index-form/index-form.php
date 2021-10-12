<section class="index-form-sect container sect"<?php echo $section_id ?>>
  <div class="index-form-sect__text">
    <h2 class="index-form-sect__title sect-title"><?php echo $section['title'] ?></h2>
    <p class="index-form-sect__descr"><?php echo $section['descr'] ?></p> <?php
    echo do_shortcode( '[contact-form-7 id="5" html_class="index-form" html_id="index-form"]' ) ?>
  </div>
  <img src="#" alt="image" data-src="<?php echo $template_directory_uri ?>/img/index-form-img.svg" class="index-form-sect__img lazy">
</section>