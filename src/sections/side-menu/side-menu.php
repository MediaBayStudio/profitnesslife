<div class="side-menu"> <?php
  wp_nav_menu( [
    'theme_location'  => 'side_menu',
    'container'       => 'nav',
    'container_class' => 'side-menu__nav',
    'menu_class'      => 'side-menu__nav-list',
    'items_wrap'      => '<ul class="%2$s">%3$s</ul>'
  ] ) ?>
  <a href="<?php echo wp_logout_url() ?>" class="side-menu__logout"><picture class="side-menu__logout-icon"><source type="image/svg+xml" srcset="<?php echo $template_directory_uri ?>/img/icon-logout.svg" media="(min-width:1023.98px)"><img src="#" alt="#"></picture>Выход</a>
</div>