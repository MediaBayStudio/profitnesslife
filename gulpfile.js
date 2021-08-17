// npm i -D yargs fs mkdirpsync path gulp gulp-cli gulp-shell gulp-sass gulp-clean-css gulp-autoprefixer gulp-html-minifier gulp-include gulp-remove-logging gulp-uglify gulp-rename gulp-strip-comments gulp-tap gulp-beautify gulp-if gulp-flatten

// npm i -D gulp-babel @babel/core @babel/plugin-transform-block-scoping @babel/plugin-transform-template-literals @babel/plugin-transform-arrow-functions

// export PATH=$PATH:/Applications/MAMP/Library/bin/

let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path').posix,
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),

  shell = require('gulp-shell'),

  srcPath = './src',
  config = require('./scripts/config.js'),
  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,

  createBlock = require('./scripts/start/create-block.js'),
  createFile = require('./scripts/start/create-file.js'),

  removeLogging = require('gulp-remove-logging'),
  babel = require('gulp-babel'),
  uglify = require('gulp-uglify'),

  buildWordpress = require('./scripts/start/build-wordpress.js'),
  start = require('./scripts/start/start.js'),

  htaccessSrc = path.join(config.libs.pages, '.htaccess'),
  htaccessDest = path.join(config.localServerDest, '.htaccess'),

  createStartCatalogs = function(cb) {
    mkdirpsync(config.dest.path);

    console.log('Создано: ' + config.dest.path);

    try {
      let htaccessCnt = fs.readFileSync(htaccessSrc).toString();
      htaccessCnt = htaccessCnt.replace(/%title%/g, config.siteName);
      createFile(htaccessDest, htaccessCnt);
    } catch (err) {
      console.log('Файл ' + htaccessSrc + ' не найден');
    }

    for (dir in config.src) {
      mkdirpsync(config.src[dir]);
      console.log('Создано: ' + config.src[dir]);
    }
    cb();
  },

  moveSource = require('./scripts/move/move-src.js'),
  { moveImages, moveFonts, moveFavicons, moveJSON, moveBlocks, moveSections, movePHP, moveHTML } = require('./scripts/move/move-files.js'),
  moveJs = require('./scripts/move/move-js.js'),

  buildJs = require('./scripts/watch/build-js.js'),
  buildCss = require('./scripts/watch/build-css.js'),

  createPage = require('./scripts/create-page.js');


task('start', series(createStartCatalogs, start));
task('wp', series(createStartCatalogs, buildWordpress));

task('createblock', function(cb) {
  let newPath = argv.src;
  if (newPath.constructor !== Boolean) {
    createBlock(newPath);
  }
  cb();
});

task('renameblock', function(cb) {
  let oldPath = argv.src,
    newPath = argv.dest,
    blocksSrc = config.src.blocks;

  if (oldPath.constructor !== Boolean && newPath.constructor !== Boolean) {
    if (flexibleWordpress) {
      let parsedPath = path.parse(path.normalize(oldPath)),
        filedir = path.join(blocksSrc, parsedPath.name),
        files;

      try {
        files = fs.readdirSync(filedir);
      } catch (err) {
        console.log('Не удалось прочитать папку ' + filedir);
        console.error(err);
        return;
      }

      files.forEach(function(file) {
        let filebase = path.parse(file).base, //file.txt
          oldFilepath = path.join(filedir, filebase),
          newFilepath = path.join(filedir, filebase.replace(parsedPath.name, newPath));

        console.log('Хочу переименовать ' + oldFilepath + ' в ' + newFilepath);
        try {
          fs.renameSync(oldFilepath, newFilepath);
          console.log('Файл ' + oldFilepath + ' переименован в ' + newFilepath);
        } catch {
          console.log('Ошибка переименования файла ' + oldFilepath)
        }

      });

      // Переименовываем саму папку
      try {
        fs.renameSync(filedir, filedir.replace(parsedPath.name, newPath));
        console.log('Файл ' + filedir + ' переименован в ' + filedir.replace(parsedPath.name, newPath));
      } catch {
        console.log('Ошибка переименования файла ' + filedir)
      }
    }
  }
  cb();
});

task('createpage', createPage);

task('default', function(done) {
  // moveSource(); // Перемещаем исходный код
  if (wordpress) {
    if (flexibleWordpress) {
      watch(path.join(config.src.blocks, '**', '*.js'), buildJs);
      watch(path.join(config.src.js, 'components', '*.js'), buildJs);
      watch(path.join(config.src.js, 'script.js'), buildJs);

      watch(path.join(config.src.path, 'style.scss'), buildCss);
      watch(path.join(config.src.blocks, '**', '*.scss'), buildCss);
      // watch(config.src.scss + '**/*.scss', buildCss);
      watch(path.join(config.src.scss, '**', '!(style-)*.scss'), buildCss);

      watch(path.join(config.src.blocks, '**', '*.php'), moveParts);
      watch(path.join(config.src.path, '*.php'), movePHP);
      watch(path.join(config.src.inc, '*.php'), movePHP);

      watch(path.join(config.src.inc, '*.php'), movePHP);
    } else {
      watch(path.join(config.src.js, 'components', '*.js'), buildJs) /*.on('unlink', path => removeFiles(path, 'unlink'))*/ ;
      watch(path.join(config.src.path, '*.html'), moveHTML);
    }
  }

  console.log(path.join(config.src.img, '**', '*'));

  watch(path.join(config.src.fonts, '**', '*'), moveFonts);
  watch(path.join(config.src.img, '**', '*'), moveImages);
  watch(path.join(config.src.path, '**', '*.json'), moveJSON);
});

task('movesrc', moveSource);
task('buildjs', buildJs);
task('movejs', moveJs);
// При flexiblewordpress собираются только blocks и assets
task('movecss', buildCss);
// html, php
task('moveblocks', moveBlocks);
task('movesections', moveSections);
task('movephp', movePHP);
task('movehtml', moveHTML);
task('movefonts', moveFonts);
task('moveimg', moveImages);
task('movefavicons', moveFavicons);
task('movejson', moveJSON);

task('moveall', parallel(
  'movesrc',
  'movejs',
  'movecss',
  'moveblocks',
  'movesections',
  'movephp',
  'movehtml',
  'movefonts',
  'moveimg',
  'movefavicons',
  'movejson'));