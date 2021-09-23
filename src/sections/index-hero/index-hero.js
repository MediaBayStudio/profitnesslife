;
(function() {
  let marquee = id('marquee'),
    marqueeText = marquee.getAttribute('data-date'),
    marqueeCnt = '';

  for (let i = 70; i >= 0; i--) {
    marqueeCnt += marqueeText;
  }

  marquee.innerHTML = marqueeCnt;
  marquee.classList.add('animating');
})();