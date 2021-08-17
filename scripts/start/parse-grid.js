let { src, dest } = require('gulp'),
  fs = require('fs'),
  path = require('path'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  beautify = require('gulp-beautify'),
  cssBreakpoints = config.cssBreakpoints,
  grid = cssBreakpoints.map(el => el.replace(/[^0-9.]*/g, '')),
  container = config.containerWidth,

  parseGrid = function(filename, filedir) {
    let filepath = path.join(config.src.assets, 'grid.scss'),
    cnt = '.container{padding:0 ' + (320 - container[0]) / 2 + 'px;}';

    for (let i = 1; i < grid.length; i++) {
      cnt += '@media ' + cssBreakpoints[i] + '{'
      cnt += '.container {'
      cnt += 'padding: 0 calc(50vw - ' + Math.ceil(container[i]) / 2 + 'px);}}';
    }

    createFile(filepath, cnt);

    src(filepath)
      .pipe(beautify.css({ indent_size: 2 }))
      .pipe(dest(config.src.assets));

  };

module.exports = parseGrid;