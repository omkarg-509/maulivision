/* ================= DATA ================= */

const scholarships = [

/* INDIA */

{
  title:"INSPIRE Scholarship",
  institute:"DST India",
  country:"India",
  fund:"paid",
  fundText:"₹80,000 / year",
  deadline:"2026-02-15",
  field:"physics",
  level:"ug",
  desc:"Scholarship for science undergraduates.",
  link:"https://online-inspire.gov.in"
},

{
  title:"KVPY Fellowship",
  institute:"IISc",
  country:"India",
  fund:"paid",
  fundText:"₹7,000 / month",
  deadline:"2026-01-25",
  field:"math",
  level:"ug",
  desc:"Merit-based science fellowship.",
  link:"https://kvpy.iisc.ac.in"
},

{
  title:"UGC NET JRF",
  institute:"UGC",
  country:"India",
  fund:"paid",
  fundText:"₹31,000 / month",
  deadline:"2026-03-01",
  field:"chemistry",
  level:"phd",
  desc:"Junior Research Fellowship.",
  link:"https://ugcnet.nta.nic.in"
},

{
  title:"IIT Merit Scholarship",
  institute:"IIT System",
  country:"India",
  fund:"partial",
  fundText:"₹40,000 / year",
  deadline:"2026-02-05",
  field:"physics",
  level:"ug",
  desc:"Partial tuition support for science students.",
  link:"https://www.iit.ac.in"
},


/* USA */

{
  title:"Fulbright STEM",
  institute:"Fulbright",
  country:"USA",
  fund:"paid",
  fundText:"$35,000 / year",
  deadline:"2026-02-10",
  field:"biology",
  level:"pg",
  desc:"Graduate research fellowship.",
  link:"https://foreign.fulbrightonline.org"
},

{
  title:"NSF GRFP",
  institute:"NSF",
  country:"USA",
  fund:"paid",
  fundText:"$34,000 / year",
  deadline:"2026-01-20",
  field:"physics",
  level:"phd",
  desc:"PhD research funding.",
  link:"https://nsfgrfp.org"
},

{
  title:"Harvard Science Grant",
  institute:"Harvard University",
  country:"USA",
  fund:"partial",
  fundText:"$15,000 / year",
  deadline:"2026-02-18",
  field:"biology",
  level:"pg",
  desc:"Partial research funding for STEM students.",
  link:"https://college.harvard.edu"
},


/* UK */

{
  title:"Commonwealth Scholarship",
  institute:"UK Govt",
  country:"UK",
  fund:"paid",
  fundText:"Full Funding",
  deadline:"2026-02-05",
  field:"earth",
  level:"pg",
  desc:"Science postgraduate scholarship.",
  link:"https://cscuk.fcdo.gov.uk"
},

{
  title:"Gates Cambridge",
  institute:"Cambridge",
  country:"UK",
  fund:"paid",
  fundText:"Full Funding",
  deadline:"2026-01-10",
  field:"math",
  level:"phd",
  desc:"Masters & PhD funding.",
  link:"https://www.gatescambridge.org"
},

{
  title:"Oxford Science Bursary",
  institute:"Oxford University",
  country:"UK",
  fund:"partial",
  fundText:"£12,000 / year",
  deadline:"2026-02-12",
  field:"chemistry",
  level:"pg",
  desc:"Partial financial support for science students.",
  link:"https://www.ox.ac.uk"
},


/* EUROPE */

{
  title:"Erasmus Mundus",
  institute:"EU",
  country:"Europe",
  fund:"paid",
  fundText:"€24,000 / year",
  deadline:"2026-02-28",
  field:"chemistry",
  level:"pg",
  desc:"Joint science degrees.",
  link:"https://erasmus-plus.ec.europa.eu"
},

{
  title:"DAAD Masters",
  institute:"DAAD",
  country:"Germany",
  fund:"paid",
  fundText:"€850 / month",
  deadline:"2026-03-05",
  field:"physics",
  level:"pg",
  desc:"German science scholarship.",
  link:"https://www.daad.de"
},

{
  title:"TU Munich Excellence Award",
  institute:"TUM",
  country:"Germany",
  fund:"partial",
  fundText:"€5,000 / year",
  deadline:"2026-01-30",
  field:"engineering",
  level:"pg",
  desc:"Partial funding for science & engineering.",
  link:"https://www.tum.de"
},


/* CANADA */

{
  title:"Vanier CGS",
  institute:"Canada Govt",
  country:"Canada",
  fund:"paid",
  fundText:"$50,000 / year",
  deadline:"2026-01-18",
  field:"biology",
  level:"phd",
  desc:"Doctoral fellowship.",
  link:"https://vanier.gc.ca"
},

{
  title:"Ontario Trillium",
  institute:"Ontario",
  country:"Canada",
  fund:"paid",
  fundText:"$40,000 / year",
  deadline:"2026-02-12",
  field:"earth",
  level:"phd",
  desc:"Science PhD funding.",
  link:"https://www.sgs.utoronto.ca"
},

{
  title:"UBC Science Award",
  institute:"UBC",
  country:"Canada",
  fund:"partial",
  fundText:"$10,000 / year",
  deadline:"2026-02-22",
  field:"physics",
  level:"pg",
  desc:"Partial scholarship for science majors.",
  link:"https://www.ubc.ca"
},


/* ASIA */

{
  title:"MEXT Scholarship",
  institute:"Japan Govt",
  country:"Japan",
  fund:"paid",
  fundText:"¥140,000 / month",
  deadline:"2026-02-20",
  field:"chemistry",
  level:"pg",
  desc:"Masters & PhD funding.",
  link:"https://www.mext.go.jp"
},

{
  title:"KGSP",
  institute:"Korean Govt",
  country:"Korea",
  fund:"paid",
  fundText:"₩900,000 / month",
  deadline:"2026-03-01",
  field:"physics",
  level:"pg",
  desc:"Graduate science funding.",
  link:"https://studyinkorea.go.kr"
},

{
  title:"NTU Research Grant",
  institute:"NTU Singapore",
  country:"Singapore",
  fund:"partial",
  fundText:"SGD 8,000 / year",
  deadline:"2026-02-14",
  field:"biology",
  level:"pg",
  desc:"Partial funding for life sciences.",
  link:"https://www.ntu.edu.sg"
},


/* AUSTRALIA */

{
  title:"RTP Scholarship",
  institute:"Australia Govt",
  country:"Australia",
  fund:"paid",
  fundText:"$32,000 / year",
  deadline:"2026-02-15",
  field:"biology",
  level:"phd",
  desc:"Research training funding.",
  link:"https://www.education.gov.au"
},

{
  title:"Monash Science Grant",
  institute:"Monash University",
  country:"Australia",
  fund:"partial",
  fundText:"$9,000 / year",
  deadline:"2026-02-28",
  field:"chemistry",
  level:"pg",
  desc:"Partial science scholarship.",
  link:"https://www.monash.edu"
}

];


