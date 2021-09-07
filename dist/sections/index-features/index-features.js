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
                  slidesToShow: 2
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