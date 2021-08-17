let fs = require('fs'),
  path = require('path').posix,
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  parseReset = function() {
    let cnt,
      libPath = path.join(config.libs.css, 'reset.scss'),
      filepath = path.join(config.src.assets, 'reset.scss');

    try {
      cnt = fs.readFileSync(libPath).toString();
    } catch (err) {
      console.log('Файл ' + libPath + ' не найден');
    }

    createFile(filepath, cnt);
  };

module.exports = parseReset;