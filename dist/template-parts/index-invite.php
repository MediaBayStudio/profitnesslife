<section class="index-invite container sect" id="invite-sect">
  <img src="#" alt="image" data-src="<?php echo $template_directory_uri ?>/img/invite-img.svg" class="index-invite__img lazy">
  <div class="index-invite__text"> <?php
    $price = get_field( 'price', 4122 ) ?>
    <h2 class="index-invite__title sect-title"><?php echo "{$section['title_part_1']} {$price} {$section['title_part_2']}" ?></h2>
    <p class="index-invite__descr sect-descr"><?php echo $section['descr'] ?></p>
    <button type="button" class="index-invite__btn btn btn-green" data-scroll-target=".index-form-sect__title">Хочу начать!</button>
  </div>
</section>