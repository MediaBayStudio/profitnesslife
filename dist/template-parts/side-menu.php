<div class="side-menu">
  <div class="side-menu__cnt">
    <div class="side-menu__hdr">
      <a href="<?php echo $site_url ?>" class="side-menu__logo"><img src="<?php echo $logo_url ?>" alt="#" class="side-menu__logo-img"></a>
      <button type="button" class="side-menu__close"></button>
    </div> <?php
    wp_nav_menu( [
      'theme_location'  => 'side_menu',
      'container'       => 'nav',
      'container_class' => 'side-menu__nav',
      'menu_class'      => 'side-menu__nav-list',
      'items_wrap'      => '<ul class="%2$s">%3$s</ul>'
    ] ) ?>
    <a href="<?php echo wp_logout_url() ?>" class="side-menu__logout"><picture class="side-menu__logout-pic"><source type="image/svg+xml" srcset="<?php echo $template_directory_uri ?>/img/icon-logout.svg" media="(min-width:1023.98px)"><img src="#" alt="#" class="side-menu__logout-img"></picture>Выход</a>
  </div>
</div>