<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-5 pt-4">
  <section class="section">
    <div class="section-header d-flex align-items-center flex-wrap gap-2">
      <h1 class="mb-0">Tiffin Manager <span class="badge bg-info ms-2">v1.0</span></h1>
      <small class="text-muted">Track tiffin quantity, payment & amount</small>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <form id="tiffin-form" class="row g-2 align-items-end" autocomplete="off">
          <div class="col-md-2">
            <label class="form-label small mb-1">Date</label>
            <input type="date" name="entry_date" id="tf-date" class="form-control" required>
          </div>
            <div class="col-md-2">
            <label class="form-label small mb-1">Time</label>
            <select name="tiffin_time" id="tf-time" class="form-select">
              <option value="morning">Morning</option>
              <option value="lunch">Lunch</option>
              <option value="evening">Evening</option>
              <option value="dinner">Dinner</option>
              <option value="night">Night</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label small mb-1">Quantity</label>
            <input type="number" min="1" name="quantity" id="tf-qty" class="form-control" value="2" required>
          </div>
          <div class="col-md-2">
            <label class="form-label small mb-1">Rate</label>
            <input type="number" step="0.01" min="0" name="rate" id="tf-rate" class="form-control" placeholder="0.00"  value="70" required>
          </div>
          <div class="col-md-2 form-check mt-4 ps-4">
            <input class="form-check-input" type="checkbox" id="tf-paid" name="paid"> <label class="form-check-label" for="tf-paid">Paid</label>
          </div>
          <div class="col-md-2 d-grid">
            <button class="btn btn-primary mt-4" type="submit">Add</button>
          </div>
        </form>
      </div>
    </div>

    <div class="row g-3 mb-3" id="tf-summary">
      <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body p-3"><div class="small text-muted">Total Qty</div><div class="fs-5" id="sum-qty">0</div></div></div></div>
      <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body p-3"><div class="small text-muted">Total Amount</div><div class="fs-5" id="sum-total">0.00</div></div></div></div>
      <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body p-3"><div class="small text-muted">Paid Amount</div><div class="fs-5" id="sum-paid">0.00</div></div></div></div>
      <div class="col-sm-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body p-3"><div class="small text-muted">Unpaid Amount</div><div class="fs-5" id="sum-unpaid">0.00</div></div></div></div>
      <div class="col-12">
        <div class="card border-start border-4" id="due-card"><div class="card-body p-3 d-flex justify-content-between align-items-center">
          <div><span class="small text-muted">Due (Unpaid)</span><div class="fs-5" id="sum-due">0.00</div></div>
          <div class="d-flex gap-2"><input type="date" id="stat-from" class="form-control form-control-sm"><input type="date" id="stat-to" class="form-control form-control-sm"><button id="stat-apply" class="btn btn-sm btn-outline-primary">Apply</button><div id="stats-loading" class="spinner-border spinner-border-sm text-primary d-none" role="status"></div></div>
        </div></div>
      </div>
    </div>

    <div class="card mb-4"><div class="card-header d-flex justify-content-between align-items-center"><h4 class="mb-0">Entries</h4></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0" id="tf-table">
            <thead class="table-light"><tr><th>Date</th><th>Time</th><th class="text-end">Qty</th><th class="text-end">Rate</th><th class="text-end">Total</th><th>Paid</th><th></th></tr></thead>
            <tbody>
              <?php foreach($entries as $e): $total = (float)$e['quantity'] * (float)$e['rate']; ?>
              <tr data-id="<?= (int)$e['id'] ?>">
                <td><?= htmlspecialchars($e['entry_date']) ?></td>
                <td><?= htmlspecialchars($e['tiffin_time']) ?></td>
                <td class="text-end"><?= (int)$e['quantity'] ?></td>
                <td class="text-end"><?= number_format((float)$e['rate'],2) ?></td>
                <td class="text-end"><?= number_format($total,2) ?></td>
                <td><input type="checkbox" class="form-check-input tf-paid-toggle" <?= $e['paid']? 'checked':'' ?>></td>
                <td><button class="btn btn-sm btn-outline-danger tf-del"><i class="fas fa-trash"></i></button></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if(empty($entries)): ?><div class="p-3 text-muted small" id="tf-empty">No entries yet.</div><?php endif; ?>
        </div>
      </div>
    </div>

    <div class="card"><div class="card-header"><h4 class="mb-0">Daily Chart</h4></div>
      <div class="card-body" style="height:300px;">
        <canvas id="tf-chart"></canvas>
        <div id="chart-empty" class="text-muted small mt-2 d-none">No data.</div>
      </div>
    </div>

  </section>
</div>

