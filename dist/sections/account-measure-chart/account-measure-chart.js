;
(function() {
  let measureForm = document.forms['measure-form'],
    measureChartCanvas = id('measure-chart'),
    measureChartCtx = measureChartCanvas.getContext('2d');

  console.log(measureForm);
  console.log(measureChartCanvas);
  console.log(measureChartCtx);

  if (measureForm) {
    measureForm.addEventListener('input', function(e) {
      measureForm.submit.classList.toggle('disabled', measureForm.chest.value.length < 2 || measureForm.waist.value.length < 2 || measureForm.hip.value.length < 2);
    });
    measureForm.addEventListener('submit', function(e) {
      e.preventDefault();

      let data = new FormData(measureForm),
        date = new Date(),
        today = ('0' + date.getDate()).slice(-2) + '.' + (date.getMonth() + 1) + '.' + date.getFullYear();

      data.append('action', 'measure_send');
      data.append('date', today);

      measureForm.classList.add('loading');

      fetch(measureForm.action, {
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
          measureForm.classList.remove('loading');
          measureForm.classList.add('disabled');

          console.log(response);

          id('measure-chest-value').textContent = measureForm['chest'].value;
          id('measure-waist-value').textContent = measureForm['waist'].value;
          id('measure-hip-value').textContent = measureForm['hip'].value;
          id('measure-date').textContent = today;

        })
        .catch(function(err) {
          console.log(err);
        });
    });

    measureChart = new Chart(measureChartCtx, {
      type: 'line',
      data: {
        labels: ['11.10', '12.10', '13.10'],
        datasets: [{
          label: 'Грудь',
          data: [100, 99, 98],
          backgroundColor: '#85B921',
          borderColor: '#85B921',
          borderWidth: 1
        }, {
          label: 'Талия',
          data: [110, 108, 107],
          backgroundColor: '#F0BE0F',
          borderColor: '#F0BE0F',
          borderWidth: 1
        }, {
          label: 'Бёдра',
          data: [77, 76, 75],
          backgroundColor: '#E99A8B',
          borderColor: '#E99A8B',
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            labels: {
              boxWidth: 10,
              boxHeight: 10,
              usePointStyle: true,
              font: {
                size: 14,
                family: 'Roboto'
              }
            }
          }
        }
      }
      // options: {
      //   scales: {
      //     y: {
      //       beginAtZero: true,
      //       min: weights[weights.length - 1] - 2,
      //       max: +weights[0] + 1,
      //       ticks: {
      //         stepSize: 1
      //       }
      //     }
      //   }
      // }
    });
  }

})();