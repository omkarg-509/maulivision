<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Settings</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header"><h4>Language Options</h4></div>
        <div class="card-body">
          <table class="table table-sm">
            <thead><tr><th>ID</th><th>Category</th><th>Options</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              <?php foreach($data['settings'] as $s): ?>
                <tr data-id="<?= $s['id'] ?>" class="<?= $s['status'] ? 'table-success' : '' ?>">
                  <td><?= $s['id'] ?></td>
                  <td><?= htmlspecialchars($s['category']) ?></td>
                  <td><?= htmlspecialchars($s['options']) ?></td>
                  <td><?= $s['status'] ? 'Active' : 'Inactive' ?></td>
                  <td><button class="btn btn-sm btn-primary activateBtn">Activate</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.activateBtn').forEach(function(btn){
      btn.addEventListener('click', function(){
        var tr = this.closest('tr');
        var id = tr.getAttribute('data-id');
        fetch('<?= BASE_URL ?>setting/update', {method:'POST', body: new URLSearchParams({id: id}), credentials:'same-origin'})
        .then(r=>r.json()).then(function(json){
          if(json.success){
            document.querySelectorAll('tbody tr').forEach(function(r){ r.classList.remove('table-success'); r.querySelector('td:nth-child(4)').innerText = 'Inactive'; });
            tr.classList.add('table-success');
            tr.querySelector('td:nth-child(4)').innerText = 'Active';
          } else alert(json.message || 'Failed');
        }).catch(function(){ alert('Network error'); })
      });
    });
  });
</script>
