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