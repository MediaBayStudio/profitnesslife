let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path'),
  fs = require('fs'),
  argv = require('yargs').argv,
  mkdirpsync = require('mkdirpsync'),

  createFile = require('./create-file.js'),
  config = require('../config.js'),
  animationsLibSrc = config.libs.animations,
  assets = config.generalAssets,
  destAnimations = path.join(config.src.assets, 'animations.scss'),

  parseAnimations = function(animations) {
    let content = '',
      i = 0,
      len = animations.length;

    if (len === 0) {
      return;
    }

    for (let animationName in animations) {
      if (animations[animationName]) {
        let anim = path.join(animationsLibSrc, animationName + '.css');
        try {
          let animCnt = fs.readFileSync(anim) + '\n';
          try {
            if (i === 0) {
              content = animCnt;
            } else {
              content = fs.readFileSync(destAnimations) + animCnt;
            }
            fs.writeFileSync(destAnimations, content);
            console.log('Создано: ' + destAnimations);
          } catch (err) {
            console.error(err)
          }
        } catch (err) {
          console.error(err)
        }
      }
      i++;
    }

  };



module.exports = parseAnimations;