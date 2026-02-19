/* ================= DATA ================= */

/* Replace this with full Excel JSON later */

const professors = [
    {
        "name": "Aravindan, Vanchiappan",
        "institute": "Indian Institute of Science Education and Research (IISER), Tirupati",
        "country": "ind",
        "field": "Enabling & Strategic Technologies",
        "sub1": "Nanoscience & Nanotechnology",
        "sub2": "Energy",
        "rank ": 37089,
        "hindex": 18,
        "papers": 274,
        "citations": 831,
        "homepage": "https://aravindvan2.wixsite.com/aravindlab/contact-me"
},
{
        "name": "Raghutla, Chandrashekar",
        "institute": "Indian Institute of Science Education and Research (IISER), Tirupati",
        "country": "ind",
        "field": "Enabling & Strategic Technologies",
        "sub1": "Energy",
        "sub2": "Environmental Sciences",
        "rank ": 72939,
        "hindex": 11,
        "papers": 23,
        "citations": 145,
        "homepage": "https://sites.google.com/view/chandrashekarraghutla/home"
},
{
        "name": "Balaraman, Ekambaram",
        "institute": "Indian Institute of Science Education and Research (IISER), Tirupati",
        "country": "ind",
        "field": "Chemistry",
        "sub1": "Organic Chemistry",
        "sub2": "General Chemistry",
        "rank ": 87581,
        "hindex": 16,
        "papers": 109,
        "citations": 542,
        "homepage": "https://www.iisertirupati.ac.in/faculty-details/?faculty_id=Che-2&id=2376"
},
{
        "name": "Banerjee, Shibdas",
        "institute": "Indian Institute of Science Education and Research (IISER), Tirupati",
        "country": "ind",
        "field": "Chemistry",
        "sub1": "Organic Chemistry",
        "sub2": "Analytical Chemistry",
        "rank ": 103823,
        "hindex": 9,
        "papers": 48,
        "citations": 186,
        "homepage": "https://www.sblab.info/"
},
{
        "name": "Kumar, Jatish Aneesh",
        "institute": "Indian Institute of Science Education and Research (IISER), Tirupati",
        "country": "ind",
        "field": "Chemistry",
        "sub1": "Organic Chemistry",
        "sub2": "Nanoscience & Nanotechnology",
        "rank ": 202635,
        "hindex": 10,
        "papers": 59,
        "citations": 234,
        "homepage": "https://sites.google.com/view/jatish-group/jatish-kumar"
},
{
    "name":"Wang, Zhong Lin",
    "institute":"Chinese Academy of Sciences",
    "country":"chn",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Materials",
    "rank":1,
    "hindex":73,
    "papers":2836,
    "citations":31659,
    "homepage":"Zhong Lin Wang's group website"
},
{
    "name":"Kresse, Georg",
    "institute":"Universit\u00e4t Wien",
    "country":"aut",
    "field":"Physics & Astronomy",
    "sub1":"Applied Physics",
    "sub2":"Chemical Physics",
    "rank":2,
    "hindex":30,
    "papers":399,
    "citations":33449,
    "homepage":"Computational Materials Physics"
  },
  {
    "name":"Grimme, Stefan",
    "institute":"Universit\u00e4t Bonn",
    "country":"deu",
    "field":"Chemistry",
    "sub1":"Chemical Physics",
    "sub2":"Organic Chemistry",
    "rank":5,
    "hindex":36,
    "papers":728,
    "citations":20500,
    "homepage":"https:\/\/www.chemie.uni-bonn.de\/grimme\/de"
  },
  {
    "name":"Perdew, John",
    "institute":"Tulane University School of Science and Engineering",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"Applied Physics",
    "sub2":"Chemical Physics",
    "rank":9,
    "hindex":35,
    "papers":361,
    "citations":27272,
    "homepage":"John P. Perdew, Ph.D. | Tulane University School of Science and Engineering"
  },
  {
    "name":"Tibshirani, Robert John",
    "institute":"Stanford University",
    "country":"usa",
    "field":"Mathematics & Statistics",
    "sub1":"Statistics & Probability",
    "sub2":"Oncology & Carcinogenesis",
    "rank":17,
    "hindex":44,
    "papers":421,
    "citations":10381,
    "homepage":"https:\/\/statistics.stanford.edu\/people\/robert-tibshirani"
  },
  {
    "name":"Li, Heng",
    "institute":"Soochow University",
    "country":"chn",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Bioinformatics",
    "sub2":"Developmental Biology",
    "rank":18,
    "hindex":39,
    "papers":155,
    "citations":14068,
    "homepage":"https:\/\/www.researchgate.net\/scientific-contributions\/Heng-Li-2129162409"
  },
  {
    "name":"Gogotsi, Yury G.",
    "institute":"Drexel University",
    "country":"usa",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Materials",
    "rank":21,
    "hindex":88,
    "papers":1112,
    "citations":17958,
    "homepage":"A.J. Drexel Nanomaterials Institute"
  },
  {
    "name":"Newman, Mark E.J.",
    "institute":"University of Michigan, Ann Arbor",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"Fluids & Plasmas",
    "sub2":"General Physics",
    "rank":24,
    "hindex":35,
    "papers":209,
    "citations":7068,
    "homepage":"Mark Newman"
  },
  {
    "name":"Neese, Frank",
    "institute":"FAccTs GmbH",
    "country":"deu",
    "field":"Chemistry",
    "sub1":"Chemical Physics",
    "sub2":"Inorganic & Nuclear Chemistry",
    "rank":29,
    "hindex":34,
    "papers":643,
    "citations":8079,
    "homepage":"https:\/\/www.mpg.de\/11712383\/kohlenforschung-neese"
  },
  {
    "name":"Witten, Edward",
    "institute":"Institute for Advanced Study",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"Nuclear & Particle Physics",
    "sub2":"Mathematical Physics",
    "rank":31,
    "hindex":39,
    "papers":327,
    "citations":5320,
    "homepage":"https:\/\/www.ias.edu\/sns\/witten"
  },
  {
    "name":"Gr\u00e4tzel, Michael",
    "institute":"\u00c9cole Polytechnique F\u00e9d\u00e9rale de Lausanne",
    "country":"che",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Chemical Physics",
    "rank":35,
    "hindex":61,
    "papers":1776,
    "citations":8562,
    "homepage":"https:\/\/www.epfl.ch\/labs\/lpi\/graetzel\/"
  },
  {
    "name":"Goodenough, John B.",
    "institute":"Texas Materials Institute",
    "country":"usa",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Applied Physics",
    "sub2":"Energy",
    "rank":37,
    "hindex":47,
    "papers":985,
    "citations":8271,
    "homepage":"https:\/\/www.nobelprize.org\/prizes\/chemistry\/2019\/goodenough\/facts\/"
  },
  {
    "name":"Manthiram, Arumugam",
    "institute":"Cockrell School of Engineering",
    "country":"usa",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Energy",
    "rank":38,
    "hindex":43,
    "papers":1045,
    "citations":9958,
    "homepage":"Manthiram Laboratory \u2013 Innovation in Clean Energy Materials"
  },
  {
    "name":"Rubin, Donald B.",
    "institute":"Harvard University",
    "country":"usa",
    "field":"Mathematics & Statistics",
    "sub1":"Statistics & Probability",
    "sub2":"Social Sciences Methods",
    "rank":41,
    "hindex":32,
    "papers":313,
    "citations":9804,
    "homepage":"https:\/\/statistics.fas.harvard.edu\/people\/donald-b-rubin"
  },
  {
    "name":"Clevers, Hans C.",
    "institute":"University Medical Center Utrecht",
    "country":"nld",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Immunology",
    "rank":42,
    "hindex":60,
    "papers":844,
    "citations":9076,
    "homepage":"Clevers group - Hubrecht Institute"
  },
  {
    "name":"Sovacool, Benjamin K.",
    "institute":"Aarhus Universitet",
    "country":"dnk",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Energy",
    "sub2":"Environmental Sciences",
    "rank":44,
    "hindex":44,
    "papers":627,
    "citations":5626,
    "homepage":"Benjamin Sovacool | About | University of Sussex"
  },
  {
    "name":"Geim, Andre K.",
    "institute":"The University of Manchester",
    "country":"gbr",
    "field":"Physics & Astronomy",
    "sub1":"Applied Physics",
    "sub2":"Nanoscience & Nanotechnology",
    "rank":47,
    "hindex":50,
    "papers":416,
    "citations":8005,
    "homepage":"https://www.graphene.manchester.ac.uk/research/people/andre-geim/"
  },
  {
    "name":"Stockwell, Brent R.",
    "institute":"Columbia University",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":50,
    "hindex":52,
    "papers":216,
    "citations":11397,
    "homepage":"The Stockwell Laboratory"
  },
  {
    "name":"N\u00d8rskov, Jens Kehlet",
    "institute":"Technical University of Denmark",
    "country":"dnk",
    "field":"Chemistry",
    "sub1":"Physical Chemistry",
    "sub2":"Chemical Physics",
    "rank":54,
    "hindex":68,
    "papers":727,
    "citations":12818,
    "homepage":"https:\/\/orbit.dtu.dk\/en\/persons\/jens-kehlet-n%C3%B8rskov\/"
  },
  {
    "name":"Yaghi, Omar M.",
    "institute":"University of California, Berkeley",
    "country":"usa",
    "field":"Chemistry",
    "sub1":"General Chemistry",
    "sub2":"Organic Chemistry",
    "rank":55,
    "hindex":64,
    "papers":382,
    "citations":15374,
    "homepage":"Omar Yaghi's Laboratory | Department of Chemistry at the University of California, Berkeley"
  },
  {
    "name":"Dincer, I.",
    "institute":"Ontario Tech University",
    "country":"can",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Energy",
    "sub2":"Mechanical Engineering & Transports",
    "rank":56,
    "hindex":39,
    "papers":1721,
    "citations":7604,
    "homepage":""
  },
  {
    "name":"Tilman, David G.",
    "institute":"University of Minnesota Twin Cities",
    "country":"usa",
    "field":"Biology",
    "sub1":"Ecology",
    "sub2":"Environmental Sciences",
    "rank":58,
    "hindex":47,
    "papers":319,
    "citations":5065,
    "homepage":""
  },
  {
    "name":"Zhu, Jiankang",
    "institute":"Southern University of Science and Technology",
    "country":"chn",
    "field":"Biology",
    "sub1":"Plant Biology & Botany",
    "sub2":"Developmental Biology",
    "rank":61,
    "hindex":45,
    "papers":601,
    "citations":6653,
    "homepage":""
  },
  {
    "name":"Novoselov, Konstantin Sergeevich",
    "institute":"National University of Singapore",
    "country":"sgp",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Applied Physics",
    "rank":73,
    "hindex":52,
    "papers":555,
    "citations":10314,
    "homepage":""
  },
  {
    "name":"Whitesides, G. M.",
    "institute":"Harvard Faculty of Arts and Sciences",
    "country":"usa",
    "field":"Chemistry",
    "sub1":"General Chemistry",
    "sub2":"Organic Chemistry",
    "rank":74,
    "hindex":45,
    "papers":1394,
    "citations":7673,
    "homepage":""
  },
  {
    "name":"Rillig, Matthias C.",
    "institute":"Berlin-Brandenburgisches Institut f\u00fcr Biodiversit\u00e4tsforschung",
    "country":"deu",
    "field":"Biology",
    "sub1":"Ecology",
    "sub2":"Agronomy & Agriculture",
    "rank":75,
    "hindex":47,
    "papers":532,
    "citations":6262,
    "homepage":""
  },
  {
    "name":"Cui, Yi",
    "institute":"Stanford Engineering",
    "country":"usa",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Energy",
    "rank":77,
    "hindex":75,
    "papers":809,
    "citations":16268,
    "homepage":""
  },
  {
    "name":"Wishart, David S.",
    "institute":"University of Alberta",
    "country":"can",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Analytical Chemistry",
    "rank":79,
    "hindex":41,
    "papers":532,
    "citations":7046,
    "homepage":""
  },
  {
    "name":"Bartel, David P.",
    "institute":"Massachusetts Institute of Technology",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Bioinformatics",
    "rank":82,
    "hindex":34,
    "papers":204,
    "citations":5446,
    "homepage":""
  },
  {
    "name":"Gelman, Andrew E.",
    "institute":"Columbia University",
    "country":"usa",
    "field":"Mathematics & Statistics",
    "sub1":"Statistics & Probability",
    "sub2":"Political Science & Public Administration",
    "rank":83,
    "hindex":38,
    "papers":344,
    "citations":5633,
    "homepage":""
  },
  {
    "name":"Kanehisa, Minoru I.",
    "institute":"Institute for Chemical Research",
    "country":"jpn",
    "field":"Biomedical Research",
    "sub1":"Bioinformatics",
    "sub2":"Developmental Biology",
    "rank":94,
    "hindex":27,
    "papers":264,
    "citations":10723,
    "homepage":""
  },
  {
    "name":"Wang, Joseph",
    "institute":"University of California, San Diego",
    "country":"usa",
    "field":"Chemistry",
    "sub1":"Analytical Chemistry",
    "sub2":"Nanoscience & Nanotechnology",
    "rank":95,
    "hindex":46,
    "papers":1311,
    "citations":7684,
    "homepage":""
  },
  {
    "name":"Mittler, Ron",
    "institute":"University of Missouri School of Medicine",
    "country":"usa",
    "field":"Biology",
    "sub1":"Plant Biology & Botany",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":98,
    "hindex":38,
    "papers":224,
    "citations":5370,
    "homepage":""
  },
  {
    "name":"Barsoum, Michel W.",
    "institute":"Drexel University College of Engineering",
    "country":"usa",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Materials",
    "sub2":"Applied Physics",
    "rank":99,
    "hindex":52,
    "papers":588,
    "citations":8215,
    "homepage":""
  },
  {
    "name":"Fierer, Noah G.",
    "institute":"University of Colorado Boulder",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Microbiology",
    "sub2":"Ecology",
    "rank":102,
    "hindex":44,
    "papers":291,
    "citations":6053,
    "homepage":""
  },
  {
    "name":"Yeh, Jien Wei",
    "institute":"National Tsing Hua University",
    "country":"twn",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Materials",
    "sub2":"Applied Physics",
    "rank":103,
    "hindex":39,
    "papers":310,
    "citations":5423,
    "homepage":""
  },
  {
    "name":"Barab\u00e1si, Albert L\u00e1sl\u00f3",
    "institute":"Northeastern University",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"Fluids & Plasmas",
    "sub2":"General Physics",
    "rank":104,
    "hindex":43,
    "papers":356,
    "citations":6770,
    "homepage":""
  },
  {
    "name":"Raabe, Dierk",
    "institute":"Max-Planck-Institut f\u00fcr Eisenforschung GmbH",
    "country":"deu",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Materials",
    "sub2":"Applied Physics",
    "rank":113,
    "hindex":48,
    "papers":1111,
    "citations":7335,
    "homepage":""
  },
  {
    "name":"Reich, Peter B.",
    "institute":"University of Michigan, Ann Arbor",
    "country":"usa",
    "field":"Biology",
    "sub1":"Ecology",
    "sub2":"Plant Biology & Botany",
    "rank":115,
    "hindex":53,
    "papers":822,
    "citations":3136,
    "homepage":""
  },
  {
    "name":"Calder, Philip C.",
    "institute":"University of Southampton",
    "country":"gbr",
    "field":"Biomedical Research",
    "sub1":"Nutrition & Dietetics",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":116,
    "hindex":33,
    "papers":896,
    "citations":3717,
    "homepage":""
  },
  {
    "name":"Shi, Caijun",
    "institute":"Hunan University",
    "country":"chn",
    "field":"Built Environment & Design",
    "sub1":"Building & Construction",
    "sub2":"Materials",
    "rank":119,
    "hindex":47,
    "papers":597,
    "citations":4729,
    "homepage":""
  },
  {
    "name":"Mizushima, Noboru N.",
    "institute":"Faculty of Medicine",
    "country":"jpn",
    "field":"Biomedical Research",
    "sub1":"Biochemistry & Molecular Biology",
    "sub2":"Developmental Biology",
    "rank":123,
    "hindex":43,
    "papers":311,
    "citations":4353,
    "homepage":""
  },
  {
    "name":"Tarascon, Jean Marie",
    "institute":"CNRS Centre National de la Recherche Scientifique",
    "country":"fra",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Energy",
    "sub2":"Applied Physics",
    "rank":125,
    "hindex":40,
    "papers":853,
    "citations":9209,
    "homepage":""
  },
  {
    "name":"Wang, Jianlong",
    "institute":"Tsinghua University",
    "country":"chn",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Environmental Sciences",
    "sub2":"Energy",
    "rank":127,
    "hindex":47,
    "papers":901,
    "citations":11008,
    "homepage":""
  },
  {
    "name":"Green, Martin A.",
    "institute":"UNSW Sydney",
    "country":"aus",
    "field":"Physics & Astronomy",
    "sub1":"Applied Physics",
    "sub2":"Nanoscience & Nanotechnology",
    "rank":130,
    "hindex":35,
    "papers":961,
    "citations":3847,
    "homepage":""
  },
  {
    "name":"Koonin, Eugene V.",
    "institute":"National Center for Biotechnology Information (NCBI)",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Bioinformatics",
    "rank":131,
    "hindex":45,
    "papers":1111,
    "citations":5527,
    "homepage":""
  },
  {
    "name":"Corma, Avelino",
    "institute":"CSIC-UPV - Instituto de Tecnolog\u00eda Qu\u00edmica (ITQ)",
    "country":"esp",
    "field":"Chemistry",
    "sub1":"Physical Chemistry",
    "sub2":"Organic Chemistry",
    "rank":139,
    "hindex":36,
    "papers":1428,
    "citations":5787,
    "homepage":""
  },
  {
    "name":"Scrivener, Karen Louise",
    "institute":"\u00c9cole Polytechnique F\u00e9d\u00e9rale de Lausanne",
    "country":"che",
    "field":"Built Environment & Design",
    "sub1":"Building & Construction",
    "sub2":"Materials",
    "rank":145,
    "hindex":45,
    "papers":366,
    "citations":6195,
    "homepage":""
  },
  {
    "name":"Lu, Tian",
    "institute":"Beijing Kein Research Center for Natural Sciences",
    "country":"chn",
    "field":"Physics & Astronomy",
    "sub1":"Chemical Physics",
    "sub2":"General Chemistry",
    "rank":148,
    "hindex":27,
    "papers":96,
    "citations":10468,
    "homepage":""
  },
  {
    "name":"Donoho, David L.",
    "institute":"Stanford University",
    "country":"usa",
    "field":"Mathematics & Statistics",
    "sub1":"Networking & Telecommunications",
    "sub2":"Statistics & Probability",
    "rank":149,
    "hindex":26,
    "papers":207,
    "citations":3532,
    "homepage":""
  },
  {
    "name":"Ritchie, Robert O.",
    "institute":"Department of Materials Science and Engineering",
    "country":"usa",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Materials",
    "sub2":"Mechanical Engineering & Transports",
    "rank":155,
    "hindex":40,
    "papers":809,
    "citations":6253,
    "homepage":""
  },
  {
    "name":"Karin, Michael J.",
    "institute":"UC San Diego School of Medicine",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":157,
    "hindex":46,
    "papers":694,
    "citations":6098,
    "homepage":""
  },
  {
    "name":"Penuelas, Josep J.",
    "institute":"Consejo Superior de Investigaciones Cient\u00edficas",
    "country":"esp",
    "field":"Biology",
    "sub1":"Ecology",
    "sub2":"Plant Biology & Botany",
    "rank":158,
    "hindex":53,
    "papers":1356,
    "citations":5671,
    "homepage":""
  },
  {
    "name":"Olabi, Abdul Ghani",
    "institute":"University of Sharjah",
    "country":"are",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Energy",
    "sub2":"Materials",
    "rank":160,
    "hindex":47,
    "papers":680,
    "citations":7172,
    "homepage":""
  },
  {
    "name":"Smith, Pete",
    "institute":"University of Aberdeen",
    "country":"gbr",
    "field":"Earth & Environmental Sciences",
    "sub1":"Agronomy & Agriculture",
    "sub2":"Environmental Sciences",
    "rank":161,
    "hindex":50,
    "papers":697,
    "citations":3205,
    "homepage":""
  },
  {
    "name":"He, Jihuan",
    "institute":"Soochow University",
    "country":"chn",
    "field":"Physics & Astronomy",
    "sub1":"Energy",
    "sub2":"Mathematical Physics",
    "rank":163,
    "hindex":27,
    "papers":679,
    "citations":3000,
    "homepage":""
  },
  {
    "name":"Sies, Helmult",
    "institute":"Heinrich-Heine-Universit\u00e4t D\u00fcsseldorf",
    "country":"deu",
    "field":"Biomedical Research",
    "sub1":"Biochemistry & Molecular Biology",
    "sub2":"Nutrition & Dietetics",
    "rank":165,
    "hindex":25,
    "papers":733,
    "citations":4002,
    "homepage":""
  },
  {
    "name":"Snaith, Henry James",
    "institute":"University of Oxford",
    "country":"gbr",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Physical Chemistry",
    "rank":168,
    "hindex":54,
    "papers":521,
    "citations":7277,
    "homepage":""
  },
  {
    "name":"Provis, John Lloyd",
    "institute":"Paul Scherrer Institut",
    "country":"che",
    "field":"Built Environment & Design",
    "sub1":"Building & Construction",
    "sub2":"Materials",
    "rank":173,
    "hindex":43,
    "papers":404,
    "citations":2908,
    "homepage":""
  },
  {
    "name":"Cand\u00e9s, Emmanuel J.",
    "institute":"Stanford University",
    "country":"usa",
    "field":"Mathematics & Statistics",
    "sub1":"Statistics & Probability",
    "sub2":"Networking & Telecommunications",
    "rank":176,
    "hindex":36,
    "papers":168,
    "citations":4696,
    "homepage":""
  },
  {
    "name":"Wang, Shaobin",
    "institute":"The University of Adelaide",
    "country":"aus",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Chemical Engineering",
    "sub2":"Physical Chemistry",
    "rank":177,
    "hindex":53,
    "papers":894,
    "citations":8656,
    "homepage":""
  },
  {
    "name":"Duarte, C. M.",
    "institute":"King Abdullah University of Science and Technology",
    "country":"sau",
    "field":"Biology",
    "sub1":"Marine Biology & Hydrobiology",
    "sub2":"Ecology",
    "rank":178,
    "hindex":43,
    "papers":1064,
    "citations":4776,
    "homepage":""
  },
  {
    "name":"HALLIWELL, Barry B.",
    "institute":"NUS Yong Loo Lin School of Medicine",
    "country":"sgp",
    "field":"Biomedical Research",
    "sub1":"Biochemistry & Molecular Biology",
    "sub2":"Neurology & Neurosurgery",
    "rank":181,
    "hindex":28,
    "papers":732,
    "citations":3316,
    "homepage":""
  },
  {
    "name":"Trenberth, Kevin E.",
    "institute":"The University of Auckland",
    "country":"nzl",
    "field":"Earth & Environmental Sciences",
    "sub1":"Meteorology & Atmospheric Sciences",
    "sub2":"Environmental Sciences",
    "rank":183,
    "hindex":33,
    "papers":270,
    "citations":3142,
    "homepage":""
  },
  {
    "name":"Sigmund, Ole",
    "institute":"Technical University of Denmark",
    "country":"dnk",
    "field":"Built Environment & Design",
    "sub1":"Design Practice & Management",
    "sub2":"Applied Mathematics",
    "rank":185,
    "hindex":32,
    "papers":391,
    "citations":3831,
    "homepage":""
  },
  {
    "name":"Saleh, Tawfik A.",
    "institute":"King Fahd University of Petroleum and Minerals",
    "country":"sau",
    "field":"Physics & Astronomy",
    "sub1":"Chemical Physics",
    "sub2":"Chemical Engineering",
    "rank":186,
    "hindex":28,
    "papers":519,
    "citations":3929,
    "homepage":""
  },
  {
    "name":"Bl\u00f6chl, Peter E.",
    "institute":"Technische Universit\u00e4t Clausthal",
    "country":"deu",
    "field":"Physics & Astronomy",
    "sub1":"Applied Physics",
    "sub2":"Chemical Physics",
    "rank":188,
    "hindex":10,
    "papers":108,
    "citations":9474,
    "homepage":""
  },
  {
    "name":"Wood, Simon N.",
    "institute":"The University of Edinburgh",
    "country":"gbr",
    "field":"Mathematics & Statistics",
    "sub1":"Statistics & Probability",
    "sub2":"Ecology",
    "rank":189,
    "hindex":23,
    "papers":97,
    "citations":3328,
    "homepage":""
  },
  {
    "name":"Sheldon, Roger A.",
    "institute":"Laboratory of Organic Chemistry and Catalysis, Delft",
    "country":"nld",
    "field":"Chemistry",
    "sub1":"Organic Chemistry",
    "sub2":"Physical Chemistry",
    "rank":190,
    "hindex":27,
    "papers":461,
    "citations":2775,
    "homepage":""
  },
  {
    "name":"Folke, Carl S.",
    "institute":"Stockholms universitet",
    "country":"swe",
    "field":"Biology",
    "sub1":"Ecology",
    "sub2":"Environmental Sciences",
    "rank":191,
    "hindex":45,
    "papers":306,
    "citations":2801,
    "homepage":""
  },
  {
    "name":"Green, Douglas R.",
    "institute":"St. Jude Children's Research Hospital",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Immunology",
    "sub2":"Developmental Biology",
    "rank":194,
    "hindex":44,
    "papers":679,
    "citations":3758,
    "homepage":""
  },
  {
    "name":"Ingber, Donald E.",
    "institute":"Harvard John A. Paulson School of Engineering and Applied Sciences",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":200,
    "hindex":40,
    "papers":505,
    "citations":4773,
    "homepage":""
  },
  {
    "name":"Lander, Eric S.",
    "institute":"Broad Institute",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Genetics & Heredity",
    "rank":204,
    "hindex":78,
    "papers":662,
    "citations":2716,
    "homepage":""
  },
  {
    "name":"Springel, Volker",
    "institute":"Max Planck Institute for Astrophysics",
    "country":"deu",
    "field":"Physics & Astronomy",
    "sub1":"Astronomy & Astrophysics",
    "sub2":"Nuclear & Particle Physics",
    "rank":206,
    "hindex":39,
    "papers":546,
    "citations":2853,
    "homepage":""
  },
  {
    "name":"Massagu\u00e9, Joan",
    "institute":"Weill Cornell Graduate School of Medical Sciences",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":208,
    "hindex":37,
    "papers":389,
    "citations":4507,
    "homepage":""
  },
  {
    "name":"Sun, Yang-kook",
    "institute":"Hanyang University",
    "country":"kor",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Energy",
    "sub2":"Nanoscience & Nanotechnology",
    "rank":213,
    "hindex":46,
    "papers":758,
    "citations":6537,
    "homepage":""
  },
  {
    "name":"Vanderbilt, David H.",
    "institute":"Rutgers University\u2013New Brunswick",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"Applied Physics",
    "sub2":"General Physics",
    "rank":216,
    "hindex":30,
    "papers":375,
    "citations":4022,
    "homepage":""
  },
  {
    "name":"Gaston, Kevin J.",
    "institute":"University of Exeter",
    "country":"gbr",
    "field":"Biology",
    "sub1":"Ecology",
    "sub2":"Evolutionary Biology",
    "rank":217,
    "hindex":35,
    "papers":748,
    "citations":4557,
    "homepage":""
  },
  {
    "name":"Hastie, Trevor J.",
    "institute":"Stanford University",
    "country":"usa",
    "field":"Mathematics & Statistics",
    "sub1":"Statistics & Probability",
    "sub2":"Artificial Intelligence & Image Processing",
    "rank":218,
    "hindex":42,
    "papers":290,
    "citations":4740,
    "homepage":""
  },
  {
    "name":"Wu, Gaoyao Yao",
    "institute":"Texas A&M University",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Biochemistry & Molecular Biology",
    "sub2":"Dairy & Animal Science",
    "rank":225,
    "hindex":30,
    "papers":798,
    "citations":3896,
    "homepage":""
  },
  {
    "name":"A. C. Ferrari, A. Carlo",
    "institute":"Department of Engineering",
    "country":"gbr",
    "field":"Physics & Astronomy",
    "sub1":"Applied Physics",
    "sub2":"Nanoscience & Nanotechnology",
    "rank":226,
    "hindex":34,
    "papers":546,
    "citations":4517,
    "homepage":""
  },
  {
    "name":"Maldacena, Juan M.",
    "institute":"Institute for Advanced Study",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"Nuclear & Particle Physics",
    "sub2":"Mathematical Physics",
    "rank":227,
    "hindex":28,
    "papers":171,
    "citations":2801,
    "homepage":""
  },
  {
    "name":"Campisi, Judith",
    "institute":"Buck Institute for Research on Aging",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":229,
    "hindex":44,
    "papers":355,
    "citations":4179,
    "homepage":""
  },
  {
    "name":"Chandel, Navdeep S.",
    "institute":"Northwestern University Feinberg School of Medicine",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Biochemistry & Molecular Biology",
    "rank":232,
    "hindex":40,
    "papers":342,
    "citations":4603,
    "homepage":""
  },
  {
    "name":"Li Prof., Victor C.",
    "institute":"Michigan Engineering",
    "country":"usa",
    "field":"Built Environment & Design",
    "sub1":"Building & Construction",
    "sub2":"Materials",
    "rank":236,
    "hindex":35,
    "papers":399,
    "citations":4290,
    "homepage":""
  },
  {
    "name":"Thompson, Richard C.",
    "institute":"University of Plymouth",
    "country":"gbr",
    "field":"Biology",
    "sub1":"Marine Biology & Hydrobiology",
    "sub2":"Environmental Sciences",
    "rank":237,
    "hindex":47,
    "papers":263,
    "citations":5776,
    "homepage":""
  },
  {
    "name":"Lovley, Derek R.",
    "institute":"University of Massachusetts Amherst",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Microbiology",
    "sub2":"Environmental Sciences",
    "rank":240,
    "hindex":33,
    "papers":524,
    "citations":4204,
    "homepage":""
  },
  {
    "name":"Wazwaz, Abdul Majid 1.",
    "institute":"Saint Xavier University",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"Numerical & Computational Mathematics",
    "sub2":"General Physics",
    "rank":242,
    "hindex":22,
    "papers":778,
    "citations":2754,
    "homepage":""
  },
  {
    "name":"Yang, Ziheng",
    "institute":"University College London",
    "country":"gbr",
    "field":"Biology",
    "sub1":"Evolutionary Biology",
    "sub2":"Developmental Biology",
    "rank":244,
    "hindex":28,
    "papers":218,
    "citations":2744,
    "homepage":""
  },
  {
    "name":"Adebayo, Tomiwa Sunday",
    "institute":"Yak\u0131n Do\u011fu \u00dcniversitesi",
    "country":"cyp",
    "field":"Earth & Environmental Sciences",
    "sub1":"Environmental Sciences",
    "sub2":"Energy",
    "rank":245,
    "hindex":39,
    "papers":259,
    "citations":2889,
    "homepage":""
  },
  {
    "name":"Koper, Marc T.M.",
    "institute":"Leiden Institute of Chemistry",
    "country":"nld",
    "field":"Chemistry",
    "sub1":"Energy",
    "sub2":"Chemical Physics",
    "rank":251,
    "hindex":44,
    "papers":508,
    "citations":6143,
    "homepage":""
  },
  {
    "name":"Eddy, Sean R.",
    "institute":"Harvard University",
    "country":"usa",
    "field":"Biomedical Research",
    "sub1":"Developmental Biology",
    "sub2":"Bioinformatics",
    "rank":252,
    "hindex":34,
    "papers":138,
    "citations":3018,
    "homepage":""
  },
  {
    "name":"ZHANG, Hua",
    "institute":"City University of Hong Kong",
    "country":"hkg",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"General Chemistry",
    "rank":255,
    "hindex":49,
    "papers":625,
    "citations":7520,
    "homepage":""
  },
  {
    "name":"Park, Nam-gyu",
    "institute":"Sungkyunkwan University",
    "country":"kor",
    "field":"Enabling & Strategic Technologies",
    "sub1":"Nanoscience & Nanotechnology",
    "sub2":"Applied Physics",
    "rank":259,
    "hindex":38,
    "papers":439,
    "citations":5175,
    "homepage":""
  },
  {
    "name":"Emanuel, Kerry A.",
    "institute":"Massachusetts Institute of Technology",
    "country":"usa",
    "field":"Earth & Environmental Sciences",
    "sub1":"Meteorology & Atmospheric Sciences",
    "sub2":"Strategic, Defence & Security Studies",
    "rank":264,
    "hindex":32,
    "papers":329,
    "citations":1992,
    "homepage":""
  },
  {
    "name":"Preskill, John P.",
    "institute":"California Institute of Technology",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"General Physics",
    "sub2":"Nuclear & Particle Physics",
    "rank":271,
    "hindex":27,
    "papers":138,
    "citations":3712,
    "homepage":""
  },
  {
    "name":"Jorgensen, William L.",
    "institute":"Yale University",
    "country":"usa",
    "field":"Chemistry",
    "sub1":"Chemical Physics",
    "sub2":"General Chemistry",
    "rank":277,
    "hindex":25,
    "papers":590,
    "citations":6115,
    "homepage":""
  },
  {
    "name":"Lloyd, Seth",
    "institute":"Massachusetts Institute of Technology",
    "country":"usa",
    "field":"Physics & Astronomy",
    "sub1":"General Physics",
    "sub2":"Fluids & Plasmas",
    "rank":278,
    "hindex":33,
    "papers":334,
    "citations":3802,
    "homepage":""
  },
  {
    "name":"Karniadakis, George Em",
    "institute":"Brown University",
    "country":"usa",
    "field":"Mathematics & Statistics",
    "sub1":"Applied Mathematics",
    "sub2":"Fluids & Plasmas",
    "rank":281,
    "hindex":47,
    "papers":765,
    "citations":13077,
    "homepage":""
  }
]

