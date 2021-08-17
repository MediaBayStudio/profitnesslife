let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path').posix,
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirp = require('mkdirp'),
  config = require('../config.js'),
  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,

  createFile = function(filepath, cnt) {
    cnt = cnt || '';

    let parsed = path.parse(filepath);

    if (cnt === 'page') {
      if (wordpress) {
        cnt = '<?php'
        cnt += '\n\n/*';
        cnt += '\n\tTemplate name: ' + parsed.name;
        cnt += '\n*/';
        cnt += '\nget_header();'
        cnt += '\n\nforeach ( $GLOBALS[\'sections\'] as $section ) {';
        cnt += '\n\trequire \'template-parts/\' . $section[\'acf_fc_layout\'] . \'.php\';';
        cnt += '\n}';
        cnt += '\n\nget_footer();'
      }
    }

    try {
      fs.writeFileSync(filepath, cnt);
      console.log('Создано: ' + filepath);
    } catch (err) {
      console.error(err)
    }
  };

module.exports = createFile;