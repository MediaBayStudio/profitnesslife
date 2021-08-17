let path = require('path').posix,
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  flexibleWordpress = config.flexibleWordpress,
  pages = config.pages,

  createJs = function() {

    // Создание файла для админки
    createFile(path.join(config.src.js, 'script-admin.js'));

    // Копирование jquery
    let jquerySrc = path.join('scripts', 'libs', 'js', 'jquery-3.5.1.min.js'),
      jqueryDest = path.join(config.src.js, 'jquery-3.5.1.min.js');

    try {
      fs.copyFileSync(jquerySrc, jqueryDest);
      console.log('Файл ' + jquerySrc + ' скопирован');
    } catch (err) {
      console.log('Файл ' + jquerySrc + ' не найден');
    }

    // Создадем для каждой страницы отдельный js
    pages.forEach(function(page) {

      // Проверка на отдельную WP страницу
      if (page.slice(0, 3) !== 'wp:') {
        page = page.replace(/,.*$/, '');

        let {
          // '/home/user/dir/file.txt'
          root, // '/'
          dir, // '/home/user/dir'
          base, // 'file.txt'
          ext, // '.txt'
          name, // file
        } = path.parse(path.normalize(page)),
          filepath = path.join(config.src.js, 'script-' + name + '.js');

        createFile(filepath)
      }

    });

    config.js.forEach(function(el) {
      el = el.replace(/\s?\[defer\]/, '');

      let src = path.join(config.libs.js, el),
        dest = config.src.js;
      try {
        mkdirpsync(dest);
        fs.copyFileSync(src, path.join(dest, el));
        console.log('Файл ' + path.join(dest, el) + ' скопирован');
      } catch (err) {
        console.log('Файл ' + src + ' не найден');
      }
    });


    // Перемещаем все js компоненты
    config.jsComponents.forEach(function(el) {
      let src = path.join(config.libs.js, 'components', el),
        dest = path.join(config.src.js, 'components');
      try {
        mkdirpsync(dest);
        fs.copyFileSync(src, path.join(dest, el));
        console.log('Файл ' + path.join(dest, el) + ' скопирован');
      } catch (err) {
        console.log('Файл ' + src + ' не найден');
      }
    });

  };

module.exports = createJs;