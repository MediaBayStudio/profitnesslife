let fs = require('fs'),
  path = require('path'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  grid = config.cssBreakpoints.map(el => el.replace(/[^0-9.]*/g, '')),
  container = config.containerWidth,
  parseVariables = function() {
    let cnt = '',
      breakpoints = [];

    config.cssBreakpoints.forEach(function(breakpoint) {
      if (breakpoint) {
        let digits = +breakpoint.replace(/[^0-9.]/g, '');

        if ((digits ^ 0) !== digits) {
          // если число не целое, округлим десятичые
          digits = digits.toFixed();
        }

        cnt += '$mediaQuery' + digits + ': \'' + breakpoint + '\';\n';
        breakpoints.push(digits);
      } else {
        breakpoints.push('320');
      }
    });

    cnt += '\n';

    config.containerWidth.forEach((width, i) => cnt += `$containerWidth${breakpoints[i]}: ${width}px;\n`);

    cnt += '\n';

    for (let variableName in config.variables) {
      let variables = config.variables[variableName];
      if (Array.isArray(variables)) {
        variables.forEach(function(value, i) {
          // Если передано число, то добавим к нему пиксели
          let ending = isNaN(+value) ? '' : 'px';
          cnt += '$' + variableName + breakpoints[i] + ':' + value + ending + ';\n';
        });
      } else {
        cnt += '\n$' + variableName + ': ' + variables + ';\n';
      }
    }

    cnt += '\n';

    cnt += '$sectionHorizontalPadding: ' + (320 - container[0]) / 2 + 'px;\n';

    for (let i = 1; i < grid.length; i++) {
      cnt += '$sectionHorizontalPadding: calc(50vw - ' + Math.ceil(container[i]) / 2 + 'px);\n';
    }

    cnt += '\n';

    cnt += '$sectionHorizontalPaddingMinus: -' + (320 - container[0]) / 2 + 'px;\n';

    for (let i = 1; i < grid.length; i++) {
      cnt += '$sectionHorizontalPaddingMinus: calc((50vw - ' + Math.ceil(container[i]) / 2 + 'px) * -1);\n';
    }

    createFile(path.join(config.src.assets, '_variables.scss'), cnt)
  };

module.exports = parseVariables;