let path = require('path').posix,
  fs = require('fs'),
  { src } = require('gulp'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),
  config = require('./config.js'),
  wordpress = config.wordpress,
  shell = require('gulp-shell'),
  flexibleWordpress = config.flexibleWordpress,
  createBlock = require('./start/create-block.js'),
  createFile = require('./start/create-file.js'),
  cssBreakpoints = config.cssBreakpoints.map(el => el.replace(/[^0-9.]*/g, '')),
  assets = config.otherAssets,
  generalAssets = config.generalAssets,
  generalScss = config.generalScss,

  createPage = function(cb) {
    let pageCnt = '',
      pagePath,
      pageTitle,
      pageSlug,
      defaultExtname,
      command = 'wp post create --post_type=page --post_status=publish';

    if (typeof cb === 'function') {
      pagePath = argv.src;
      pageTitle = argv.title;
      pageSlug = argv.slug;

      if (pagePath.constructor === Boolean) {
        console.warn('После --src нужно передать значение');
        return;
      }

    } else {
      let page = cb.split(/,\s|,/);

      if (cb.slice(0, 3) === 'wp:') {
        pagePath = false;
        pageTitle = page[0].replace(/wp:\s?/, '');
        pageSlug = page[1];
      } else {
        pagePath = page[0];
        pageTitle = page[1];
        pageSlug = page[2];
      }

    } // endif typeof cb === 'function'


    if (pagePath) {
      let parsedPath = path.parse(path.normalize(pagePath)),
        destFile = config.src.path,
        files = [];

      if (wordpress) {
        defaultExtname = '.php';

        // Создаем контент страницы
        pageCnt += '<?php';
        pageCnt += '\n\n/*';
        pageCnt += '\n\tTemplate name: ' + parsedPath.name;
        pageCnt += '\n*/';
        pageCnt += '\n\nget_header();';
        pageCnt += '\n\nforeach ( $GLOBALS[\'sections\'] as $section ) {';
        pageCnt += '\n\trequire \'template-parts/\' . $section[\'acf_fc_layout\'] . \'.php\';';
        pageCnt += '\n}';

        // Создаем scss файлы по размерам экранов
        cssBreakpoints.forEach(function(breakpoint) {
          let suffix = breakpoint === '' ? '' : '.' + (+breakpoint + 0.02),
            filepath = path.join(config.src.scss, 'style-' + parsedPath.name + suffix + '.scss'),
            cnt = assets.reduce((prev, next) => prev + '@import \'' + path.relative(config.src.scss, next) + '\';\n', '');

          if (breakpoint === '') {
            cnt = generalAssets.reduce((prev, next) => prev + '@import \'' + path.relative(config.src.scss, next) + '\';\n', '') +
              cnt +
              generalScss.reduce((prev, next) => prev + '@import \'' + path.relative(config.src.scss, next) + '\';\n', '');
          }

          createFile(filepath, cnt);
        });

        files.push(path.join(config.src.js, 'script-' + parsedPath.name + '.js'));

        pageCnt += '\n\nget_footer();';

      } else {
        defaultExtname = '.html';
      } // endif wordpress

      if (parsedPath.ext === '') {
        parsedPath.ext = defaultExtname;
        parsedPath.base += defaultExtname;
      } else {
        if (parsedPath.ext !== defaultExtname) {
          parsedPath.base = parsedPath.base.replace(parsedPath.ext, defaultExtname);
          parsedPath.ext = defaultExtname;
        } // endif parsedPath.ext !== defaultExtname
      } // endif parsedPath.ext === ''

      destFile = path.join(destFile, parsedPath.base);

      createFile(destFile, pageCnt);

      files.forEach(createFile);
    }

    if (pageTitle) {
      command += ' --post_title="' + pageTitle + '"';

      if (pageSlug) {
        command += ' --post_name=' + pageSlug + ' --meta_input=\'{"_wp_page_template":"' + pageSlug + '.php"}\'';
      }

      console.log(command);

      src('gulpfile.js', { read: false })
        .pipe(shell([
          command
        ], {
          cwd: config.localServerDest
        }));
    }

    if (typeof cb === 'function') {
      cb();
    }
  }

module.exports = createPage;