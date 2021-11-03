<section class="index-chat container sect" id="chat">
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
  <img src="#" alt="image" data-src="<?php echo $template_directory_uri ?>/img/index-chat-img.svg" class="index-chat__img lazy">
</section>