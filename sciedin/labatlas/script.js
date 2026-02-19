/* facilities.js
   SciEdIn — Research Facilities page JS
   Data: 20 real facilities with links (collected from institution facility pages)
*/

/* ------------------------
   Data: 20 real facilities
   fields: id, name, institute, country, facilityType, location, desc, link
   ------------------------ */
const facilities = [
  {
    id:1,
    name: "Centre for Advanced Instrumentation",
    institute: "IIT Bombay",
    country: "India",
    facilityType: "characterisation",
    location: "Mumbai, India",
    desc: "Shared instruments and characterisation facilities (SEM, XRD, spectroscopy) supporting materials & engineering research.",
    link: "https://instruments.iitb.ac.in/"
  },
  {
    id:2,
    name: "Supercomputer & Experimental Facilities (SERC)",
    institute: "IISc Bangalore",
    country: "India",
    facilityType: "supercomputer",
    location: "Bengaluru, India",
    desc: "Advanced computational & instrumentation facilities including HPC, spectroscopy and microscopy cores.",
    link: "https://serc.iisc.ac.in/"
  },
  {
    id:3,
    name: "MIT.nano — Nanoscience & Nanofabrication",
    institute: "Massachusetts Institute of Technology",
    country: "USA",
    facilityType: "cleanroom",
    location: "Cambridge, MA, USA",
    desc: "Campus-wide nanofabrication and characterization facility (shared cleanrooms, AFM, SEM, e-beam lithography).",
    link: "https://mitnano.mit.edu/"
  },
  {
    id:4,
    name: "Stanford Nano Shared Facilities",
    institute: "Stanford University",
    country: "USA",
    facilityType: "cleanroom",
    location: "Stanford, CA, USA",
    desc: "Multi-user nanofabrication, microscopy and device labs supporting interdisciplinary research.",
    link: "https://snsf.stanford.edu/"
  },
  {
    id:5,
    name: "National Graphene Institute (Characterisation)",
    institute: "University of Manchester",
    country: "UK",
    facilityType: "characterisation",
    location: "Manchester, UK",
    desc: "Characterisation and device fabrication focused on graphene & 2D materials (cleanrooms and advanced microscopes).",
    link: "https://www.graphene.manchester.ac.uk/ngi/"
  },
  {
    id:6,
    name: "Materials & Characterisation Facilities",
    institute: "Imperial College London",
    country: "UK",
    facilityType: "characterisation",
    location: "London, UK",
    desc: "Central materials facilities, instrumentation and service labs for materials synthesis and characterisation.",
    link: "https://www.imperial.ac.uk/engineering/departments/materials/facilities/"
  },
  {
    id:7,
    name: "ETH Zurich — Research Facilities & Labs",
    institute: "ETH Zurich",
    country: "Switzerland",
    facilityType: "core-lab",
    location: "Zurich, Switzerland",
    desc: "Institutional facilities and service departments supporting materials, micro- and nanotechnology work.",
    link: "https://ethz.ch/en/the-eth-zurich/organisation/departments/facility-services.html"
  },
  {
    id:8,
    name: "Core Facilities (university-wide)",
    institute: "National University of Singapore (NUS)",
    country: "Singapore",
    facilityType: "core-lab",
    location: "Singapore",
    desc: "Centralized research cores across life sciences, imaging, and physical sciences for campus users.",
    link: "https://medicine.nus.edu.sg/trp/immunology/core-facilities/"
  },
  {
    id:9,
    name: "Molecular Foundry (User Facility)",
    institute: "Lawrence Berkeley National Lab",
    country: "USA",
    facilityType: "characterisation",
    location: "Berkeley, CA, USA",
    desc: "DOE nanoscale science user facility — multi-disciplinary nanoscience instrumentation open to external users.",
    link: "https://foundry.lbl.gov/"
  },
  {
    id:10,
    name: "Kavli Nanoscience Institute / Micro-Nano Lab",
    institute: "Caltech",
    country: "USA",
    facilityType: "cleanroom",
    location: "Pasadena, CA, USA",
    desc: "Caltech's KNI and micronano cleanrooms: multi-user nanofab + characterization infrastructure.",
    link: "https://www.kni.caltech.edu/"
  },
  {
    id:11,
    name: "Core Labs (Core Research Infrastructure)",
    institute: "KAUST",
    country: "Saudi Arabia",
    facilityType: "core-lab",
    location: "Thuwal, Saudi Arabia",
    desc: "Interconnected, state-of-the-art core labs (supercomputing, imaging, prototyping and more) for researchers and partners.",
    link: "https://corelabs.kaust.edu.sa/"
  },
  {
    id:12,
    name: "Center of MicroNanoTechnology (CMi)",
    institute: "EPFL",
    country: "Switzerland",
    facilityType: "cleanroom",
    location: "Lausanne, Switzerland",
    desc: "Large cleanroom complex, micro/nanofabrication and support services for EPFL researchers and partners.",
    link: "https://www.epfl.ch/research/facilities/cmi/"
  },
  {
    id:13,
    name: "PHOTON SCIENCE Facilities (DESY)",
    institute: "DESY",
    country: "Germany",
    facilityType: "photon-source",
    location: "Hamburg & Zeuthen, Germany",
    desc: "Large-scale photon sources (PETRA III, FLASH) and beamlines serving international users for X-ray science.",
    link: "https://photon-science.desy.de/facilities/index_eng.html"
  },
  {
    id:14,
    name: "Misaki Marine Biological Station / AORI facilities",
    institute: "The University of Tokyo",
    country: "Japan",
    facilityType: "marine-station",
    location: "Japan",
    desc: "Field stations and marine biology laboratories for marine ecology, oceanography and coastal research.",
    link: "https://www.aori.u-tokyo.ac.jp/english/"
  },
  {
    id:15,
    name: "Departmental Research Facilities",
    institute: "University of Oxford (Physics & Research Facilities)",
    country: "UK",
    facilityType: "core-lab",
    location: "Oxford, UK",
    desc: "Oxford's searchable database of equipment, labs and high-end research resources across departments.",
    link: "https://www.ox.ac.uk/research/engage-with-us/external-organisations/resources-facilities/use-equipment-resources"
  },
  {
    id:16,
    name: "Cavendish Laboratory — Research Labs & Facilities",
    institute: "University of Cambridge",
    country: "UK",
    facilityType: "core-lab",
    location: "Cambridge, UK",
    desc: "Cavendish shared labs and national facilities (CORDE) with microscopy, fabrication and bespoke instrumentation.",
    link: "https://www.phy.cam.ac.uk/about/research-labs-and-facilities/"
  },
  {
    id:17,
    name: "University Core Facilities (U of T)",
    institute: "University of Toronto",
    country: "Canada",
    facilityType: "core-lab",
    location: "Toronto, Canada",
    desc: "Institutional core facilities and equipment databases across life sciences, engineering and materials.",
    link: "https://research.utoronto.ca/training-resources-facilities/institutional-core-facilities"
  },
  {
    id:18,
    name: "ANU Research Facilities (Advanced Imaging, NanoPhase etc.)",
    institute: "Australian National University",
    country: "Australia",
    facilityType: "characterisation",
    location: "Canberra, Australia",
    desc: "ANU's imaging precinct, nano-phase facility, mass spec, radiocarbon lab and other shared research infrastructure.",
    link: "https://science.anu.edu.au/research/facilities"
  },
  {
    id:19,
    name: "Research Facilities — University of Melbourne",
    institute: "University of Melbourne",
    country: "Australia",
    facilityType: "core-lab",
    location: "Melbourne, Australia",
    desc: "Research platforms, microscopy, biomedical precinct and technology platforms supporting campus research.",
    link: "https://research.unimelb.edu.au/facilities"
  },
  {
    id:20,
    name: "Research Facilities (Tsinghua University)",
    institute: "Tsinghua University",
    country: "China",
    facilityType: "core-lab",
    location: "Beijing, China",
    desc: "Large set of national and ministerial labs, research centres and core testing platforms across disciplines.",
    link: "https://www.tsinghua.edu.cn/en/info/1020/9927.htm"
  }
];

