/* ================= VIDEO DATA ================= */

const videos = [
  {
    id: "4uFsqTUEgd4",
    title: "Explain Gene Regulation in Eukaryotes",
    description: "A detailed conceptual explanation of gene regulation mechanisms in eukaryotic cells."
  },
  {
    id: "CqQoQ9ktzA8",
    title: "Describe Oxidative Phosphorylation",
    description: "Deep dive into the electron transport chain and ATP synthesis in mitochondria."
  },
];

/* ================= ELEMENTS ================= */

const player = document.getElementById("youtubePlayer");
const title = document.getElementById("videoTitle");
const description = document.getElementById("videoDescription");

const prevBtn = document.getElementById("prevVideo");
const nextBtn = document.getElementById("nextVideo");

let currentIndex = 0;

/* ================= LOAD VIDEO ================= */

function loadVideo(index){
  const video = videos[index];
  player.src = `https://www.youtube.com/embed/${video.id}`;
  title.textContent = video.title;
  description.textContent = video.description;
}

loadVideo(currentIndex);

/* ================= NAVIGATION ================= */

nextBtn.addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % videos.length;
  loadVideo(currentIndex);
});

prevBtn.addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + videos.length) % videos.length;
  loadVideo(currentIndex);
});
