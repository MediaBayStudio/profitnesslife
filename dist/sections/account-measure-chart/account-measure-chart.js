;
(function() {
  let measureForm = document.forms['measure-form'],
    measureChartCanvas = id('measure-chart'),
    measureChartCtx = measureChartCanvas.getContext('2d'),
    gridFontSize = media('(max-width:767.98px)') ? 10 : 16,
    legendFontSize = media('(max-width:767.98px)') ? 14 : 16;

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
            // align: 'start',
            padding: 10,
            labels: {
              padding: 15,
              boxWidth: 5,
              boxHeight: 5,
              usePointStyle: true,
              font: {
                size: legendFontSize,
                family: 'Roboto'
              }
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: '#B0BBA7',
              font: {
                size: gridFontSize,
                family: 'Roboto'
              }
            },
            grid: {
              display: false
            }
          },
          y: {
            ticks: {
              stepSize: 1,
              color: '#B0BBA7',
              font: {
                size: gridFontSize,
                family: 'Roboto'
              }
            },
            grid: {
              display: false
            }
          }
        }
      }
    });
  }

})();