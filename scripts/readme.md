# Gulp start
## Описание
Создание папок и файлов проекта при помощи команды gulp start.
Структура файлов, папок и их содержимого устанавливается в `scripts/config.js`.



gulp createblock --name %name% - создает в папке src/blocks/ папку с названием %name%, которая будет содержать php файл, scss файлы по размерам экрана и js файл.

gulp createscssblock --name %name% - создает в папке src/scss/ папку с названием %name%, которая будет содержать только scss файлы по размерам экрана. Файлы из этой папки в итоге будут вставлены в гланый css файл.


При dynamicWordpress scss/assets и scss/blocks будут всталвены главный css файл.


gulp createpage --pagename '...' 
Таск создает необходимые файлы для новой страницы и заполняет контентом по умолчанию.
В названии можно передавать файл с расширением и без расширения.
Если wordpress === true, то в любом случае будет создан .php файл. Например, about.html при wordpress === true, будет переименован в about.php. Для изменения этого поведения можно указать ignoreext, например: gulp createpage --pagename about.html --ignoreext.

gulp createpage --pagename about
gulp createpage --pagename about.php 
gulp createpage --pagename about.html

Если flexibleWordpress === true, то к .php файлу будут добавлены .scss и .js файлы, которые разместятся в соответствующие папки.



gulp movesrc - перемещает исходный код в папку с сайтом/assets.
Входит в gulp default.


php создает файл style-info.js, в котором хранит информацию о том, какие секции на каких страницах. Это нужно для импорта scss в gulp на этапе разработки.


```style.css```
В стили темы будут по умолчанию вставлены `generalAssets` (reset, fonts, animations) и `generalScss` (buttons, forms, popups and etc). Это можно изменить, передав массив в свойство `themeStyleImports` конфигурации.
Например:
```javascript
config.themeStyleImports: [
  // generalAssets
  config.src.assets + 'animations',
  config.src.assets + 'fonts',
  config.src.assets + 'grid',
  config.src.assets + 'reset',
  // generalScss
  config.src.scss + 'buttons/buttons',
  config.src.scss + 'interface/interface',
  config.src.scss + 'popups/popups',
  config.src.scss + 'forms/forms',
  config.src.scss + 'sliders/sliders',
  // other ...
]
```

ВСЕ JS ПОЛИФИЛЛЫ ДОЛЖНЫ БЫТЬ ОБЯЗАТЕЛЬНО .min.js