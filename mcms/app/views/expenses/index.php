<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content">
      <div id="app">
  <section class="section">
    <div class="section-header d-flex align-items-center flex-wrap gap-2">
      <h1 class="mb-0">Expenses</h1>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <form id="finance-form" class="row g-2 align-items-end" autocomplete="off">
          <div class="col-md-2">
            <label class="form-label small mb-1">Date</label>
            <input type="date" name="entry_date" id="fin-date" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label class="form-label small mb-1">Type</label>
            <select name="type" id="fin-type" class="form-select" required>
              <option value="income">Income</option>
              <option value="expense">Expense</option>
              <option value="borrow">Borrow</option>
              <option value="repay">Repay</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label small mb-1">Method</label>
            <select name="method" id="fin-method" class="form-select" required>
              <option value="cash">Cash</option>
              <option value="online">Online</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label small mb-1">Amount</label>
            <input type="number" step="0.01" min="0" name="amount" id="fin-amount" class="form-control" placeholder="0.00" required>
          </div>
          <div class="col-md-3">
            <label class="form-label small mb-1">Note</label>
            <input type="text" name="note" id="fin-note" class="form-control" placeholder="Optional note">
          </div>
          <div class="col-md-1 d-grid">
            <button class="btn btn-primary mt-4" type="submit">Add</button>
          </div>
        </form>
      </div>
    </div>

    <div class="row g-3 mb-3" id="fin-summary">
      <div class="col-sm-6 col-md-3">
        <div class="card text-bg-success bg-opacity-10 border-0 shadow-sm">
          <div class="card-body p-3">
            <div class="small text-muted">Income</div>
            <div class="fs-5" id="sum-income">0.00</div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card text-bg-danger bg-opacity-10 border-0 shadow-sm">
          <div class="card-body p-3">
            <div class="small text-muted">Expense</div>
            <div class="fs-5" id="sum-expense">0.00</div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card text-bg-warning bg-opacity-10 border-0 shadow-sm">
          <div class="card-body p-3">
            <div class="small text-muted">Borrow</div>
            <div class="fs-5" id="sum-borrow">0.00</div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card text-bg-info bg-opacity-10 border-0 shadow-sm">
          <div class="card-body p-3">
            <div class="small text-muted">Repay</div>
            <div class="fs-5" id="sum-repay">0.00</div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card border-start border-4" id="net-card">
          <div class="card-body p-3 d-flex justify-content-between align-items-center">
            <div><span class="small text-muted">Net (Income - Expense)</span><div class="fs-5" id="sum-net">0.00</div></div>
            <div class="d-flex gap-2">
              <input type="date" id="stat-from" class="form-control form-control-sm">
              <input type="date" id="stat-to" class="form-control form-control-sm">
              <button id="stat-apply" class="btn btn-sm btn-outline-primary">Apply</button>
              <div id="stats-loading" class="spinner-border spinner-border-sm text-primary d-none" role="status"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header"><h4 class="mb-0">Entries</h4></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0" id="fin-table">
            <thead class="table-light">
              <tr>
                <th>Date</th><th>Type</th><th>Method</th><th class="text-end">Amount</th><th>Note</th><th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($entries as $e): ?>
                <tr data-id="<?= (int)$e['id'] ?>">
                  <td class="fin-date"><?= htmlspecialchars($e['entry_date']) ?></td>
                  <td class="fin-type" data-val="<?= htmlspecialchars($e['type']) ?>"><span class="badge bg-secondary text-uppercase small"><?= htmlspecialchars($e['type']) ?></span></td>
                  <td class="fin-method" data-val="<?= htmlspecialchars($e['method']) ?>"><?= htmlspecialchars($e['method']) ?></td>
                  <td class="fin-amount text-end" data-val="<?= number_format((float)$e['amount'],2,'.','') ?>"><?= number_format((float)$e['amount'],2) ?></td>
                  <td class="fin-note"><?= htmlspecialchars($e['note']) ?></td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <button class="btn btn-outline-secondary fin-edit">Edit</button>
                      <button class="btn btn-success d-none fin-save">Save</button>
                      <button class="btn btn-warning d-none fin-cancel">Cancel</button>
                      <button class="btn btn-outline-danger fin-del"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if(empty($entries)): ?><div class="p-3 text-muted small" id="fin-empty">No entries yet.</div><?php endif; ?>
          <nav>
            <ul class="pagination justify-content-center my-2" id="fin-pagination"></ul>
          </nav>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Daily Chart</h4>
      </div>
      <div class="card-body" style="height:300px;">
        <canvas id="fin-chart"></canvas>
        <div id="chart-empty" class="text-muted small mt-2 d-none">No data.</div>
      </div>
    </div>
  </section>
