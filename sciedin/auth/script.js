document.addEventListener("DOMContentLoaded", () => {

  const track = document.getElementById("weeklyTrack");
  const nextBtn = document.getElementById("nextBtn");
  const prevBtn = document.getElementById("prevBtn");

  if (!track || !nextBtn || !prevBtn) {
    console.warn("Weekly slider elements not found");
    return;
  }

  const gap = 22;
  let position = 0;
  let cardWidth = 0;

  /* Calculate card width safely */
  function calculateCardWidth() {
    const card = track.querySelector(".weekly-card");
    if (!card) return;
    cardWidth = card.offsetWidth + gap;
  }

  calculateCardWidth();
  window.addEventListener("resize", calculateCardWidth);

  function getMaxScroll() {
    return Math.max(
      0,
      track.scrollWidth - track.parentElement.offsetWidth
    );
  }

  function updatePosition() {
    track.style.transform = `translateX(-${position}px)`;
  }

  /* NEXT */
  nextBtn.addEventListener("click", () => {
    position += cardWidth;

    if (position > getMaxScroll()) {
      position = getMaxScroll();
    }

    updatePosition();
  });

  /* PREV */
  prevBtn.addEventListener("click", () => {
    position -= cardWidth;

    if (position < 0) {
      position = 0;
    }

    updatePosition();
  });

  /* AUTO SLIDE */
  setInterval(() => {
    position += cardWidth;

    if (position > getMaxScroll()) {
      position = 0;
    }

    updatePosition();
  }, 4500);

});