var browser = {
    // Opera 8.0+
    isOpera: (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0,
    // Firefox 1.0+
    isFirefox: typeof InstallTrigger !== 'undefined',
    // Safari 3.0+ "[object HTMLElementConstructor]"
    isSafari: /constructor/i.test(window.HTMLElement) || (function(p) {
      return p.toString() === "[object SafariRemoteNotification]";
    })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification)),
    // Internet Explorer 6-11
    isIE: /*@cc_on!@*/ false || !!document.documentMode,
    // Edge 20+
    isEdge: !( /*@cc_on!@*/ false || !!document.documentMode) && !!window.StyleMedia,
    // Chrome 1+
    isChrome: !!window.chrome && !!window.chrome.webstore,
    isYandex: !!window.yandex,
    isMac: window.navigator.platform.toUpperCase().indexOf('MAC') >= 0
  },
  dispatchEvent = function(eventName, element) {
    if (typeof window.CustomEvent === 'function') {
      let event = new CustomEvent(eventName)
      element.dispatchEvent(event);
    }
  },
  // Размреы экранов для медиазапросов
  // mediaQueries = {
  //   's': '(min-width:575.98px)',
  //   'm': '(min-width:767.98px)',
  //   'lg': '(min-width:1023.98px)',
  //   'xl': '(min-width:1439.98px)'
  // },
  SLIDER = {
    // nextArrow: '<button type="button" class="arrow"></button>',
    // prevArrow: '<button type="button" class="arrow"></button>',
    dot: '<button type="button" class="dot"></button>',
    hasSlickClass: function($el) {
      return $el.hasClass('slick-slider');
    },
    unslick: function($el) {
      $el.slick('unslick');
    },
    createArrow: function(className, inside) {
      className = (className.indexOf('prev') === -1 ? 'next ' : 'prev ') + className;
      return '<button type="button" class="arrow arrow-' + className + '">' + inside + '</button>';
    },
    arrow: '<svg class="arrow__svg" width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.22 17.983a.665.665 0 0 1-.073-.91l.073-.08 6.469-6.034-6.47-6.034a.665.665 0 0 1-.072-.91l.073-.08a.79.79 0 0 1 .976-.067l.084.068 7 6.529c.267.248.29.637.073.91l-.073.08-7 6.528a.789.789 0 0 1-1.06 0Z" fill="currentColor"/></svg>',
    // setImages: function(slides) {
    //   for (let i = 0, len = slides.length; i < len; i++) {
    //     let img = q('img', slides[i]);
    //     // Если элемент найден и он без display:none
    //     if (img && img.offsetParent) {
    //       img.src = img.getAttribute('data-lazy') || img.getAttribute('data-src');
    //     }
    //   }
    // }
  },
  /*
Объединение слушателей для window на события 'load', 'resize', 'scroll'
Все слушатели на окно следует задавать через него, например:
  window.resize.push(functionName)
Все ф-ии, добавленные в [] window.resize, будут заданы одним слушателем
*/
  windowFuncs = {
    load: [],
    resize: [],
    scroll: [],
    call: function(event) {
      let funcs = windowFuncs[event.type] || event;
      for (let i = funcs.length - 1; i >= 0; i--) {
        console.log(funcs[i].name);
        funcs[i]();
      }
    }
  },
  resetQuestionnaire = function(resetByUser) {
    let target = this !== window ? this : event.target,
      userID = target.getAttribute('data-user'),
      data = 'action=questionnaire_send&reset=reset',
      url = siteUrl + '/wp-admin/admin-ajax.php';

    if (userID) {
      data += '&user=' + userID;
    }

    if (resetByUser) {
      data += '&reset_by_user=1';
    }

    target.classList.add('loading');

    fetch(url, {
        method: 'POST',
        body: data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
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
        console.log(response);
        location.reload();
      })
      .catch(function(err) {
        target.classList.remove('loading');
        console.log(err);
      });
  },
  weightChart,
  measureChart,
  mask, // ф-я маски телефонов в поля ввода (в файле telMask.js)
  lazy,
  menu,
  sideMenu,
  burger,
  hdr,
  overlay,
  body,
  fakeScrollbar,
  resetQuestionnairePopup,
  allowedProductsPopup,
  nutritionRulesPopup,
  workoutRulesPopup,
  inventoryPopup,
  productsCartPopup,
  loginPopup,
  workoutPopup,
  errorPopup,
  thanksPopup,
  thanksPopupTimer,
  // Сокращение записи querySelector
  q = function(selector, element) {
    element = element || document.body;
    return element.querySelector(selector);
  },
  // Сокращение записи querySelectorAll + перевод в массив
  qa = function(selectors, element, toArray) {
    element = element || document.body;
    return toArray ? Array.prototype.slice.call(element.querySelectorAll(selectors)) : element.querySelectorAll(selectors);
  },
  // Сокращение записи getElementById
  id = function(selector) {
    return document.getElementById(selector);
  },
  // Фикс 100% высоты экрана для моб. браузеров
  setVh = function() {
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');
  },
  // Сокращение записи window.matchMedia('query').matches
  media = function(media) {
    return window.matchMedia(media).matches;
  },
  // Функция создания мобильного меню
  mobileMenu,
  // Прокрутка до элемента при помощи requestAnimationFrame
  scrollToTarget = function(e, target) {
    e && e.preventDefault();

    if (this === window) {
      _ = e.target;
    } else {
      _ = this;
    }

    if (target == 0) {
      target = body;
    } else {
      target = target || _.getAttribute('data-scroll-target');
    }

    if (!target && _.tagName === 'A') {
      target = _.getAttribute('href').replace(/http:\/\/|https:\/\//, '');
      target = q(target);
    }

    if (target.constructor === String) {
      target = target.replace(/http:\/\/|https:\/\//, '');
      target = q(target);
    }

    if (!target) {
      console.warn('Scroll target not found');
      return;
    }

    menu && menu.close();

    let wndwY = window.pageYOffset,
      targetStyles = getComputedStyle(target),
      targetTop = target.getBoundingClientRect().top - +(targetStyles.paddingTop).slice(0, -2) - +(targetStyles.marginTop).slice(0, -2),
      start = null,
      V = .35,
      step = function(time) {
        if (start === null) {
          start = time;
        }

        // targetTop = target.getBoundingClientRect().top - +(targetStyles.paddingTop).slice(0, -2) - +(targetStyles.marginTop).slice(0, -2)
        console.log(targetTop);
        let progress = time - start,
          r = (targetTop < 0 ? Math.max(wndwY - progress / V, wndwY + targetTop) : Math.min(wndwY + progress / V, wndwY + targetTop));

        window.scrollTo(0, r);

        if (r != wndwY + targetTop) {
          requestAnimationFrame(step);
        }
      }

      console.log(targetStyles.paddingTop);

    requestAnimationFrame(step);
  },
  // Функция запрета/разрешения прокрутки страницы
  pageScroll = function(disallow) {
    if (!fakeScrollbar) {
      return;
    }
    fakeScrollbar.classList.toggle('active', disallow);
    body.classList.toggle('no-scroll', disallow);
    body.style.paddingRight = disallow ? fakeScrollbar.offsetWidth - fakeScrollbar.clientWidth + 'px' : '';
  },
  // Функция липкого элемента средствами js
  sticky = function($el, fixThresholdDir, className) {
    $el = typeof $el === 'string' ? q($el) : $el;
    className = className || 'fixed';
    fixThresholdDir = fixThresholdDir || 'bottom';

    let fixThreshold = $el.getBoundingClientRect()[fixThresholdDir] + pageYOffset,
      $elClone = $el.cloneNode(true),
      $elParent = $el.parentElement,
      fixElement = function() {
        if (!$el.classList.contains(className) && pageYOffset >= fixThreshold) {
          $elParent.appendChild($elParent.replaceChild($elClone, $el));
          $el.classList.add(className);

          window.removeEventListener('scroll', fixElement);
          window.addEventListener('scroll', unfixElement);
        }
      },
      unfixElement = function() {
        if ($el.classList.contains(className) && pageYOffset <= fixThreshold) {
          $elParent.replaceChild($el, $elClone);
          $el.classList.remove(className);

          window.removeEventListener('scroll', unfixElement);
          window.addEventListener('scroll', fixElement);
        }
      };

    $elClone.classList.add('clone');
    fixElement();
    window.addEventListener('scroll', fixElement);
  };

document.addEventListener('DOMContentLoaded', function() {
  body = document.body;

  ;(function() {
    let setCursorPosition = function(pos, inputElement) {
      inputElement.focus();
      if (inputElement.setSelectionRange) {
        inputElement.setSelectionRange(pos, pos);
      } else if (inputElement.createTextRange) {
        let range = inputElement.createTextRange();
  
        range.collapse(true);
        range.moveEnd('character', pos);
        range.moveStart('character', pos);
        range.select();
      }
    };
  
    mask = function() {
      let pattern = '+7(___)___-__-__',
        i = 0,
        def = pattern.replace(/\D/g, ''),
        val = this.value.replace(/\D/g, '');
  
      if (def.length >= val.length) {
        val = def;
      }
  
      this.value = pattern.replace(/./g, function(match) {
        return /[_\d]/.test(match) && i < val.length ? val.charAt(i++) : i >= val.length ? '' : match;
      });
  
      if (event.type === 'blur') {
        if (this.value.length === 2) {
          this.value = '';
          this.classList.remove('filled');
        }
      } else {
        setCursorPosition(this.value.length, this);
      }
    };
  
    let input = qa('[name=tel]');
  
    for (let i = 0; i < input.length; i++) {
      input[i].addEventListener('input', mask);
      input[i].addEventListener('focus', mask);
      input[i].addEventListener('blur', mask);
    }
  
  })();

  ;
  (function() {
    let maybeCloseBlock = document.querySelector('.account-hero-maybe-close'),
      setHeigth = function() {
        maybeCloseBlock.style.transition = 'none';
        maybeCloseBlock.style.height = 'auto';
        maybeCloseBlock.style.height = maybeCloseBlock.scrollHeight + 'px';
        maybeCloseBlock.style.transition = '';
      },
      closeBlock = function(e) {
        if (e && e.type === 'click' && e.target.classList.contains('account-hero__close')) {
          maybeCloseBlock.addEventListener('transitionend', function(e) {
            if (e.propertyName === 'height') {
              maybeCloseBlock.style.display = 'none';
            }
          });
          maybeCloseBlock.style.cssText = 'padding:0;margin:0;height:0;opacity:0';
        }
        fetch(siteUrl + '/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=close_welcome_block&user-id=' + maybeCloseBlock.getAttribute('data-user-id')
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
            console.log('response', response);
          })
          .catch(function(err) {
            console.log(err);
          });
      };
  
    if (maybeCloseBlock) {
      setHeigth();
      maybeCloseBlock.addEventListener('click', closeBlock);
      windowFuncs.resize.push(setHeigth);
    }
  })();

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
            let widget = new cp.CloudPayments();
            widget.pay('charge', {
              publicId: 'test_api_00000000000000000000001',
              description: 'Оплата марафона стройности',
              amount: +$form['price'].value,
              currency: 'RUB',
              accountId: $form['email'].value,
              email: $form['email'].value,
              skin: 'mini',
              data: {
                myProp: 'myProp value'
              }
            }, {
              onSuccess: function(options) {
                q('.pay-hero').classList.remove('active');
                q('#success-pay').classList.add('active');
                console.log('success');
  
                let data = new FormData($form);
                data.append('action', 'create_payment');
  
                // console.log(data);
  
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
                  });
  
              },
              onFail: function(reason, options) {
                q('.pay-hero').classList.remove('active');
                q('#failure-pay').classList.add('active');
                console.log('fail');
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
          $formBtn.blur();
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

  mobileMenu = function(_) {
    let setMenuStyles = function(trf, trs) {
        let args = [trf, trs],
          props = ['transform', 'transition'],
          values = ['translate3d(' + trf + ', 0px, 0px)', 'transform ' + trs];
  
        for (let i = args.length - 1; i >= 0; i--) {
          if (args[i] !== 0) {
            if (args[i] === '') {
              args[i] = '';
            } else {
              args[i] = values[i];
            }
            menuCnt.style[props[i]] = args[i];
          }
        }
      },
      checkForString = function(variable) {
        return variable.constructor === String ? q(variable) : variable;
      },
      openMenu = function() {
        if (!opened) {
          dispatchEvent('menubeforeopen', menu);
          if (menu.hasAttribute('style')) {
            menu.removeAttribute('style');
            menu.offsetHeight;
          }
          menu.classList.add('active');
          openBtn.classList.add('active');
          menuCnt.scrollTop = 0;
  
          if (!fade) {
            setMenuStyles('0px', '.5s');
            menuWidth = menuCnt.offsetWidth;
          }
          if (!allowPageScroll) {
            pageScroll(true);
          }
        }
      },
      closeMenu = function(e, forSwipe) {
        if (opened) {
          let target = e && e.target;
          // Если меню открыто и произошел свайп или нет события (закрыто вызовом функции close()) или есть евент и его св-ва
          if (forSwipe || !e || (e.type === 'keyup' && e.keyCode === 27 || target === menu || target === closeBtn)) {
            menu.classList.remove('active');
            openBtn.classList.remove('active');
  
            if (!fade) {
              setMenuStyles(initialTransformX, '.5s');
            }
          }
        }
      },
      swipeStart = function(e) {
        if (allowSwipe) {
          let evt = e.touches[0] || window.e.touches[0];
  
          isSwipe = isScroll = false;
          posInitX = posX1 = evt.clientX;
          posInitY = posY1 = evt.clientY;
          swipeStartTime = Date.now();
  
          menuCnt.addEventListener('touchend', swipeEnd);
          menuCnt.addEventListener('touchmove', swipeAction);
          setMenuStyles(0, '');
        }
      },
      swipeAction = function(e) {
        if (allowSwipe) {
          let evt = e.touches[0] || window.e.touches[0],
            style = menuCnt.style.transform,
            transform = +style.match(trfRegExp)[0];
  
          posX2 = posX1 - evt.clientX;
          posX1 = evt.clientX;
  
          posY2 = posY1 - evt.clientY;
          posY1 = evt.clientY;
  
          // Если еще не определено свайп или скролл (двигаемся в бок или вверх/вниз)
          if (!isSwipe && !isScroll) {
            let posY = Math.abs(posY2),
              posX = Math.abs(posX2);
  
            if (posY > 7 || posX2 === 0) {
              isScroll = true;
            } else if (posY < 7) {
              isSwipe = true;
            }
          }
  
          if (isSwipe) {
            // Если двигаемся влево или вправо при уже открытом меню, фиксируем позицию
            if ((toLeft && posInitX > posX1) || (toRight && posInitX < posX1)) {
              setMenuStyles('0px', 0);
              return;
            }
            setMenuStyles(transform - posX2 + 'px', 0);
          }
        }
      },
      swipeEnd = function(e) {
        posFinal = posInitX - posX1;
  
        let absPosFinal = Math.abs(posFinal);
  
        swipeEndTime = Date.now();
  
        if (absPosFinal > 1 && isSwipe) {
          if (toLeft && posFinal < 0 || toRight && posFinal > 0) {
            if (absPosFinal >= menuWidth * swipeThreshold || swipeEndTime - swipeStartTime < 300) {
              closeMenu(e, true);
            } else {
              opened = false;
              openMenu(e, true);
            }
          }
          allowSwipe = false;
        }
  
        menu.removeEventListener('touchend', swipeEnd);
        menu.removeEventListener('touchmove', swipeAction);
  
      },
      transitionEnd = function(e) {
        if (fade) {
          if (e.propertyName === 'opacity') {
            transitionEndEvents();
          }
        } else {
          if (e.propertyName === 'transform') {
            transitionEndEvents();
          }
        } 
        allowSwipe = true;
      },
      transitionEndEvents = function() {
        if (opened) {
          menu.isOpened = opened = false;
          openBtn.addEventListener('click', openMenu);
          closeBtn.removeEventListener('click', closeMenu);
          if (!allowPageScroll) {
            pageScroll(false);
          }
          console.log('sticky hdr');
          sticky(hdr);
        } else {
          menu.isOpened = opened = true;
          openBtn.removeEventListener('click', openMenu);
          closeBtn.addEventListener('click', closeMenu);
        }
      },
      init = function() {
        menu = checkForString(_.menu);
        menuCnt = checkForString(_.menuCnt);
        openBtn = checkForString(_.openBtn);
        closeBtn = checkForString(_.closeBtn);
        allowPageScroll = options.allowPageScroll;
        toRight = options.toRight;
        toLeft = options.toLeft;
        initialTransformX = toLeft ? '100%' : toRight ? '-100%' : 0;
        fade = options.fade;
  
        setListeners('add');
  
        if (fade) {
          toRight = toLeft = false;
        } else {
          setMenuStyles(initialTransformX, 0);
          menu.addEventListener('touchstart', swipeStart);
        }
        menu.isOpened = false;
      },
      setListeners = function(action) {
        openBtn[action + 'EventListener']('click', openMenu);
        menu[action + 'EventListener']('click', closeMenu);
        menu[action + 'EventListener']('transitionend', transitionEnd);
        document[action + 'EventListener']('keyup', closeMenu);
      },
      destroy = function() {
        if (opened) {
          closeMenu();
        }
  
        if (fade) {
          toRight = toLeft = false;
        } else {
          setMenuStyles('', '');
          menu.removeEventListener('touchstart', swipeStart);
        }
  
        setListeners('remove');
        menu = null;
        menuCnt = null;
        openBtn = null;
        closeBtn = null;
      },
      applyMediaParams = function() {
        // console.log('applyMediaParams');
        if (targetMediaQuery) {
          // console.log('set ' + targetMediaQuery + ' params');
          for (let option in responsive[targetMediaQuery]) {
            options[option] = responsive[targetMediaQuery][option];
          }
          currentMediaQuery = targetMediaQuery;
        } else { // set initial params
          for (let option in initialOptions) {
            options[option] = initialOptions[option];
          }
          currentMediaQuery = null;
        }
        if (menu) {
          destroy();
          init();
        }
      },
      checkMedia = function() {
        if (responsive) {
          targetMediaQuery = null;
          for (let mediaQuery in responsive) {
            if (media(mediaQuery)) {
              targetMediaQuery = mediaQuery;
            }
          }
          if (targetMediaQuery !== currentMediaQuery) {
            applyMediaParams();
          }
        }
        if (!menu) {
          init();
        }
      },
      options = JSON.parse(JSON.stringify(_)),
      initialOptions = JSON.parse(JSON.stringify(_)),
      responsive = _.responsive,
      targetMediaQuery = null,
      currentMediaQuery = null,
      menu,
      menuCnt,
      openBtn,
      closeBtn,
      swipeStartTime,
      swipeEndTime,
      allowPageScroll,
      swipeThreshold = 0.5,
      toRight,
      toLeft,
      initialTransformX,
      fade,
      startPageY = pageYOffset,
      trfRegExp = /([-0-9.]+(?=px))/,
      isSwipe = false,
      isScroll = false,
      allowSwipe = false,
      opened = false,
      posX1 = 0,
      posX2 = 0,
      posY1 = 0,
      posY2 = 0,
      posInitX = 0,
      posInitY = 0,
      posFinal = 0,
      menuWidth = 0;
  
    if (_.menu) {
      // Элементы не изменяются через responsive
      checkMedia();
  
      windowFuncs.resize.push(checkMedia);
  
      // Если разрешена прокрутка, то закрываем при прокрутке
      // if (allowPageScroll) {
      //   windowFuncs.scroll.push(closeMenu);
      // }
  
      return {
        options: options,
        menu: menu,
        menuCnt: menuCnt,
        openBtn: openBtn,
        closeBtn: closeBtn,
        open: openMenu,
        close: closeMenu,
        destroy: destroy,
        opened: opened
      };
    }
  };

  // В основном для IE
  if (!NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach;
  }

  if (!HTMLCollection.prototype.forEach) {
    HTMLCollection.prototype.forEach = Array.prototype.forEach;
  }

  fakeScrollbar = id('fake-scrollbar');

  burger = q('.hdr__burger');

  hdr = q('.hdr');

  menu = mobileMenu({
    menu: q('.menu'),
    menuCnt: q('.menu__cnt'),
    openBtn: burger,
    closeBtn: q('.menu__close'),
    // toRight: true,
    fade: true,
    allowPageScroll: false
  });

  if (q('.side-menu')) {
    if (media('(max-width:767.98px)')) {
      sideMenu = mobileMenu({
        menu: q('.side-menu'),
        menuCnt: q('.side-menu__cnt'),
        openBtn: burger,
        closeBtn: q('.side-menu__close'),
        fade: true,
        allowPageScroll: false
      });
      sideMenu.menu.addEventListener('menubeforeopen', function() {
        let images = qa('.side-menu__pic > source, .side-menu__logout-pic > source', this);
        for (let i = images.length - 1; i >= 0; i--) {
          images[i].removeAttribute('media');
        }
      });
      // sideMenu.open();
    } else {
      sideMenu && sideMenu.destroy();
    }
  }

  let navLinks = qa('.hdr .nav-link, .ftr .nav-link, .index-hero__btn, .index-invite__btn');

  for (let i = 0, len = navLinks.length; i < len; i++) {
    navLinks[i].addEventListener('click', scrollToTarget);
  }

  // sticky(hdr);

  // thanksPopup = new Popup('.thanks', {
  // closeButtons: '.thanks__close'
  // });

  // Инициализация lazyload
  lazy = new lazyload({
    clearSrc: true,
    clearMedia: true
  });

  window.svg4everybody && svg4everybody();

  // Добавление расчета vh на ресайз окна
  windowFuncs.resize.push(setVh);

  // Сбор событий resize, load, scroll и установка на window
  for (let eventType in windowFuncs) {
    if (eventType !== 'call') {
      let funcsArray = windowFuncs[eventType];
      if (funcsArray.length > 0) {
        windowFuncs.call(funcsArray);
        window.addEventListener(eventType, windowFuncs.call);
      }
    }
  }

  // настройки grab курсора на всех слайдерах
  // let slickLists = $('.slick-list.draggable');

  // slickLists.on('mousedown', function() {
  //   $(this).addClass('grabbing');
  // }).on('beforeChange', function() {
  //   $(this).removeClass('grabbing');
  // });

  // $(document).on('mouseup', function() {
  //   slickLists.removeClass('grabbing');
  // });
});