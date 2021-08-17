let path = require('path').posix,
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),
  config = require('./config.js'),
  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,
  createBlock = require('./start/create-block.js'),
  createFile = require('./start/create-file.js'),
  cssBreakpoints = config.cssBreakpoints.map(el => el.replace(/[^0-9.]*/g, '')),
  assets = config.otherAssets.map(path.normalize),
  generalAssets = config.generalAssets.map(path.normalize),
  generalScss = config.generalScss,

  createPage = function(cb) {
    let pageCnt = '',
      pageName = argv.pagename,
      ignoreExtname = argv.ignoreext,
      defaultExtname;

    if (pageName.constructor === Boolean) {
      console.warn('После --pagename нужно передать значение');
      return;
    }

    let parsedPath = path.parse(path.normalize(pageName)),
      destFile = config.src.path + parsedPath.base,
      files = [];

    if (wordpress) {
      if (parsedPath.ext !== '.php' && !ignoreExtname) {
        parsedPath.ext = '.php';
        destFile = destFile.replace(parsedPath.ext, '.php');
      }
      defaultExtname = '.php';
      pageCnt += '<?php';
      pageCnt += '\nget_header();';
      pageCnt += '\n\n/*';
      pageCnt += '\n\tTemplate name: ' + parsedPath.name;
      pageCnt += '\n*/';
      cnt += '\n\nforeach ( $GLOBALS[\'sections\'] as $section ) {';
      cnt += '\n\trequire \'template-parts/\' . $section[\'acf_fc_layout\'] . \'.php\';';
      cnt += '\n}';

      cssBreakpoints.forEach(function(breakpoint) {
        let suffix = breakpoint === '' ? '' : '.' + (+breakpoint + 0.02),
          filepath = config.src.scss + 'style-' + parsedPath.name + suffix + '.scss',
          cnt = assets.reduce((prev, next) => prev + '@import \'' + path.relative(config.src.scss, next) + '\';\n', '');

        createFile(filepath, cnt);
      });

      files.push(config.src.js + 'script-' + parsedPath.name + '.js');


      pageCnt += '\nget_footer();';

    } else {
      // Без вп
      defaultExtname = '.html';
    }

    if (parsedPath.ext === '' && !ignoreExtname) {
      destFile += defaultExtname;
    }

    files.push(destFile);

    files.forEach(createFile);


    cb();
  }

module.exports = createPage;