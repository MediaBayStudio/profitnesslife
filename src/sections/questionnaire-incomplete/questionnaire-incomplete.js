;
(function() {
  let section = id('questionnaire-incomplete-section'),
    questionnaireForm = document.forms['questionnaire-form'];

  if (section && questionnaireForm) {
    let stepSelector = '.questionnaire-form__step',
      sectionBtn = q('.questionnaire-incomplete-section__btn', section),
      completeBlock = q('.questionnaire-form__complete', questionnaireForm),
      currentStepElement = q('.questionnaire-form__count-current', questionnaireForm),
      backBtn = q('.questionnaire-form__back', questionnaireForm),
      nextBtn = q('.questionnaire-form__next', questionnaireForm),
      submitBtn = questionnaireForm['submit'],
      progressLine = q('.questionnaire-form__progress-line', questionnaireForm),
      steps = qa(stepSelector + ':not(.extra-step)', questionnaireForm),
      extraSteps = qa(stepSelector + '.extra-step', questionnaireForm),
      stepsLength = steps.length,
      extraStepsLength = extraSteps.length,
      isExtraStep = false,
      next = true,
      extraStepCount = 0,
      lastExtraStepCount = 0,
      currentStepNumber = 0,
      // currentStepNumber = 5,
      validateRules = {
        'defaults': {
          'min': 'Значение не может быть меньше %min%',
          'max': 'Значение не может быть больше %max%',
          'required': 'Заполните поле'
        },
        'current-weight': {
          'weight-loss': 'Текущий вес не может быть меньше желаемого веса',
          'target-weight': 'Текущий вес не может быть равен желаемому',
          'weight-gain': 'Текущий вес не может быть больше желаемого веса'
        },
        'target-weight': {
          'weight-loss': 'Желаемый вес не может быть больше текущего веса',
          'current-weight': 'Желаемый вес не может быть равен текущему',
          'weight-gain': 'Жалемый вес не может быть меньше текущего веса'
        },
        'height': {},
        'age': {},
        'categories[]': {
          'max': 'Выберите максимум 4 пункта'
        },
        'training-restrictions[]': {
          'max': 'Выберите максимум 3 пункта'
        },
        'milk-products[]': {
          'required': 'Выберите хоть что-то'
        },
        'meat-products[]': {
          'required': 'Выберите хоть что-то'
        },
        'fish-products[]': {
          'required': 'Выберите хоть что-то'
        },
        'cereals-products[]': {
          'required': 'Выберите хоть что-то'
        },
        'inventory[]': {
          'required': 'Выберите хоть что-то'
        }
      },
      validateLength = 4,
      errorHTML = '<span class="questionnaire-form__error">%text%</span>',
      submitForm = function(e) {
        e.preventDefault();

        questionnaireForm.classList.add('loading');
        submitBtn.blur();

        let data = new FormData(questionnaireForm),
          url = siteUrl + '/wp-admin/admin-ajax.php';

        data.append('action', 'questionnaire_send');

        fetch(url, {
            method: 'POST',
            body: data
          })
          .then(function(response) {
            if (response.ok) {
              return response.text();
            } else {
              console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
              return '';
            }
          })
          .then(function(response) {
            response = JSON.parse(response);

            // let breakfasts = '<h3>Завтрак:</h3>',
            //   lunches = '<h3>Обед:</h3>',
            //   dinners = '<h3>Ужин:</h3>',
            //   snack_1 = '<h3>Перекус:</h3>',
            //   snack_2 = '<h3>Перекус:</h3>',
            //   j = 0;

            // for (let key in response.breakfasts) {
            //   j++;
            //   breakfasts += '<span style="display:block">День ' + j + ': ' + response.breakfasts[key].title + ' (~' + response.breakfasts[key].calories + ' ккал)</span>';
            // }

            // j = 0;
            // for (let key in response.snack_1) {
            //   j++;
            //   snack_1 += '<span style="display:block">День ' + j + ': ' + response.snack_1[key].title + ' (~' + response.snack_1[key].calories + ' ккал)</span>';
            // }

            // j = 0;
            // for (let key in response.lunches) {
            //   j++;
            //   lunches += '<span style="display:block">День ' + j + ': ' + response.lunches[key].title + ' (~' + response.lunches[key].calories + ' ккал)</span>';
            // }

            // j = 0;
            // for (let key in response.snack_2) {
            //   j++;
            //   snack_2 += '<span style="display:block">День ' + j + ': ' + response.snack_2[key].title + ' (~' + response.snack_2[key].calories + ' ккал)</span>';
            // }

            // j = 0;
            // for (let key in response.dinners) {
            //   j++;
            //   dinners += '<span style="display:block">День ' + j + ': ' + response.dinners[key].title + ' (~' + response.dinners[key].calories + ' ккал)</span>';
            // }

            // completeBlock.insertAdjacentHTML('beforeend', '<button type="button" id="reset" class="btn btn-green" style="width:200px;margin:0 0 20px;">Сбросить анкету</button>');
            // completeBlock.insertAdjacentHTML('beforeend', '<p>Суточная норма калорий: ' + response.bmr + '</p>');
            // completeBlock.insertAdjacentHTML('beforeend', '<p>Исключили продукты: ' + response.categories + '</p>');
            // completeBlock.insertAdjacentHTML('beforeend', '<p>Исключили только на завтраки: ' + response.categories_breakfasts + '</p>');
            // completeBlock.insertAdjacentHTML('beforeend', '<p>Калорий на завтрак: ' + response.breakfast_ccal + '</p>');
            // completeBlock.insertAdjacentHTML('beforeend', '<p>Калорий на обед: ' + response.lunch_ccal + '</p>');
            // completeBlock.insertAdjacentHTML('beforeend', '<p>Калорий на ужин: ' + response.dinner_ccal + '</p>');
            // completeBlock.insertAdjacentHTML('beforeend', '<p>terms: ' + response.terms + '</p>');
            // completeBlock.insertAdjacentHTML('beforeend', breakfasts + snack_1 + lunches + snack_2 + dinners);

            // questionnaireForm.classList.remove('loading');
            // questionnaireForm.classList.add('complete');
            // console.log(response);
            // scrollToTarget('', '.questionnaire-incomplete-section__title');
            location.href = siteUrl + '/account';

            // id('reset').addEventListener('click', function() {
            //   let data = 'action=questionnaire_send&reset=reset',
            //     url = siteUrl + '/wp-admin/admin-ajax.php';

            //   fetch(url, {
            //       method: 'POST',
            //       body: data,
            //       headers: {
            //         'Content-Type': 'application/x-www-form-urlencoded'
            //       }
            //     })
            //     .then(function(response) {
            //       if (response.ok) {
            //         return response.text();
            //       } else {
            //         console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
            //         return '';
            //       }
            //     })
            //     .then(function(response) {
            //       // console.log(response);
            //       location.reload();
            //     })
            //     .catch(function(err) {
            //       console.log(err);
            //     });
            // });
          })
          .catch(function(err) {
            // if (errorPopup) {
            //   errorPopup.children[0].insertAdjacentHTML('beforeend', '<button type="button" id="error-popup-btn" class="btn btn-green" style="padding:10px 20px">Попробовать еще</button>');
            //   id('error-popup-btn').addEventListener('click', resetQuestionnaire);
            //   errorPopup.openPopup();
            // }
            errorPopup.openPopup();
            console.log(err);
          });
      },
      placeError = function(inputParent, errorText, action) {
        action = action || 'add';

        let error = q('.questionnaire-form__error', inputParent);

        if (error) {
          if (action === 'add') {
            error.textContent = errorText;
          } else {
            inputParent.removeChild(error);
          }
        } else {
          if (action === 'add') {
            inputParent.insertAdjacentHTML('beforeend', errorHTML.replace('%text%', errorText));
          }
        }

      },
      removeFieldErrors = function() {
        let errors = qa('.questionnaire-form__error', questionnaireForm);

        for (let i = errors.length - 1; i >= 0; i--) {
          errors[i].parentElement.removeChild(errors[i]);
        }
      },
      validateFields = function() {
        let activeStep = q(stepSelector + ':not(.hide)', questionnaireForm),
          inputs = activeStep.getElementsByTagName('input'),
          validateCount = 0;

        for (let inputName in validateRules) {
          let input = inputs[inputName],
            rule = validateRules[inputName],
            errorText;

          if (input) {

            // Если хотя бы один чекбокс выбран, то идем дальше
            if (input.type === 'checkbox') {
              if (inputName === 'categories[]') {
                // Максимум 4 чекбокса
                let fieldsBlock = input.parentElement.parentElement,
                  checkedInputs = qa('input:checked', fieldsBlock);

                if (checkedInputs.length > 4) {
                  errorText = validateRules[inputName].max || validateRules.defaults.required;
                  placeError(fieldsBlock, errorText);
                  console.log(errorText, input.name);
                  return false;
                } else {
                  return true;
                }

              } else if (inputName === 'training-restrictions[]') {
                // Максимум 4 чекбокса
                let fieldsBlock = input.parentElement.parentElement,
                  checkedInputs = qa('input:checked', fieldsBlock);

                if (checkedInputs.length > 3) {
                  errorText = validateRules[inputName].max || validateRules.defaults.required;
                  placeError(fieldsBlock, errorText);
                  console.log(errorText, input.name);
                  return false;
                } else {
                  return true;
                }

              } else {
                for (let i = 0, len = inputs.length; i < len; i++) {
                  if (inputs[i].checked) {
                    return true;
                  }
                }
                // Если не выбран ни один чекбокс, то показываем ошибку
                errorText = validateRules[inputName].required || validateRules.defaults.required;
                placeError(input.parentElement.parentElement, errorText);
                console.log(errorText, input.name);
                return false;
              } // endif inputName === 'categories[]'
            }

            let parent = input.parentElement,
              value = input.value,
              name = input.name,
              min = input.getAttribute('min'),
              max = input.getAttribute('max'),
              required = input.hasAttribute('required');

            if (parent.classList.contains('disabled')) {
              continue;
            }

            if (required) {
              if (!value) {
                errorText = validateRules[inputName].required || validateRules.defaults.required;
                placeError(parent, errorText);
                console.log(errorText, input.name);
                continue;
              }
            }
            if (min) {
              if (+value < +min) {
                errorText = validateRules[inputName].min || validateRules.defaults.min.replace('%min%', min);
                placeError(parent, errorText);
                console.log(errorText, input.name);
                continue;
              }
            }
            if (max) {
              if (+value > +max) {
                errorText = validateRules[inputName].max || validateRules.defaults.max.replace('%max%', max);
                placeError(parent, errorText);
                console.log(errorText, input.name);
                continue;
              }
            }

            if (questionnaireForm['target'].value === 'weight-loss') {
              if (name === 'current-weight') {
                if (+value < +questionnaireForm['target-weight'].value) {
                  errorText = validateRules[inputName]['weight-loss'];
                  placeError(parent, errorText);
                  continue;
                }
                if (value === questionnaireForm['target-weight'].value) {
                  errorText = validateRules[inputName]['target-weight'];
                  placeError(parent, errorText);
                  continue;
                }
              } else if (name === 'target-weight') {
                if (+value > +questionnaireForm['current-weight'].value) {
                  errorText = validateRules[inputName]['weight-loss'];
                  placeError(parent, errorText);
                  continue;
                }
                if (value === questionnaireForm['current-weight'].value) {
                  errorText = validateRules[inputName]['current-weight'];
                  placeError(parent, errorText);
                  continue;
                }
              }
            } else if (questionnaireForm['target'].value === 'weight-gain') {
              if (name === 'current-weight') {
                if (+value > +questionnaireForm['target-weight'].value) {
                  errorText = validateRules[inputName]['weight-gain'];
                  placeError(parent, errorText);
                  continue;
                }
                if (value === questionnaireForm['target-weight'].value) {
                  errorText = validateRules[inputName]['target-weight'];
                  placeError(parent, errorText);
                  continue;
                }
              } else if (name === 'target-weight') {
                if (+value < +questionnaireForm['current-weight'].value) {
                  errorText = validateRules[inputName]['weight-gain'];
                  placeError(parent, errorText);
                  continue;
                }
                if (value === questionnaireForm['current-weight'].value) {
                  errorText = validateRules[inputName]['current-weight'];
                  placeError(parent, errorText);
                  continue;
                }
              }
            }

            placeError(parent, '', 'remove');
            validateCount++;
          } else {
            // console.log('count+');
            // validateCount++;
          } // endif input
        } // end for in

        // Проверяем есть ли вообше у инпутов required
        if (validateCount === 0) {
          for (let i = inputs.length - 1; i >= 0; i--) {
            if (!inputs[i].required) {
              validateCount++;
            }
          }
        }

        if (currentStepNumber === 2 && questionnaireForm.target.value === 'weight-maintaining') {
          validateCount++;
        }


        if (validateCount >= validateLength) {
          return true;
        } else {
          return false;
        }

        // return validateCount === validateLength;
      },
      resetFields = function() {
        let activeStep = q(stepSelector + ':not(.hide)', questionnaireForm),
          inputs = qa('input', activeStep);

        removeFieldErrors();

        for (let i = 0, len = inputs.length; i < len; i++) {
          let type = inputs[i].type;

          if (type === 'radio' || type === 'checkbox') {
            inputs[i].checked = false;
          } else if (type === 'text' || type === 'number') {
            inputs[i].value = '';
          }
        } // endfor

      },
      // Показать дополнительный шаг
      showExtraStep = function(extraStep) {
        // console.log('extraStep');
        // Ищем активный шаг
        let activeStep = q(stepSelector + ':not(.hide)', questionnaireForm);

        // Скрываем активный шаг
        activeStep.classList.add('hide');

        // Показываем дополнительный шаг
        extraStep.classList.remove('hide');

        // Скрываем кнопку "Далее", если шаг не содержит класс with-next-button
        nextBtn.classList.toggle('hide', !extraStep.classList.contains('with-next-button'));

        // Показываем/скрываем кнопку отправки
        submitBtn.classList.toggle('hide', !extraStep.classList.contains('with-final-button'));

        if (extraStepCount <= 0) {
          extraStepCount = 1;
        }

        // if (lastExtraStepCount <= 0) {
        //   lastExtraStepCount = 1;
        // }

        currentStepElement.textContent = currentStepNumber + 1 + '.' + extraStepCount;
        // currentStepElement.textContent = currentStepNumber + 1 + '.' + lastExtraStepCount;

        scrollToTarget('', '.questionnaire-incomplete-section__title');
      },
      // Показать шаг
      showStep = function(number) {
        // console.log('showStep');
        isExtraStep = false;

        if (questionnaireForm.target.value === 'weight-maintaining') {
          questionnaireForm['target-weight'].removeAttribute('required');
          questionnaireForm['target-weight'].setAttribute('tabindex', '-1');
        } else {
          questionnaireForm['target-weight'].setAttribute('required', '');
          questionnaireForm['target-weight'].removeAttribute('tabindex');
        }

        let activeStep = q(stepSelector + ':not(.hide)', questionnaireForm);

        currentStepElement.textContent = number + 1;

        // Скрываем кнопку "Далее", если шаг не содержит класс with-next-button
        nextBtn.classList.toggle('hide', !steps[number].classList.contains('with-next-button'));

        // Показываем/скрываем кнопку отправки
        submitBtn.classList.toggle('hide', !steps[number].classList.contains('with-final-button'));

        // Если на первом шаге выбрали "поддержание веса", то блокируем поле для ввода "желаемый вес"
        questionnaireForm['target-weight'].parentElement.classList.toggle('disabled', questionnaireForm['weight-maintaining'].checked);

        // Если мы на самом первом шаге, то блокируем кнопку "назад"
        backBtn.classList.toggle('disabled', number === 0);

        activeStep.classList.add('hide');
        steps[number].classList.remove('hide');

        progressLine.style.width = currentStepNumber / stepsLength * 100 + '%';

        resetFields();

        scrollToTarget('', '.questionnaire-incomplete-section__title');

        // extraStepCount = 0;
      },
      showForm = function() {
        section.classList.add('show-form');
        questionnaireForm.classList.remove('hide');
      };

    sectionBtn.addEventListener('click', showForm);

    questionnaireForm.addEventListener('submit', submitForm);

    backBtn.addEventListener('click', function() {
      // Очищение и сброс checked всех полей на текущем шаге
      resetFields();

      if (isExtraStep) {
        // С дополнительного шага
        let activeExtraStep = q('.extra-step:not(.hide)', questionnaireForm),
          checkedFields = qa(':checked', steps[currentStepNumber]),
          excludeName,
          excludeValue;

        if (activeExtraStep) {
          excludeName = activeExtraStep.getAttribute('data-question');
          excludeValue = activeExtraStep.getAttribute('data-answer');
        }

        if (extraStepCount >= 1) {
          for (let i = extraStepCount - 1; i >= 0; i--) {
            if (excludeName === checkedFields[i].name && excludeValue === checkedFields[i].value) {
              continue;
            }
            let extraStep = q('.extra-step[data-question="' + checkedFields[i].name + '"][data-answer="' + checkedFields[i].value + '"]', questionnaireForm);
            if (extraStep) {
              // на доп шаг
              isExtraStep = true;
              showExtraStep(extraStep);
              extraStepCount--;
              return;
            } // endif extraStep
          } // endfor
        }

        extraStepCount = 0;
        showStep(currentStepNumber);

      } else {
        let prevStep = steps[--currentStepNumber];
        // let prevStep = steps[currentStepNumber - 1];

        if (prevStep) {
          // С обычного шага на доп. шаг
          let checkedFields = qa(':checked', prevStep);

          for (let i = checkedFields.length - 1; i >= 0; i--) {
            let extraStep = q('.extra-step[data-question="' + checkedFields[i].name + '"][data-answer="' + checkedFields[i].value + '"]', questionnaireForm);
            if (extraStep) {
              isExtraStep = true;
              showExtraStep(extraStep);
              extraStepCount--;
              // lastExtraStepCount--;
              // extraStepCount = lastExtraStepCount;
              return;
            } // endif extraStep
          } // endfor
        } // endif prevStep

        // С обычного шага на обычный шаг
        showStep(currentStepNumber);

      } // endif isExtraStep

    });

    nextBtn.addEventListener('click', function() {
      console.log('currentStepNumber', currentStepNumber);
      if (validateFields()) {
        let checkedFields = qa('input[type="checkbox"]:checked', steps[currentStepNumber]),
          extraStep = null,
          activeStep = null,
          existsExtraStep = false;

        if (currentStepNumber === 5 && !isExtraStep) {
          extraStepCount = 0;
        }

        // Нужно проверить есть ли дополнительный шаг
        for (let i = extraStepCount, len = checkedFields.length; i < len; i++) {
          extraStep = q('.extra-step[data-question="' + checkedFields[i].name + '"][data-answer="' + checkedFields[i].value + '"]', questionnaireForm);
          activeStep = q(stepSelector + ':not(.hide)', questionnaireForm);

          console.log('input', checkedFields[i]);

          if (extraStep && extraStep !== activeStep) {
            existsExtraStep = true;
            break;
          }
        }

        console.log(checkedFields);
        console.log('activeStep', activeStep);
        console.log('extraStep', extraStep);

        if (isExtraStep) {
          // С дополнительного шага
          console.log('С доп шага');
          // console.log('existsExtraStep', existsExtraStep);

          // На дополнительный шаг
          if (existsExtraStep) {
            isExtraStep = true;
            extraStepCount++;
            showExtraStep(extraStep);
            console.log('на доп шаг');
            return;
          }
        } else {
          // С обычного шага
          console.log('С обычного шага');

          // На дополнительнй шаг
          if (existsExtraStep) {
            console.log('на доп шаг');
            isExtraStep = true;
            // extraStepCount++;
            extraStepCount = 0;
            showExtraStep(extraStep);
            return;
          }
        }

        // На обычный шаг
        showStep(++currentStepNumber);
      }
    });


    questionnaireForm.addEventListener('change', function(e) {
      let input = e.target,
        type = input.type,
        value = input.value,
        name = input.name;

      if (type === 'radio') {
        let extraStep = q('.extra-step[data-question="' + name + '"][data-answer="' + value + '"]', questionnaireForm);

        if (extraStep) {
          isExtraStep = true;
          extraStepCount++;
          // lastExtraStepCount++;
          showExtraStep(extraStep);
          return;
        }

        showStep(++currentStepNumber);
      } else if (type === 'checkbox') {
        let checkboxesBlock = input.parentElement.parentElement,
          fields = qa('input:not([value="all"])', checkboxesBlock);

        if (value === 'all') {
          for (let i = fields.length - 1; i >= 0; i--) {
            fields[i].checked = input.checked;
          }
        } else {
          let fieldAll = q('input[value="all"]', checkboxesBlock),
            checkedInputs = qa('input:checked:not([value="all"])', checkboxesBlock);

          if (fieldAll) {
            fieldAll.checked = checkedInputs.length === fields.length;
          }
        }
      }

    });

    questionnaireForm.addEventListener('keydown', function(e) {
      if (e.keyCode === 13) {
        if (submitBtn.classList.contains('hide')) {
          e.preventDefault();
        }
        if (validateFields()) {
          showStep(++currentStepNumber);
        }
      }
    });
  }

})();