document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

//=include ../sections/questionnaire-complete/questionnaire-complete.js

;
(function() {

  let questionnaireForm = id('questionnaire-form');

  if (questionnaireForm) {
    questionnaireForm.addEventListener('change', function(e) {
      let input = e.target,
        checkedInput = q('input[name="' + input.name + '"].checked', questionnaireForm);

      if (checkedInput) {
        checkedInput.classList.remove('checked');
        checkedInput.parentElement.classList.remove('checked');
      }

      input.classList.add('checked');
      input.parentElement.classList.add('checked');
    });
  }

})();

//=include ../sections/footer/footer.js

});