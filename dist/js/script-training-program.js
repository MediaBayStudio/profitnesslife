document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

//=include ../sections/training-program-hero/training-program-hero.js

;(function() {
  let workoutBlocks = qa('.workout-block'),
    slidesSelector = '.workout';

  workoutBlocks.forEach(function(workoutBlock) {
    let $workoutBlock = $(workoutBlock);

    $workoutBlock.slick({
      variableWidth: true,
      infinite: false,
      arrows: false,
      slide: slidesSelector,
      mobileFirst: true,
      responsive: [{
        breakpoint: 575.98,
        settings: {
          slidesToShow: 2
        }
      }, {
        breakpoint: 767.98,
        settings: 'unslick'
      }]
    });
  });

})();

//=include ../sections/footer/footer.js

});