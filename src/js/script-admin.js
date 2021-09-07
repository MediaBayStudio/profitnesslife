var q = function(selector, element) {
  element = element || document.body;
  return element.querySelector(selector);
}

function createRecipe() {
  let url = siteUrl + '/wp-admin/admin-ajax.php',
  // let url = siteUrl + '/wp-json/wp/v2/recipe',
    recipeField = q('[data-name="recipe"]'),
    recipeTextField = q('[data-name="recipe_text"] textarea'),
    caloriesField = q('[data-name="calories"] input'),
    optionField = q('[data-placeholder="Выбрать"]').value,
    postID = optionField,
    targetCalories = caloriesField.value;
    data = 'action=load_recipe&id=' + optionField;

  if (caloriesField.value) {
    data += '&calories=' + caloriesField.value;
  }

  // fetch(url + '?include[]=' + postID, {
  fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(response) {
      if (response.ok) {
        return response.text();
      } else {
        console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
        // showError('Ошибка ' + response.status + ' (' + response.statusText + ')', btn);
        return '';
      }
    })
    .then(function(response) {
      try {
        // response = JSON.parse(response);
        // let postData = response[0],
          // postACF = postData.acf,
          // target

        console.log(response);
        recipeTextField.value = response;
      } catch (err) {
        console.log(err);
        // showError(err, loadmoreButton);
      }
    });

  // fetch(url, {
  //     method: 'POST',
  //     headers: {
  //       'Content-Type': 'application/x-www-form-urlencoded'
  //     },
  //     body: data
  //   })
  //   .then(function(response) {
  //     if (response.ok) {
  //       return response.text();
  //     } else {
  //       console.log('Ошибка ' + response.status + ' (' + response.statusText + ')');
  //       // showError('Ошибка ' + response.status + ' (' + response.statusText + ')', btn);
  //       return '';
  //     }
  //   })
  //   .then(function(response) {
  //     try {
  //       recipeTextField.value = response;
  //       console.log(response);
  //       // response = JSON.parse(response);
  //     } catch (err) {
  //       console.log(err);
  //       // showError(err, loadmoreButton);
  //     }
  //   })
    // .catch(function(err) {
    //   showError(err, loadmoreButton);
    // });
}