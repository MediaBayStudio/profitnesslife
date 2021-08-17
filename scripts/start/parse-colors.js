let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path'),
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),

  createFile = require('./create-file.js'),
  config = require('../config.js'),
  assets = config.generalAssets,
  destColors = path.join(config.src.assets, '_colors.scss'),

  parseColors = function(palette) {
    let colors = [];

    for (let colorName in palette) {
      let colorValue = palette[colorName];
      colors.push('$' + colorName + ': ' + colorValue.toLowerCase() + ';');
    }

    createFile(destColors, colors.join('\n'));
  };



module.exports = parseColors;