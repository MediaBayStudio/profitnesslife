;
(function() {
  let maybeCloseBlock = document.querySelector('.account-hero-maybe-close'),
    setHeigth = function() {
      maybeCloseBlock.style.transition = 'none';
      maybeCloseBlock.style.height = 'auto';
      maybeCloseBlock.style.height = maybeCloseBlock.scrollHeight + 'px';
      maybeCloseBlock.style.transition = '';
    },
    closeBlock = function(e) {
      if (e && e.type === 'click' && e.target.classList.contains('account-hero__close')) {
        maybeCloseBlock.addEventListener('transitionend', function(e) {
          if (e.propertyName === 'height') {
            maybeCloseBlock.style.display = 'none';
          }
        });
        maybeCloseBlock.style.cssText = 'padding:0;margin:0;height:0;opacity:0';
      }
      fetch(siteUrl + '/wp-admin/admin-ajax.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'action=close_welcome_block&user-id=' + maybeCloseBlock.getAttribute('data-user-id')
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
          console.log('response', response);
        })
        .catch(function(err) {
          console.log(err);
        });
    };

  if (maybeCloseBlock) {
    setHeigth();
    maybeCloseBlock.addEventListener('click', closeBlock);
    windowFuncs.resize.push(setHeigth);
  }
})();