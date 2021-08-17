let fs = require('fs'),
  path = require('path'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),

  parseMixins = function() {
    let cnt =
      `
/*
  Создает функцию calc(), которая исходя из переданных
  значений ширины экранов и пикселей, делает функцию
  "от->до".

  Например:
  margin-bottom: responsive('320->575', '20->100');
  На размерах экранов от 320 до 575, нижний отступ будет
  расти от 20 до 100.

  Аргумент $minus: true делает значение отрицательным,
  например:
  margin-bottom: responsive('320->575', '0->100', true);
  На размерах экранов от 320 до 575, нижний отступ будет
  уменьшаться от 0 до -100.
*/
@function responsive($screenWidth, $pxs, $minus: false) {

  $arrow: str-index($screenWidth, '->');

  $minScreenWidth: str-slice($screenWidth, 0, $arrow - 1);
  $maxScreenWidth: str-slice($screenWidth, $arrow + 2, -1);

  $arrow: str-index($pxs, '->');

  $minPxs: str-slice($pxs, 0, $arrow - 1);
  $maxPxs: str-slice($pxs, $arrow + 2, -1);

  $minus: '';
  $start: '';

  @if $minus {
    $minus: ') * -1';
    $start: '(';
  }

  @return calc(#{$start}(100vw - #{$minScreenWidth}px)/(#{$maxScreenWidth} - #{$minScreenWidth})*(#{$maxPxs} - #{$minPxs}) + #{$minPxs}px#{$minus});
}

`;

    createFile(path.join(config.src.assets, '_mixins.scss'), cnt);
  };

module.exports = parseMixins;