<section class="index-chat container sect" id="chat">
  <div class="index-chat__text">
    <h2 class="index-chat__title sect-title"><?php echo $section['title'] ?></h2>
    <p class="index-chat__descr"><?php echo $section['descr'] ?></p>
    <ul class="index-chat__list"> <?php
      #foreach ( $section['links'] as $link ) : ?>
        <li class="index-chat__list-item">
          <a href="<?php echo $manager_link_viber ?>" target="_blank" class="index-chat__link">
            <img src="#" alt="viber icon" data-src="<?php echo $template_directory_uri ?>/img/icon-viber.svg" class="index-chat__link-img lazy">
          </a>
        </li>
        <li class="index-chat__list-item">
          <a href="<?php echo $manager_link_whatsapp ?>" target="_blank" class="index-chat__link">
            <img src="#" alt="whatsapp icon" data-src="<?php echo $template_directory_uri ?>/img/icon-whatsapp.svg" class="index-chat__link-img lazy">
          </a>
        </li> <?php
    #endforeach ?>
    </ul>
  </div>
  <img src="#" alt="image" data-src="<?php echo $template_directory_uri ?>/img/index-chat-img.svg" class="index-chat__img lazy">
</section>