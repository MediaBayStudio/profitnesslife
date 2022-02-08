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
              // infinite: false,
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