let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path'),
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),

  createFile = require('./create-file.js'),
  config = require('../config.js'),
  assets = config.otherAssets,
  fontsSrc = config.src.fonts,
  fontsLibs = config.libs.fonts,
  destFonts = path.join(config.src.assets, 'fonts.scss'),

  parseFonts = function(fonts) {
    let content = '';

    // разбираем шрифты, копируем в папку из бибилотеки
    for (let font of fonts) {
      let parsedFont = font.split('-')
        .reduce(function(prev, next, i) {
          if (i === 0) {
            prev.name = next;
          } else {
            prev.params = next;
          }
          return prev;
        }, {}),
        fontLibPath,
        fontStyle,
        fontWeight,
        fontFamily = parsedFont.name,
        fontName,
        fontSrc;

      if (parsedFont.params) {
        fontLibPath = path.join(fontsLibs, fontFamily, fontFamily + '-' + parsedFont.params + '.woff');

        let parsedPath = path.parse(fontLibPath),
          fontParams = parsedFont.params.toLowerCase();

        try {
          fs.accessSync(fontLibPath, fs.constants.R_OK);
          console.log('Файл ' + fontLibPath + ' найден и скопирован');

          // перемещаем файл шрифтов
          let newFilePath = path.join(fontsSrc, parsedPath.base);
          fs.copyFileSync(fontLibPath, newFilePath);
        } catch (err) {
          console.log('Файл ' + fontLibPath + ' не найден');
        }

        if (fontParams.indexOf('semibold') !== -1) {
          fontWeight = '600';
        } else if (fontParams.indexOf('extrabold') !== -1) {
          fontWeight = '800';
        } else if (fontParams.indexOf('bold') !== -1) {
          fontWeight = 'bold';
        } else if (fontParams.indexOf('black') !== -1) {
          fontWeight = '900';
        } else if (fontParams.indexOf('medium') !== -1) {
          fontWeight = '500';
        } else if (fontParams.indexOf('extralight') !== -1) {
          fontWeight = '200';
        } else if (fontParams.indexOf('light') !== -1) {
          fontWeight = '300';
        } else if (fontParams.indexOf('thin') !== -1) {
          fontWeight = '100';
        } else {
          fontWeight = 'normal';
        }

        if (fontParams.indexOf('italic') !== -1) {
          fontStyle = 'italic';
        } else {
          fontStyle = 'normal'
        }

        fontSrc = "url('fonts/" + parsedPath.base + "')";

        // ищем regular
      } else {
        fontLibPath = path.join(fontsLibs, fontFamily, fontFamily + '.woff');
        let parsedPath = path.parse(fontLibPath);
        try {
          fs.accessSync(fontLibPath, fs.constants.R_OK);
          console.log('Файл ' + fontLibPath + ' найден и скопирован');

          // перемещаем файл шрифтов
          let newFilePath = path.join(fontsSrc, parsedPath.base);
          fs.copyFileSync(fontLibPath, newFilePath);
        } catch (err) {
          console.log('Файл ' + fontLibPath + ' не найден');
          fontLibPath = fontLibPath.replace(/(\.[^.]+$)/, '-Regular$1');
          console.log('Буду искать ' + fontLibPath);
          try {
            fs.accessSync(fontLibPath, fs.constants.R_OK);
            console.log('Файл ' + fontLibPath + ' найден и скопирован');

            // перемещаем файл шрифтов
            let newFilePath = path.join(fontsSrc, parsedPath.base);
            fs.copyFileSync(fontLibPath, newFilePath);
          } catch (err) {
            console.log('Файл ' + fontLibPath + ' не найден');
          }
        }
        // fontName = parsedPath.name;
        fontStyle = 'normal';
        fontWeight = 'normal';
        fontSrc = "url('../fonts/" + parsedPath.base + "')";
      }


      let fontFaceTemplate = {
          family: fontFamily,
          src: fontSrc,
          weight: fontWeight,
          style: fontStyle,
          display: 'swap'
        },
        fontFace =
        `
@font-face {
  font-family: '${fontFamily}';
  src: ${fontSrc} format('woff');
  font-weight: ${fontWeight};
  font-style: ${fontStyle};
  font-display: swap;
}
`;

      content += fontFace;
    }

    // создаем файл _fonts.scss

    createFile(destFonts, content);
  };



module.exports = parseFonts;