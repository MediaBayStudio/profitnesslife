<div class="login-popup popup">
  <div class="login-popup__cnt popup__cnt">
    <button type="button" class="login-popup__close popup__close"></button> <?php
    if ( !is_user_logged_in() ) {
      require  $template_directory . '/components/form-sign.php';
    } ?>
  </div>
</div>