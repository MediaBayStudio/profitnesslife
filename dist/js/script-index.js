document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

;
(function() {
  let marquee = id('marquee'),
    marqueeText = marquee.getAttribute('data-date'),
    marqueeCnt = '';

  for (let i = 100; i >= 0; i--) {
    marqueeCnt += marqueeText;
  }

  marquee.innerHTML = marqueeCnt;
  marquee.classList.add('animating');
})();

;
(function() {
  let indexFeaturesBlock = qa('.index-features__features');

  for (let i = 0, len = indexFeaturesBlock.length; i < len; i++) {
    let slides = qa('.index-features__feature', indexFeaturesBlock[i]),
      slidesLength = slides.length,
      buildSlider = function() {
        let $slidesSect = $(indexFeaturesBlock[i]);
        // если ширина экрана больше 578px и слайдов меньше 4, то слайдера не будет
        if (media('(min-width:575.98px)') && slides.length < 3) {
          if (SLIDER.hasSlickClass($slidesSect)) {
            SLIDER.unslick($slidesSect);
          }
          // если ширина экрана больше 1440px и слайдов меньше 7, то слайдера не будет
        } else if (media('(min-width:1023.98px)')) {
          if (SLIDER.hasSlickClass($slidesSect)) {
            SLIDER.unslick($slidesSect);
          }
          // в других случаях делаем слайдер
        } else {
          if (SLIDER.hasSlickClass($slidesSect)) {
            // слайдер уже создан
            return;
          }
          if (slides.length && slides.length > 2) {
            $slidesSect.slick({
              infinite: false,
              arrows: false, // true by default
              dots: true,
              dotsClass: 'index-hero__features-dots dots',
              appendDots: $('.index-features__nav', indexFeaturesBlock[i].parentElement),
              customPaging: function() {
                return SLIDER.dot;
              },
              mobileFirst: true,
              responsive: [{
                breakpoint: 575.98,
                settings: {
                  slidesToShow: 2,
                  slidesToScroll: 2
                }
              }]
            });
          }
        }
      };

      buildSlider();
      windowFuncs.resize.push(buildSlider);
  }

})();

;
(function() {
  let tabClass = 'index-interface__screens-tab',
    screenClass = 'index-interface__screen',
    screensBlock = q('.index-interface__screens'),
    tabBackground = q('.index-interface__screens-tab-background', screensBlock),
    tabs = qa('.' + tabClass, screensBlock, true),
    screens = qa('.' + screenClass, screensBlock),
    setBackground = function(target) {
      tabBackground.style.transform = 'translate3d(' + target.offsetLeft + 'px, 0px, 0px)';
      tabBackground.style.height = target.offsetHeight + 'px';
      tabBackground.style.width = target.offsetWidth + 'px';
    };

  screensBlock.addEventListener('click', function(e) {
    let target = e.target;
    if (target.classList.contains(tabClass)) {
      let activeElements = qa('.' + tabClass + '.active, .' + screenClass + '.active', screensBlock);

      activeElements.forEach(activeElement => activeElement.classList.remove('active'));

      target.classList.add('active');
      screens[tabs.indexOf(target)].classList.add('active');

      setBackground(target);
    }
  });

  setBackground(tabs[0])
})();

//=include ../sections/index-invite/index-invite.js

