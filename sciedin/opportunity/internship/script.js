/* ================= DATA ================= */

const internships = [

  /* INDIA */

  {
    title: "IIT Gandhinagar SRIP",
    institute: "IIT Gandhinagar",
    country: "India",
    stipend: "paid",
    stipendText: "₹10,000 / month",
    deadline: "2026-03-15",
    category: "physics",
    description: "Faculty-guided summer research in science disciplines.",
    link: "https://www.iitgn.ac.in"
  },

  {
    title: "TIFR Summer Programme",
    institute: "TIFR Mumbai",
    country: "India",
    stipend: "paid",
    stipendText: "₹12,000 / month",
    deadline: "2026-02-28",
    category: "physics",
    description: "Advanced research in theoretical and experimental science.",
    link: "https://www.tifr.res.in"
  },

  {
    title: "Science Academies SRFP",
    institute: "IAS / INSA / NASI",
    country: "India",
    stipend: "paid",
    stipendText: "₹10,000 / month",
    deadline: "2026-01-31",
    category: "math",
    description: "National fellowship for science students.",
    link: "https://webjapps.ias.ac.in"
  },


  /* USA */

  {
    title: "MIT Summer Research",
    institute: "MIT",
    country: "USA",
    stipend: "paid",
    stipendText: "$7,000 + housing",
    deadline: "2026-02-15",
    category: "physics",
    description: "Cutting-edge research in materials and physics.",
    link: "https://oge.mit.edu"
  },

  {
    title: "NIH SIP",
    institute: "NIH",
    country: "USA",
    stipend: "paid",
    stipendText: "$2,300 / month",
    deadline: "2026-02-10",
    category: "biology",
    description: "Biomedical research in NIH laboratories.",
    link: "https://www.training.nih.gov"
  },

  {
    title: "NASA OSTEM",
    institute: "NASA",
    country: "USA",
    stipend: "paid",
    stipendText: "$3,000 / month",
    deadline: "2026-03-05",
    category: "earth",
    description: "Space science and earth system research.",
    link: "https://stem.nasa.gov"
  },


  /* EUROPE */

  {
    title: "DAAD WISE",
    institute: "German Universities",
    country: "Germany",
    stipend: "paid",
    stipendText: "€934 / month",
    deadline: "2026-03-01",
    category: "chemistry",
    description: "Research in chemistry and materials science.",
    link: "https://www.daad.de/wise"
  },

  {
    title: "Max Planck Internship",
    institute: "Max Planck Society",
    country: "Germany",
    stipend: "paid",
    stipendText: "€1000 / month",
    deadline: "2026-02-20",
    category: "physics",
    description: "Fundamental physics and chemistry research.",
    link: "https://www.mpg.de"
  },

  {
    title: "CERN Summer Student",
    institute: "CERN",
    country: "Switzerland",
    stipend: "paid",
    stipendText: "CHF 90/day",
    deadline: "2026-01-25",
    category: "physics",
    description: "Particle physics research at CERN.",
    link: "https://home.cern"
  },


  /* CANADA */

  {
    title: "Mitacs Globalink",
    institute: "Canada Universities",
    country: "Canada",
    stipend: "paid",
    stipendText: "$12,000 total",
    deadline: "2026-01-20",
    category: "math",
    description: "International research placement.",
    link: "https://www.mitacs.ca"
  },


  /* ASIA */

  {
    title: "RIKEN Summer Program",
    institute: "RIKEN",
    country: "Japan",
    stipend: "paid",
    stipendText: "¥1,500/day",
    deadline: "2026-02-12",
    category: "biology",
    description: "Life science and neuroscience research.",
    link: "https://www.riken.jp"
  },

  {
    title: "KAIST Research Internship",
    institute: "KAIST",
    country: "South Korea",
    stipend: "paid",
    stipendText: "$1,200 / month",
    deadline: "2026-03-10",
    category: "chemistry",
    description: "Advanced materials and nano research.",
    link: "https://www.kaist.ac.kr"
  },


  /* AUSTRALIA */

  {
    title: "CSIRO Vacation Scholarship",
    institute: "CSIRO",
    country: "Australia",
    stipend: "paid",
    stipendText: "$36/day",
    deadline: "2026-02-18",
    category: "earth",
    description: "Climate and environmental research.",
    link: "https://www.csiro.au"
  }

];


/* ================= ELEMENTS ================= */

const grid = document.getElementById("internshipGrid");

const countryFilter = document.getElementById("countryFilter");
const stipendFilter = document.getElementById("stipendFilter");
const clearBtn = document.getElementById("clearFilters");


/* ================= ICON MAP ================= */

const icons = {
  physics: "⚛️",
  biology: "🧬",
  chemistry: "⚗️",
  math: "📐",
  earth: "🌍"
};


/* ================= INIT FILTERS ================= */

function initFilters() {

  const countries = [...new Set(internships.map(i => i.country))];

  countries.forEach(c => {
    const opt = document.createElement("option");
    opt.value = c;
    opt.textContent = c;
    countryFilter.appendChild(opt);
  });

}

initFilters();


/* ================= RENDER ================= */

function render(list) {

  grid.innerHTML = "";

  list.forEach(item => {

    const deadline = new Date(item.deadline);
    const today = new Date();

    const daysLeft = Math.ceil(
      (deadline - today) / (1000 * 60 * 60 * 24)
    );

    let status = "";

    if (daysLeft < 0) status = "Closed";
    else if (daysLeft <= 7) status = `⚠️ ${daysLeft} days left`;
    else status = `${daysLeft} days left`;

    const icon = icons[item.category] || "🔬";


    const card = document.createElement("div");
    card.className = "intern-card";

    card.innerHTML = `

      <div class="intern-visual visual-${item.category}">
        ${icon}
      </div>

      <div class="intern-content">

        <h3>${item.title}</h3>

        <div class="intern-meta">

          ${item.institute} • ${item.country}<br>

          Stipend: ${item.stipendText}<br>

          <span class="deadline">
            Last Date: ${item.deadline} (${status})
          </span>

        </div>

        <p>${item.description}</p>

        <a href="${item.link}" target="_blank" class="apply-btn">
          Apply Now
        </a>

      </div>

    `;

    grid.appendChild(card);

  });

}

render(internships);


/* ================= FILTER ================= */

function applyFilters() {

  const country = countryFilter.value;
  const stipend = stipendFilter.value;

  const filtered = internships.filter(i => {

    return (

      (country === "" || i.country === country) &&
      (stipend === "" || i.stipend === stipend)

    );

  });

  render(filtered);

}


/* ================= EVENTS ================= */

countryFilter.addEventListener("change", applyFilters);
stipendFilter.addEventListener("change", applyFilters);

clearBtn.addEventListener("click", () => {

  countryFilter.value = "";
  stipendFilter.value = "";

  render(internships);

});


/* ================= QUOTES ROTATOR ================= */

const quotes = [

  "Internships turn curiosity into capability.",

  "Every great scientist was once an intern who asked questions.",

  "Research internships are where theory meets discovery.",

  "Your first experiment can shape your entire career.",

  "Learning in the lab today builds innovation for tomorrow."

];

const quoteBox = document.getElementById("quoteBox");

let quoteIndex = 0;

function rotateQuote() {

  quoteBox.style.opacity = 0;

  setTimeout(() => {

    quoteBox.textContent = `"${quotes[quoteIndex]}"`;

    quoteBox.style.opacity = 1;

    quoteIndex = (quoteIndex + 1) % quotes.length;

  }, 600);

}

setInterval(rotateQuote, 4000);