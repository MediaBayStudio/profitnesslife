let path = require('path'),
  fs = require('fs'),
  config = require('../config.js'),
  createFile = require('./create-file.js'),
  wordpress = config.wordpress,
  flexibleWordpress = config.flexibleWordpress,
  cssBreakpoints = config.cssBreakpoints,
  scssArray = config.scss,
  pages = config.pages,

  createFunctionsPHP = function() {
    let functionsLibsSrc = path.join(config.libs.pages, 'functions.php'),
      functionsDest = path.join(config.src.path, 'functions.php'),
      functionsCnt = '';

    try {
      functionsCnt = fs.readFileSync(functionsLibsSrc);
    } catch (err) {
      console.log('Файл ' + functionsLibsSrc + ' не найден');
      return;
    }

    cssBreakpoints = '$screen_widths = [' + cssBreakpoints.map(function(el) {
      if (el === '') {
        return '\'0\'';
      } else {
        return '\'' + Math.ceil(el.replace(/[^0-9.]+/g, '')) + '\'';
      }
    }).join(', ') + '];';

    let adminFunctions = '\nif ( is_super_admin() || is_admin_bar_showing() ) {\n\n';

    for (let key in config.phpIncludes) {
      let include = config.phpIncludes[key],
        includePath = path.join(config.libs.phpIncludes, include.path);

      try {
        let data = fs.readFileSync(includePath),
          enqueueStyles = '',
          enqueueScripts = '';

        if (includePath.indexOf('enqueue') !== -1) {
          data = data.toString();

          config.pages.forEach(function(page) {
            page = page.split(/,\s|,/)[0];

            let {
              // '/home/user/dir/file.txt'
              root, // '/'
              dir, // '/home/user/dir'
              base, // 'file.txt'
              ext, // '.txt'
              name, // file
            } = path.parse(page),
              frontPageCondition = '';


            if (enqueueStyles) {
              enqueueStyles += ' else if ';
            } else {
              enqueueStyles += 'if ';
            }

            if (base.indexOf('index') !== -1) {
              frontPageCondition = ' || is_front_page()';
            }

            enqueueStyles += '( is_page_template( \'' + base + '\' )' + frontPageCondition + ' ) {';
            enqueueStyles += '\n\t\t$style_name = \'style-' + name + '\';';
            enqueueStyles += '\n\t\t$script_name = \'script-' + name + '\';\n\t}';

          });

          enqueueStyles += '\n\n\tenqueue_style( $style_name, $screen_widths );';
          enqueueScripts += '\n\n\t$scripts = [\'script\', $script_name];';

          data = data.replace('#!!!styles', enqueueStyles);
          data = data.replace('#!!!screen_widths', cssBreakpoints);
          data = data.replace('#!!!scripts', enqueueScripts);

        }
        createFile(path.join(config.src.inc, include.path), data);

        if (include.onlyAdmin) {
          adminFunctions += include.comment ? '\t// ' + include.comment : '';
          adminFunctions += '\n';
          adminFunctions += '\trequire $template_directory . \'/inc/' + include.path + '\';\n\n';
        } else {
          functionsCnt += include.comment ? '// ' + include.comment : '';
          functionsCnt += '\n';
          functionsCnt += 'require $template_directory . \'/inc/' + include.path + '\';\n\n';
        }

      } catch (err) {
        console.log('Файл ' + includePath + ' не найден');

        if (include.onlyAdmin) {
          adminFunctions += include.comment ? '\t// ' + include.comment : '';
          adminFunctions += '\n';
          adminFunctions += '\t// require $template_directory . \'/inc/' + include.path + '\';\n\n';
        } else {
          functionsCnt += include.comment ? '// ' + include.comment : '';
          functionsCnt += '\n';
          functionsCnt += '// require $template_directory . \'/inc/' + include.path + '\';\n\n';
        }

      }
    }

    adminFunctions += '\n}';

    functionsCnt += adminFunctions;

    createFile(functionsDest, functionsCnt);

  };

module.exports = createFunctionsPHP;