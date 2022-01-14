<?php 
  $price = get_field( 'price', 4122 ) ?>
<section class="pay-hero active">
  <picture class="pay-hero__pic">
    <source srcset="<?php echo $template_directory_uri ?>/img/menu-img.svg" type="image/svg+xml" media="(min-width:767.98px)">
    <img src="#" alt="#">
  </picture>
  <h1 class="pay-hero__title sect-title">Страница оплаты марафона</h1>
  <form class="pay-hero__form pay-form" id="pay-form">
    <input type="text" name="username" class="cf7-form-field">
    <input type="text" name="price" class="cf7-form-field" value="<?php echo preg_replace( '/[^0-9]/', '', $price ) ?>">
    <label class="field field_name">
      <input type="text" name="name" class="field__inp">
      <span class="field__text">Имя</span>
    </label>
    <label class="field field_surname">
      <input type="text" name="surname" class="field__inp">
      <span class="field__text">Фамилия</span>
    </label>
    <label class="field field_tel">
      <input type="text" name="tel" class="field__inp">
      <span class="field__text">Телефон</span>
    </label>
    <label class="field field_email">
      <input type="text" name="email" class="field__inp">
      <span class="field__text">E-mail</span>
    </label>
    <div class="form-bottom">
      <div class="form-price-block">
        <span>К оплате:</span>
        <span class="form-price"><?php echo $price ?> &#8381;</span>
      </div>
    </div>
    <button name="submit" class="form-btn btn btn-green">Оплатить</button>
  </form>
</section>
<section class="pay-hero" id="failure-pay">
  <img src="<?php echo $template_directory_uri ?>/img/icon-failure-pay.svg" alt="failure pay" class="pay-hero__img">
  <h2 class="pay-hero__title sect-title">Ошибка оплаты</h2>
  <p class="pay-hero__descr">Платёж не прошёл. Попробуйте повторить попытку или обратитесь к менеджеру.</p>
  <button type="button" class="pay-hero__btn btn btn-ol">Повторить попытку</button>
  <div class="pay-hero__manager-block">
    <span>Связаться с менеджером:</span>
    <a href="<?php echo $whatsapp ?>" target="_blank" class="pay-hero__manager-whatsapp lazy" data-src="#"></a>
  </div>
</section>
<section class="pay-hero" id="success-pay">
  <img src="<?php echo $template_directory_uri ?>/img/icon-success-pay.svg" alt="success pay" class="pay-hero__img">
  <h2 class="pay-hero__title sect-title">Оплата прошла успешно!</h2>
  <p class="pay-hero__descr">Мы свяжемся с вами в ближайшее время, и вы получите логин и пароль для входа в личный кабинет.</p>
  <a href="<?php echo $site_url ?>" class="pay-hero__btn btn btn-ol">На главную</a>
</section>
<script async src="https://widget.cloudpayments.ru/bundles/cloudpayments.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let repeatBtn = document.querySelector('#failure-pay .pay-hero__btn'),
      parent = repeatBtn.parentElement,
      form = document.forms['pay-form'];

    repeatBtn.addEventListener('click', function() {
      parent.classList.remove('active');
      parent.previousElementSibling.classList.add('active');
      form.reset();
      for (let i = 0, len = form.elements.length; i < len; i++) {
        form.elements[i].classList.remove('filled');
      }
    });
  });
</script>