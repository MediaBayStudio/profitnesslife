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