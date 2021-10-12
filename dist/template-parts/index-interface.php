<section class="index-interface container sect"<?php echo $section_id ?>>
  <h2 class="index-interface__title sect-title"><?php echo $section['title'] ?></h2>
  <div class="index-interface__screens">
    <div class="index-interface__screens-tabs">
      <div class="index-interface__screens-tab-background"></div>
      <button type="button" class="index-interface__screens-tab active">Питание</button>
      <button type="button" class="index-interface__screens-tab">Тренировки</button>
      <button type="button" class="index-interface__screens-tab">Поддержка</button>
      <button type="button" class="index-interface__screens-tab">Прогресс</button>
    </div>
    <div class="index-interface__screen nutrition-screen active">
      <picture class="nutrition-screen__pic lazy">
        <source type="image/webp" media="(max-width:767.98px)" srcset="#" data-srcset="<?php #echo $template_directory_uri ?>/img/interface-1-mobile.webp">
        <source type="image/webp" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-1-tablet.webp">
        <source type="image/png" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-1-tablet.png">
        <source type="image/webp" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-1-laptop.webp">
        <source type="image/png" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-1-laptop.png">
        <source type="image/webp" media="(min-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-1.webp">
        <img src="#" alt="Интерфейс экрана питание" data-src="<?php echo $template_directory_uri ?>/img/interface-1.png" class="nutrition-screen__img">
      </picture>
    </div>
    <div class="index-interface__screen training-screen">
      <picture class="training-screen__pic lazy">
        <source type="image/webp" media="(max-width:767.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-2-mobile.webp">
        <source type="image/png" media="(max-width:767.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-2-mobile.png">
        <source type="image/webp" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-2-tablet.webp">
        <source type="image/png" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-2-tablet.png">
        <source type="image/webp" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-2-laptop.webp">
        <source type="image/png" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-2-laptop.png">
        <source type="image/webp" media="(min-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-2.webp">
        <img src="#" alt="Интерфейс экрана тренировки" data-src="<?php echo $template_directory_uri ?>/img/interface-2.png" class="training-screen__img">
      </picture>
    </div>
    <div class="index-interface__screen support-screen">
      <picture class="support-screen__pic lazy">
        <source type="image/webp" media="(max-width:767.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-3-mobile.webp">
        <source type="image/png" media="(max-width:767.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-3-mobile.png">
        <source type="image/webp" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-3-tablet.webp">
        <source type="image/png" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-3-tablet.png">
        <source type="image/webp" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-3-laptop.webp">
        <source type="image/png" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-3-laptop.png">
        <source type="image/webp" media="(min-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-3.webp">
        <img src="#" alt="Интерфейс экрана поддержки" data-src="<?php echo $template_directory_uri ?>/img/interface-3.png" class="support-screen__img">
      </picture>
    </div>
    <div class="index-interface__screen progress-screen">
      <picture class="progress-screen__pic lazy">
        <source type="image/webp" media="(max-width:767.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-4-mobile.webp">
        <source type="image/png" media="(max-width:767.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-4-mobile.png">
        <source type="image/webp" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-4-tablet.webp">
        <source type="image/png" media="(min-width:767.98px) and (max-width:1023.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-4-tablet.png">
        <source type="image/webp" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-4-laptop.webp">
        <source type="image/png" media="(min-width:1023.98px) and (max-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-4-laptop.png">
        <source type="image/webp" media="(min-width:1279.98px)" srcset="#" data-srcset="<?php echo $template_directory_uri ?>/img/interface-4.webp">
        <img src="#" alt="Интерфейс экрана прогресса" data-src="<?php echo $template_directory_uri ?>/img/interface-4.png" class="progress-screen__img">
      </picture>
    </div>
  </div>
</section>