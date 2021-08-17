const path = require('path').posix;
/**
 * Пути для локального хоста зависят от версии MAMP
 * https://www.mamp.info/en/mac/
 * вставить имя пользователя вместо - NAME
 * v.1 - /Users/NAME/Sites/localhost/
 * v.2 -/Applications/MAMP/htdocs/
 */
// const USER_SERV_DEST = '/Users/vadim/Sites/localhost/',
const USER_SERV_DEST = '/Applications/MAMP/htdocs/',
// const USER_SERV_DEST = 'test',
          SITE_NAME = 'profitnesslife',
          // PC_USERNAME = 'vadim';
          PC_USERNAME = 'administrator';

const paths = {
  localServer: path.join(USER_SERV_DEST, SITE_NAME),
  theme: path.join(USER_SERV_DEST, SITE_NAME, 'wp-content', 'themes',  SITE_NAME),
  src: 'src',

  get assets() {
    return path.join(this.theme, 'src')
  }
}

let wordpress = true;

const config = {
  localServerDest: paths.localServer,
  wordpress,
  themeStyleTemplate: [
    '@charset "UTF-8";',
    '/*',
    'Theme Name: Profitnesslife',
    'Author: Медиа Гавань',
    'Author URI: https://media-bay.ru/',
    'Version: 1.0',
    '*/'
  ],

  dbname: 'andrejqb_profit',
  dbhost: '127.0.0.1:8889',
  dbuser: 'admin',
  dbpass: '12345',
  siteName: 'name',
  siteurl: 'http://localhost:8888/' + SITE_NAME,
  wpAdmin: 'admin',
  siteTtitle: 'Profitnesslife',
  siteDescr: 'Фитнес&#8209;марафон',
  adminEmail: 'alexander@media-bay.ru',

  // Путь для локальных плагинов
  wpPluginsPath: path.join('Users', PC_USERNAME, 'Desktop', 'projects', SITE_NAME, 'scripts', 'libs', 'wordpress-plugins'),

  /* Плагины с .zip на конце будут взяты из локального хранилища
      остальные будут скачаны из репозитория вордпресс
   */
  wpPlugins: [
    'cyr2lat',
    'contact-form-7.zip',
    'flamingo',
    'contact-form-7-honeypot',
    // 'advanced-custom-fields-table-field',
    'advanced-custom-fields-pro.zip',
    'backupbuddy-8.7.3.0.zip'
  ],

  dest: {
    path: paths.theme,
    sourceCode: paths.assets,
    parts: path.join(paths.theme, (wordpress ? 'template-parts': 'layouts')),
    components: path.join(paths.theme, 'components'),
    sections: path.join(paths.theme, 'sections'),
    scss: path.join(paths.theme, 'scss'),
    fonts: path.join(paths.theme, 'fonts'),
    js: path.join(paths.theme, 'js'),
    json: paths.theme,
    php: paths.theme,
    inc: path.join(paths.theme, 'inc'),
    html: paths.theme,
    img: path.join(paths.theme, 'img'),
  },

  src: {
    path: paths.src,
    assets: path.join(paths.src, 'scss', 'assets'),
    components: path.join(paths.src, 'components'),
    sections: path.join(paths.src, 'sections'),
    scss: path.join(paths.src, 'scss'),
    fonts: path.join(paths.src, 'fonts'),
    js: path.join(paths.src, 'js'),
    json: paths.src,
    php: paths.src,
    inc: path.join(paths.src, 'inc'),
    html: paths.src,
    img: path.join(paths.src, 'img'),
  },

  libs: {
    pages: path.normalize('./scripts/libs/'),
    animations: path.normalize('./scripts/libs/animations'),
    fonts: path.normalize('./scripts/libs/fonts'),
    css: path.normalize('./scripts/libs/css'),
    js: path.normalize('./scripts/libs/js'),
    polyfills: path.normalize('./scripts/libs/polyfills'),
    phpIncludes: path.normalize('./scripts/libs/inc'),
  },

  cssBreakpoints: [
    '',
    '(min-width:575.98px)',
    '(min-width:767.98px)',
    '(min-width:1023.98px)',
    '(min-width:1279.98px)'
  ],

  containerWidth: [
    280,
    536,
    720,
    960,
    1160
  ],

  cssAnimations: {
    translateToBottom: true,
    spin: true,
    searching: false
  },

  cssColors: {
    lightBeige: '#F6EFE9',
    cream: '#F3E0D7',
    coral: '#E99A8B',
    beige: '#CDB7AA',
    pistachio: '#B0BBA7',
    accentGreen: '#85B921',
    light: '#F3F2EE',
    white: '#fff',
    black: '#0A0A0A',
  },

  fonts: [
    'NotoSans-Bold',
    'NotoSans-Regular',
    'Roboto-Regular',
    'Roboto-Medium',
    'Roboto-Bold'
  ],

  variables: {
    /* containerWidth будет создан автоматичеки
        Вертикальные отступы для всех размеров экранов будут созданы автоматически
        0 - значит пропустить
        sectionHorizontalPadding будут созданы автоматичеси
        mediaQuery будут созданы автоматически
     */
    sectionVerticalPadding: [
      35,
      0,
      45,
      55,
      65
    ]
  },

  /* Формирование файла assets.css, он будет вставлен в основной файл стилей темы
   */
  get generalAssets() {
    return [
      'src/scss/assets/animations',
      'src/scss/assets/fonts',
      'src/scss/assets/grid',
      'src/scss/assets/reset'
    ].map(stylePath => path.normalize(stylePath));
  },

  // Будут вставлены в каждый файл .scss
  get otherAssets() {
    return [
      'src/scss/assets/colors',
      'src/scss/assets/mixins',
      'src/scss/assets/variables'
    ].map(stylePath => path.normalize(stylePath));
  },

  // У полифиллов просто сканируется вся папка
  /*
  polyfills: {
    'intersection-observer.min.js': true,
    'custom-events.min.js': true,
    'closest.min.js': true
  },
  */

  phpIncludes: {
    // ajaxSearch: {
    //   comment: 'Замена стандартного поиска на ajax +расшиерние поиска по acf полям',
    //   path: 'ajax-search.php'
    // },
    buildStyles: {
      comment: 'Функция формирования стилей для страницы при сохранении страницы',
      path: 'build-styles.php',
      onlyAdmin: true
    },
    buildScripts: {
      comment: 'Функция формирования скриптов для страницы при сохранении страницы',
      path: 'build-scripts.php',
      onlyAdmin: true
    },
    generateImages: {
      comment: 'Функция создания webp и минификации изображений',
      path: 'generate-images.php',
      onlyAdmin: true
    },
    createPicture: {
      comment: 'Создание <picture> для img',
      path: 'create-picture.php'
    },
    createLinkPreload: {
      comment: 'Создание <link rel="preload" /> для img',
      path: 'create-link-preload.php'
    },
    buildPagesInfo: {
      comment: 'Формирование файла pages-info.json, для понимания на какой странице какие секции используются',
      path: 'build-pages-info.php',
      onlyAdmin: true
    },
    enableSvgAndWebp: {
      comment: 'Активация SVG и WebP в админке',
      path: 'enable-svg-and-webp.php'
    },
    enqueueStylesAndScripts: {
      comment: 'Регистрация стилей и скриптов для страниц и прочие манипуляции с ними',
      path: 'enqueue-styles-and-scripts.php'
    },
    disableWpScriptsAndStyles: {
      comment: 'Отключение стандартных скриптов и стилей, гутенберга, emoji и т.п.',
      path: 'disable-wp-scripts-and-styles.php'
    },
    menus: {
      comment: 'Регистрация меню на сайте',
      path: 'menus.php'
    },
    optionsFileds: {
      comment: 'Регистрация доп. полей в меню Настройки->Общее',
      path: 'options-fields.php'
    },
    registerCustomPostsTypesAndTaxonomies: {
      comment: 'Регистрация и изменение записей и таксономий',
      path: 'register-custom-posts-types-and-taxonomies.php'
    },
    adminMenuActions: {
      comment: 'Удаление лишних пунктов из меню админ-панели и прочие настройки админ-панели',
      path: 'admin-menu-actions.php',
      onlyAdmin: true
    },
    themeSupportAndThumbnails: {
      comment: 'Нужные поддержки темой, рамзеры для нарезки изображений',
      path: 'theme-support-and-thumbnails.php'
    },
    phpPathJoin: {
      comment: 'Склеивание путей с правильным сепаратором',
      path: 'php-path-join.php'
    },
    // name: {
    //   comment: '',
    //   path: phpIncludesPath + 'ajax'
    // }
  },

  get pages() {
    let extname = '.' + (wordpress ? 'php' : 'html');
    return [
      'index',
      'account',
      'diet-plan',
      'training-program',
      'chat',
      'questionnaire',
      '404'
    ].map(page => path.join(this.src.path, page + extname));
  },

  get generalScss() {
    return [
      'buttons.scss',
      'interface.scss',
      'popups.scss',
      'forms.scss',
      'sliders.scss'
    ].map(filename => path.join(this.src.scss, 'style', filename));
  },

  get sections() {
    let extname = '.' + (wordpress ? 'php' : 'html');
    return [
      'header',
      'header-account',
      'mobile-menu',
      'side-menu',

      'index-hero',
      'index-features',
      'index-interface',
      'index-invite',
      'index-reviews',
      'index-team',
      'index-form',
      'index-payment',
      'index-chat',
      'index-instagram',

      'account-hero',
      'account-weight-chart',
      'account-measure-chart',
      'account-photo-progress',

      'diet-plan-hero',

      'training-program-hero',

      'chat-hero',

      'questionnaire-complete',
      'questionnaire-incomplete',

      'footer',
      'products-popup',
      'diet-rules-popup',
      'thanks-popup'
    ].map(sectionName => path.join(this.src.sections, sectionName, sectionName + extname));
  },

  // Каждый блок должен быть обернут в функцию с какими-то аргументами
  // и выводиться в цикле или в каких-то других местах php
  get components() {
    let extname = '.' + (wordpress ? 'php' : 'html');
    return [
      'dish-block',
      'questionnaire-card',
      'account-hero-block'
    ].map(componentName => path.join(this.src.components, componentName, componentName + extname));
  },

  get themeStyleImports() {
    return [
      path.join(this.src.assets, 'animations'),
      path.join(this.src.assets, 'fonts'),
      path.join(this.src.assets, 'grid'),
      path.join(this.src.assets, 'reset'),
      path.join(this.src.scss, 'style', 'buttons'),
      path.join(this.src.scss, 'style', 'interface'),
      path.join(this.src.scss, 'style', 'popups'),
      path.join(this.src.scss, 'style', 'forms'),
      path.join(this.src.scss, 'style', 'sliders')
    ];
  },

  // Главный файл стилей (работает только с !flexibleWordpress)
  get mainScss() {
    return path.join(this.src.scss, 'style.css');
  },

  /* if flexibleWordpress, то будут созданы css файлы
      для каждой страницы, при этом страницы в скобках исключаются
      для страниц в скобках будет создан один файл

      if !flexibleWordpress, то будут созданы все файлы по списку
    */
  get scss() {
    return [
      // ! - запрещает создавать размеры для файлов
      // 'src/scss/style-service (design, context, instagram, perfomance, seo, web)',
      path.normalize('src/scss/!hover'),
      // Стили для админки
      path.normalize('src/scss/style-admin')
    ];
  },

  /* Здесь нужно задать импорты в scss файлы
      будет работать только если flexibleWordpress: false
  */
  get scssImports() {
    return {
      'src/scss/style-index': [
        'src/scss/general/interface/interface',
      ]
    };
  },

  /* Если flexibleWordpress, то для каждой страницы будет создан ее собственный js
      Если !flexibleWordpress, то указываются js файлы и defer
      Также будет создан и подключен script-admin.js, для админ-панели
   */
  get js() {
    return [
      'svg4everybody.min.js',
      'slick.min.js',
      'lazy.min.js', ,
      'Popup.min.js',
      'script.js'
    ];
  },

  get jsComponents() {
    return [
      // 'main.js',
      'utils.js',
      'menu.js',
      'popups.js',
      'validateForms.js',
      'telMask.js',
      'sliders.js'
    ];
  }
};

module.exports = config;