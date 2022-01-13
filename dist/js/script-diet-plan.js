document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

//=include ../sections/diet-plan-hero/diet-plan-hero.js

//=include ../sections/diet-plan-products-cart/diet-plan-products-cart.js

;
(function() {
  let calendar = q('.diet-plan__calendar'),
    calendarItems = qa('.calendar-item', calendar),
    calendarNav = q('.diet-plan__calendar-nav', calendar),
    dietPlanList = q('.diet-plan__list'),
    dietPlanDay = q('.diet-plan__day'),
    dietPlanDate = q('.diet-plan__date'),
    openCalendarBtn = q('.diet-plan__calendar-btn'),
    events = qa('.event', calendar),
    todayDay = calendar.getAttribute('data-today'),
    setDataDay = function(days) {
      for (let i = 0, len = days.length; i < len; i++) {
        days[i].setAttribute('data-day', i);
      }
    },
    closeCalendar = function(e) {
      let target = e.target;

      if (!target.closest('.diet-plan__calendar')) {
        calendar.classList.remove('active');
        document.removeEventListener('click', closeCalendar);
      }
    };

  events.forEach(function(event, i) {
    let parent = event.parentElement,
      next = parent.nextElementSibling,
      prev = parent.previousElementSibling;

    setDataDay(parent.children);
    parent.classList.add('active-week');

    if (i === 0 && next) {
      next.classList.add('active-week');
      next.setAttribute('data-week', 2);
      setDataDay(next.children);
    }

    if (i === 1 && prev) {
      prev.classList.add('active-week');
      prev.setAttribute('data-week', 2);
      setDataDay(prev.children);
    }
  });

  events[0]&&events[0].parentElement.setAttribute('data-week', 1);
  events[1]&&events[1].parentElement.setAttribute('data-week', 3);

  if (calendarItems.length > 1) {
    calendarNav.classList.remove('hide');
  }

  openCalendarBtn.addEventListener('click', function(e) {
    let target = e.target;

    if (target.classList.contains('diet-plan__calendar-next') || target.classList.contains('diet-plan__calendar-prev')) {
      let activeItem = q('.calendar-item.active', calendar),
        item = q('.calendar-item:not(.active)', calendar);

      activeItem.classList.remove('active');
      item.classList.add('active');
    } else {
      calendar.classList.add('active');

      let activeCalendarItem = q('.calendar-item.active', calendar),
        todayElement = q('.today', calendar),
        todayElementparent = todayElement.closest('.calendar-item');

      if (activeCalendarItem) {
        activeCalendarItem.classList.remove('active');
      }

      todayElementparent.classList.add('active');

      setTimeout(function() {
        document.addEventListener('click', closeCalendar);
      });
    }
  });

  // Замена блюда
  dietPlanList.addEventListener('click', function(e) {
    let target = e.target;

    if (target.classList.contains('diet-plan__item-change')) {
      let parent = target.closest('.diet-plan__item'),
        parentId = +parent.getAttribute('data-id'),
        replacementIds = JSON.parse(target.getAttribute('data-replacement')),
        parentIndex = replacementIds.indexOf(parentId),
        replacementId = replacementIds[parentIndex + 1] || replacementIds[0],
        data = `action=replace_dish&replacement_id=${replacementId}&today=${todayDay}&dish_type=${parent.getAttribute('data-type')}`;

      parent.classList.add('loading');

      fetch(siteUrl + '/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: data,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        })
        .then(function(response) {
          if (response.ok) {
            return response.text();
          } else {
            console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
            return '';
          }
        })
        .then(function(response) {
          response = JSON.parse(response);

          let recipe = q('.diet-plan__item-recipe-text', parent),
            calories = q('.diet-plan__item-calories', parent),
            title = q('.diet-plan__item-title', parent),
            productsCartPopupTable = q('.products-cart-popup__table', productsCartPopup),
            productsCartHero = q('.products-cart-hero'),
            ingredientsList = q('.diet-plan__item-igredietns-list', parent),
            ingredientsHTML = '',
            cartHTML = '';

          for (let key in response.cart) {
            let number = '';
            if (response.cart[key].number) {
              number += response.cart[key].number + ' ' + response.cart[key].label;
            }
            cartHTML +=
              `<div class="products-cart-popup__tr">
                <span class="products-cart-popup__td">${key}</span>
                <span class="products-cart-popup__td">${number}</span>
              </div>`;
          }

          productsCartHero.classList.add('attention');

          productsCartPopupTable.innerHTML = cartHTML;

          response.item.ingredients.forEach(function(ingredient) {
            ingredientsHTML += '<li class="diet-plan__item-igredietns-li">' + ingredient.title;
            if (ingredient.number) {
              ingredientsHTML += ' (' + ingredient.number + ' ' + ingredient.units + ')';
            }
            ingredientsHTML += '</li>';
          });

          // Если есть или нет рецепта 
          if (response.item.text) {
            recipe.innerHTML = response.item.text;
            parent.classList.remove('no-recipe');
          } else {
            parent.classList.add('no-recipe');
          }
          calories.innerHTML = 'Калорийность ' + response.item.calories + ' ккал';
          title.innerHTML = response.item.title;
          ingredientsList.innerHTML = ingredientsHTML;

          // console.log(ingredientsHTML);

          parent.classList.remove('loading');
          parent.setAttribute('data-id', response.item.id);
          // target.setAttribute('data-replacement', JSON.stringify(response.replacement_ids));
        })
        .catch(function(err) {
          console.log(err);
        });
    }
  });

  // СТАРАЯ ВЕРСИЯ С ЗАМНОЙ НА ДОПОЛНИТЕЛЬНЫЕ БЛЮДА ИЗ СПИСКА
  // dietPlanList.addEventListener('click', function(e) {
  //   return;
  //   let target = e.target;
  //   if (target.classList.contains('diet-plan__item-change')) {
  //     let parent = target.closest('.diet-plan__item'),
  //       replacement_ids = JSON.parse(target.getAttribute('data-replacement')),
  //       data = 'action=replace_dish&type=' + target.getAttribute('data-replacement-selector') + '&today=' + todayDay;

  //     if (parent) {
  //       let parentId = +parent.getAttribute('data-id'), // id текущего блюда
  //         haveBeenReplaced = parent.getAttribute('data-replaced'), // замены, которые уже были
  //         replacementId; // id на которое будет замена блюда

  //       data += '&dish_type=' + parent.getAttribute('data-type');

  //       console.log('current id ' + parentId);
  //       console.log(replacement_ids);

  //       if (haveBeenReplaced) {
  //         console.log('Замены уже были на странице');
  //         // Если замены уже были, то ищем последний ID
  //         // и будем отправлять на сервер следующий за ним
  //         haveBeenReplaced = haveBeenReplaced.split(' ');

  //         let lastIndex = replacement_ids.indexOf(+haveBeenReplaced[haveBeenReplaced.length - 1]);

  //         console.log('Индекс блюда последней замены: ', lastIndex);

  //         if (lastIndex !== -1) {
  //           // Нужно предложить следующее блюдо, если оно есть
  //           if (replacement_ids[lastIndex + 1]) {
  //             console.log('Предлагаем другое блюдо');
  //             replacementId = replacement_ids[lastIndex + 1];
  //             console.log('Будет замена на ID - ' + replacementId);
  //           } else {
  //             // Иначе начинаем с 0
  //             console.log('Начинаем замены сначала');
  //             replacementId = replacement_ids[0];
  //             console.log('Будет замена на ID - ' + replacementId);
  //           }
  //         }

  //         haveBeenReplaced.push(replacementId);
  //         parent.setAttribute('data-replaced', haveBeenReplaced.join(' '));

  //       } else {
  //         let parentIdIndex = replacement_ids.indexOf(parentId);

  //         if (parentIdIndex !== -1) {
  //           console.log('Замены блюд уже были когда-то');

  //           data += '&initial_id=' + parentId;

  //           if (replacement_ids[parentIdIndex + 1]) {
  //             console.log('Предлагаем другое блюдо');
  //             // Предлагаем следующее блюдо, если оно есть
  //             replacementId = replacement_ids[parentIdIndex + 1];
  //             console.log('Будет замена на ID - ' + replacementId);
  //           } else {
  //             // Иначне начинаем сначала
  //             console.log('Начинаем замены сначала');
  //             replacementId = replacement_ids[0];
  //             console.log('Будет замена на ID - ' + replacementId);
  //           }
  //         } else {
  //           // еще не было ни одной замены
  //           console.log('Самая первая замена блюда');
  //           replacementId = replacement_ids[0];
  //           data += '&initial_id=' + parentId;
  //           console.log('Будет замена на ID - ' + replacementId);
  //         }
  //       }

  //       parent.setAttribute('data-replaced', replacementId);

  //       data += '&replacement_id=' + replacementId;

  //       // отправляем на сервер ID первоначального блюда
  //       // ID блюда, на которое заменяем

  //       parent.classList.add('loading');
  //       target.blur();

  //       fetch(siteUrl + '/wp-admin/admin-ajax.php', {
  //           method: 'POST',
  //           body: data,
  //           headers: {
  //             'Content-Type': 'application/x-www-form-urlencoded'
  //           }
  //         })
  //         .then(function(response) {
  //           if (response.ok) {
  //             return response.text();
  //           } else {
  //             console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
  //             return '';
  //           }
  //         })
  //         .then(function(response) {
  //           // console.log(response);
  //           // return;
  //           response = JSON.parse(response);

  //           let recipe = q('.diet-plan__item-recipe-text', parent),
  //             calories = q('.diet-plan__item-calories', parent),
  //             title = q('.diet-plan__item-title', parent),
  //             productsCartPopupTable = q('.products-cart-popup__table', productsCartPopup),
  //             productsCartHero = q('.products-cart-hero'),
  //             ingredientsList = q('.diet-plan__item-igredietns-list', parent),
  //             ingredientsHTML = '',
  //             cartHTML = '';

  //           for (let key in response.cart) {
  //             let number = '';
  //             if (response.cart[key].number) {
  //               number += response.cart[key].number + ' ' + response.cart[key].label;
  //             }
  //             cartHTML +=
  //               `<div class="products-cart-popup__tr">
  //               <span class="products-cart-popup__td">${key}</span>
  //               <span class="products-cart-popup__td">${number}</span>
  //             </div>`;
  //           }

  //           productsCartHero.classList.add('attention');

  //           productsCartPopupTable.innerHTML = cartHTML;

  //           response.item.ingredients.forEach(function(ingredient) {
  //             ingredientsHTML += '<li class="diet-plan__item-igredietns-li">' + ingredient.title;
  //             if (ingredient.number) {
  //               ingredientsHTML += ' (' + ingredient.number + ' ' + ingredient.units + ')';
  //             }
  //             ingredientsHTML += '</li>';
  //           });

  //           recipe.innerHTML = response.item.text;
  //           calories.innerHTML = 'Калорийность ' + response.item.calories + ' ккал';
  //           title.innerHTML = response.item.title;
  //           ingredientsList.innerHTML = ingredientsHTML;

  //           // console.log(ingredientsHTML);

  //           parent.classList.remove('loading');
  //           parent.setAttribute('data-id', response.item.id);
  //           target.setAttribute('data-replacement', JSON.stringify(response.replacement_ids));
  //         })
  //         .catch(function(err) {
  //           console.log(err);
  //         });
  //     }

  //     console.log('click on the change-button');
  //   }
  // });

  // Клик по дате внутри календаря
  calendar.addEventListener('click', function(e) {
    let target = e.target;

    if (target.classList.contains('calendar-day') && target.parentElement.classList.contains('active-week')) {
      let today = q('.today', calendar),
        tragetElementDate = target.getAttribute('data-date').slice(-10),
        week = target.parentElement.getAttribute('data-week'),
        day = target.getAttribute('data-day'),
        data = 'action=load_diet_plan&day=' + target.textContent +
        '&week=' + week +
        '&day_index=' + day;

      console.log('data-day', day);

      calendar.classList.add('loading');

      fetch(siteUrl + '/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: data,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        })
        .then(function(response) {
          if (response.ok) {
            return response.text();
          } else {
            console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
            return '';
          }
        })
        .then(function(response) {
          let dayName = '',
            targetDate = new Date(tragetElementDate.slice(-4), tragetElementDate.slice(3, 5), tragetElementDate.slice(0, 2)).getDate(),
            todayDate = new Date().getDate();

          // console.log('tragetElementDate', tragetElementDate);
          // console.log('targetDate', targetDate);
          // console.log('todayDate', todayDate);

          if (targetDate === todayDate) {
            dayName = 'сегодня, ';
          } else if (todayDate - targetDate === 1) {
            dayName = 'вчера, ';
          } else if (todayDate + 1 === targetDate) {
            dayName = 'завтра, ';
          }

          console.log(day);

          dietPlanList.innerHTML = response;
          dietPlanDay.textContent = 'День ' + ((+day + 1) + ((week - 1) * 7));
          dietPlanDate.innerHTML = '<span class="diet-plan__today">' + dayName + '</span>' + target.getAttribute('data-date').slice(-10);
          // console.log(response);
          // console.log(JSON.parse(response));
          calendar.classList.remove('loading');
          today.classList.remove('today');
          target.classList.add('today');
        })
        .catch(function(err) {
          console.log(err);
        });
    }
  });

})();

;
(function() {
  allowedProductsPopup = new Popup('.allowed-products-popup', {
    openButtons: '.allowed-popup-open',
    closeButtons: '.allowed-products-popup__close'
  });
})();

;(function() {
  nutritionRulesPopup = new Popup('.nutrition-rules-popup', {
    openButtons: '.nutrition-rules-popup-open',
    closeButtons: '.nutrition-rules-popup__close'
  });
})();

;(function() {
  productsCartPopup = new Popup('.products-cart-popup', {
    openButtons: '.products-cart-popup-open',
    closeButtons: '.products-cart-popup__close'
  });
  productsCartPopup.addEventListener('popupbeforeopen', function() {
    q('.products-cart-hero').classList.remove('attention');
  });
  // productsCartPopup.openPopup();
})();

//=include ../sections/footer/footer.js

});