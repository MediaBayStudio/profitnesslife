document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

;
(function() {
  let marquee = id('marquee'),
    marqueeText = marquee.getAttribute('data-date'),
    marqueeCnt = '';

  for (let i = 70; i >= 0; i--) {
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

//=include ../sections/footer/footer.js

});