//=include components/utils.js

;
(function() {

  if (typeof acf !== 'undefined') {
    acf.addAction('append_field/key=field_61c1703f55e8b', function(i) {
      console.log(i);
      let td = i.$el[0],
        parentTbody = td.closest('tbody'),

        hideDateInput = q('input[type="hidden"]', td),
        visibleDateInput = q('input[type="text"]', td),
        currentDate = new Date(),
        nextMonday = new Date(),
        currentDay = currentDate.getDate(),
        currentMonth = currentDate.getMonth() + 1,
        currentYear = currentDate.getFullYear();

      nextMonday.setDate(currentDate.getDate() + (((1 + 7 - currentDate.getDay()) % 7) || 7));

      let nexMondayDay = nextMonday.getDate(),
        nextMondayMonth = nextMonday.getMonth() + 1,
        nextMondayYear = nextMonday.getFullYear(),
        nextMondayDate = `${nexMondayDay}.${nextMondayMonth}.${nextMondayYear}`;

      currentDate = `${currentDay}.${currentMonth}.${currentYear}`;

      // console.log('currentDay', currentDay);
      // console.log('currentMonth', currentMonth);
      // console.log('currentYear', currentYear);

      // console.log('nexMondayDay', nexMondayDay);
      // console.log('nextMondayMonth', nextMondayMonth);
      // console.log('nextMondayYear', nextMondayYear);

      // console.log('currentDate', currentDate);
      // console.log('nextMondayDate', nextMondayDate);

      hideDateInput.value = `${nextMondayYear}${nextMondayMonth}${nexMondayDay}`;
      visibleDateInput.value = nextMondayDate;

    });
  }

})();