let path = require('path'),
  fs = require('fs'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  fonts = '\n\t$fonts = [\n' + config.fonts.map(el => '\t\t\'' + el + '.woff\'').join(',\n') + '\n\t];',
  wordpress = config.wordpress,

  cssBreakpoints = config.cssBreakpoints,
  grid = cssBreakpoints.map(el => +el.replace(/[^0-9.]*/g, '')),
  container = config.containerWidth,

  createSiteHeader = function() {
    let headerLibSrc = path.join(config.libs.pages, 'header'),
      headerDest = path.join(config.src.path, 'header'),
      headerCnt = '';

    if (wordpress) {
      headerLibSrc += '.php';
      headerDest += '.php';
    } else {
      // Если без вордпресса .html
      headerLibSrc += '.html';
      headerDest += '.html';
    }

    try {
      headerCnt = fs.readFileSync(headerLibSrc).toString();
    } catch (err) {
      console.log('Файл ' + headerLibSrc + ' не найден');
    }

    if (wordpress) {
      let fontsPreload = '<!-- fonts preload --> <?php',
        pageStylesPreload = '\n\t<link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/style-<?php echo $page_style ?>.css" />';

      for (let i = 1; i < grid.length; i++) {
        pageStylesPreload += '\n\t<link rel="preload" as="style" href="<?php echo $template_directory_uri ?>/css/style-<?php echo $page_style ?>.' + grid[i].toFixed() + '.css" media="' + cssBreakpoints[i] + '" />';
      }
      headerCnt = headerCnt.replace('<!-- page styles preload -->', pageStylesPreload);
      fontsPreload += fonts;
      fontsPreload += '\n\tforeach ( $fonts as $font ) : ?>';
      fontsPreload += '\n\n\t<link rel="preload" href="<?php echo $template_directory_uri . \'/fonts/\' . $font ?>" as="font" type="font/woff" crossorigin="anonymous" /> <?php';
      fontsPreload += '\n\tendforeach ?>';
      headerCnt = headerCnt.replace('<!-- fonts preload -->', fontsPreload);
    } else {
      // Если без вордпресса .html
    }


    createFile(headerDest, headerCnt);

  };

module.exports = createSiteHeader;