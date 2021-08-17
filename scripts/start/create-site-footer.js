let path = require('path'),
  fs = require('fs'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  wordpress = config.wordpress,

  createSiteFooter = function() {
    let footerLibSrc = path.join(config.libs.pages, 'footer'),
      footerDest = path.join(config.src.path, 'footer'),
      footerCnt = '';

    if (wordpress) {
      footerLibSrc += '.php';
      footerDest += '.php';
    } else {
      // Если без вордпресса .html
      footerLibSrc += '.html';
      footerDest += '.html';
    }

    try {
      footerCnt = fs.readFileSync(footerLibSrc).toString();
    } catch (err) {
      console.log('Файл ' + footerLibSrc + ' не найден');
    }

    if (wordpress) {
      if (config.phpRequire) {
        let phpRequire = config.phpRequire[footerDest];
        
        if (phpRequire) {
          phpRequire = phpRequire.map(path.normalize)
                                 .map(el => 'require \'template-parts/' + path.parse(el).base + '\';')
                                 .join('\n');

          footerCnt = footerCnt.replace('#require', phpRequire);
        }            
      }
    } else {
      // Если без вордпресса .html
    }


    createFile(footerDest, footerCnt);

  };

module.exports = createSiteFooter;