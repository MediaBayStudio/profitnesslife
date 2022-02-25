;
(function() {
  // Массив форм, на которые будет добавлена валидация
  let $forms = [
    id('index-form'),
    id('pay-form')
  ];

  let formValidator = function(params) {
    let $form = params.form,
      $formBtn = params.formBtn,
      $uploadFilesBlock = params.uploadFilesBlock,
      errorsClass = 'invalid',
      $filesInput = params.filesInput,
      // Правила проверки форм, аналогично jquery.validate
      rules = {
        name: {
          required: true
        },
        surname: {
          required: true
        },
        tel: {
          required: true,
          pattern: /\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}/,
          // or: 'email'
        },
        email: {
          required: true,
          pattern: /^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z])+$/,
          // or: 'tel'
        },
        msg: {
          required: true,
          pattern: /[^\<\>\[\]%\&'`]+$/
        },
        policy: {
          required: true
        }
      },
      messages = {
        tel: {
          required: 'Введите ваш телефон',
          pattern: 'Укажите верный телефон'
        },
        name: {
          required: 'Введите ваше имя'
        },
        surname: {
          required: 'Введите вашу фамилию'
        },
        email: {
          required: 'Введите ваш E-mail',
          pattern: 'Введите верный E-mail'
        },
        msg: {
          required: 'Введите ваше сообщение',
          pattern: 'Введены недопустимые символы'
        },
        policy: {
          required: 'Согласитель с политикой обработки персональных данных'
        }
      },
      /*
        Функция получения значения полей у текущей формы.
        Ищет только те элементы формы, именя которых указаны в rules.
        Возвращает объект: 
        {название-поля: значение-поля}
        Например:
        {'user-email': 'mail@mail.ru'}
      */
      getFormData = function($form) {
        let formElements = $form.elements,
          values = {};

        for (let rule in rules) {
          let formElement = formElements[rule];

          if (formElement) {
            values[rule] = formElement.value;
          }
        }

        return values;
      },
      /*
        Функция проверки правильности заполнения формы.
      */
      validationForm = function(event) {
        let errors = {},
          thisForm = $form,
          values = getFormData(thisForm);

        for (let elementName in values) {
          let rule = rules[elementName],
            $formElement = thisForm[elementName],
            elementValue = values[elementName],
            or = rule.or,
            $orFormElement = thisForm[or];

          if (rule) {
            if ($formElement.hasAttribute('required') || rule.required === true) {
              let elementType = $formElement.type,
                pattern = rule.pattern;

              // Если элемент не чекнут или пустой
              if (((elementType === 'checkbox' || elementType === 'radio') && !$formElement.checked) ||
                elementValue === '') {

                if (or && $orFormElement) {
                  if ($orFormElement.value === '') {
                    errors[elementName] = messages[elementName].required;
                    continue;
                  }
                } else {
                  errors[elementName] = messages[elementName].required;
                  continue;
                }
              }

              // Если текстовый элемент, у которого есть щаблон для заполнения
              if (elementType !== 'cehckbox' && elementType !== 'radio' && pattern) {
                if (elementValue !== '' && pattern.test(elementValue) === false) {
                  errors[elementName] = messages[elementName].pattern;
                  continue;
                }
              }

              hideError($formElement);
            }
          }
        }

        if (Object.keys(errors).length == 0) {
          thisForm.removeEventListener('change', validationForm);
          thisForm.removeEventListener('input', validationForm);
          $form.validatie = true;
        } else {
          thisForm.addEventListener('change', validationForm);
          thisForm.addEventListener('input', validationForm);
          showErrors(thisForm, errors);
          $form.validatie = false;
        }

      },
      showErrors = function($form, errors) {
        let $formElements = $form.elements;

        for (let elementName in errors) {
          let errorText = errors[elementName],
            $errorElement = `<label class="${errorsClass}">${errorText}</label>`,
            $formElement = $formElements[elementName],
            $nextElement = $formElement.nextElementSibling;

          if ($nextElement && $nextElement.classList.contains(errorsClass)) {
            if ($nextElement.textContent !== errorText) {
              $nextElement.textContent = errorText;
            }
            continue;
          } else {
            $formElement.insertAdjacentHTML('afterend', $errorElement);
          }

          $formElement.classList.add(errorsClass);
        }

      },
      hideError = function($formElement) {
        let $nextElement = $formElement.nextElementSibling;
        $formElement.classList.remove(errorsClass);
        if ($nextElement && $nextElement.classList.contains(errorsClass)) {
          $nextElement.parentElement.removeChild($nextElement);
        }
      },
      submitHandler = function(event) {
        let $form = q('#' + event.detail.id + '>form'),
          eventType = event.type;

        if (eventType === 'wpcf7mailsent') {
          let $formElements = $form.elements;

          for (let i = 0; i < $formElements.length; i++) {
            hideError($formElements[i]);
            $formElements[i].classList.remove('filled');
          }

          $form.reset();
          if ($uploadFilesBlock) {
            $uploadFilesBlock.innerHTML = '';
          }
          // if ($form === $quizForm) {
          //   id('quiz').resetQuiz();
          // }
          console.log('отправлено');
          thanksPopup.openPopup();
          thanksPopupTimer = setTimeout(function() {
            thanksPopup.closePopup();
          }, 3000);
        } else if (eventType === 'wpcf7mailfailed') {
          console.log('отправка не удалась');
          errorPopup.openPopup();
        }

        $form.classList.remove('loading');

        setTimeout(function() {
          $form.classList.remove('sent');
        }, 3000);

      },
      toggleInputsClass = function() {
        let $input = event.target,
          type = $input.type,
          files = $input.files,
          classList = $input.classList,
          value = $input.value;

        if (type === 'text' || type === 'email' || type === 'tel' || type === 'number' || $input.tagName === 'TEXTAREA') {
          if (value === '') {
            classList.remove('filled');
          } else {
            classList.add('filled');
          }
        } else if (type === 'file') {
          // $input.filesArray = [];

          let uploadedFiles = '';
          for (let i = 0, len = files.length; i < len; i++) {
            // $input.filesArray[i] = files[i];
            uploadedFiles += '<span class="uploadedfiles__file"><span class="uploadedfiles__file-text">' + files[i].name + '</span></span>';
          }
          $uploadFilesBlock.innerHTML = uploadedFiles;
        }
      };

    $form.setAttribute('novalidate', '');
    $form.validatie = false;
    $formBtn.addEventListener('click', function(e) {
      validationForm();
      
      if ($form.id === 'pay-form') {
        e.preventDefault();
        if ($form.validatie) {
          $form.classList.add('loading');
          // $formBtn.blur();
          // $form.blur();
          let widget = new cp.CloudPayments();
          widget.pay('charge', {
            // publicId: 'test_api_00000000000000000000001',
            publicId: 'pk_82de7baa82dfeede0822c06c01cbc',
            description: 'Оплата марафона стройности',
            amount: +$form['price'].value,
            currency: 'RUB',
            accountId: $form['email'].value,
            email: $form['email'].value,
            skin: 'mini'
          }, {
            onSuccess: function(options) {
              q('.pay-hero').classList.remove('active');
              q('#success-pay').classList.add('active');
              console.log('success');
              $form.classList.remove('loading');
              let data = new FormData($form);
              data.append('action', 'create_payment');

              fetch(siteUrl + '/wp-admin/admin-ajax.php', {
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
                  // response = JSON.parse(response);
                  console.log(response);
                })
                .catch(function(err) {
                  console.log(err);
                  errorPopup && errorPopup.openPopup();
                  $form.classList.remove('loading');
                });

            },
            onFail: function(reason, options) {
              q('.pay-hero').classList.remove('active');
              q('#failure-pay').classList.add('active');
              console.log('fail');
              $form.classList.remove('loading');
            },
            onComplete: function(paymentResult, options) {}
          });
          return;
        }
      }
      if ($form.validatie === false) {
        e.preventDefault();
      } else {
        $form.classList.add('loading');
        // $formBtn.blur();
        // $form.blur();
      }
    });
    if (!document.wpcf7mailsent) {
      document.addEventListener('wpcf7mailsent', submitHandler);
      document.wpcf7mailsent = true;
    }
    $form.addEventListener('input', toggleInputsClass);
  };

  for (var i = $forms.length - 1; i >= 0; i--) {
    if ($forms[i]) {
      formValidator({
        form: $forms[i],
        formBtn: q('.btn', $forms[i]) || q('.btn[form="' + $forms[i].id + '"]'),
        uploadFilesBlock: q('.uploadedfiles', $forms[i]),
        filesInput: q('input[type="file"]', $forms[i])
      });
    }
  }
})();