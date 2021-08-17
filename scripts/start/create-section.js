let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path'),
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),

  createFile = require('./create-file.js'),
  config = require('../config.js'),
  cssBreakpoints = config.cssBreakpoints.map(el => el.replace(/[^0-9.]*/g, '')),
  assets = config.otherAssets,

  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,

  createSection = function(section) {
    let {
      // '/home/user/dir/file.txt'
      root, // '/'
      dir, // '/home/user/dir'
      base, // 'file.txt'
      ext, // '.txt'
      name, // file
    } = path.parse(section);

    if (ext === '') {
      ext = wordpress ? '.php' : '.html';
      base += wordpress ? '.php' : '.html';
    }

    if (dir === '' ) {
      dir = path.join(config.src.sections, name);
    }


    let jsPath = path.join(dir, name + '.js');

    // Создадим путь до папки с файлами
    mkdirpsync(dir);
    console.log('Создано: ' + dir);

    if (name !== 'header' && name !== 'footer') {
      // Создадим php файл
      createFile(path.join(dir, base), '');
    }

    // Создадим scss файлы
    cssBreakpoints.forEach(function(breakpoint) {
      let suffix = breakpoint === '' ? '' : '.' + (+breakpoint + 0.02),
        filepath = path.join(dir, name + suffix + '.scss'),
        cnt = assets.reduce((prev, next) => prev + '@import \'' + path.relative(dir, next) + '\';\n', ''),
        existsFileCnt = '';

      // Проверяем есть ли какой-то сниппет в библиотеке
      try {
        existsFileCnt = '\n\n' + fs.readFileSync(path.join(config.libs.css, name + suffix + '.scss')).toString();
      } catch (err) {
        
      }

      cnt += existsFileCnt;

      createFile(filepath, cnt);
    });

    // Создадим js файлы
    createFile(jsPath, '');

  };

module.exports = createSection;