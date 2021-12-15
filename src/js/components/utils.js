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
  // Сокращение записи querySelector
  q = function(selector, element) {
    element = element || body;
    return element.querySelector(selector);
  },
  // Сокращение записи querySelectorAll + перевод в массив
  qa = function(selectors, element, toArray) {
    element = element || body;
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