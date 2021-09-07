<?php

if ( !$questionnaire_complete ) : ?>
  <section class="questionnaire-incomplete-section container" id="questionnaire-incomplete-section">
    <h1 class="questionnaire-incomplete-section__title sect-h1"><?php echo $section['title'] ?></h1>
    <p class="questionnaire-incomplete-section__descr"><?php echo $section['descr'] ?></p>
    <form action="" method="POST" id="questionnaire-form" class="questionnaire-incomplete-section__form questionnaire-form">
      <div class="questionnaire-form__top">
        <button class="questionnaire-form__back">Назад</button>
        <div class="questionnaire-form__count">
          Вопрос <span class="questionnaire-form__count-current">1</span> из <span class="questionnaire-form__count-total">10</span>
        </div>
        <div class="questionnaire-form__progress">
          <div class="questionnaire-form__progress-line"></div>
        </div>
      </div>

      <div class="questionnaire-form__step">
        <span class="questionnaire-form__step-title">Ваша цель</span>
        <div class="questionnaire-form__step-fields">
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-1.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="target" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Потеря веса</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="target" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Набор мышечной массы</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-3.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="target" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Поддержание веса</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step">
        <span class="questionnaire-form__step-title">Пол</span>
        <div class="questionnaire-form__step-fields">
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-1.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="sex" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Мужской</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="sex" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Женский</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step">
        <div class="questionnaire-form__step-fields">
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Текущий вес</span>
            <input type="number" name="current-weight" placeholder="70" maxlength="3" min="40" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">кг</span>
          </label>
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Желаемый вес</span>
            <input type="number" name="target-weight" placeholder="60" maxlength="3" min="40" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">кг</span>
          </label>
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Рост</span>
            <input type="number" name="height" placeholder="170" maxlength="3" min="120" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">см</span>
          </label>
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Возраст</span>
            <input type="number" name="age" placeholder="30" maxlength="2" min="18" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">лет</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step">
        <span class="questionnaire-form__step-title">Стаж тренировок</span>
        <div class="questionnaire-form__step-fields">
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-1.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="training-experience" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Новичок</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="training-experience" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Иногда тренируюсь</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="training-experience" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Тренируюсь регулярно</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step">
        <span class="questionnaire-form__step-title">Ежедневная физическая активность</span>
        <div class="questionnaire-form__step-fields">
          <label class="questionnaire-form__radio">
            <input type="radio" name="activity" class="questionnaire-form__radio-input">
            <div class="questionnaire-form__radio-pseudo-input"></div>
            <span class="questionnaire-form__radio-text">Сидячий, малоподвижный</span>
            <p class="questionnaire-form__radio-descr">Без физических нагрузок, передвижения на транспорте, сидячий образ жизни. Около 2000–3000 шагов в день.</p>
          </label>
          <label class="questionnaire-form__radio">
            <input type="radio" name="activity" class="questionnaire-form__radio-input">
            <div class="questionnaire-form__radio-pseudo-input"></div>
            <span class="questionnaire-form__radio-text">Средняя активность</span>
            <p class="questionnaire-form__radio-descr">Умеренные физические нагрузки на работе или в целом, сидячий образ жизни, но периодически занимающийся спортом 2–3 раза в неделю. Около 4000–6000 шагов в день.</p>
          </label>
          <label class="questionnaire-form__radio">
            <input type="radio" name="activity" class="questionnaire-form__radio-input">
            <div class="questionnaire-form__radio-pseudo-input"></div>
            <span class="questionnaire-form__radio-text">Высокая активность</span>
            <p class="questionnaire-form__radio-descr">Физическая занятость на работе. Тренировки 3–4 раза в неделю. Около 7000–10000 шагов в день.</p>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step">
        <span class="questionnaire-form__step-title">Выберите продуктые, которые необходимо исключить из вашего меню</span>
        <p class="questionnaire-form__step-descr">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню, и мы не предложим вам блюда с этими продуктами</p>
        <div class="questionnaire-form__step-fields">
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-1.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Молочные продукты</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Мясо</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Рыба</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Глютен</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Морепродукты (кальмары/креветки)</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Яйца</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Орехи</span>
          </label>
          <label class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <input type="radio" name="categories" class="questionnaire-form__radio-card-input">
            <span class="questionnaire-form__radio-card-text">Мёд</span>
          </label>
        </div>
      </div>

    </form>
  </section> <?php
endif ?>