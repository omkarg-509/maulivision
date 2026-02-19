/* ================= DATA ================= */

const jobs = [

{
  title:"Research Scientist",
  institute:"CSIR India",
  country:"India",
  type:"research",
  level:"entry",
  deadline:"2026-02-20",
  field:"physics",
  desc:"Experimental research in materials science.",
  link:"https://www.csir.res.in"
},

{
  title:"Postdoctoral Fellow",
  institute:"IISc Bangalore",
  country:"India",
  type:"academic",
  level:"mid",
  deadline:"2026-03-05",
  field:"biology",
  desc:"Molecular biology research group.",
  link:"https://iisc.ac.in"
},

{
  title:"R&D Scientist",
  institute:"Bharat Biotech",
  country:"India",
  type:"industry",
  level:"mid",
  deadline:"2026-02-15",
  field:"chemistry",
  desc:"Vaccine research division.",
  link:"https://www.bharatbiotech.com"
},


/* USA */

{
  title:"Research Engineer",
  institute:"NASA",
  country:"USA",
  type:"research",
  level:"mid",
  deadline:"2026-02-28",
  field:"earth",
  desc:"Climate modeling division.",
  link:"https://www.nasa.gov"
},

{
  title:"Senior Scientist",
  institute:"Pfizer R&D",
  country:"USA",
  type:"industry",
  level:"senior",
  deadline:"2026-03-01",
  field:"biology",
  desc:"Drug discovery research.",
  link:"https://www.pfizer.com"
},

{
  title:"Assistant Professor",
  institute:"MIT",
  country:"USA",
  type:"academic",
  level:"senior",
  deadline:"2026-02-10",
  field:"physics",
  desc:"Teaching and research in applied physics.",
  link:"https://web.mit.edu"
},


/* EUROPE */

{
  title:"Research Fellow",
  institute:"CERN",
  country:"Switzerland",
  type:"research",
  level:"mid",
  deadline:"2026-01-30",
  field:"physics",
  desc:"High energy physics experiments.",
  link:"https://home.cern"
},

{
  title:"Lab Scientist",
  institute:"Max Planck",
  country:"Germany",
  type:"research",
  level:"entry",
  deadline:"2026-02-18",
  field:"chemistry",
  desc:"Surface chemistry laboratory.",
  link:"https://www.mpg.de"
},

{
  title:"Lecturer (Sciences)",
  institute:"Oxford University",
  country:"UK",
  type:"academic",
  level:"mid",
  deadline:"2026-02-12",
  field:"math",
  desc:"Applied mathematics department.",
  link:"https://www.ox.ac.uk"
},


/* ASIA */

{
  title:"Research Associate",
  institute:"RIKEN",
  country:"Japan",
  type:"research",
  level:"mid",
  deadline:"2026-02-25",
  field:"biology",
  desc:"Neuroscience research lab.",
  link:"https://www.riken.jp"
},

{
  title:"Scientist",
  institute:"KAIST",
  country:"Korea",
  type:"academic",
  level:"mid",
  deadline:"2026-03-05",
  field:"engineering",
  desc:"Nano materials research.",
  link:"https://www.kaist.ac.kr"
},

{
  title:"Environmental Scientist",
  institute:"CSIRO",
  country:"Australia",
  type:"research",
  level:"entry",
  deadline:"2026-02-22",
  field:"earth",
  desc:"Climate and ecosystem modeling.",
  link:"https://www.csiro.au"
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

const grid = document.getElementById("jobsGrid");
const countryFilter = document.getElementById("countryFilter");
const typeFilter = document.getElementById("typeFilter");
const levelFilter = document.getElementById("levelFilter");
const clearBtn = document.getElementById("clearFilters");
const quoteBox = document.getElementById("quoteBox");


/* ================= INIT ================= */

function initFilters(){

  const countries=[...new Set(jobs.map(j=>j.country))];

  countries.forEach(c=>{

    const o=document.createElement("option");
    o.value=c;
    o.textContent=c;

    countryFilter.appendChild(o);

  });

}

initFilters();


/* ================= RENDER ================= */

function render(list){

  grid.innerHTML="";

  list.forEach(j=>{

    const d=new Date(j.deadline);
    const t=new Date();

    const days=Math.ceil((d-t)/(1000*60*60*24));

    let status="";

    if(days<0) status="Closed";
    else if(days<=7) status=`⚠️ ${days} days left`;
    else status=`${days} days left`;


    const card=document.createElement("div");
    card.className="job-card";

    card.innerHTML=`

      <div class="job-visual">
        ${icons[j.field] || "🔬"}
      </div>

      <div class="job-content">

        <h3>${j.title}</h3>

        <div class="job-meta">

          ${j.institute} • ${j.country}<br>

          Role: ${j.type.toUpperCase()} • Level: ${j.level}<br>

          <span class="deadline">
            Last Date: ${j.deadline} (${status})
          </span>

        </div>

        <p>${j.desc}</p>

        <a href="${j.link}" target="_blank" class="apply-btn">
          Apply Now
        </a>

      </div>

    `;

    grid.appendChild(card);

  });

}

render(jobs);


/* ================= FILTER ================= */

function applyFilters(){

  const c=countryFilter.value;
  const t=typeFilter.value;
  const l=levelFilter.value;

  const filtered=jobs.filter(j=>{

    return (
      (c==="" || j.country===c) &&
      (t==="" || j.type===t) &&
      (l==="" || j.level===l)
    );

  });

  render(filtered);

}


/* ================= EVENTS ================= */

countryFilter.addEventListener("change",applyFilters);
typeFilter.addEventListener("change",applyFilters);
levelFilter.addEventListener("change",applyFilters);

clearBtn.addEventListener("click",()=>{

  countryFilter.value="";
  typeFilter.value="";
  levelFilter.value="";

  render(jobs);

});


/* ================= QUOTES ================= */

const quotes=[

  "Science careers change the world.",

  "Every lab needs curious minds.",

  "Research today shapes tomorrow.",

  "Innovation starts with scientists.",

  "Your knowledge can save lives."

];

let q=0;

setInterval(()=>{

  quoteBox.style.opacity=0;

  setTimeout(()=>{

    quoteBox.textContent=`"${quotes[q]}"`;

    quoteBox.style.opacity=1;

    q=(q+1)%quotes.length;

  },600);

},4000);