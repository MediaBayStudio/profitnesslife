<div class="reset-questionnaire-popup popup">
  <div class="reset-questionnaire-popup__cnt popup__cnt">
    <button type="button" class="reset-questionnaire-popup__close popup__close"></button>
    <p class="reset-questionnaire-popup__title popup__title">Сброс анкеты</p>
    <p class="reset-questionnaire-popup__descr popup__descr">Вы уверены, что хотите сбросить анкету? Сделать это можно только один раз</p>
    <div class="reset-questionnaire-popup__buttons">
      <button type="button" class="reset-questionnaire-popup__cancel-btn btn btn-ol">Отмена</button>
      <button type="button" class="reset-questionnaire-popup__confirm-btn btn btn-green" data-user="<?php echo $user_id ?>" onclick="resetQuestionnaire(true)"><span class="loader loader-white"><span class="loader__circle"></span></span>Сбросить</button>
    </div>
  </div>
</div>