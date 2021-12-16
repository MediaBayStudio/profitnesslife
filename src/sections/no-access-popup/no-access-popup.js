;
(function() {
  noAccessPopup = new Popup('.no-access-popup', {
    // openButtons: '.no-access-btn',
    closeButtons: '.no-access-popup__close'
  });

  if (location.search.indexOf('completed=true') !== -1) {
    noAccessPopup.openPopup();
    noAccessPopup.addEventListener('popupbeforeclose', function() {
      history.replaceState({}, '', siteUrl + '/');
    });
  }
})();