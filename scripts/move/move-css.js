let { src, dest } = require('gulp'),
  fs = require('fs'),
  // path = require('path'),
  path = require('path').posix,
  argv = require('yargs').argv,
  include = require('gulp-include'),
  config = require('../config.js'),
  sass = require('gulp-sass')(require('sass')),
  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,

  cleanCSSOptions = {
    level: {
      1: {
        cleanupCharsets: true,
        normalizeUrls: true,
        optimizeBackground: true,
        optimizeBorderRadius: true,
        optimizeFilter: true,
        optimizeFont: true,
        optimizeFontWeight: true,
        optimizeOutline: true,
        removeEmpty: true,
        removeNegativePaddings: true,
        removeQuotes: true,
        removeWhitespace: true,
        replaceMultipleZeros: true,
        replaceTimeUnits: true,
        replaceZeroUnits: true,
        roundingPrecision: false,
        selectorsSortingMethod: 'standard',
        specialComments: 'all',
        tidyAtRules: true,
        tidyBlockScopes: true,
        tidySelectors: true,
      },
      2: {
        mergeAdjacentRules: true,
        mergeIntoShorthands: true,
        mergeMedia: true,
        mergeNonAdjacentRules: true,
        mergeSemantically: false,
        overrideProperties: true,
        removeEmpty: true,
        reduceNonAdjacentRules: true,
        removeDuplicateFontRules: true,
        removeDuplicateMediaBlocks: true,
        removeDuplicateRules: true,
        removeUnusedAtRules: false,
        restructureRules: false,
        skipProperties: ['background']
      }
    }
  },

  buildCss = function(cb) {

    if (wordpress) {
      // move blocks
      src(path.join(config.src.blocks, '**', '*.scss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
          cascade: false,
          grid: 'no-autoplace'
        }))
        .pipe(gulpif(() => !argv.nocleancss, cleancss(cleanCSSOptions)))
        .pipe(flatten({ includeParents: 0 }))
        .pipe(dest(config.dest.blocks))
        .pipe(dest(path.join('dist', 'blocks')))
    }

    cb();
  };


module.exports = buildCss;