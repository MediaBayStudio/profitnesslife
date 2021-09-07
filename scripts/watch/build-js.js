let { src, dest } = require('gulp'),
  fs = require('fs'),
  // path = require('path'),
  path = require('path').posix,
  argv = require('yargs').argv,
  include = require('gulp-include'),
  config = require('../config.js'),
  createFile = require('../start/create-file.js'),

  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,

  buildJs = function(cb) {
    let componentsSrc;

    if (wordpress) {
      componentsSrc = path.join(config.src.js, 'components');

      // if (flexibleWordpress) {
        src(path.join(config.src.components, '**', '*.js'))
          .pipe(include()).on('error', console.log)
          .pipe(dest(config.dest.components))
          .pipe(dest(path.join('dist', 'components')));

          src(path.join(config.src.sections, '**', '*.js'))
          .pipe(include()).on('error', console.log)
          .pipe(dest(config.dest.sections))
          .pipe(dest(path.join('dist', 'sections')));

        src(path.join(componentsSrc, '*.js'))
          .pipe(include()).on('error', console.log)
          .pipe(dest(path.join(config.dest.js, 'components')))
          .pipe(dest(path.join('dist', 'js', 'components')));

          src(path.join(config.src.js, '*.js'))
          .pipe(include()).on('error', console.log)
          .pipe(dest(config.dest.js))
          .pipe(dest(path.join('dist', 'js')));

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
                  let cnt = '',
                    scriptSRC = path.join(config.src.js, 'script-' + pageName + '.js'),
                    blocks = pagesInfoContent[pageName];

                  cnt += 'document.addEventListener(\'DOMContentLoaded\', function() {\n';
                  cnt += blocks.reduce((prev, next) => prev + '\n//=include ' + path.relative(config.src.js, path.join(config.src.sections, next, next + '.js')) + '\n', '');
                  cnt += '\n});'

                  createFile(scriptSRC, cnt);
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
      // } else {
      //   src(componentsSrc + 'main.js')
      //     .pipe(include()).on('error', console.log)
      //     .pipe(dest(config.dest.js))
      //     .pipe(dest(path.join('dist', 'js')));
      // }
    }

    cb();
  }


module.exports = buildJs;