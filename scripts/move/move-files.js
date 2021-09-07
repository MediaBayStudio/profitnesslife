let { src, dest } = require('gulp'),
  config = require('../config.js'),
  // path = require('path'),
  path = require('path').posix,
  flatten = require('gulp-flatten'),
  imagemin = require('gulp-imagemin'),
  webp = require('gulp-webp'),
  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,
  htmlmin = require('gulp-html-minifier'),
  strip = require('gulp-strip-comments'),
  include = require('gulp-include'),
  moveBlocks = function(cb) {
    src(path.join(config.src.components, '**', '*.php'))
      .pipe(flatten({ includeParents: 0 }))
      .pipe(dest(config.dest.components))
      .pipe(dest(path.join('dist', 'components')));
    cb();
  },
  moveSections = function(cb) {
    src(path.join(config.src.sections, '**', '*.php'))
      .pipe(flatten({ includeParents: 0 }))
      .pipe(dest(config.dest.parts))
      .pipe(dest(path.join('dist', 'template-parts')));
    cb();
  },
  movePHP = function(cb) {
    // console.log(path.relative('./gulpfile.js', 'C:/OSPanel/domains/fortross/wp-content/themes/fortross'));
    src(path.join(config.src.path, '*.php'))
      .pipe(dest(config.dest.path))
      .pipe(dest('dist'));

    src(path.join(config.src.inc, '*.php'))
      .pipe(dest(config.dest.inc))
      .pipe(dest(path.join('dist', 'inc')));
    cb();
  },
  moveHTML = function(cb) {
    src(path.join(config.src.path, '*.html'))
      .pipe(dest(config.dest.path))
      .pipe(dest('dist'));
    cb();
  },
  moveFonts = function(cb) {
    src(path.join(config.src.fonts, '**', '*'), { allowEmpty: true })
      .pipe(dest(config.dest.fonts))
      .pipe(dest(path.join('dist', 'fonts')));
    cb();
  },
  moveImages = function(cb) {
    src(path.join(config.src.img, '**', '*'), { allowEmpty: true })
      .pipe(dest(config.dest.img))
      .pipe(dest(path.join('dist', 'img')));

    src(path.join(config.src.img, '**', '*.+(jpg|jpeg|png)'), { allowEmpty: true })
      .pipe(webp())
      .pipe(dest(config.dest.img));

    src(path.join(config.src.img, '**', '*.+(jpg|jpeg|png)'), { allowEmpty: true })
      .pipe(imagemin())
      .pipe(dest(config.dest.img));

    cb();
  },
  moveFavicons = function(cb) {
    src([
        './src/android-chrome-192x192.png',
        './src/android-chrome-512x512.png',
        './src/apple-touch-icon.png',
        './src/browserconfig.xml',
        './src/favicon-16x16.png',
        './src/favicon-32x32.png',
        './src/favicon.ico',
        './src/mstile-150x150.png',
        './src/safari-pinned-tab.svg',
        './src/site.webmanifest'
      ], { allowEmpty: true })
      .pipe(dest(config.dest.path))
      .pipe(dest('dist'));
    cb();
  },
  moveJSON = function(cb) {
    src(config.src.json, { allowEmpty: true })
      .pipe(dest(config.dest.path))
      .pipe(dest('dist'));
    cb();
  };


module.exports = { moveImages, moveFonts, movePHP, moveHTML, moveSections, moveBlocks, moveFavicons, moveJSON };