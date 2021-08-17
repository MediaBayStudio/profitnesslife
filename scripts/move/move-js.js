let { src, dest } = require('gulp'),
  fs = require('fs'),
  // path = require('path'),
  path = require('path').posix,
  argv = require('yargs').argv,
  include = require('gulp-include'),
  config = require('../config.js'),
  removeLogging = require('gulp-remove-logging'),
  babel = require('gulp-babel'),
  uglify = require('gulp-uglify'),
  gulpif = require('gulp-if'),

  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,

  moveJs = function(cb) {
    let sourceJs;

    if (wordpress) {

      // src(path.normalize(config.src.blocks + '/**/*.js'))
      // .pipe(include()).on('error', console.log)
      // .pipe(gulpif(() => !argv.nobabel, removeLogging()))
      // .pipe(gulpif(() => !argv.nobabel, babel()))
      // .pipe(gulpif(() => !argv.nobabel, uglify()))
      // .pipe(dest(config.dest.blocks));

      // src(path.normalize(config.src.js + '/components/*.js'))
      // .pipe(include()).on('error', console.log)
      // .pipe(dest(config.dest.js));

      src([
          path.join(config.src.js, '+(script-)*.js'),
          path.join(config.src.js, 'script.js')
        ], { allowEmpty: true })
        .pipe(include()).on('error', console.log)
        .pipe(gulpif(() => !argv.nobabel, removeLogging()))
        .pipe(gulpif(() => !argv.nobabel, babel()))
        .pipe(gulpif(() => !argv.nobabel && !argv.nouglify, uglify()))
        .pipe(dest(config.dest.js))
        .pipe(dest(path.join('dist', 'js')));
      // .pipe(dest('./test/'));

    }

    console.log(sourceJs);

    cb();
  };


module.exports = moveJs;