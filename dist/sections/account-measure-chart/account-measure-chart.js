;
(function() {
  let measureForm = document.forms['measure-form'],
    measureChartCanvas = id('measure-chart'),
    measureChartCtx = measureChartCanvas.getContext('2d'),
    gridFontSize = media('(max-width:767.98px)') ? 10 : 16,
    legendFontSize = media('(max-width:767.98px)') ? 14 : 16;

  if (measureForm) {

    let initialDate = measureChartCanvas.getAttribute('data-initial-date'),
      initialChest = measureChartCanvas.getAttribute('data-initial-chest'),
      initialWaist = measureChartCanvas.getAttribute('data-initial-waist'),
      initialHip = measureChartCanvas.getAttribute('data-initial-hip'),
      measureChartData = measureChartCanvas.getAttribute('data-measure-chart'),
      measureChartIsCreated = measureChartData !== 'null',
      createMeasureChart = function(date, chest, waist, hip) {
        measureChart = new Chart(measureChartCtx, {
          type: 'line',
          data: {
            labels: date,
            datasets: [{
              label: 'Грудь',
              data: chest,
              backgroundColor: '#85B921',
              borderColor: '#85B921',
              borderWidth: 1
            }, {
              label: 'Талия',
              data: waist,
              backgroundColor: '#F0BE0F',
              borderColor: '#F0BE0F',
              borderWidth: 1
            }, {
              label: 'Бёдра',
              data: hip,
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
      },
      parseMeasureChartData = function(measureData) {
        measureData = measureData || measureChartCanvas.getAttribute('data-measure-chart');

        console.log(measureData);

        let data = measureData.constructor === Array ? measureData : JSON.parse(measureData),
          date = [initialDate.slice(0, -5)],
          chest = [],
          waist = [],
          hip = [];

        initialChest && chest.push(initialChest);
        initialWaist && waist.push(initialWaist);
        initialHip && hip.push(initialHip);

        data.forEach(function(el) {
          date.push(el.date.slice(0, -5));
          chest.push(el.chest);
          waist.push(el.waist);
          hip.push(el.hip);
        });

        return {
          'date': date,
          'chest': chest,
          'waist': waist,
          'hip': hip
        }
      };

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
      measureForm.submit.blur();

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
          console.log(response);
          response = JSON.parse(response);
          measureForm.classList.add('disabled');
          measureForm.classList.remove('loading');
          q('.measure-current').classList.remove('no-data');
          q('.measure-form__descr').textContent = 'Будет доступно завтра';
          id('measure-chest-value').textContent = measureForm['chest'].value;
          id('measure-waist-value').textContent = measureForm['waist'].value;
          id('measure-hip-value').textContent = measureForm['hip'].value;
          id('measure-date').textContent = today;

          if (measureChartIsCreated) {
            measureChartData = parseMeasureChartData(response.chart_data);
            measureChart.data.labels = measureChartData.date;
            measureChart.data.datasets.forEach(function(dataset) {
              let bodyPart;
              switch (dataset.label) {
                case 'Грудь':
                  bodyPart = 'chest';
                  break;
                case 'Талия':
                  bodyPart = 'waist';
                  break;
                case 'Бёдра':
                  bodyPart = 'hip';
                  break;
              }
              return dataset.data = measureChartData[bodyPart];
            });
            measureChart.update();
            console.log('update');
          } else {
            if (measureChartData === 'null') {
              if (!response.initial_measure) {
                // Строим график только если измерение не первоначальное
                measureChartData = parseMeasureChartData(response.chart_data);
                measureChartCanvas.classList.remove('hide');
                createMeasureChart(measureChartData.date, measureChartData.chest, measureChartData.waist, measureChartData.hip);
                measureChartIsCreated = true;
              }
            } else {
              measureChartData = parseMeasureChartData();
              measureChartCanvas.classList.remove('hide');
              createMeasureChart(measureChartData.date, measureChartData.chest, measureChartData.waist, measureChartData.hip);
              measureChartIsCreated = true;
            }
          }

          // Очищаем поля формы
          measureForm['chest'].value = '';
          measureForm['waist'].value = '';
          measureForm['hip'].value = '';

          measureForm['chest'].setAttribute('tabindex', '-1');
          measureForm['waist'].setAttribute('tabindex', '-1');
          measureForm['hip'].setAttribute('tabindex', '-1');

        })
        .catch(function(err) {
          console.log(err);
        });
    });

    // Постройка графика
    if (initialChest && initialWaist && initialHip) {
      console.log(measureChartData);

      if (measureChartData !== 'null') {
        measureChartData = parseMeasureChartData();

        createMeasureChart(measureChartData.date, measureChartData.chest, measureChartData.waist, measureChartData.hip);
        measureChartIsCreated = true;
      }
    }

  }

})();