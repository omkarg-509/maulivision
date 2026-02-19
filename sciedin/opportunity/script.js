const quotes = [
  "Science is a way of thinking much more than it is a body of knowledge. — Carl Sagan",
  "Research is what I'm doing when I don't know what I'm doing. — Wernher von Braun",
  "The important thing is not to stop questioning. — Albert Einstein",
  "Science knows no country, because knowledge belongs to humanity. — Louis Pasteur",
  "Somewhere, something incredible is waiting to be known. — Carl Sagan"
];

const quoteText = document.getElementById("quoteText");
let quoteIndex = 0;

/* Rotate Quotes */
setInterval(() => {
  quoteText.textContent = quotes[quoteIndex];
  quoteIndex = (quoteIndex + 1) % quotes.length;
}, 4000);

/* Initial quote */
quoteText.textContent = quotes[0];

/* Handle Card Clicks */
const cards = document.querySelectorAll(".op-card");
const resultTitle = document.getElementById("resultTitle");
const resultsBox = document.getElementById("resultsBox");

cards.forEach(card => {
  card.addEventListener("click", () => {
    const type = card.dataset.type;

    resultTitle.textContent = `Showing ${card.querySelector("h3").textContent}`;
    resultsBox.innerHTML = `
      <p>
        Opportunities related to <strong>${type}</strong> will appear here.
        <br><br>
        This section will soon be powered by real-time data from universities,
        labs, funding agencies, and research groups.
      </p>
    `;
  });
});