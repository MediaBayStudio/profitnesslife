let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path'),
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirp = require('mkdirp'),
  mkdirpsync = require('mkdirpsync'),
  config = require('../config.js'),

  createPage = require('../create-page.js'),
  createBlock = require('./create-block.js'),
  createSection = require('./create-section.js'),
  createFile = require('./create-file.js'),
  parseAnimations = require('./parse-animations.js'),
  parseFonts = require('./parse-fonts.js'),
  parseColors = require('./parse-colors.js'),
  parseMixins = require('./parse-mixins.js'),
  parseGrid = require('./parse-grid.js'),
  parseReset = require('./parse-reset.js'),
  parseVariables = require('./parse-variables.js'),

  createSiteHeader = require('./create-site-header.js'),
  createSiteFooter = require('./create-site-footer.js'),

  createFunctionsPHP = require('./create-functions-php.js'),

  createSCSS = require('./create-scss.js'),

  createJs = require('./create-js.js'),

  start = function(cb) {
    // Создание блоков .php, .scss, .js
    if (config.components) {
      config.components.forEach(createBlock);
    }

    config.sections.forEach(createSection);

    // Создание страниц
    config.pages.forEach(createPage);

    createFile(path.join(config.dest.path, 'pages-info.json'), '');

    // assets
    parseAnimations(config.cssAnimations);
    parseColors(config.cssColors);
    parseFonts(config.fonts);
    parseGrid();
    parseMixins();
    parseVariables();
    parseReset();

    // Прочие scss
    createSCSS(config.scss);

    createSiteHeader();
    createSiteFooter();
    createFunctionsPHP();
    createJs();

    cb();
  };


module.exports = start;