/* ================= ELEMENTS ================= */

const grid = document.getElementById("profGrid");

const searchInput = document.getElementById("searchInput");

const countryFilter = document.getElementById("countryFilter");
const fieldFilter = document.getElementById("fieldFilter");
const sub1Filter = document.getElementById("sub1Filter");
const instituteFilter = document.getElementById("instituteFilter");

const minPapersInput = document.getElementById("minPapers");
const minCitationsInput = document.getElementById("minCitations");

const clearBtn = document.getElementById("clearFilters");


/* ================= DATA ================= */

// Make sure professors is loaded before this
// Example: fetch JSON in CodePen Assets


/* ================= INIT FILTERS ================= */

function initFilters() {

  const countries = [...new Set(professors.map(p => p.country))];
  const fields = [...new Set(professors.map(p => p.field))];
  const sub1s = [...new Set(professors.map(p => p.sub1))];
  const institutes = [...new Set(professors.map(p => p.institute))];

  fillSelect(countryFilter, countries);
  fillSelect(fieldFilter, fields);
  fillSelect(sub1Filter, sub1s);
  fillSelect(instituteFilter, institutes);

}


/* ================= HELPER ================= */

function fillSelect(select, values) {

  values
    .filter(v => v && v !== "")
    .sort()
    .forEach(v => {

      const option = document.createElement("option");
      option.value = v;
      option.textContent = v;

      select.appendChild(option);

    });

}


