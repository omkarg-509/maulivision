<?php require_once '../app/views/layouts/sidebar.php'; ?>
<style>
  #todo-list .todo-title{transition:color .25s, text-decoration-color .25s;}
  #todo-list li.new-item{animation: flashBg 1.2s ease-in-out 1;}
  @keyframes flashBg {0%{background:#e3f2ff;}60%{background:#ffffff;}100%{background:#ffffff;}}
  #todo-list li{transition:background .3s;}
  #todo-list li:hover{background:#f8f9fa;}
</style>
<div class="main-content mt-5 pt-4">
  <section class="section">
    <div class="section-header">
      <h1>Your To‑Do List</h1>
    </div>
    <div class="card mb-4">
      <div class="card-body">
        <form id="todo-form" class="row g-2 mb-3" autocomplete="off">
          <div class="col-sm-9 col-md-10">
            <input type="text" id="todo-title" class="form-control" placeholder="Add new task..." required>
          </div>
          <div class="col-sm-3 col-md-2 d-grid">
            <button class="btn btn-primary w-100" type="submit">Add</button>
          </div>
        </form>
        <div id="todo-empty" class="text-muted small <?= empty($todos) ? '' : 'd-none' ?>">No tasks yet. Add your first one.</div>
        <ul id="todo-list" class="list-group">
          <?php foreach ($todos as $t): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= (int)$t['id'] ?>">
              <div>
                <input type="checkbox" class="form-check-input me-2 todo-toggle" <?= $t['is_done'] ? 'checked' : '' ?>>
                <span class="todo-title <?= $t['is_done'] ? 'text-decoration-line-through text-muted' : '' ?>"><?= htmlspecialchars($t['title']) ?></span>
              </div>
              <button class="btn btn-sm btn-outline-danger todo-delete"><i class="fas fa-trash"></i></button>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex flex-wrap align-items-end gap-2">
        <h4 class="mb-0 me-auto">Progress (Daily)</h4>
        <div class="d-flex gap-2 flex-wrap">
          <input type="date" id="stat-from" class="form-control form-control-sm" style="min-width:150px">
          <input type="date" id="stat-to" class="form-control form-control-sm" style="min-width:150px">
          <button id="stat-apply" class="btn btn-sm btn-outline-primary">Apply</button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="todo-chart" height="140"></canvas>
        <div id="stats-empty" class="text-muted small mt-2 d-none">No data in range.</div>
      </div>
    </div>
  </section>
</div>

<script>
(function(){
  const BASE = '<?= BASE_URL ?>';
  const form = document.getElementById('todo-form');
  const input = document.getElementById('todo-title');
  const list = document.getElementById('todo-list');
  const empty = document.getElementById('todo-empty');

  function toggleEmpty(){
    if(list.children.length === 0){ empty.classList.remove('d-none'); } else { empty.classList.add('d-none'); }
  }

  form.addEventListener('submit', function(e){
    e.preventDefault();
    const title = input.value.trim();
    if(!title) return;
    fetch(BASE+'own/add', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'title='+encodeURIComponent(title)})
      .then(r=>r.json())
      .then(data=>{
        if(data.ok){
          const li = document.createElement('li');
          li.className='list-group-item d-flex justify-content-between align-items-center new-item';
          li.setAttribute('data-id', data.id);
          li.innerHTML='<div><input type="checkbox" class="form-check-input me-2 todo-toggle"> <span class="todo-title">'+data.title+'</span></div><button class="btn btn-sm btn-outline-danger todo-delete"><i class="fas fa-trash"></i></button>';
          list.prepend(li);
          setTimeout(()=>li.classList.remove('new-item'),1300);
          input.value='';
          toggleEmpty();
        }
      });
  });

  list.addEventListener('click', function(e){
    const li = e.target.closest('li');
    if(!li) return;
    const id = li.getAttribute('data-id');

    if(e.target.closest('.todo-delete')){
      if(!id){ li.remove(); toggleEmpty(); return; }
      fetch(BASE+'own/delete/'+id)
        .then(r=>r.json()).then(data=>{ if(data.ok){ li.remove(); toggleEmpty(); }});
    }
    else if(e.target.classList.contains('todo-toggle')){
      if(!id){ e.target.checked = !e.target.checked; return; }
      fetch(BASE+'own/toggle/'+id)
        .then(r=>r.json()).then(data=>{
          if(data.ok){
            const span = li.querySelector('.todo-title');
            if(e.target.checked){ span.classList.add('text-decoration-line-through','text-muted'); }
            else { span.classList.remove('text-decoration-line-through','text-muted'); }
          } else {
            e.target.checked = !e.target.checked;
          }
        });
    }
  });

  // Stats / Chart
  const fromInput = document.getElementById('stat-from');
  const toInput = document.getElementById('stat-to');
  const applyBtn = document.getElementById('stat-apply');
  const statsEmpty = document.getElementById('stats-empty');
  let chart;

  function defaultDates(){
    const today = new Date();
    const past = new Date(Date.now() - 6*24*3600*1000);
    fromInput.value = past.toISOString().slice(0,10);
    toInput.value = today.toISOString().slice(0,10);
  }

  function loadStats(){
    const f = fromInput.value;
    const t = toInput.value;
    fetch(`${BASE}own/stats?from=${encodeURIComponent(f)}&to=${encodeURIComponent(t)}`)
      .then(r=>r.json())
      .then(json=>{
        if(!json.ok) return;
        const rows = json.data;
        if(!rows.length){
          statsEmpty.classList.remove('d-none');
          if(chart){ chart.destroy(); chart=null; }
          return;
        }
        statsEmpty.classList.add('d-none');
        const labels = rows.map(r=>r.day);
        const totals = rows.map(r=>r.total);
        const dones = rows.map(r=>r.done);
        const ctx = document.getElementById('todo-chart').getContext('2d');
        if(chart){ chart.destroy(); }
        chart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels,
            datasets: [
              {label: 'Total', data: totals, backgroundColor: 'rgba(54, 162, 235, 0.6)'},
              {label: 'Done', data: dones, backgroundColor: 'rgba(40, 167, 69, 0.7)'}
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {legend:{position:'bottom'}},
            scales: {y:{beginAtZero:true, precision:0}}
          }
        });
      });
  }

  applyBtn.addEventListener('click', function(e){ e.preventDefault(); loadStats(); });

  toggleEmpty();
  defaultDates();
  // Load Chart.js dynamically (use CDN)
  const script = document.createElement('script');
  script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
  script.onload = loadStats;
  document.head.appendChild(script);
})();
</script>