;
(function() {
  let reviewsSlider = q('.reviews-sect__reviews'),
    slidesSelector = '.reviews-sect__review';

  // lazyload ?

  if (reviewsSlider) {
    let changeSwipe = function(e) {
        let target = e.target,
          option;

        if (e.type === 'touchend') {
          option = true;
        } else {
          let parent = target.closest('.review__photos');
          console.log([parent]);
          if (parent) {
            let scrollWidth = parent.scrollWidth,
                clientWidth = parent.clientWidth,
                scrollLeft = parent.scrollLeft;
            // Если внутри блока есть прокрутка
            if (scrollWidth > clientWidth) {
              // Если прокрутка прокручена до конца
              // if (scrollLeft + clientWidth === scrollWidth) {
                // option = true;
              // } else {
                option = false;
              // }
            } else {
              option = true;
            }
          } else {
            option = true;
          }
        }

        $(reviewsSlider).slick('slickSetOption', 'swipe', option);
      },
      initReviewsSlider = function() {
        console.log('initReviewsSlider');
        let buildReviewsSlider = function() {
          console.log('buildReviewsSlider');
          let $slider = $(reviewsSlider),
            slides = qa(slidesSelector, reviewsSlider);

          if (SLIDER.hasSlickClass($slider)) {
            return;
          }
          if (slides.length && slides.length > 1) {
            $slider.slick({
              appendDots: $('.reviews-sect__nav', reviewsSlider.parentElement),
              slide: slidesSelector,
              infinite: false,
              arrows: false,
              dots: true,
              swipe: false,
              draggable: false,
              dotsClass: 'reviews-sect__dots dots',
              customPaging: function() {
                return SLIDER.dot;
              }
            });
          }

          reviewsSlider.addEventListener('touchstart', changeSwipe);
          reviewsSlider.addEventListener('touchend', changeSwipe);
          // $slider.on('afterChange', function() {
          //   qa('.review__photos', reviewsSlider).forEach(el => el.scrollLeft = 0);
          //   $slider.slick('slickSetOption', 'swipe', true);
          // });

          // reviewsSlider.addEventListener('touchstart', disallowSwipe);
          // reviewsSlider.addEventListener('touchend', allowSwipe);
        }

        buildReviewsSlider();
        windowFuncs.resize.push(buildReviewsSlider);

        reviewsSlider.removeEventListener('lazyloaded', initReviewsSlider);
      };


    reviewsSlider.addEventListener('lazyloaded', initReviewsSlider);
  }
})();

;
(function() {
  let teamSlider = q('.index-team__team'),
    slidesSelector = '.index-team__person';

  // lazyload ?

  if (teamSlider) {
    let initTeamBlock = function() {
      console.log('initTeamBlock');
      let buildTeamSlider = function() {
          console.log('buildTeamSlider');
          let $slider = $(teamSlider),
            slides = qa(slidesSelector, teamSlider);

          if (SLIDER.hasSlickClass($slider)) {
            return;
          }
          if (slides.length && slides.length > 1) {
            $slider.slick({
              appendDots: $('.index-team__nav', teamSlider.parentElement),
              slide: slidesSelector,
              infinite: false,
              arrows: false,
              dots: true,
              draggable: false,
              dotsClass: 'index-team__dots dots',
              customPaging: function() {
                return SLIDER.dot;
              },
              mobileFirst: true,
              responsive: [{
                breakpoint: 575.98,
                settings: {
                  appendArrows: $('.index-team__nav', teamSlider.parentElement),
                  arrows: true,
                  nextArrow: SLIDER.createArrow('index-team__next', SLIDER.arrow),
                  prevArrow: SLIDER.createArrow('index-team__prev', SLIDER.arrow)
                }
              }]
            });
          }
        },
        itemsBlock = qa('.index-team__person-info', teamSlider),
        minHeightElement = '.index-team__person-prop-title',
        clickTragetClasses = [
          'index-team__person-prop-title'
          // 'faq__question-text'
        ],
        itemClass = '.index-team__person-prop';

      for (let i = 0, len = itemsBlock.length; i < len; i++) {
        let items = itemsBlock[i].children,
          dropdownText = function(element) {

            if (media('(min-width:1023.98px)')) {
              return;
            }

            if (!element) {
              for (var i = items.length - 1; i >= 0; i--) {
                let minHeightEl = q(minHeightElement, items[i]);
                if (minHeightEl) {
                  items[i].style.maxHeight = minHeightEl.scrollHeight + 'px';
                } else {
                  return;
                }
              }
              return;
            }

            let parent = element.closest(itemClass),
              activeElement = q('.active', itemsBlock[i]);


            if (parent) {
              parent.classList.add('active');
              parent.style.maxHeight = parent.scrollHeight + 'px'
            }

            if (activeElement) {
              activeElement.classList.remove('active');
              let minHeightEl = q(minHeightElement, activeElement);
              if (minHeightEl) {
                activeElement.style.maxHeight = minHeightEl.scrollHeight + 'px';
              } else {
                return;
              }
            }

          };

        dropdownText();

        windowFuncs.resize.push(dropdownText);

        itemsBlock[i].addEventListener('click', function() {
          let target = event.target;

          if (clickTragetClasses.some(className => target.classList.contains(className))) {
            dropdownText(target);
          }

        });
      }

      buildTeamSlider();
      windowFuncs.resize.push(buildTeamSlider);

      teamSlider.removeEventListener('lazyloaded', initTeamBlock);
    };


    teamSlider.addEventListener('lazyloaded', initTeamBlock);
  } // endif teamBlock
})();

