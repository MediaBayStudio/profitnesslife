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