/* ================= ICONS ================= */

const icons = {
  physics: "⚛️",
  biology: "🧬",
  chemistry: "⚗️",
  math: "📐",
  earth: "🌍",
  engineering: "⚙️"
};


/* ================= ELEMENTS ================= */

const grid = document.getElementById("scholarGrid");
const countryFilter = document.getElementById("countryFilter");
const fundFilter = document.getElementById("fundFilter");
const levelFilter = document.getElementById("levelFilter");
const clearBtn = document.getElementById("clearFilters");
const quoteBox = document.getElementById("quoteBox");


/* ================= HELPERS ================= */

function formatLevel(level) {

  if (level === "ug") return "Undergraduate";
  if (level === "pg") return "Postgraduate";
  if (level === "phd") return "PhD";

  return "";
}


/* ================= INIT ================= */

function initFilters() {

  const countries = [...new Set(scholarships.map(s => s.country))];

  countries.forEach(c => {

    const o = document.createElement("option");
    o.value = c;
    o.textContent = c;

    countryFilter.appendChild(o);

  });

}

initFilters();


/* ================= RENDER ================= */

function render(list) {

  grid.innerHTML = "";

  list.forEach(s => {

    const d = new Date(s.deadline);
    const t = new Date();

    const days = Math.ceil((d - t) / (1000 * 60 * 60 * 24));

    let status = "";

    if (days < 0) status = "Closed";
    else if (days <= 7) status = `⚠️ ${days} days left`;
    else status = `${days} days left`;


    const card = document.createElement("div");
    card.className = "scholar-card";

    card.innerHTML = `

      <div class="scholar-visual">
        ${icons[s.field] || "🔬"}
      </div>

      <div class="scholar-content">

        <h3>${s.title}</h3>

        <div class="scholar-meta">

          ${s.institute} • ${s.country}<br>

          Level: ${formatLevel(s.level)}<br>

          Funding: ${s.fundText}<br>

          <span class="deadline">
            Last Date: ${s.deadline} (${status})
          </span>

        </div>

        <p>${s.desc}</p>

        <a href="${s.link}" target="_blank" class="apply-btn">
          Apply Now
        </a>

      </div>

    `;

    grid.appendChild(card);

  });

}

render(scholarships);


/* ================= FILTER ================= */

function applyFilters() {

  const c = countryFilter.value;
  const f = fundFilter.value;
  const l = levelFilter.value;

  const filtered = scholarships.filter(s => {

    return (

      (c === "" || s.country === c) &&
      (f === "" || s.fund === f) &&
      (l === "" || s.level === l)

    );

  });

  render(filtered);

}


/* ================= EVENTS ================= */

countryFilter.addEventListener("change", applyFilters);
fundFilter.addEventListener("change", applyFilters);
levelFilter.addEventListener("change", applyFilters);

clearBtn.addEventListener("click", () => {

  countryFilter.value = "";
  fundFilter.value = "";
  levelFilter.value = "";

  render(scholarships);

});


/* ================= QUOTES ================= */

const quotes = [

  "Scholarships empower minds to innovate.",

  "Education is the strongest investment.",

  "Funding today builds discoveries tomorrow.",

  "Science grows when talent is supported.",

  "Your research journey begins here."

];

let q = 0;

setInterval(() => {

  quoteBox.style.opacity = 0;

  setTimeout(() => {

    quoteBox.textContent = `"${quotes[q]}"`;

    quoteBox.style.opacity = 1;

    q = (q + 1) % quotes.length;

  }, 600);

}, 4000);