</div>

<script>
(function(){
  const BASE='<?= BASE_URL ?>';
  const form=document.getElementById('finance-form');
  const table=document.querySelector('#fin-table tbody');
  const emptyDiv=document.getElementById('fin-empty');
  const dateInput=document.getElementById('fin-date');
  dateInput.value=new Date().toISOString().slice(0,10);

  function toggleEmpty(){ if(table.children.length===0){ if(!emptyDiv){return;} emptyDiv.classList.remove('d-none'); } else if(emptyDiv){ emptyDiv.classList.add('d-none'); } }

  // --- Pagination ---
  const FIN_PAGE_SIZE = 10;
  function renderFinPage(page){
    const rows = Array.from(table.children);
    const total = rows.length;
    const totalPages = Math.ceil(total/FIN_PAGE_SIZE) || 1;
    page = Math.max(1, Math.min(page, totalPages));
    rows.forEach(r=> r.style.display='none');
    rows.slice((page-1)*FIN_PAGE_SIZE, page*FIN_PAGE_SIZE).forEach(r=> r.style.display='');
    const pag=document.getElementById('fin-pagination'); if(!pag) return;
    pag.innerHTML='';
    for(let i=1;i<=totalPages;i++){
      const li=document.createElement('li'); li.className='page-item'+(i===page?' active':'');
      const a=document.createElement('a'); a.className='page-link'; a.href='#'; a.textContent=i;
      a.addEventListener('click', e=>{e.preventDefault(); renderFinPage(i);});
      li.appendChild(a); pag.appendChild(li);
    }
  }
  renderFinPage(1);

  form.addEventListener('submit', e=>{
    e.preventDefault();
    const fd=new FormData(form);
    fetch(BASE+'expenses/financeAdd',{method:'POST', body:fd})
      .then(r=>r.json())
      .then(j=>{
        if(!j.ok) return;
        const tr=document.createElement('tr');
        tr.setAttribute('data-id', j.id);
        tr.innerHTML=`<td>${j.entry_date}</td><td><span class="badge bg-secondary text-uppercase small">${j.type}</span></td><td>${j.method}</td><td class="text-end">${Number(j.amount).toFixed(2)}</td><td>${j.note||''}</td><td><button class="btn btn-sm btn-outline-danger fin-del"><i class="fas fa-trash"></i></button></td>`;
        table.prepend(tr);
        toggleEmpty(); renderFinPage(1);
        form.reset();
        dateInput.value=new Date().toISOString().slice(0,10);
        refreshStatsDebounced();
      });
  });

  table.addEventListener('click', e=>{
    if(e.target.closest('.fin-del')){
      const tr=e.target.closest('tr');
      const id=tr.getAttribute('data-id');
      fetch(BASE+'expenses/financeDelete/'+id)
        .then(r=>r.json()).then(j=>{ if(j.ok){ tr.remove(); toggleEmpty(); renderFinPage(1); refreshStatsDebounced(); } });
    } else if(e.target.closest('.fin-edit')){
      const tr=e.target.closest('tr'); enterEdit(tr);
    } else if(e.target.closest('.fin-cancel')){
      const tr=e.target.closest('tr'); exitEdit(tr, true);
    } else if(e.target.closest('.fin-save')){
      const tr=e.target.closest('tr'); saveEdit(tr);
    }
  });

  function enterEdit(tr){
    // Toggle buttons
    tr.querySelector('.fin-edit').classList.add('d-none');
    tr.querySelector('.fin-save').classList.remove('d-none');
    tr.querySelector('.fin-cancel').classList.remove('d-none');
    // Replace cells with inputs
    const dateCell=tr.querySelector('.fin-date');
    const typeCell=tr.querySelector('.fin-type');
    const methodCell=tr.querySelector('.fin-method');
    const amountCell=tr.querySelector('.fin-amount');
    const noteCell=tr.querySelector('.fin-note');
    const dateVal=dateCell.textContent.trim();
    const typeVal=typeCell.getAttribute('data-val');
    const methodVal=methodCell.getAttribute('data-val');
    const amountVal=amountCell.getAttribute('data-val');
    const noteVal=noteCell.textContent.trim();
    dateCell.innerHTML = `<input type="date" class="form-control form-control-sm fin-edit-date" value="${dateVal}">`;
    typeCell.innerHTML = `<select class="form-select form-select-sm fin-edit-type"><option value="income">Income</option><option value="expense">Expense</option><option value="borrow">Borrow</option><option value="repay">Repay</option></select>`;
    typeCell.querySelector('select').value = typeVal;
    methodCell.innerHTML = `<select class="form-select form-select-sm fin-edit-method"><option value="cash">Cash</option><option value="online">Online</option></select>`;
    methodCell.querySelector('select').value = methodVal;
    amountCell.innerHTML = `<input type="number" step="0.01" min="0" class="form-control form-control-sm fin-edit-amount" value="${amountVal}">`;
    noteCell.innerHTML = `<input type="text" class="form-control form-control-sm fin-edit-note" value="${noteVal}">`;
  }

  function exitEdit(tr, revert){
    // Toggle buttons
    tr.querySelector('.fin-edit').classList.remove('d-none');
    tr.querySelector('.fin-save').classList.add('d-none');
    tr.querySelector('.fin-cancel').classList.add('d-none');
    if(!revert) return; // if not revert, caller will re-render values
    const dateCell=tr.querySelector('.fin-date');
    const typeCell=tr.querySelector('.fin-type');
    const methodCell=tr.querySelector('.fin-method');
    const amountCell=tr.querySelector('.fin-amount');
    const noteCell=tr.querySelector('.fin-note');
    const dateVal=dateCell.querySelector('.fin-edit-date').value;
    const typeVal=typeCell.querySelector('.fin-edit-type').value;
    const methodVal=methodCell.querySelector('.fin-edit-method').value;
    const amountVal=Number(amountCell.querySelector('.fin-edit-amount').value||0);
    const noteVal=noteCell.querySelector('.fin-edit-note').value;
    dateCell.textContent = dateVal;
    typeCell.setAttribute('data-val', typeVal);
    typeCell.innerHTML = `<span class="badge bg-secondary text-uppercase small">${typeVal}</span>`;
    methodCell.setAttribute('data-val', methodVal);
    methodCell.textContent = methodVal;
    amountCell.setAttribute('data-val', amountVal.toFixed(2));
    amountCell.textContent = amountVal.toFixed(2);
    noteCell.textContent = noteVal;
  }

  function saveEdit(tr){
    const id = tr.getAttribute('data-id');
    const dateVal = tr.querySelector('.fin-edit-date').value;
    const typeVal = tr.querySelector('.fin-edit-type').value;
    const methodVal = tr.querySelector('.fin-edit-method').value;
    const amountVal = tr.querySelector('.fin-edit-amount').value;
    const noteVal = tr.querySelector('.fin-edit-note').value;
    const fd = new FormData();
    fd.set('entry_date', dateVal);
    fd.set('type', typeVal);
    fd.set('method', methodVal);
    fd.set('amount', amountVal);
    fd.set('note', noteVal);
    fetch(BASE+'expenses/financeUpdate/'+id, {method:'POST', body: fd})
      .then(r=>r.json()).then(j=>{
        if(j && j.ok){
          // Re-render with updated values
          exitEdit(tr, true);
          refreshStatsDebounced();
        } else {
          alert('Update failed');
        }
      });
  }

  // Stats + Chart
  const fromInput=document.getElementById('stat-from');
  const toInput=document.getElementById('stat-to');
  const applyBtn=document.getElementById('stat-apply');
  const loader=document.getElementById('stats-loading');
  function defDates(){ const today=new Date(); const past=new Date(Date.now()-6*24*3600*1000); fromInput.value=past.toISOString().slice(0,10); toInput.value=today.toISOString().slice(0,10);} defDates();
  let chart; let statsTimer;
  function refreshStatsDebounced(){ clearTimeout(statsTimer); statsTimer=setTimeout(loadStats,300); }
  applyBtn.addEventListener('click', e=>{e.preventDefault(); loadStats();});

  function loadStats(){
    if(loader) loader.classList.remove('d-none');
    fetch(`${BASE}expenses/financeStats?from=${encodeURIComponent(fromInput.value)}&to=${encodeURIComponent(toInput.value)}`)
      .then(r=>r.json())
      .then(j=>{
        if(loader) loader.classList.add('d-none');
        if(!j.ok) return;
        const s=j.summary;
        document.getElementById('sum-income').textContent=s.income.toFixed(2);
        document.getElementById('sum-expense').textContent=s.expense.toFixed(2);
        document.getElementById('sum-borrow').textContent=s.borrow.toFixed(2);
        document.getElementById('sum-repay').textContent=s.repay.toFixed(2);
        document.getElementById('sum-net').textContent=s.net.toFixed(2);
        // Color net card border depending on positive/negative
        const netCard=document.getElementById('net-card');
        netCard.classList.remove('border-success','border-danger');
        netCard.classList.add(s.net>=0?'border-success':'border-danger');
        const rows=j.daily;
        const chartEmpty=document.getElementById('chart-empty');
        if(!rows.length){ chartEmpty.classList.remove('d-none'); if(chart){chart.destroy(); chart=null;} return; } else { chartEmpty.classList.add('d-none'); }
        const labels=rows.map(r=>r.day);
        const income=rows.map(r=>r.income);
        const expense=rows.map(r=>r.expense);
        const netCumulative=[]; let running=0; rows.forEach(r=>{ running += (r.income - r.expense); netCumulative.push(running); });
        const ctx=document.getElementById('fin-chart').getContext('2d');
        if(typeof Chart==='undefined'){ loadChartLib(()=> buildChart(ctx,labels,income,expense,netCumulative)); }
        else { buildChart(ctx,labels,income,expense,netCumulative); }
      });
  }

  function buildChart(ctx, labels, income, expense, net){
    if(chart) chart.destroy();
    chart=new Chart(ctx,{
      type:'bar',
      data:{labels, datasets:[
        {label:'Income', data:income, backgroundColor:'rgba(40,167,69,0.7)'},
        {label:'Expense', data:expense, backgroundColor:'rgba(220,53,69,0.6)'},
        {label:'Net Cumulative', type:'line', data:net, borderColor:'#0d6efd', backgroundColor:'rgba(13,110,253,0.2)', yAxisID:'y'}
      ]},
      options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}}, scales:{y:{beginAtZero:true}}}
    });
  }

  function loadChartLib(cb){
    const script=document.createElement('script');
    script.src='https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
    script.onload=cb; document.head.appendChild(script);
  }

  loadStats();
})();
</script>