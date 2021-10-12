<aside class="menu" style="display:none">
  <div class="menu__cnt container">
    <div class="menu__hdr">
      <a href="<?php echo $site_url ?>" class="menu__logo">
        <img src="<?php echo $logo_url ?>" alt="Логотип Proftnesslife" class="menu__logo-img">
      </a>
      <button type="button" class="menu__close">
        <svg width="12" height="12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20" class="menu__close-svg"><path stroke="currentColor" d="M20.4455.353553L1.35359 19.4454M19.7384 19.4455L.646481.353591" class="menu__close-path"/></svg>
      </button>
    </div> <?php
    wp_nav_menu( [
      'theme_location'  => 'header_menu',
      'container'       => 'nav',
      'container_class' => 'menu__nav',
      'menu_class'      => 'menu__nav-list',
      'items_wrap'      => '<ul class="%2$s">%3$s</ul>'
    ] ) ?>
    <button type="button" class="menu__login btn btn-green">Личный кабинет</button>
    <img src="#" alt="image" data-src="<?php echo $template_directory_uri ?>/img/menu-img.jpg" class="menu__img lazy">
  </div>
</aside>