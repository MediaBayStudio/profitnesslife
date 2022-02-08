;
(function() {
  let tabClass = 'index-interface__screens-tab',
    screenClass = 'index-interface__screen',
    screensBlock = q('.index-interface__screens'),
    tabBackground = q('.index-interface__screens-tab-background', screensBlock),
    tabs = qa('.' + tabClass, screensBlock, true),
    screens = qa('.' + screenClass, screensBlock),
    setBackground = function(target) {
      tabBackground.style.transform = 'translate3d(' + target.offsetLeft + 'px, 0px, 0px)';
      tabBackground.style.height = target.offsetHeight + 'px';
      tabBackground.style.width = target.offsetWidth + 'px';
    };

  screensBlock.addEventListener('click', function(e) {
    let target = e.target;
    if (target.classList.contains(tabClass)) {
      let activeElements = qa('.' + tabClass + '.active, .' + screenClass + '.active', screensBlock);

      activeElements.forEach(activeElement => activeElement.classList.remove('active'));

      target.classList.add('active');
      screens[tabs.indexOf(target)].classList.add('active');

      setBackground(target);
    }
  });

  setBackground(tabs[0])
})();