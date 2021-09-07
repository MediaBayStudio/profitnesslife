var { src, dest } = require('gulp'),
  fs = require('fs'),
  // path = require('path'),
  path = require('path').posix,
  argv = require('yargs').argv,
  include = require('gulp-include'),
  sass = require('gulp-sass')(require('sass')),
  autoprefixer = require('gulp-autoprefixer'),
  flatten = require('gulp-flatten'),
  tap = require('gulp-tap'),
  gulpif = require('gulp-if'),
  cleancss = require('gulp-clean-css'),
  config = require('../config.js'),
  createFile = require('../start/create-file.js'),

  generalScss = config.generalScss,
  cssBreakpoints = config.cssBreakpoints.map(el => el.replace(/[^0-9.]*/g, '')),
  assets = config.otherAssets,
  generalAssets = config.generalAssets,

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

  formingImports = function(array, filepath) {
    return array.reduce(function(prev, next) {
      let relativePath = path.relative(filepath, next);

      return prev + '\n@import \'' + relativePath.replace(/\.scss$/, '') + '\';'
    }, '');
  },

  buildCss = function(cb) {
    if (wordpress) {

      // Файл темы вордпресс
      src(path.join(config.src.path, 'style.scss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
          cascade: false,
          grid: 'no-autoplace'
        }))
        .pipe(gulpif(() => !argv.nocleancss, cleancss(cleanCSSOptions)))
        .pipe(tap(file => {
          let template = config.themeStyleTemplate
            .reduce((prev, next) => prev + next + '\n', ''),
            fileContent = file.contents.toString(),
            cnt = '';

          if (fileContent.match('@charset "UTF-8";')) {
            cnt = fileContent.replace('@charset "UTF-8";', template);
          } else {
            cnt = template + fileContent;
          }

          file.contents = Buffer.from(cnt, '');
        }))
        .pipe(dest(config.dest.path))
        .pipe(dest('dist'));

      // Секции
      src(path.join(config.src.sections, '**', '*.scss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
          cascade: false,
          grid: 'no-autoplace'
        }))
        .pipe(gulpif(() => !argv.nocleancss, cleancss(cleanCSSOptions)))
        .pipe(dest(config.dest.sections))
        .pipe(dest(path.join('dist', 'sections')));

      // Компоненты
      src(path.join(config.src.components, '**', '*.scss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
          cascade: false,
          grid: 'no-autoplace'
        }))
        .pipe(gulpif(() => !argv.nocleancss, cleancss(cleanCSSOptions)))
        .pipe(dest(config.dest.components))
        .pipe(dest(path.join('dist', 'components')));

      // Стить для админки
      src(path.join(config.src.scss, 'style-admin.scss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
          cascade: false,
          grid: 'no-autoplace'
        }))
        // .pipe(gulpif(() => !argv.nocleancss, cleancss(cleanCSSOptions)))
        .pipe(dest(config.dest.path))
        .pipe(dest('dist', 'scss'));

      // Файлы по страницам
      src(path.join(config.src.scss, '*.scss'))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
          cascade: false,
          grid: 'no-autoplace'
        }))
        .pipe(gulpif(() => !argv.nocleancss, cleancss(cleanCSSOptions)))
        .pipe(flatten({ includeParents: 0 }))
        .pipe(dest(config.dest.scss))
        .pipe(dest(path.join('dist', 'scss')));


      // Собираем src файлы на основе pages-info.scss
      let pagesInfoContent,
        pagesInfoPath = path.join(config.dest.path, 'pages-info.json');

      try {
        if (fs.existsSync(pagesInfoPath)) {
          try {
            pagesInfoContent = fs.readFileSync(pagesInfoPath).toString();
            if (pagesInfoContent) {
              pagesInfoContent = JSON.parse(pagesInfoContent);
              for (let pageName in pagesInfoContent) {
                let styleSRC = path.join(config.src.scss, 'style-' + pageName + '.scss'),
                  styleDEST = path.join(config.dest.scss, 'style-' + pageName + '.scss'),
                  blocks = pagesInfoContent[pageName];

                cssBreakpoints.forEach(function(breakpoint) {
                  let suffix = breakpoint === '' ? '' : '.' + (+breakpoint + 0.02),
                    filepath = path.join(config.src.scss, 'style-' + pageName + suffix + '.scss'),
                    cnt = assets.reduce((prev, next) => prev + '@import \'' + path.relative(config.src.scss, next) + '\';\n', '');


                  cnt += blocks.reduce((prev, next) => prev + '@import \'' + path.relative(config.src.scss, path.join(config.src.sections, next, next + suffix)) + '\';\n', '');

                  createFile(filepath, cnt);
                });

              }
            }
          } catch (err) {
            console.log(err);
            console.log('pages-info.json не найден');
          }
        } else {
          console.log('pages-info.json не найден');
        }
      } catch (err) {
        console.log(err);
      }
    }


    cb();
  };


module.exports = buildCss;