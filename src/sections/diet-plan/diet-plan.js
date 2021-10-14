;(function() {
  let calendar = q('.diet-plan__calendar'),
    dietPlanList = q('.diet-plan__list'),
    dietPlanDay = q('.diet-plan__day'),
    dietPlanDate = q('.diet-plan__date'),
    events = qa('.event', calendar),
    setDataDay = function(days) {
      for (let i = 0, len = days.length; i < len; i++) {
        days[i].setAttribute('data-day', i);
      }
    };

  events.forEach(function(event, i) {
    let parent = event.parentElement,
      next = parent.nextElementSibling,
      prev = parent.previousElementSibling;

    setDataDay(parent.children);
    parent.classList.add('active-week');

    if (i === 0 && next) {
      next.classList.add('active-week');
      next.setAttribute('data-week', 2);
      setDataDay(next.children);
    }

    if (i === 1 && prev) {
      prev.classList.add('active-week');
      prev.setAttribute('data-week', 2);
      setDataDay(prev.children);
    }
  });

  events[0].parentElement.setAttribute('data-week', 1);
  events[1].parentElement.setAttribute('data-week', 3);

  calendar.addEventListener('click', function(e) {
    let target = e.target;

    if (target.classList.contains('calendar-day') && target.parentElement.classList.contains('active-week')) {
      let today = q('.today', calendar),
        tragetElementDate = target.getAttribute('data-date').slice(-10),
        week = target.parentElement.getAttribute('data-week'),
        day = target.getAttribute('data-day'),
        data = 'action=load_diet_plan&day=' + target.textContent +
          '&week=' + week +
          '&day_index=' + day;

      calendar.classList.add('loading');

      fetch(siteUrl + '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
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
        let dayName = '',
          targetDate = new Date(tragetElementDate.slice(-4), tragetElementDate.slice(3, 5), tragetElementDate.slice(0, 2)).getDate(),
          todayDate = new Date().getDate();


          console.log('tragetElementDate', tragetElementDate);
        console.log('targetDate', targetDate);
        console.log('todayDate', todayDate);

        if (targetDate === todayDate) {
          dayName = 'сегодня, ';
        } else if (todayDate - targetDate === 1) {
          dayName = 'вчера, ';
        } else if (todayDate + 1 === targetDate) {
          dayName = 'завтра, ';
        }

        dietPlanList.innerHTML = response;
        dietPlanDay.textContent = 'День ' + ((+day + 1) * week);
        dietPlanDate.innerHTML = '<span class="diet-plan__today">' + dayName + '</span>' + target.getAttribute('data-date').slice(-10);
        // console.log(response);
        // console.log(JSON.parse(response));
        calendar.classList.remove('loading');
        today.classList.remove('today');
        target.classList.add('today');
      })
      .catch(function(err) {
        console.log(err);
      });
    }
  });

})();