<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-4 pt-2">
  <section class="section">
    <div class="section-header"><h1 class="mb-0">Bills</h1></div>
    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead class="table-light"><tr><th>#</th><th>Customer</th><th>Phone</th><th>Total</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
              <?php $i=1; foreach(($bills??[]) as $b): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($b['customer_name']) ?></td>
                <td><?= $b['phone_number'] ? htmlspecialchars($b['phone_number']) : '-' ?></td>
                <td><?= number_format((float)$b['total_amount'], 2) ?></td>
                <td><?= htmlspecialchars($b['created_at']) ?></td>
                <td><a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>bill/show/<?= (int)$b['id'] ?>">View</a></td>
              </tr>
              <?php endforeach; ?>
              <?php if(empty($bills)): ?>
                <tr><td colspan="6" class="text-center text-muted small p-3">No bills yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
