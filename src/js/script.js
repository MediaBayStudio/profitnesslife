//=include components/utils.js

document.addEventListener('DOMContentLoaded', function() {
  body = document.body;

  //=include components/telMask.js
  

  //=include ../components/account-hero-block/account-hero-block.js
  //=include ../components/form-sign/form-sign.js

  //=include components/validateForms.js

  //=include components/menu.js

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

  sticky(hdr);

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