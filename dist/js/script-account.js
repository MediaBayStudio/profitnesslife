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
    userAvatarForm = document.forms['user-avatar-form'],
    userAvatar = {
      source: q('source', userAvatarForm),
      img: q('img', userAvatarForm)
    },
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
      today = ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + date.getFullYear();

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
        weightGoalCurrent.textContent = Math.abs(weight - weightForm.getAttribute('data-target-weight')).toFixed(1) + ' /';

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

  userAvatarForm.addEventListener('change', function(e) {
    let data = new FormData(userAvatarForm);

    data.append('action', 'photo_send');
    data.append('type', 'avatar');

    userAvatarForm.classList.add('loading');

    fetch(userAvatarForm.action, {
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

        userAvatarForm.classList.remove('loading');
        
        userAvatar.source.srcset = response.img_webp;
        userAvatar.img.src = response.img;

        userAvatarForm.reset();
      })
      .catch(function(err) {
        console.log(err);
        errorPopup.openPopup();
        userAvatarForm.reset();
        userAvatarForm.classList.remove('loading');
      });
  });
})();

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

;
(function() {
  let weightChartSect = id('weight-chart-sect');

  if (weightChartSect) {
    let weightChartCanvas = id('weight-chart'),
      weightChartTabs = q('.weight-chart__tabs', weightChartSect),
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

    if (!weightChartSect.classList.contains('hide')) {
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
      console.log('slidesLength', slidesLength);
      // if (media('(min-width:575.98px)') && slides.length < 4) {
      if (media('(min-width:1279.98px)') && slides.length < 5) {
        console.log('here-1');
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      // } else if (media('(min-width:767.98px)') && slides.length < 5) {
      } else if (media('(min-width:1023.98px)') && slides.length < 4) {
        console.log('here-2');
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      // } else if (media('(min-width:1023.98px)') && slides.length < 4) {
      } else if (media('(min-width:767.98px)') && slides.length < 4) {
        console.log('here-3');
        if (SLIDER.hasSlickClass($slidesSect)) {
          SLIDER.unslick($slidesSect);
        }
      // } else if (media('(min-width:1279.98px)') && slides.length < 5) {
      } else if (media('(min-width:575.98px)') && slides.length < 4) {
        console.log('here-4');
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


  photoProgressForm.addEventListener('change', function(e) {
    let data = new FormData(photoProgressForm);

    data.append('action', 'photo_send');

    slider.classList.add('loading');

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
          errorPopup.openPopup();
        }
      })
      .then(function(response) {
        response = JSON.parse(response);

        let img = response.img,
          imgWebp = response.img_webp;

        if (!img) {
           photoProgressForm.reset();
          slider.classList.remove('loading');
          errorPopup.openPopup();
          return;
        }

        let slide = '<picture class="photo-progress-pic">';
        if (imgWebp) {
          slide += '<source type="image/webp" srcset="' + imgWebp + '">';
        }
        slide +=  '<img src="' + response.img + '" alt="Фото" class="photo-progress-img"></picture>';

        if (SLIDER.hasSlickClass($slidesSect)) {
          $slidesSect.slick('slickAdd', slide, 1, true);
        } else {
          photoProgressForm.insertAdjacentHTML('afterend', slide);
        }

        slides = qa('form, picture', slider);
        slidesLength = slides.length;

        buildSlider();

        photoProgressForm.reset();

        slider.classList.remove('loading');
      })
      .catch(function(err) {
        console.log(err);
        errorPopup.openPopup();
        slider.classList.remove('loading');
        photoProgressForm.reset();
      });
  });
})();

//=include ../sections/footer/footer.js

});