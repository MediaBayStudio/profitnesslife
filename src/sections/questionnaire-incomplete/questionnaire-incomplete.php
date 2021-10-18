<?php
if ( !$questionnaire_complete ) : ?>
  <section class="questionnaire-incomplete-section" id="questionnaire-incomplete-section">
    <img src="<?php echo $template_directory_uri ?>/img/questionnaire-start-img.svg" alt="Анкета участника" width="225" height="170" class="questionnaire-incomplete-section__img">
    <h1 class="questionnaire-incomplete-section__title sect-title"><?php echo $section['title'] ?></h1>
    <p class="questionnaire-incomplete-section__descr"><?php echo $section['descr'] ?></p>
    <button type="button" class="btn btn-green questionnaire-incomplete-section__btn">Начать</button>
    <form method="POST" novalidate id="questionnaire-form" class="questionnaire-incomplete-section__form questionnaire-form hide">
      <input type="text" name="user-id" style="display:none;" value="user_<?php echo $user_id ?>">
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
              'url' => 'questionnaire-weight-loss-img.svg'
            ],
            [
              'title' => 'Набор мышечной массы',
              'name' => 'target',
              'id' => 'weight-gain',
              'url' => 'questionnaire-weight-gain-img.svg'
            ],
            [
              'title' => 'Поддержание веса',
              'name' => 'target',
              'id' => 'weight-maintaining',
              'url' => 'questionnaire-weight-maintaining-img.svg'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-target',
              'title' => $field_data['title'],
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
                'alt' => 'icon',
                'width' => 110,
                'height' => 74
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
              'url' => 'questionnaire-male-img.svg',
            ],
            [
              'title' => 'Женский',
              'name' => 'sex',
              'id' => 'female',
              'url' => 'questionnaire-female-img.svg',
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
        <span class="questionnaire-form__step-title">Есть ли у&nbsp;вас дети на грудном вскармливании?</span>
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
        <div class="questionnaire-form__step-fields questionnaire-form__text-fields">
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
              'url' => 'questionnaire-training-experience-newbie-img.svg'
            ],
            [
              'title' => 'Иногда тренируюсь',
              'name' => 'training-experience',
              'id' => 'middle',
              'url' => 'questionnaire-training-experience-middle-img.svg'
            ],
            [
              'title' => 'Тренируюсь регулярно',
              'name' => 'training-experience',
              'id' => 'expert',
              'url' => 'questionnaire-training-experience-expert-img.svg'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-training-experience',
              'title' => $field_data['title'],
              'lazyload' => true,
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
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
        <div class="questionnaire-form__step-fields categories-step__fields"> <?php
          $categories = get_terms( [
            'taxonomy' => 'dish_category',
            'hide_empty' => false
          ] );
          $milk_products_checkboxes[] = [
            'title' => 'Молочные продукты полностью (творог/кефир/йогурт/сметана/сыры/биота)',
            'name' => 'milk-products[]',
            'value' => 'all'
          ];
          $meat_products_checkboxes[] = [
            'title' => 'Мясо полностью',
            'name' => 'meat-products[]',
            'value' => 'all'
          ];
          $fish_products_checkboxes[] = [
            'title' => 'Рыба полностью',
            'name' => 'fish-products[]',
            'value' => 'all'
          ];
          for ( $i = 0, $len = count( $categories ); $i < $len; $i++ ) {
            switch ( $categories[ $i ]->parent ) {
              case 0:
                questionnaire_card( [
                  'class' => 'questionnaire-card-products',
                  'lazyload' => true,
                  'img' => [
                    'url' => $template_directory_uri . '/img/questionnaire-' . $categories[ $i ]->slug . '-img.svg',
                    'width' => 60,
                    'height' => 60
                  ],
                  'checkbox' => [
                    'title' => $categories[ $i ]->name,
                    'name' => 'categories[]',
                    'id' => $categories[ $i ]->slug,
                    'value' => $categories[ $i ]->slug,
                    'image_first' => false,
                    'checkbox_inside' => false
                  ]
                ] );
                break;
              case 6:
                $milk_products_checkboxes[] = [
                  'title' => $categories[ $i ]->name,
                  'name' => 'milk-products[]',
                  'value' => $categories[ $i ]->slug
                ];
                break;
              case 11:
                $meat_products_checkboxes[] = [
                  'title' => $categories[ $i ]->name,
                  'name' => 'meat-products[]',
                  'value' => $categories[ $i ]->slug
                ];
                break;
              case 16:
                $fish_products_checkboxes[] = [
                  'title' => $categories[ $i ]->name,
                  'name' => 'fish-products[]',
                  'value' => $categories[ $i ]->slug
                ];
                break;
            }
          }
          $cereals_products_checkboxes = [
            [
              'title' => 'Исключить крупы полностью',
              'name' => 'cereals-products[]',
              'value' => 'all-cereals'
            ],
            [
              'title' => 'Исключить только на завтрак',
              'name' => 'cereals-products[]',
              'value' => 'exclude-breakfast'
            ]
          ] ?>
        </div>
      </div>

      <div class="questionnaire-form__step extra-step with-next-button hide" data-question="categories[]" data-answer="milk-products">
        <span class="questionnaire-form__step-title">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню</span>
        <p class="questionnaire-form__step-descr">Уточните, пожалуйста, какие молочные продукты необходимо исключить из меню</p> <?php
        questionnaire_card( [
          'class' => 'questionnaire-card-products-checkboxes',
          'checkboxes_class' => 'checkbox-red',
          'img' => [
            'url' => $template_directory_uri . '/img/questionnaire-milk-products-img.svg',
            'width' => 82,
            'height' => 72,
            'alt' => 'Изображение молочных продуктов'
          ],
          'checkboxes' => $milk_products_checkboxes
        ] ) ?>
      </div>

      <div class="questionnaire-form__step extra-step with-next-button hide" data-question="categories[]" data-answer="meat-products">
        <span class="questionnaire-form__step-title">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню</span>
        <p class="questionnaire-form__step-descr">Уточните, пожалуйста, какие мясные продукты необходимо исключить из меню</p> <?php
        questionnaire_card( [
          'class' => 'questionnaire-card-products-checkboxes',
          'checkboxes_class' => 'checkbox-red',
          'img' => [
            'url' => $template_directory_uri . '/img/questionnaire-meat-products-img.svg',
            'width' => 82,
            'height' => 72,
            'alt' => 'Изображение мясных продуктов'
          ],
          'checkboxes' => $meat_products_checkboxes
        ] ) ?>
      </div>

      <div class="questionnaire-form__step extra-step with-next-button hide" data-question="categories[]" data-answer="fish-products">
        <span class="questionnaire-form__step-title">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню</span>
        <p class="questionnaire-form__step-descr">Уточните, пожалуйста, какие виды рыбы необходимо исключить из меню</p> <?php
        questionnaire_card( [
          'class' => 'questionnaire-card-products-checkboxes',
          'checkboxes_class' => 'checkbox-red',
          'img' => [
            'url' => $template_directory_uri . '/img/questionnaire-fish-products-img.svg',
            'width' => 82,
            'height' => 72,
            'alt' => 'Изображение рыбных продуктов'
          ],
          'checkboxes' => $fish_products_checkboxes
        ] ) ?>
      </div>

      <div class="questionnaire-form__step extra-step with-next-button hide" data-question="categories[]" data-answer="cereals">
        <span class="questionnaire-form__step-title">Отметьте продукты, которые бы вы НЕ хотели видеть в своём меню</span>
        <p class="questionnaire-form__step-descr">Уточните, пожалуйста, как именно вы бы хотели исключить крупы</p> <?php
        questionnaire_card( [
          'class' => 'questionnaire-card-products-checkboxes',
          'checkboxes_class' => 'checkbox-red',
          'img' => [
            'url' => $template_directory_uri . '/img/questionnaire-cereals-img.svg',
            'width' => 82,
            'height' => 72,
            'alt' => 'Изображение крупы'
          ],
          'checkboxes' => $cereals_products_checkboxes
        ] ) ?>
      </div>

      <div class="questionnaire-form__step with-next-button hide">
        <span class="questionnaire-form__step-title">Какие физические ограничения у вас есть?</span>
        <div class="training-restrictions-checkboxes">
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="back" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на спину</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="knees" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на колени</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="cardio" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя кардио</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="abs" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на пресс</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="hands" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на руки</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="pectoral" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Нельзя нагрузку на грудные мышцы</span>
          </label>
          <label class="training-restrictions-checkboxes__checkbox">
            <input type="checkbox" name="training-restrictions[]" value="jumping-running" class="checkbox-green">
            <span class="checkbox-pseudo-input"></span>
            <span class="checkbox-text">Без прыжков и бега</span>
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
              'url' => 'questionnaire-place-home-img.svg',
              'alt' => 'Изображение занятий дома'
            ],
            [
              'title' => 'В зале',
              'name' => 'place',
              'id' => 'in-gym',
              'url' => 'questionnaire-place-gym-img.svg',
              'alt' => 'Изображение занятий в зале'
            ]
          ];

          foreach ( $fields_data as $field_data ) {
            questionnaire_card( [
              'class' => 'questionnaire-card-place',
              'title' => $field_data['title'],
              'lazyload' => true,
              'img' => [
                'url' => $template_directory_uri . '/img/' . $field_data['url'],
                'alt' => $field_data['alt'],
                'width' => 137,
                'height' => 87
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

      <!-- <div class="questionnaire-form__step extra-step with-next-button hide" data-question="place" data-answer="at-home"> -->
        <!-- <span class="questionnaire-form__step-title">С каким интвентарём вы бы хотели тренироваться дома?</span> --> <?php
        // questionnaire_card( [
        //   'class' => 'questionnaire-card-inventory-checkboxes',
        //   'checkboxes_class' => 'checkbox-green',
        //   'checkboxes' => [
        //     [
        //       'title' => 'С гантелями',
        //       'id' => 'with-weight',
        //       'value' => 'with-weight',
        //       'name' => 'inventory'
        //     ],
        //     [
        //       'title' => 'С резинками',
        //       'id' => 'with-elastic',
        //       'value' => 'with-elastic',
        //       'name' => 'inventory'
        //     ],
        //     [
        //       'title' => 'Без инвентаря',
        //       'id' => 'without-inventory',
        //       'value' => 'without-inventory',
        //       'name' => 'inventory'
        //     ]
        //   ]
        // ] ) ?>
      <!-- </div> -->

      <div class="questionnaire-form__step with-final-button hide">
        <span class="questionnaire-form__step-title">Каким проблемным местам вы хотите уделить внимание?</span>
        <div class="questionnaire-form__step-fields">
          <input type="checkbox" name="body-parts" id="belly" value="belly" class="checkbox-green">
          <label for="belly" class="questionnaire-card questionnaire-card-body-parts">
            <img src="#" alt="Изображение живота" width="95" height="90" data-src="<?php echo $template_directory_uri ?>/img/questionnaire-body-parts-img-1.svg" class="questionnaire-card__img lazy">
            <span class="questionnaire-card__checkbox-pseudo-input checkbox-pseudo-input"></span>
            <span class="questionnaire-card__checkbox-text checkbox-text">Живот</span>
          </label>
          <input type="checkbox" name="body-parts" id="sides" value="sides" class="checkbox-green">
          <label for="sides" class="questionnaire-card questionnaire-card-body-parts">
            <img src="#" alt="Изображение боков" width="95" height="90" data-src="<?php echo $template_directory_uri ?>/img/questionnaire-body-parts-img-2.svg" class="questionnaire-card__img lazy">
            <span class="questionnaire-card__checkbox-pseudo-input checkbox-pseudo-input"></span>
            <span class="questionnaire-card__checkbox-text checkbox-text">Бока</span>
          </label>
          <input type="checkbox" name="body-parts" id="hips" value="hips" class="checkbox-green">
          <label for="hips" class="questionnaire-card questionnaire-card-body-parts">
            <img src="#" alt="Изображение бедер" width="95" height="90" data-src="<?php echo $template_directory_uri ?>/img/questionnaire-body-parts-img-3.svg" class="questionnaire-card__img lazy">
            <span class="questionnaire-card__checkbox-pseudo-input checkbox-pseudo-input"></span>
            <span class="questionnaire-card__checkbox-text checkbox-text">Бедра</span>
          </label>
          <input type="checkbox" name="body-parts" id="butt" value="butt" class="checkbox-green">
          <label for="butt" class="questionnaire-card questionnaire-card-body-parts">
            <img src="#" alt="Изображение ягодиц" width="95" height="90" data-src="<?php echo $template_directory_uri ?>/img/questionnaire-body-parts-img-4.svg" class="questionnaire-card__img lazy">
            <span class="questionnaire-card__checkbox-pseudo-input checkbox-pseudo-input"></span>
            <span class="questionnaire-card__checkbox-text checkbox-text">Ягодицы</span>
          </label>
          <input type="checkbox" name="body-parts" id="hands" value="hands" class="checkbox-green">
          <label for="hands" class="questionnaire-card questionnaire-card-body-parts">
            <img src="#" alt="Изображение рук" width="95" height="90" data-src="<?php echo $template_directory_uri ?>/img/questionnaire-body-parts-img-5.svg" class="questionnaire-card__img lazy">
            <span class="questionnaire-card__checkbox-pseudo-input checkbox-pseudo-input"></span>
            <span class="questionnaire-card__checkbox-text checkbox-text">Руки</span>
          </label>
        </div>
      </div>

      <div class="questionnaire-form__complete"></div>

      <button type="button" class="questionnaire-form__next btn btn-green hide">Далее</button>
      <button class="questionnaire-form__submit btn btn-green hide">Завершить</button>

    </form>
  </section> <?php
endif ?>