<script>
(function(){
  const BASE='<?= BASE_URL ?>';
  const form=document.getElementById('tiffin-form');
  const tableBody=document.querySelector('#tf-table tbody');
  const emptyDiv=document.getElementById('tf-empty');
  const dateInput=document.getElementById('tf-date');
  dateInput.value=new Date().toISOString().slice(0,10);

  function toggleEmpty(){ if(tableBody.children.length===0){ if(emptyDiv) emptyDiv.classList.remove('d-none'); } else if(emptyDiv) emptyDiv.classList.add('d-none'); }

  form.addEventListener('submit', e=>{
    e.preventDefault();
    const fd=new FormData(form);
    fetch(BASE+'own/tiffinAdd',{method:'POST', body:fd}).then(r=>r.json()).then(j=>{
      if(!j.ok) return;
      const tr=document.createElement('tr');
      tr.setAttribute('data-id', j.id);
      tr.innerHTML=`<td>${j.entry_date}</td><td>${j.tiffin_time}</td><td class='text-end'>${j.quantity}</td><td class='text-end'>${Number(j.rate).toFixed(2)}</td><td class='text-end'>${(j.total).toFixed(2)}</td><td><input type='checkbox' class='form-check-input tf-paid-toggle' ${j.paid? 'checked':''}></td><td><button class='btn btn-sm btn-outline-danger tf-del'><i class='fas fa-trash'></i></button></td>`;
      tableBody.prepend(tr); toggleEmpty(); form.reset(); dateInput.value=new Date().toISOString().slice(0,10); refreshStatsDebounced();
    });
  });

  tableBody.addEventListener('click', e=>{
    const tr=e.target.closest('tr'); if(!tr) return; const id=tr.getAttribute('data-id');
    if(e.target.closest('.tf-del')){
      fetch(BASE+'own/tiffinDelete/'+id).then(r=>r.json()).then(j=>{ if(j.ok){ tr.remove(); toggleEmpty(); refreshStatsDebounced(); }});
    } else if(e.target.classList.contains('tf-paid-toggle')){
      fetch(BASE+'own/tiffinTogglePaid/'+id).then(r=>r.json()).then(j=>{ if(j.ok){ refreshStatsDebounced(); } else { e.target.checked=!e.target.checked; } });
    }
  });

  // Stats & Chart
  const fromInput=document.getElementById('stat-from');
  const toInput=document.getElementById('stat-to');
  const applyBtn=document.getElementById('stat-apply');
  const loader=document.getElementById('stats-loading');
  function defDates(){ const today=new Date(); const past=new Date(Date.now()-6*24*3600*1000); fromInput.value=past.toISOString().slice(0,10); toInput.value=today.toISOString().slice(0,10);} defDates();
  let chart; let statsTimer; function refreshStatsDebounced(){ clearTimeout(statsTimer); statsTimer=setTimeout(loadStats,300);} applyBtn.addEventListener('click', e=>{e.preventDefault(); loadStats();});

  function loadStats(){ if(loader) loader.classList.remove('d-none'); fetch(`${BASE}own/tiffinStats?from=${encodeURIComponent(fromInput.value)}&to=${encodeURIComponent(toInput.value)}`)
    .then(r=>r.json()).then(j=>{ if(loader) loader.classList.add('d-none'); if(!j.ok) return; const s=j.summary; document.getElementById('sum-qty').textContent=s.qty; document.getElementById('sum-total').textContent=s.total.toFixed(2); document.getElementById('sum-paid').textContent=s.paid.toFixed(2); document.getElementById('sum-unpaid').textContent=s.unpaid.toFixed(2); document.getElementById('sum-due').textContent=s.unpaid.toFixed(2); const rows=j.daily; const chartEmpty=document.getElementById('chart-empty'); if(!rows.length){ chartEmpty.classList.remove('d-none'); if(chart){chart.destroy(); chart=null;} return;} else { chartEmpty.classList.add('d-none'); } const labels=rows.map(r=>r.day); const qty=rows.map(r=>r.total_qty); const totalAmt=rows.map(r=>r.total_amount); const paidAmt=rows.map(r=>r.paid_amount); const unpaidAmt=rows.map(r=>r.unpaid_amount); const cumulative=[]; let run=0; rows.forEach(r=>{ run+=r.total_amount; cumulative.push(run); }); const ctx=document.getElementById('tf-chart').getContext('2d'); if(typeof Chart==='undefined'){ loadChartLib(()=> buildChart(ctx,labels,qty,totalAmt,paidAmt,unpaidAmt,cumulative)); } else { buildChart(ctx,labels,qty,totalAmt,paidAmt,unpaidAmt,cumulative); } }); }

  function buildChart(ctx, labels, qty, totalAmt, paidAmt, unpaidAmt, cumulative){
    if(chart) chart.destroy();
    chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          { label: 'Qty', data: qty, backgroundColor: 'rgba(13,110,253,0.6)' },
          { label: 'Paid Amt', data: paidAmt, backgroundColor: 'rgba(40,167,69,0.7)' },
            { label: 'Unpaid Amt', data: unpaidAmt, backgroundColor: 'rgba(220,53,69,0.6)' },
          { label: 'Cumulative Total', type: 'line', data: cumulative, borderColor: '#6f42c1', backgroundColor: 'rgba(111,66,193,0.15)', yAxisID: 'y' }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
      }
    });
  }

  function loadChartLib(cb){ const script=document.createElement('script'); script.src='https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js'; script.onload=cb; document.head.appendChild(script); }
  loadStats();
})();
</script>