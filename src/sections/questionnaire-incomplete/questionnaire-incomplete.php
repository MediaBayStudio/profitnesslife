<a href="<?php echo wp_logout_url() ?>">Выход</a>
<?php
if ( !$questionnaire_complete ) : ?>
  <section class="questionnaire-incomplete-section" id="questionnaire-incomplete-section">
    <h1 class="questionnaire-incomplete-section__title sect-h1"><?php echo $section['title'] ?></h1>
    <p class="questionnaire-incomplete-section__descr"><?php echo $section['descr'] ?></p>
    <form action="" method="POST" novalidate id="questionnaire-form" class="questionnaire-incomplete-section__form questionnaire-form">
      <div class="questionnaire-form__top">
        <button type="button" class="questionnaire-form__back disabled">Назад</button>
        <div class="questionnaire-form__count">
          Вопрос <span class="questionnaire-form__count-current">1</span> из <span class="questionnaire-form__count-total">9</span>
        </div>
        <div class="questionnaire-form__progress">
          <div class="questionnaire-form__progress-line"></div>
        </div>
      </div>

      <div class="questionnaire-form__step">
        <span class="questionnaire-form__step-title">Ваша цель</span>
        <div class="questionnaire-form__step-fields"> <?php
          $fields_data = [
            [
              'title' => 'Потеря веса',
              'name' => 'target',
              'id' => 'weight-loss',
              'url' => 'questionnaire-weight-loss-img.png'
            ],
            [
              'title' => 'Набор мышечной массы',
              'name' => 'target',
              'id' => 'weight-gain',
              'url' => 'questionnaire-weight-gain-img.png'
            ],
            [
              'title' => 'Поддержание веса',
              'name' => 'target',
              'id' => 'weight-maintaining',
              'url' => 'questionnaire-weight-maintaining-img.png'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-target',
              'title' => $field_data['title'],
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
                'alt' => 'icon',
                'width' => 82,
                'height' => 82
              ],
              'radio' => [
                'name' => $field_data['name'],
                'id' => $field_data['id'],
                'value' => $field_data['id']
              ]
            ] );
          } ?>
        </div>
      </div>

      <div class="questionnaire-form__step hide">
        <span class="questionnaire-form__step-title">Пол</span>
        <div class="questionnaire-form__step-fields"> <?php
          $fields_data = [
            [
              'title' => 'Мужской',
              'name' => 'sex',
              'id' => 'male',
              'url' => 'questionnaire-male-img.png',
            ],
            [
              'title' => 'Женский',
              'name' => 'sex',
              'id' => 'female',
              'url' => 'questionnaire-female-img.png',
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-sex',
              'title' => $field_data['title'],
              'lazyload' => true,
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
                'alt' => 'icon',
                'width' => 110,
                'height' => 100
              ],
              'radio' => [
                'name' => $field_data['name'],
                'id' => $field_data['id'],
                'value' => $field_data['id']
              ]
            ] );
          }  ?>
        </div>
      </div>

      <div class="questionnaire-form__step extra-step hide" data-question="sex" data-answer="female">
        <span class="questionnaire-form__step-title">Есть ли у вас дети на грудном вскармливании?</span>
        <div class="questionnaire-form__step-fields"> <?php
          $fields_data = [
            [
              'title' => 'Да',
              'name' => 'children',
              'value' => 'y',
              'id' => 'children-y'
            ],
            [
              'title' => 'Нет',
              'name' => 'children',
              'value' => 'n',
              'id' => 'children-n'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-children',
              'title' => $field_data['title'],
              'radio' => [
                'name' => $field_data['name'],
                'id' => $field_data['id'],
                'value' => $field_data['value']
              ]
            ] );
          }  ?>
        </div>
      </div>

      <div class="questionnaire-form__step with-next-button hide">
        <div class="questionnaire-form__step-fields">
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Текущий вес</span>
            <input type="number" name="current-weight" required placeholder="70" min="40" max="200" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">кг</span>
          </label>
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Желаемый вес</span>
            <input type="number" name="target-weight" required placeholder="60" min="40" max="200" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">кг</span>
          </label>
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Рост</span>
            <input type="number" name="height" required placeholder="170" min="120" max="250" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">см</span>
          </label>
          <label class="questionnaire-form__field">
            <span class="questionnaire-form__field-text">Возраст</span>
            <input type="number" name="age" required placeholder="30" min="18" max="70" class="questionnaire-form__field-input">
            <span class="questionnaire-form__field-units">лет</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step hide">
        <span class="questionnaire-form__step-title">Стаж тренировок</span>
        <div class="questionnaire-form__step-fields"> <?php
          $fields_data = [
            [
              'title' => 'Новичок',
              'name' => 'training-experience',
              'id' => 'newbie',
              'url' => 'questionnaire-training-experience-newbie-img.png'
            ],
            [
              'title' => 'Иногда тренируюсь',
              'name' => 'training-experience',
              'id' => 'middle',
              'url' => 'questionnaire-training-experience-middle-img.png'
            ],
            [
              'title' => 'Тренируюсь регулярно',
              'name' => 'training-experience',
              'id' => 'expert',
              'url' => 'questionnaire-training-experience-expert-img.png'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-training-experience',
              'title' => $field_data['title'],
              'lazyload' => true,
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
                'width' => 100,
                'height' => 95
              ],
              'radio' => [
                'name' => $field_data['name'],
                'id' => $field_data['id'],
                'value' => $field_data['id']
              ]
            ] );
          }  ?>
        </div>
      </div>

      <div class="questionnaire-form__step hide">
        <span class="questionnaire-form__step-title">Ежедневная физическая активность</span>
        <div class="questionnaire-form__step-fields"> <?php
          $fields_data = [
            [
              'title' => 'Сидячий, малоподвижный',
              'descr' => 'Без физических нагрузок, передвижения на транспорте, сидячий образ жизни. Около 2000–3000 шагов в день.',
              'name' => 'activity',
              'id' => 'inactive'
            ],
            [
              'title' => 'Тренируюсь регулярно',
              'descr' => 'Умеренные физические нагрузки на работе или в целом, сидячий образ жизни, но периодически занимающийся спортом 2–3 раза в неделю. Около 4000–6000 шагов в день.',
              'name' => 'activity',
              'id' => 'medium-active'
            ],
            [
              'title' => 'Высокая активность',
              'descr' => 'Физическая занятость на работе. Тренировки 3–4 раза в неделю. Около 7000–10000 шагов в день.',
              'name' => 'activity',
              'id' => 'high-active'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-activity',
              'title' => $field_data['title'],
              'descr' => $field_data['descr'],
              'radio' => [
                'name' => $field_data['name'],
                'id' => $field_data['id'],
                'value' => $field_data['id']
              ]
            ] );
          }  ?>
        </div>
      </div>

      <div class="questionnaire-form__step with-next-button categories-step hide">
        <span class="questionnaire-form__step-title">Выберите продуктые, которые необходимо исключить из вашего меню</span>
        <p class="questionnaire-form__step-descr">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню, и мы не предложим вам блюда с этими продуктами</p>
        <div class="questionnaire-form__step-fields"> <?php
          // $fields_data = [
          //   [
          //     'title' => 'Новичок',
          //     'name' => 'training-experience',
          //     'id' => 'newbie',
          //     'url' => 'questionnaire-training-experience-newbie-img.png'
          //   ],
          //   [
          //     'title' => 'Иногда тренируюсь',
          //     'name' => 'training-experience',
          //     'id' => 'middle',
          //     'url' => 'questionnaire-training-experience-middle-img.png'
          //   ],
          //   [
          //     'title' => 'Тренируюсь регулярно',
          //     'name' => 'training-experience',
          //     'id' => 'expert',
          //     'url' => 'questionnaire-training-experience-expert-img.png'
          //   ]
          // ];

          // foreach ( $fields_data as $field_data ) {
          //   questionnaire_card( [
          //     'class' => 'questionnaire-card-training-experience',
          //     'title' => $field_data['title'],
          //     'lazyload' => true,
          //     'img' => [
          //       'url' => $template_directory_uri . '/img/' . $field_data['url'],
          //       'width' => 100,
          //       'height' => 95
          //     ],
          //     'radio' => [
          //       'name' => $field_data['name'],
          //       'id' => $field_data['id'],
          //       'value' => $field_data['id']
          //     ]
          //   ] );
          // }  ?>
          <input type="checkbox" name="categories[]" value="milk-products" id="milk-products" class="questionnaire-form__radio-card-input">
          <label for="milk-products" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-1.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Молочные продукты</span>
          </label>
          <input type="checkbox" name="categories[]" value="meat-products" id="meat-products" class="questionnaire-form__radio-card-input">
          <label for="meat-products" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Мясо</span>
          </label>
          <input type="checkbox" name="categories[]" value="fish-products" id="fish-products" class="questionnaire-form__radio-card-input">
          <label for="fish-products" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Рыба</span>
          </label>
          <input type="checkbox" name="categories[]" value="gluten-products" id="gluten-products" class="questionnaire-form__radio-card-input">
          <label for="gluten-products" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Глютен</span>
          </label>
          <input type="checkbox" name="categories[]" value="seafood-products" id="seafood-products" class="questionnaire-form__radio-card-input">
          <label for="seafood-products" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Морепродукты (кальмары/креветки)</span>
          </label>
          <input type="checkbox" name="categories[]" value="eggs" id="eggs" class="questionnaire-form__radio-card-input">
          <label for="eggs" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Яйца</span>
          </label>
          <input type="checkbox" name="categories[]" value="nuts" id="nuts" class="questionnaire-form__radio-card-input">
          <label for="nuts" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Орехи</span>
          </label>
          <input type="checkbox" name="categories[]" value="honey" id="honey" class="questionnaire-form__radio-card-input">
          <label for="honey" class="questionnaire-form__radio-card">
            <img src="#" alt="#" data-src="<?php echo $template_directory_uri ?>/img/i-2.svg" class="questionnaire-form__radio-card-img lazy">
            <span class="questionnaire-form__radio-card-text">Мёд</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step extra-step with-next-button hide" data-question="categories[]" data-answer="milk-products">
        <span class="questionnaire-form__step-title">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню</span>
        <p class="questionnaire-form__step-descr">Уточните, пожалуйста, какие молочные продукты необходимо исключить из меню</p>
        <div class="questionnaire-form__step-checkboxes-card checkboxes-card">
          <img src="#" alt="Изображение молочных продуктов" width="82" height="72" class="checkboxes-card__img">
          <div class="checkboxes-card__fields">
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="milk-products[]" value="all" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Молочные продукты полностью</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="milk-products[]" value="tvorog" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Творог</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="milk-products[]" value="moloko" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Молоко коровье</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="milk-products[]" value="kefir" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Кефир</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="milk-products[]" value="jogurt" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Йогурт</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="milk-products[]" value="smetana" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Сметана</span>
            </label>
          </div>
        </div>
      </div>

      <div class="questionnaire-form__step extra-step with-next-button hide" data-question="categories[]" data-answer="meat-products">
        <span class="questionnaire-form__step-title">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню</span>
        <p class="questionnaire-form__step-descr">Уточните, пожалуйста, какие мясные продукты необходимо исключить из меню</p>
        <div class="questionnaire-form__step-checkboxes-card checkboxes-card">
          <img src="#" alt="Изображение мясных продуктов" width="82" height="72" class="checkboxes-card__img">
          <div class="checkboxes-card__fields">
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="meat-products[]" value="all" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Мясо полностью</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="meat-products[]" value="tvorog" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Курица</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="meat-products[]" value="moloko" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Индейка</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="meat-products[]" value="kefir" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Говядина/Телятина</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="meat-products[]" value="jogurt" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Субпродукты (печень/сердечки)</span>
            </label>
          </div>
        </div>
      </div>

      <div class="questionnaire-form__step extra-step with-next-button hide" data-question="categories[]" data-answer="fish-products">
        <span class="questionnaire-form__step-title">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню</span>
        <p class="questionnaire-form__step-descr">Уточните, пожалуйста, какие виды рыбы необходимо исключить из меню</p>
        <div class="questionnaire-form__step-checkboxes-card checkboxes-card">
          <img src="#" alt="Изображение рыбных продуктов" width="82" height="72" class="checkboxes-card__img">
          <div class="checkboxes-card__fields">
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="fish-products[]" value="all" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Рыба полностью</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="fish-products[]" value="tvorog" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Белая рыба</span>
            </label>
            <label class="checkboxes-card__checkbox">
              <input type="checkbox" name="fish-products[]" value="moloko" class="checkboxes-card__checkbox-input" required>
              <span class="checkboxes-card__checkbox-pseudo-input"></span>
              <span class="checkboxes-card__checkbox-text">Красаня рыба</span>
            </label>
          </div>
        </div>
      </div>

      <div class="questionnaire-form__step with-next-button hide">
        <span class="questionnaire-form__step-title">Какие физические ограничения у вас есть?</span>
        <div class="training-restrictions-checkboxes">
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="spina" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на спину</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="koleni" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на колени</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="kardio" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нелья кардио</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="press" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на пресс</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="ruki" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на руки</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__step hide">
        <span class="questionnaire-form__step-title">Где планируете заниматься?</span>
        <div class="questionnaire-form__step-fields"> <?php
          $fields_data = [
            [
              'title' => 'Дома',
              'name' => 'place',
              'id' => 'at-home',
              'url' => 'questionnaire-place-img.png'
            ],
            [
              'title' => 'В зале',
              'name' => 'place',
              'id' => 'in-gym',
              'url' => 'questionnaire-place-img.png'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-place',
              'title' => $field_data['title'],
              'lazyload' => true,
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
                'width' => 145,
                'height' => 115
              ],
              'radio' => [
                'name' => $field_data['name'],
                'id' => $field_data['id'],
                'value' => $field_data['id']
              ]
            ] );
          }  ?>
        </div>
      </div>

      <div class="questionnaire-form__step with-final-button hide">
        <span class="questionnaire-form__step-title">Каким проблемным местам вы хотите уделить внимание?</span>
        <div class="questionnaire-form__step-fields"> <?php
          $fields_data = [
            [
              'title' => 'Живот',
              'name' => 'body-parts',
              'id' => 'jivot',
              'url' => 'questionnaire-body-parts-img-1.png'
            ],
            [
              'title' => 'Бока',
              'name' => 'body-parts',
              'id' => 'boka',
              'url' => 'questionnaire-body-parts-img-2.png'
            ],
            [
              'title' => 'Бедра',
              'name' => 'body-parts',
              'id' => 'bedra',
              'url' => 'questionnaire-body-parts-img-3.png'
            ],
            [
              'title' => 'Ягодицы',
              'name' => 'body-parts',
              'id' => 'jagodicy',
              'url' => 'questionnaire-body-parts-img-2.png'
            ],
            [
              'title' => 'Руки',
              'name' => 'body-parts',
              'id' => 'ruki',
              'url' => 'questionnaire-body-parts-img-2.png'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-body-parts',
              'lazyload' => true,
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
                'width' => 95,
                'height' => 90
              ],
              'checkbox' => [
                'title' => $field_data['title'],
                'name' => $field_data['name'],
                'id' => $field_data['id'],
                'value' => $field_data['id'],
                'class' => 'checkbox-green'
              ]
            ] );
          }  ?>
        </div>
      </div>

      <div class="questionnaire-form__complete"></div>

      <button type="button" class="questionnaire-form__next btn btn-green hide">Далее</button>
      <button class="questionnaire-form__submit btn btn-green hide">Завершить</button>

    </form>
  </section> <?php
endif ?>