/* ================= RENDER ================= */

function render(list) {

  grid.innerHTML = "";

  if (list.length === 0) {
    grid.innerHTML = "<p class='no-result'>No professors found.</p>";
    return;
  }

  list.forEach(p => {

    const card = document.createElement("div");
    card.className = "prof-card";

    card.innerHTML = `

      <div class="card-body">

        <h3>${p.name}</h3>

        <div class="prof-meta">
          ${p.institute} • ${p.country.toUpperCase()}
        </div>

        <div class="prof-tags">
          <span>${p.field}</span>
          <span>${p.sub1}</span>
          <span>${p.sub2}</span>
        </div>

        <div class="stats">

          <div>📄 Papers: ${p.papers}</div>
          <div>🔗 Citations: ${p.citations}</div>

        </div>

      </div>

      <a href="${p.homepage || '#'}"
         target="_blank"
         class="homepage-btn">

        Visit Homepage

      </a>

    `;

    grid.appendChild(card);

  });

}


/* ================= FILTER ================= */

function applyFilters() {

  const text = searchInput.value.toLowerCase();

  const country = countryFilter.value;
  const field = fieldFilter.value;
  const sub1 = sub1Filter.value;
  const institute = instituteFilter.value;

  const minPapers = Number(minPapersInput.value) || 0;
  const minCitations = Number(minCitationsInput.value) || 0;


  const filtered = professors.filter(p => {

    /* Search Match */

    const searchMatch =
      p.name.toLowerCase().includes(text) ||
      p.institute.toLowerCase().includes(text) ||
      p.field.toLowerCase().includes(text) ||
      p.sub1.toLowerCase().includes(text) ||
      p.country.toLowerCase().includes(text);


    return (

      searchMatch &&

      (country === "" || p.country === country) &&
      (field === "" || p.field === field) &&
      (sub1 === "" || p.sub1 === sub1) &&
      (institute === "" || p.institute === institute) &&

      p.papers >= minPapers &&
      p.citations >= minCitations

    );

  });

  render(filtered);

}


/* ================= EVENTS ================= */

searchInput.addEventListener("input", applyFilters);

countryFilter.addEventListener("change", applyFilters);
fieldFilter.addEventListener("change", applyFilters);
sub1Filter.addEventListener("change", applyFilters);
instituteFilter.addEventListener("change", applyFilters);

minPapersInput.addEventListener("input", applyFilters);
minCitationsInput.addEventListener("input", applyFilters);


clearBtn.addEventListener("click", () => {

  searchInput.value = "";

  countryFilter.value = "";
  fieldFilter.value = "";
  sub1Filter.value = "";
  instituteFilter.value = "";

  minPapersInput.value = "";
  minCitationsInput.value = "";

  render(professors);

});


/* ================= INIT ================= */

initFilters();
render(professors);