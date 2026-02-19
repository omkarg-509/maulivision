/* ================= DATA ================= */

const collaborations = [

{
  title:"Quantum Materials Research",
  institute:"IISc Bangalore",
  country:"India",
  type:"academic",
  field:"physics",
  desc:"Seeking PhD students and postdocs for quantum transport studies.",
  tags:["PhD","Postdoc","Materials"]
},

{
  title:"Cancer Drug Discovery",
  institute:"Tata Memorial Centre",
  country:"India",
  type:"industry",
  field:"biology",
  desc:"Collaboration for molecular screening and clinical validation.",
  tags:["Biotech","Clinical","Drug"]
},

{
  title:"Green Hydrogen Lab",
  institute:"IIT Bombay",
  country:"India",
  type:"academic",
  field:"chemistry",
  desc:"Looking for partners in sustainable energy storage research.",
  tags:["Energy","Sustainability","Hydrogen"]
},

{
  title:"Climate Modeling Platform",
  institute:"NASA",
  country:"USA",
  type:"industry",
  field:"earth",
  desc:"Joint research on climate prediction algorithms.",
  tags:["Climate","Data","Simulation"]
},

{
  title:"Nano Sensors Startup",
  institute:"NanoSense Ltd",
  country:"Germany",
  type:"startup",
  field:"engineering",
  desc:"Looking for academic partners for prototype testing.",
  tags:["Sensors","Startup","Nano"]
}

];


/* ================= ELEMENTS ================= */

const grid = document.getElementById("collabGrid");

const typeFilter = document.getElementById("typeFilter");
const fieldFilter = document.getElementById("fieldFilter");
const clearBtn = document.getElementById("clearFilters");
const quoteBox = document.getElementById("quoteBox");


/* ================= RENDER ================= */

function render(list){

  grid.innerHTML="";

  list.forEach(c=>{

    const card=document.createElement("div");

    card.className="collab-card";

    card.innerHTML=`

      <h3>${c.title}</h3>

      <div class="collab-meta">
        ${c.institute} • ${c.country} • ${c.type.toUpperCase()}
      </div>

      <p>${c.desc}</p>

      <div class="collab-tags">

        ${c.tags.map(t=>`<span>${t}</span>`).join("")}

      </div>

    `;

    grid.appendChild(card);

  });

}

render(collaborations);


/* ================= FILTER ================= */

function applyFilters(){

  const t=typeFilter.value;
  const f=fieldFilter.value;

  const filtered=collaborations.filter(c=>{

    return (

      (t==="" || c.type===t) &&
      (f==="" || c.field===f)

    );

  });

  render(filtered);

}


/* ================= EVENTS ================= */

typeFilter.addEventListener("change",applyFilters);
fieldFilter.addEventListener("change",applyFilters);

clearBtn.addEventListener("click",()=>{

  typeFilter.value="";
  fieldFilter.value="";

  render(collaborations);

});


/* ================= QUOTES ================= */

const quotes=[

  "Great discoveries are never made alone.",

  "Collaboration multiplies knowledge.",

  "Science grows through shared minds.",

  "Together, we solve the impossible.",

  "Partnership fuels innovation."

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