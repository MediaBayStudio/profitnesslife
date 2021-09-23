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