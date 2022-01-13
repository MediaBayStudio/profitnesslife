<?php
global $typeform;

if ( ! $typeform || $typeform == 'sign' )
  $f_sign = 'style="display:block;"';
?>

<div class="form-tab-rcl" id="login-form-rcl" <?php echo $f_sign; ?>>
    <div class="form_head">
      <span class="form_head_title">Войти в личный кабинет</span>
      <p class="form_head_descr">Данные для входа в личный кабинет выдаются каждому участнику марафона после оплаты</p>
        <!-- <div class="form_auth form_active"><?php #_e( 'Authorization', 'wp-recall' ); ?></div> -->
    </div>

    <div class="form-block-rcl"><?php #rcl_notice_form( 'login' ); ?></div>

  <?php $user_login  = (isset( $_REQUEST['user_login'] )) ? wp_strip_all_tags( $_REQUEST['user_login'], 0 ) : ''; ?>
  <?php $user_pass   = (isset( $_REQUEST['user_pass'] )) ? wp_strip_all_tags( $_REQUEST['user_pass'], 0 ) : ''; ?>

  <!-- <form class="form-sign" action="<?php #rcl_form_action( 'login' ); ?>" method="post"> -->
  <form class="form-sign" method="POST" onkeyup="if (event.keyCode === 13) {this.submit()}">
      <label class="form-sign__field field">
          <input required type="text" placeholder="<?php _e( 'Login', 'wp-recall' ); ?>" value="<?php echo $user_login; ?>" name="user_login" class="field__inp">
      </label>
      <label class="form-sign__field field">
          <input required type="password" placeholder="<?php _e( 'Password', 'wp-recall' ); ?>" value="<?php echo $user_pass; ?>" name="user_pass" class="field__inp">
      </label>
      <!-- <div class="form-block-rcl"> -->
    <?php #do_action( 'login_form' ); ?>

          <!-- <div class="default-field rcl-field-input type-checkbox-input">
              <div class="rcl-checkbox-box">
                  <input type="checkbox" id="chck_remember" class="checkbox-custom" value="1" name="rememberme">
                  <label class="block-label" for="chck_remember"><?php #_e( 'Remember', 'wp-recall' ); ?></label>
              </div>
          </div> -->
      <!-- </div> -->
      <div class="form-block-rcl">
    <?php
    echo rcl_get_button( array(
      'label'    => __( 'Entry', 'wp-recall' ),
      'submit'   => true,
      'fullwidth'  => true,
      'size'     => 'medium',
      'icon'     => 'fa-sign-in',
      'class'    => 'link-tab-form'
    ) );
    ?>
          <!-- <a href="#" class="link-remember-rcl link-tab-rcl "><?php #_e( 'Lost your Password', 'wp-recall' ); // Забыли пароль            ?>?</a> -->
    <?php echo wp_nonce_field( 'login-key-rcl', 'login_wpnonce', true, false ); ?>
          <input type="hidden" name="redirect_to" value="<?php rcl_referer_url( 'login' ); ?>">
      </div>
  </form>
</div>