//=include ../sections/index-form/index-form.js

;
(function() {
  let paymentSlider = q('.index-payment__list'),
    slidesSelector = '.index-payment__method';

  // lazyload ?

  if (paymentSlider) {
    let initPaymentSlider = function() {
      console.log('initPaymentSlider');
      let buildPaymentSlider = function() {
        console.log('buildPaymentSlider');
        let $slider = $(paymentSlider),
          slides = qa(slidesSelector, paymentSlider);

        if (media('(min-width:1023.98px)')) {
          if (SLIDER.hasSlickClass($slider)) {
            SLIDER.unslick($slider);
          }
        } else if (media('(min-width:767.98px)') && slides.length < 3) {
          if (SLIDER.hasSlickClass($slider)) {
            SLIDER.unslick($slider);
          }
        } else {
          if (SLIDER.hasSlickClass($slider)) {
            return;
          }
          if (slides.length && slides.length > 1) {
            $slider.slick({
              appendDots: $('.index-payment__nav', paymentSlider.parentElement),
              slide: slidesSelector,
              infinite: false,
              arrows: false,
              dots: true,
              dotsClass: 'index-payment__dots dots',
              customPaging: function() {
                return SLIDER.dot;
              },
              mobileFirst: true,
              responsive: [{
                breakpoint: 575.98,
                settings: {
                  slidesToShow: 2,
                  slidesToScroll: 2
                }
              }]
            });
          }
        }
      };

      buildPaymentSlider();
      windowFuncs.resize.push(buildPaymentSlider);

      paymentSlider.removeEventListener('lazyloaded', initPaymentSlider);
    };


    paymentSlider.addEventListener('lazyloaded', initPaymentSlider);
  }
})();

//=include ../sections/index-chat/index-chat.js

;
(function() {
  let instagramSlider = q('.index-instagram__posts'),
    slidesSelector = '.instagram-post';

  // lazyload ?

  if (instagramSlider) {
    let initInstagramSlider = function() {
      console.log('initInstagramSlider');
      let buildInstagramSlider = function() {
        console.log('buildInstagramSlider');
        let $slider = $(instagramSlider),
          slides = qa(slidesSelector, instagramSlider);

        if (media('(min-width:1279.98px)') && slides.length < 5) {
          if (SLIDER.hasSlickClass($slider)) {
            SLIDER.unslick($slider);
          }
        } else if (media('(min-width:767.98px)') && slides.length < 4) {
          if (SLIDER.hasSlickClass($slider)) {
            SLIDER.unslick($slider);
          }
        } else if (media('(min-width:575.98px)') && slides.length < 3) {
          if (SLIDER.hasSlickClass($slider)) {
            SLIDER.unslick($slider);
          }
        } else {
          if (SLIDER.hasSlickClass($slider)) {
            return;
          }
          if (slides.length && slides.length > 1) {
            $slider.slick({
              slide: slidesSelector,
              infinite: false,
              arrows: false,
              centerMode: true,
              centerPadding: 'calc((100vw - 320px)/ (575 - 320)*(135 - 50) + 50px)',
              mobileFirst: true,
              responsive: [{
                breakpoint: 575.98,
                settings: {
                  centerMode: false,
                  slidesToShow: 2,
                  slidesToScroll: 2
                }
              }, {
                breakpoint: 767.98,
                settings: {
                  centerMode: false,
                  slidesToShow: 3,
                  slidesToScroll: 3
                }
              }, {
                breakpoint: 1279.98,
                settings: {
                  centerMode: false,
                  slidesToShow: 4,
                  slidesToScroll: 4
                }
              }]
            });
          }
        }
      };

      buildInstagramSlider();
      windowFuncs.resize.push(buildInstagramSlider);

      instagramSlider.removeEventListener('lazyloaded', initInstagramSlider);
    };


    instagramSlider.addEventListener('lazyloaded', initInstagramSlider);
  }
})();

//=include ../sections/footer/footer.js

});