/* ================= QUOTES ================= */

const quotes = [

  "Every question is the seed of discovery.",

  "Curiosity is the engine of science.",

  "No question is ever too small.",

  "Great minds begin with simple doubts.",

  "Understanding starts with asking."

];

const quoteBox = document.getElementById("quoteBox");

let q = 0;

setInterval(() => {

  quoteBox.style.opacity = 0;

  setTimeout(() => {

    quoteBox.textContent = `"${quotes[q]}"`;

    quoteBox.style.opacity = 1;

    q = (q + 1) % quotes.length;

  }, 600);

}, 4000);