let { src, dest } = require('gulp'),
  fs = require('fs'),
  // path = require('path'),
  path = require('path').posix,
  argv = require('yargs').argv,
  rename = require('gulp-rename'),
  config = require('../config.js'),

  moveSrc = function(cb) {
    src(path.join(config.src.js, '**', '*'))
      .pipe(dest(path.join(config.dest.sourceCode, 'js')));

    src(path.join(config.src.scss, '**', '*'))
      .pipe(dest(path.join(config.dest.sourceCode, 'scss')));

    src(path.join(config.src.components, '**', '*'))
      .pipe(dest(path.join(config.dest.sourceCode, 'components')));

    src(path.join(config.src.sections, '**', '*'))
      .pipe(dest(path.join(config.dest.sourceCode, 'sections')));

    src(path.join(config.src.inc, '*'))
      .pipe(dest(path.join(config.dest.sourceCode, 'inc')));

    src(path.join(config.src.path, '*.php'))
      .pipe(rename(function(filpeath) {
        filpeath.extname = '.!php';
      }))
      .pipe(dest(config.dest.sourceCode));

    cb();
  };

module.exports = moveSrc;