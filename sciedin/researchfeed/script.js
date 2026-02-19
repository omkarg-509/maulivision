/* ================= DATA ================= */

const posts = [

{
  title:"Synthesis of Silver Nanoparticles",
  author:"Ananya Sharma",
  institute:"IIT Delhi",
  type:"Experiment",
  desc:"Green synthesis method using plant extracts.",
  icon:"🧪"
},

{
  title:"Machine Learning in Protein Folding",
  author:"Rahul Mehta",
  institute:"IISc Bangalore",
  type:"Research Paper",
  desc:"Deep learning approach to structure prediction.",
  icon:"📄"
},

{
  title:"Low-Cost Water Purification System",
  author:"Neha Verma",
  institute:"NIT Trichy",
  type:"Poster",
  desc:"Affordable filtration for rural areas.",
  icon:"📊"
},

{
  title:"Photosynthesis Rate Analysis",
  author:"Kunal Singh",
  institute:"Delhi University",
  type:"Lab Video",
  desc:"Experimental study on light intensity effects.",
  icon:"🎥"
},

{
  title:"Microplastic Detection Study",
  author:"Aditi Rao",
  institute:"IIT Bombay",
  type:"Review",
  desc:"Survey of detection techniques.",
  icon:"📚"
}

];


/* ================= ELEMENT ================= */

const grid = document.getElementById("feedGrid");


/* ================= RENDER ================= */

function render(){

  grid.innerHTML="";

  posts.forEach(p=>{

    const card=document.createElement("div");

    card.className="feed-card";

    card.innerHTML=`

      <div class="feed-img">
        ${p.icon}
      </div>

      <div class="feed-content">

        <h3>${p.title}</h3>

        <div class="feed-meta">
          ${p.author} • ${p.institute} • ${p.type}
        </div>

        <p>${p.desc}</p>

        <div class="feed-actions">

          <button>👍 Appreciate</button>
          <button>💬 Comment</button>
          <button>🔗 Share</button>

        </div>

      </div>

    `;

    grid.appendChild(card);

  });

}

render();