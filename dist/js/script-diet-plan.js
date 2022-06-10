document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

//=include ../sections/diet-plan-hero/diet-plan-hero.js

//=include ../sections/diet-plan-products-cart/diet-plan-products-cart.js

;
(function() {
  let calendar = q('.diet-plan__calendar');
  let calendarItems = qa('.calendar-item', calendar);
  let calendarNav = q('.diet-plan__calendar-nav', calendar);
  let dietPlanList = q('.diet-plan__list');
  let dietPlanDay = q('.diet-plan__day');
  let dietPlanDate = q('.diet-plan__date');
  let openCalendarBtn = q('.diet-plan__calendar-btn');
  let events = qa('.event', calendar);
  let todayDay = calendar.getAttribute('data-today');
  let closeCalendar = function(e) {
    let target = e.target;

    if (!target.closest('.diet-plan__calendar')) {
      console.log('close');
      calendar.classList.remove('active');
      document.removeEventListener('click', closeCalendar);
    }
  };

  const startDateCell = events[0];
  const finishDateCell = events[1];
  const startDate = startDateCell.getAttribute('data-date').split('.').reverse();
  const finishDate = finishDateCell.getAttribute('data-date').split('.').reverse();

  Date.prototype.addDays = function(days) {
    let dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
  }

  function getDates(startDate, stopDate) {
    let dateArray = [];
    let currentDate = startDate;
    while (currentDate <= stopDate) {
      dateArray.push(currentDate)
      currentDate = currentDate.addDays(1);
    }
    return dateArray.map(date => ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + date.getFullYear());
   }

  let dateArray = getDates(new Date(startDate[0], startDate[1] - 1, startDate[2]), (new Date(finishDate[0], finishDate[1] - 1, finishDate[2])).addDays(0));

  dateArray.forEach(function(date, i) {
    const cell = q('[data-date="' + date + '"]', calendar);
    cell.setAttribute('data-day', i);
    cell.classList.add('available');
  });

  if (calendarItems.length > 1) {
    calendarNav.classList.remove('hide');
  }

  // Open Calendar
  openCalendarBtn.addEventListener('click', function(e) {
    console.log('click');
    document.removeEventListener('click', closeCalendar);
    calendar.classList.toggle('active');

    const activeCalendarItem = q('.calendar-item.active', calendar);
    const todayElement = q('.today', calendar);
    const todayElementparent = todayElement.closest('.calendar-item');

    if (activeCalendarItem) {
      activeCalendarItem.classList.remove('active');
    }

    todayElementparent.classList.add('active');

    setTimeout(function() {
      document.addEventListener('click', closeCalendar);
    });

  });

  // openCalendarBtn.click();

  // Клик по дате внутри календаря
  calendar.addEventListener('click', function(e) {
    let target = e.target;
    if (target.classList.contains('diet-plan__calendar-next') || target.classList.contains('diet-plan__calendar-prev')) {
      const activeItem = q('.calendar-item.active', calendar);
      const item = q('.calendar-item:not(.active)', calendar);

      activeItem.classList.remove('active');
      item.classList.add('active');
    } else if (target.classList.contains('calendar-day') && target.classList.contains('available')) {
      let today = q('.today', calendar);
      let tragetElementDate = target.getAttribute('data-date');
      let tragetElementDateArray = tragetElementDate.split('.');
      let day = target.getAttribute('data-day');
      let data = 'action=load_diet_plan&day_index=' + day;

      calendar.classList.add('loading');
      dietPlanList.classList.add('loading');

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
        let dayName = '';
        const targetDate = new Date(tragetElementDateArray[2], tragetElementDateArray[1] - 1, tragetElementDateArray[0]);
        const nowDate = new Date();
        const targetTime = Date.parse(targetDate);
        const nowTime = Date.parse(nowDate);
        const nowDay = nowDate.getDate();
        const targetDay = targetDate.getDate();
        const lastDayOfCurrentMonth = new Date(tragetElementDateArray[2], tragetElementDateArray[1] - 1, 0).getDate();

        if (targetTime === nowTime) {
          dayName = 'сегодня, ';
        } else if (targetTime < nowTime) {
          if (nowDay - targetDay === 1) {
            dayName = 'вчера, ';  
          }
        } else {
          if (nowDay + 1 === targetDay || lastDayOfCurrentMonth === nowDay && targetDay === 1) {
            dayName = 'завтра, ';  
          }
        }

        dietPlanList.innerHTML = response;
        dietPlanDay.textContent = 'День ' + (+day + 1);
        dietPlanDate.innerHTML = '<span class="diet-plan__today">' + dayName + '</span>' + tragetElementDate;
        calendar.classList.remove('loading');
        dietPlanList.classList.remove('loading');
        today.classList.remove('today');
        target.classList.add('today');
      })
      .catch(function(err) {
        errorPopup && errorPopup.openPopup();
        calendar.classList.remove('loading');
        dietPlanList.classList.remove('loading');
        console.log(err);
      });
    }
  });

  // Замена блюда
  dietPlanList.addEventListener('click', function(e) {
    let target = e.target;

    if (target.classList.contains('diet-plan__item-change')) {
      target.blur();
      target.setAttribute('tabindex', -1);
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
            cartHTML = '',
            cart = Object.assign(response.cart.top, response.cart.bottom);

          for (let key in cart) {
            let number = '';
            if (cart[key].number) {
              number += cart[key].number + ' ' + cart[key].label;
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
          target.removeAttribute('tabindex');
          parent.setAttribute('data-id', response.item.id);
          // target.setAttribute('data-replacement', JSON.stringify(response.replacement_ids));
        })
        .catch(function(err) {
          console.log(err);
          target.removeAttribute('tabindex');
          errorPopup.openPopup();
          parent.classList.remove('loading');
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

;
(function() {
  errorPopup = new Popup('.error-popup', {
    closeButtons: '.error-popup__close'
  });
  // errorPopup.openPopup();
})();

//=include ../sections/footer/footer.js

});