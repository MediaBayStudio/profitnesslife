document.addEventListener('DOMContentLoaded', function() {

//=include ../sections/header/header.js

//=include ../sections/mobile-menu/mobile-menu.js

//=include ../sections/training-program-hero/training-program-hero.js

;(function() {
  let workoutBlocks = qa('.workout-block'),
    slidesSelector = '.workout';

  workoutBlocks.forEach(function(workoutBlock) {
    let $workoutBlock = $(workoutBlock);

    $workoutBlock.slick({
      variableWidth: true,
      infinite: false,
      arrows: false,
      slide: slidesSelector,
      mobileFirst: true,
      responsive: [{
        breakpoint: 575.98,
        settings: {
          slidesToShow: 2
        }
      }, {
        breakpoint: 767.98,
        settings: 'unslick'
      }]
    });
  });

})();

(function () {
  workoutPopup = new Popup(".workout-popup", {
    openButtons: ".workout",
    closeButtons: ".workout-popup__close",
  });
  // workoutPopup.openPopup();

  let playlist = q(".workout-popup__playlist", workoutPopup),
    video = q(".workout-popup__video", workoutPopup),
    number = q(".workout-popup__number", workoutPopup),
    name = q(".workout-popup__name", workoutPopup),
    descr = q(".workout-popup__descr", workoutPopup),
    popupTitle = q(".workout-popup__title", workoutPopup),
    playlistItemClass = "workout-popup__playlist-item";

  workoutPopup.addEventListener("popupbeforeclose", function () {
    video.pause();
    video.currentTime = 0;
  });

  workoutPopup.addEventListener("popupbeforeopen", function () {
    let target = workoutPopup.caller,
      workoutNumber = target.getAttribute("data-title"),
      workout = target.closest(".workout") || target,
      workoutTitle = q(".workout__title", workout),
      data = JSON.parse(workout.getAttribute("data-videos")),
      videosHTML = "";

    video.src = data[0].src;
    name.textContent = data[0].title;

    if (workoutNumber) {
      number.textContent = "Упражнение " + workoutNumber;
    } else {
      number.textContent = "";
    }

    popupTitle.textContent = workoutTitle.textContent;

    data.forEach(function (video, i) {
      videosHTML += `<div class="workout-popup__playlist-item${
        i === 0 ? " active" : ""
      }" data-title="${video.title}" data-src="${video.src}" data-index="${
        i + 1
      }" data-reps-number="${video.reps.number}" data-reps-units="${
        video.reps.units
      }">
        <span class="workout-popup__playlist-item-play-icon"></span>
        <span class="workout-popup__playlist-item-number">Упражнение ${
          i + 1
        }</span>
        <span class="workout-popup__playlist-item-duration">${
          video.duration
        }</span>
      </div>`;
    });

    playlist.innerHTML = videosHTML;
    descr.textContent = "";
    video.load();
  });

  // video.addEventListener("click", function () {
  //   if (video.paused) {
  //     video.setAttribute("controls", "");
  //     setTimeout(function () {
  //       video.play();
  //     });
  //   } else {
  //     video.pause();
  //     video.removeAttribute("controls");
  //   }
  // });

  video.addEventListener("ended", function () {
    let currentVideoInPlaylist = q('[data-src="' + video.src + '"]', playlist),
      activePlaylistElement = q(".active", playlist),
      nextVideoInPlaylist = currentVideoInPlaylist.nextElementSibling;

    if (nextVideoInPlaylist) {
      let repsNumber = nextVideoInPlaylist.getAttribute("data-reps-number"),
        repsUnits = nextVideoInPlaylist.getAttribute("data-reps-units");

      video.src = nextVideoInPlaylist.getAttribute("data-src");
      activePlaylistElement.classList.remove("active");
      nextVideoInPlaylist.classList.add("active");

      number.textContent =
        "Упражнение " + nextVideoInPlaylist.getAttribute("data-index");
      name.textContent = nextVideoInPlaylist.getAttribute("data-title");

      if (repsNumber) {
        descr.textContent = "4 подхода по " + repsNumber + " " + repsUnits;
      } else {
        descr.textContent = "";
      }
      video.load();
      video.play();
      // console.log(nextVideoInPlaylist);
    }
  });

  playlist.addEventListener("click", function (e) {
    let target = e.target;

    if (target.classList.contains(playlistItemClass)) {
      video.pause();
      video.currentTime = 0;

      let activeItem = q(".active", workoutPopup),
        repsNumber = target.getAttribute("data-reps-number"),
        repsUnits = target.getAttribute("data-reps-units");

      activeItem.classList.remove("active");
      target.classList.add("active");
      number.textContent = "Упражнение " + target.getAttribute("data-index");
      name.textContent = target.getAttribute("data-title");
      video.src = target.getAttribute("data-src");

      video.load();
      // video.play();

      if (repsNumber) {
        descr.textContent = "4 подхода по " + repsNumber + " " + repsUnits;
      } else {
        descr.textContent = "";
      }
    }
  });
})();


;(function() {
  workoutRulesPopup = new Popup('.workout-rules-popup', {
    openButtons: '.workout-rules-popup-open',
    closeButtons: '.workout-rules-popup__close'
  });
})();

;(function() {
  inventoryPopup = new Popup('.inventory-popup', {
    openButtons: '.inventory-popup-open',
    closeButtons: '.inventory-popup__close'
  });
})();

//=include ../sections/footer/footer.js

});