/* ------------------------
   DOM references
   ------------------------ */
const facGrid = document.getElementById('facGrid');
const searchInput = document.getElementById('searchInput');
const countryFilter = document.getElementById('countryFilter');
const instituteFilter = document.getElementById('instituteFilter');
const typeFilter = document.getElementById('typeFilter');
const clearBtn = document.getElementById('clearFilters');
const resultsCount = document.getElementById('resultsCount');
const pagination = document.getElementById('pagination');

/* ------------------------
   Helper: unique list for selects
   ------------------------ */
function populateFilters(){
  const countries = Array.from(new Set(facilities.map(f => f.country))).sort();
  const institutes = Array.from(new Set(facilities.map(f => f.institute))).sort();

  countries.forEach(c => {
    const o = document.createElement('option'); o.value = c; o.textContent = c;
    countryFilter.appendChild(o);
  });

  institutes.forEach(i => {
    const o = document.createElement('option'); o.value = i; o.textContent = i;
    instituteFilter.appendChild(o);
  });
}

/* ------------------------
   Render cards (with pagination)
   ------------------------ */
let pageSize = 9;
let currentPage = 1;
let currentList = facilities.slice();

function render(list){
  facGrid.innerHTML = "";
  resultsCount.textContent = `Showing ${list.length} facility${list.length !== 1 ? 'ies' : 'y'}`;

  // pagination
  const totalPages = Math.max(1, Math.ceil(list.length / pageSize));
  currentPage = Math.min(currentPage, totalPages);

  const start = (currentPage - 1) * pageSize;
  const slice = list.slice(start, start + pageSize);

  slice.forEach(f => {
    const card = document.createElement('div'); card.className = 'fac-card';
    card.innerHTML = `
      <div class="fac-visual">${iconForType(f.facilityType)}</div>

      <div class="fac-body" style="flex:1">
        <h3>${escapeHtml(f.name)}</h3>
        <div class="fac-meta">${escapeHtml(f.institute)} • ${escapeHtml(f.location)}</div>
        <div class="fac-tags">
          <span>${escapeHtml(f.country)}</span>
          <span>${escapeHtml(capitalizeType(f.facilityType))}</span>
        </div>

        <div class="fac-desc">${escapeHtml(f.desc)}</div>
      </div>

      <div class="fac-actions">
        <a class="btn-outline" href="${f.link}" target="_blank" rel="noopener">Visit</a>
      </div>
    `;
    facGrid.appendChild(card);
  });

  renderPager(totalPages);
}

