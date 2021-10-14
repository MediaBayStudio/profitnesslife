;
(function() {

  let weightForm = q('.weight-form'),
    weightChartSect = q('.weight-chart-sect'),
    weightGoalCurrent = id('weight-goal-current'),
    weightGoalTotal = id('weight-goal-total'),
    weightGoalSvgBar = id('weight-goal-svg-bar'),
    updateSvgBar = function(value) {
      let r = weightGoalSvgBar.getAttribute('r'),
        c = Math.PI * (r * 2);

      value = 100 - value;

      if (value <= 0) {
        return;
      }
      if (value > 100) {
        value = 100;
      }

      let pct = ((100 - value) / 100) * c;

      weightGoalSvgBar.style.opacity = 1;
      weightGoalSvgBar.style.strokeDashoffset = pct;
    },
    updateWeightChart = function(chart, label, data) {
      // Данные для вставки в кнопку
      let jsonData = {
          date: label,
          weight: data
        },
        activeButton = q('.weight-chart__tab.active'),
        currentWeekNumber = q('.weight-chart-sect').getAttribute('data-week'),
        currentWeekButton = q('.weight-chart__tab:nth-child(' + currentWeekNumber + ')'),
        buttonData = currentWeekButton.getAttribute('data-chart');

      if (buttonData) {
        let existChartData = JSON.parse(buttonData);
        existChartData.push(jsonData);
        jsonData = existChartData;
      }

      jsonData = JSON.stringify([jsonData]);

      currentWeekButton.setAttribute('data-chart', jsonData);

      if (activeButton) {
        activeButton.classList.remove('active');
      }
      weightChartSect.classList.remove('hide');
      currentWeekButton.classList.add('active');

      if (weightChart.config.data.datasets[0].data.length === 7) {
        chart.data.labels = [label.slice(0, -5)];
        chart.data.datasets.forEach(dataset => dataset.data = [data]);
        currentWeekButton.classList.remove('disabled');
        weightChart.options.scales.y.min = data - 2;
        weightChart.options.scales.y.max = +data + 1;
      } else {
        chart.data.labels.push(label.slice(0, -5));
        chart.data.datasets.forEach(dataset => dataset.data.push(data));
      }
      
      chart.options.scales.y.min = data - 2;
      chart.update();
    };

  updateSvgBar(parseInt(weightGoalCurrent.textContent) / parseInt(weightGoalTotal.textContent) * 100);

  weightForm['current-weight'].addEventListener('input', function(e) {
    weightForm.submit.classList.toggle('disabled', e.target.value.length <= 1);
  });

  weightForm.addEventListener('submit', function(e) {
    e.preventDefault();
    let data = new FormData(weightForm),
      date = new Date(),
      weight = weightForm['current-weight'].value,
      today = ('0' + date.getDate()).slice(-2) + '.' + (date.getMonth() + 1) + '.' + date.getFullYear();

    data.append('action', 'weight_send');
    data.append('date', today);

    weightForm.classList.add('loading');
    weightForm.submit.blur();

    fetch(weightForm.action, {
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
        weightForm.classList.remove('loading');
        weightForm.classList.add('disabled');

        id('current-weight-date').textContent = today;
        id('current-weight-number').innerHTML = weight + ' <span class="user-data__current-weight-units">кг</span>';
        weightGoalCurrent.textContent = Math.abs(weight - weightForm.getAttribute('data-target-weight')) + ' /';

        updateSvgBar(parseInt(weightGoalCurrent.textContent) / parseInt(weightGoalTotal.textContent) * 100);

        console.log(today);

        if (weightChart) {
          updateWeightChart(weightChart, today, weight);
        }

      })
      .catch(function(err) {
        console.log(err);
      });
  });
})();