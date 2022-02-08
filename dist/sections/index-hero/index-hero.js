;
(function() {
  // return;
  let marquee = document.querySelector('.index-hero__marquee'),
    styles = getComputedStyle(marquee),
    pt = styles.paddingTop.slice(0, -2),
    pb = styles.paddingBottom.slice(0, -2),
    fz = media('(min-width:1023.98px)') ? '22' : media('(min-width:767.98px)') ? '16' : '14',
    y = media('(min-width:1023.98px)') ? '22' : media('(min-width:767.98px)') ? '18' : '17',
    svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${marquee.offsetWidth}" height="${marquee.offsetHeight - pt - pb}" viewBox="0 0 ${marquee.offsetWidth} ${marquee.offsetHeight - pt - pb}"><text font-size="${fz}px" font-family="Roboto, sans-serif" fill="#e99a8b" x="0" y="${y}">${marquee.getAttribute('data-date')}</text></svg>`;

  marquee.style.background = 'url("data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svg))) + '") center repeat-x #fff';
  marquee.style.width = '200vw';
  marquee.classList.add('animating');
})();