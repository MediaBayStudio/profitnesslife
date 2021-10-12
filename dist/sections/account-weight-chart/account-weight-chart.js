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
      weights = [];

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
        scales: {
          x: {
            ticks: {
              color: '#B0BBA7',
              font: {
                size: 10,
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
                size: 10,
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