;
(function() {
  let photoProgressForm = document.forms['photo-progress-form'],
    slider = id('photo-progress-slider'),
    $slidesSect = $(slider),
    slides = qa('form, picture', slider),
    slidesLength = slides.length,

    buildSlider = function() {
      console.log('slidesLength', slidesLength);
      // if (media('(min-width:575.98px)') && slides.length < 4) {
      if (media('(min-width:1279.98px)') && slides.length < 5) {
        console.log('here-1');
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      // } else if (media('(min-width:767.98px)') && slides.length < 5) {
      } else if (media('(min-width:1023.98px)') && slides.length < 4) {
        console.log('here-2');
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      // } else if (media('(min-width:1023.98px)') && slides.length < 4) {
      } else if (media('(min-width:767.98px)') && slides.length < 4) {
        console.log('here-3');
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      // } else if (media('(min-width:1279.98px)') && slides.length < 5) {
      } else if (media('(min-width:575.98px)') && slides.length < 4) {
        console.log('here-4');
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
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: false,
            appendArrows: $('.photo-progress-slider__nav', slider.parentElement),
            nextArrow: SLIDER.createArrow('photo-progress-slider__next', SLIDER.arrow),
            prevArrow: SLIDER.createArrow('photo-progress-slider__prev', SLIDER.arrow),
            dots: true,
            variableWidth: true,
            slide: 'form, picture',
            dotsClass: 'photo-progress-slider__dots dots',
            appendDots: $('.photo-progress-slider__nav', slider.parentElement),
            customPaging: function() {
              return SLIDER.dot;
            },
            mobileFirst: true,
            responsive: [{
              breakpoint: 575.98,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3
              }
            }, {
              breakpoint: 767.98,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 4
              }
            }, {
              breakpoint: 1023.98,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3
              }
            }, {
              breakpoint: 1279.98,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 4
              }
            }]
          });
        }
      }
    };

  buildSlider();
  windowFuncs.resize.push(buildSlider);


  photoProgressForm.addEventListener('change', function(e) {
    let data = new FormData(photoProgressForm);

    data.append('action', 'photo_send');

    slider.classList.add('loading');

    fetch(photoProgressForm.action, {
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
        response = JSON.parse(response);
        console.log(response);
        let slide = `<picture class="photo-progress-pic">
          <source type="image/webp" srcset="${response.img_webp}">
          <img src="${response.img}" alt="Фото" class="photo-progress-img">
        </picture>`;

        if (SLIDER.hasSlickClass($slidesSect)) {
          $slidesSect.slick('slickAdd', slide, 1, true);
        } else {
          photoProgressForm.insertAdjacentHTML('afterend', slide);
        }

        slides = qa('form, picture', slider);
        slidesLength = slides.length;

        buildSlider();

        photoProgressForm.reset();

        slider.classList.remove('loading');
      })
      .catch(function(err) {
        console.log(err);
      });
  });
})();