// include to script.js

;(function() {
  let form = q('.form-sign');

  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      form.classList.add('loading');
      form.blur();
      

      let data = new FormData(form),
        error = q('.invalid', form);
      data.append('action', 'auth');

      if (error) {
        error.parentElement.removeChild(error);
      }

      fetch(siteUrl + '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data
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

        console.log(response);

        if (response.error) {
          form['user_pass'].insertAdjacentHTML('afterend', '<label class="invalid">Неверный логин или пароль</label>');
          form.classList.remove('loading');
        } else {
          location.replace(siteUrl + '/account');
        }
      })
      .catch(function(err) {
        console.log(err);
        form.classList.remove('loading');
        errorPopup.openPopup();
      });
    });

    // btn.addEventListener('click', function(e) {
    //   e.preventDefault();
    //   console.log('here');
    // });
  }
})();