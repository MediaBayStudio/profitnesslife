// export PATH=$PATH:/Applications/MAMP/Library/bin/

let { src, dest, watch, series, parallel, task } = require('gulp'),
  path = require('path'),
  shell = require('gulp-shell'),
  generatePassword = function(len) {
    let password = '',
      symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (let i = 0; i < len; i++) {
      password += symbols.charAt(Math.floor(Math.random() * symbols.length));
    }
    return password;
  },
  config = require('../config.js'),
  dbname = config.dbname,
  dbhost = config.dbhost,
  dbuser = config.dbuser,
  dbpass = config.dbpass,
  siteName = config.siteName,
  siteurl = config.siteurl,
  wpAdmin = config.wpAdmin,
  siteTtitle = config.siteTtitle,
  siteDescr = config.siteDescr,
  adminEmail = config.adminEmail,
  wpPluginsPath = config.wpPluginsPath,
  wpPlugins = config.wpPlugins,
  plugins = wpPlugins.map(plugin => plugin.indexOf('.zip') === -1 ? plugin : path.join(wpPluginsPath, plugin)).join(' '),
  buildWordpress = function(cb) {
    let adminPassword = generatePassword(15);

    src('gulpfile.js', { read: false })
      .pipe(shell([
        'wp core download --locale=ru_RU',
        '(export PATH=$PATH:/Applications/MAMP/Library/bin/ && wp config create --dbname='+dbname+' --dbuser='+dbuser+' --dbpass='+dbpass+' --dbhost='+dbhost+')',
        'wp db create',
        'wp core install --url='+siteurl+' --title="'+siteTtitle+'" --admin_user='+wpAdmin+' --admin_password='+adminPassword+' --admin_email='+adminEmail,
        "wp rewrite structure '/%category%/%postname%/'",
        'wp option update blogdescription "'+siteDescr+'"',
        'wp option update blogpublic 0',

        'wp option update show_on_front page',
        'wp option update page_on_front 2',

        'wp option update thumbnail_size_w 0',
        'wp option update thumbnail_size_h 0',
        'wp option update medium_size_w 0',
        'wp option update medium_size_h 0',
        'wp option update large_size_w 0',
        'wp option update large_size_h 0',

        'wp plugin delete akismet',
        'wp plugin delete hello',
        'wp plugin install '+plugins+' --activate',
        // 'wp plugin install cyr2lat --activate'
        // 'wp plugin install "/Users/administrator/Desktop/wordpress plugins/backupbuddy-8.7.2.0.zip" --activate'

        'wp post delete 1 --force', // удалить существующую запись
        'wp post update 2 --post_title=Главная',
        'wp post update 2 --post_name=index',
        // 'wp post meta update _wp_page_template index.php'
      ], {
        cwd: config.localServerDest
      }));

    // title ""
    // или wp option update blogname ""

    console.log('Admin password ' + adminPassword);

    cb();
  };

module.exports = buildWordpress;