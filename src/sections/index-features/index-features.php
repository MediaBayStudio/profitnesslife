<section class="index-features container sect"<?php echo $section_id ?>>
  <h2 class="index-features__title sect-title"><?php echo $section['title'] ?></h2>
  <p class="index-features__descr sect-descr"><?php echo $section['descr'] ?></p>
  <ul class="index-features__features"> <?php
    foreach ( $section['features'] as $feature ) : ?>
    <li class="index-features__feature">
      <figure class="index-features__feature-fig">
        <img src="#" alt="#" data-src="<?php echo $feature['img']['url'] ?>" class="index-features__feature-img lazy">
      </figure>
      <p class="index-features__feature-descr"><?php echo $feature['descr'] ?></p>
    </li> <?php
    endforeach ?>
  </ul>
  <div class="index-features__nav"></div>
</section>