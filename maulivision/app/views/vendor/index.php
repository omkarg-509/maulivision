<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-5 pt-4">
  <section class="section">
    <div class="section-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h1 class="mb-0">Vendors</h1>
      <a href="<?= BASE_URL ?>vendor/create" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Add Vendor</a>
    </div>
    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0" id="vendor-table">
            <thead class="table-light"><tr><th>Name</th><th>Phone</th><th>Business</th><th>Role</th><th>Status</th><th></th></tr></thead>
            <tbody>
              <?php foreach($vendors as $v): ?>
                <tr data-id="<?= (int)$v['id'] ?>">
                  <td><?= htmlspecialchars($v['full_name']) ?></td>
                  <td><?= htmlspecialchars($v['phone']) ?></td>
                  <td><?= htmlspecialchars($v['business_name']) ?></td>
                  <td><?= htmlspecialchars($v['business_role']) ?></td>
                  <td><span class="badge <?= $v['status']==='active' ? 'bg-success':'bg-secondary' ?> status-badge"><?= htmlspecialchars($v['status']) ?></span></td>
                  <td><button class="btn btn-sm btn-outline-warning toggle-status">Toggle</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if(empty($vendors)): ?><div class="p-3 text-muted small" id="vendor-empty">No vendors yet.</div><?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
(function(){
  const BASE='<?= BASE_URL ?>';
  const table=document.getElementById('vendor-table');
  table.addEventListener('click', e=>{
    if(e.target.classList.contains('toggle-status')){
      const tr=e.target.closest('tr'); const id=tr.getAttribute('data-id');
      fetch(BASE+'vendor/toggle/'+id).then(r=>r.json()).then(j=>{ if(j.ok){ const badge=tr.querySelector('.status-badge'); const now=badge.textContent.trim()==='active'?'inactive':'active'; badge.textContent=now; badge.className='badge status-badge '+(now==='active'?'bg-success':'bg-secondary'); } });
    }
  });
})();
</script>