/* safe minimal escaping */
function escapeHtml(s){
  if(!s) return '';
  return s.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;');
}

function capitalizeType(t){
  if(!t) return '';
  return t.split('-').map(x => x[0].toUpperCase() + x.slice(1)).join(' ');
}

function iconForType(t){
  switch(t){
    case 'cleanroom': return '🔬';
    case 'characterisation': return '🔭';
    case 'core-lab': return '🏛️';
    case 'supercomputer': return '🖥️';
    case 'photon-source': return '💥';
    case 'marine-station': return '🌊';
    default: return '⚙️';
  }
}

/* ------------------------
   Pager
   ------------------------ */
function renderPager(totalPages){
  pagination.innerHTML = '';
  if(totalPages <= 1) return;

  for(let p=1;p<=totalPages;p++){
    const b = document.createElement('button');
    b.className = 'page-btn' + (p===currentPage ? ' active' : '');
    b.textContent = p;
    b.onclick = () => { currentPage = p; applyFilters(); window.scrollTo({top:220, behavior:'smooth'}); };
    pagination.appendChild(b);
  }
}

/* ------------------------
   Filtering
   ------------------------ */
function applyFilters(){
  const q = (searchInput.value || '').trim().toLowerCase();
  const country = countryFilter.value;
  const institute = instituteFilter.value;
  const type = typeFilter.value;

  let filtered = facilities.filter(f => {
    const textMatch = (
      f.name.toLowerCase().includes(q) ||
      f.institute.toLowerCase().includes(q) ||
      (f.desc || '').toLowerCase().includes(q) ||
      (f.location || '').toLowerCase().includes(q)
    );

    return textMatch &&
      (country === '' || f.country === country) &&
      (institute === '' || f.institute === institute) &&
      (type === '' || f.facilityType === type);
  });

  currentList = filtered;
  currentPage = 1;
  render(filtered);
}

/* ------------------------
   Events
   ------------------------ */
searchInput.addEventListener('input', () => { applyFilters(); });
countryFilter.addEventListener('change', applyFilters);
instituteFilter.addEventListener('change', applyFilters);
typeFilter.addEventListener('change', applyFilters);
clearBtn.addEventListener('click', () => {
  searchInput.value = '';
  countryFilter.value = '';
  instituteFilter.value = '';
  typeFilter.value = '';
  currentPage = 1;
  currentList = facilities.slice();
  render(currentList);
});

/* ------------------------
   Init
   ------------------------ */
populateFilters();
render(facilities);