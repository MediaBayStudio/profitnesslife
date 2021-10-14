document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

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

;
(function() {
  let weightChartCanvas = id('weight-chart');
  if (weightChartCanvas) {
    let weightChartTabs = q('.weight-chart__tabs'),
      activeWeekBtn = q('.weight-chart__tab.active', weightChartTabs),
      weekData = JSON.parse(activeWeekBtn.getAttribute('data-chart')),
      weightChartCtx = weightChartCanvas.getContext('2d'),
      updateWeightChart = function(e) {
        let target = e.target,
          chartData = target.getAttribute('data-chart');

        if (target.classList.contains('weight-chart__tab') && chartData) {
          chartData = JSON.parse(chartData);

          let dates = [],
            weights = [];

          for (let key in chartData) {
            dates[dates.length] = chartData[key].date.slice(0, -5);
            weights[weights.length] = chartData[key].weight;
          }

          weightChart.data.labels = dates;
          weightChart.data.datasets.forEach(dataset => dataset.data = weights);
          weightChart.options.scales.y.min = weights[weights.length - 1] - 2;
          weightChart.options.scales.y.max = +weights[0] + 1;
          weightChart.update();
        }
      };

    weightChartTabs.addEventListener('click', updateWeightChart);

    if (!weekData) {
      activeWeekBtn = q('.weight-chart__tab[data-chart]', weightChartTabs);
      weekData = JSON.parse(activeWeekBtn.getAttribute('data-chart'));
    }

    let dates = [],
      weights = [],
      gridFontSize = media('(max-width:767.98px)') ? 10 : 16;

    for (let key in weekData) {
      dates[dates.length] = weekData[key].date.slice(0, -5);
      weights[weights.length] = weekData[key].weight;
    }


    weightChart = new Chart(weightChartCtx, {
      type: 'line',
      defaults: {
        borderColor: 'red'
      },
      data: {
        labels: dates,
        datasets: [{
          label: 'Вес, кг',
          data: weights,
          backgroundColor: '#85B921',
          borderColor: '#85B921',
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false
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
            },
            beginAtZero: true,
            min: weights[weights.length - 1] - 2,
            max: +weights[0] + 1
          }
        }
      }
    });
  }
})();

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

;
(function() {
  let photoProgressForm = document.forms['photo-progress-form'],
    slider = id('photo-progress-slider'),
    $slidesSect = $(slider),
    slides = qa('form, picture', slider),
    slidesLength = slides.length,

    buildSlider = function() {
      if (media('(min-width:575.98px)') && slides.length < 4) {
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      } else if (media('(min-width:767.98px)') && slides.length < 5) {
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      } else if (media('(min-width:1023.98px)') && slides.length < 4) {
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      } else if (media('(min-width:1279.98px)') && slides.length < 5) {
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


  console.log($slidesSect);



  photoProgressForm.addEventListener('change', function(e) {
    let data = new FormData(photoProgressForm);

    data.append('action', 'photo_send');

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

        $slidesSect.slick('slickAdd', slide, 1, true);
        photoProgressForm.reset();
      })
      .catch(function(err) {
        console.log(err);
      });
  });
})();

//=include ../sections/footer/footer.js

});