let path = require('path'),
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  flexibleWordpress = config.flexibleWordpress,
  scssImports = config.scssImports,
  generalScss = config.generalScss,
  pages = config.pages,
  cssBreakpoints = config.cssBreakpoints.map(el => el.replace(/[^0-9.]*/g, '')),
  assets = config.otherAssets,
  generalAssets = config.generalAssets,

  formingImports = function(array, filepath) {
    return array.reduce(function(prev, next) {
      let relativePath = path.relative(filepath, next);

      return prev + '\n@import \'' + relativePath.replace(/\.scss$/, '') + '\';'
    }, '');
  },

  createSCSS = function(scssArray) {

    // Создаем для каждой страницы файл стилей, imports не вставляем

    // Ищем со скобками
    let combinedPagesArray = scssArray.filter(el => el.search(/\(.*\)/) !== -1),
      combinedPagesObject = {};

    // Превратим в объект название-массив страниц
    combinedPagesArray.forEach(function(el) {
      let styleName = el.replace(/\s?\(.*\)/, '').replace(/.*\/(?!$)/, ''),
        pagesArray = el.replace(/[^(]+/, '').replace(/\(|\)/g, '').split(/,\s|,/);

      combinedPagesObject[styleName] = pagesArray;
    });

    // Создаем файлы стилей для страниц
    pages.forEach(function(page) {

      if (page.slice(0, 3) === 'wp:') {
        return;
      }

      let {
        // '/home/user/dir/file.txt'
        root, // '/'
        dir, // '/home/user/dir'
        base, // 'file.txt'
        ext, // '.txt'
        name, // file
      } = path.parse(page);

      for (styleName in combinedPagesObject) {
        if (combinedPagesObject[styleName].includes(name)) {
          name = styleName.replace('style-', '');
        }
      }

      cssBreakpoints.forEach(function(breakpoint) {
        let suffix = breakpoint === '' ? '' : '.' + (+breakpoint + 0.02),
          filepath = path.join(config.src.scss, 'style-' + name + suffix + '.scss'),
          cnt = formingImports(assets, config.src.scss);

        createFile(filepath, cnt);
      });

    });

    // Создаем стиль темы style.css
    let template = config.themeStyleTemplate
      .reduce((prev, next) => prev + next + '\n', ''),
      themeStylePath = path.join(config.src.path, 'style.scss'),
      cnt = template;

    if (config.themeStyleImports && config.themeStyleImports.length > 0) {
      cnt += formingImports(config.themeStyleImports, config.src.path);
    } else {
      cnt += formingImports(generalAssets, config.src.path) + formingImports(generalScss, config.src.path);
    }

    createFile(themeStylePath, cnt);

    // Создаем файл hover.scss
    createFile(path.join(config.src.scss, 'hover.scss'), formingImports(assets, config.src.scss));

    // Создаем файл style-admin.scss
    createFile(path.join(config.src.scss, 'style-admin.scss'), formingImports(assets, config.src.scss));


    // Общие файлы стилей будем складывать в одну папку - style (buttons, sliders, forms)
    if (generalScss.length > 0) {
      generalScss.forEach(function(el) {
        let {
          // '/home/user/dir/file.txt'
          root, // '/'
          dir, // '/home/user/dir'
          base, // 'file.txt'
          ext, // '.txt'
          name, // file
        } = path.parse(el);

        if (config.libs.css) {
          try {
            let data = fs.readFileSync(path.join(config.libs.css, base)),
              filepath = path.join(dir, name + '.scss'),
              cnt = formingImports(assets, dir);
            mkdirpsync(dir);

            console.log('проверка ' + config.libs.css + base);

            createFile(filepath, cnt + '\n' + data);
          } catch (err) {
            console.log('Файл ' + config.libs.css + base + ' не найден');
            mkdirpsync(dir);
            let data = fs.readFileSync(path.join(config.libs.css, base)),
              filepath = path.join(dir, name + '.scss'),
              cnt = formingImports(assets, dir);
            createFile(filepath, cnt + '\n' + data);
          }
        }
      });
    }

  };

module.exports = createSCSS;