<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-4 pt-2">
  <section class="section">
    <div class="section-header"><h1 class="mb-0">Laundry Manager</h1></div>

    <div class="card mb-4">
      <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>laundry/store" class="row g-2" autocomplete="off">
          <div class="col-md-3">
            <label class="form-label small">Customer Name</label>
            <input type="text" name="customer_name" class="form-control" placeholder="Name">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" placeholder="Phone">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label small">End Date</label>
            <input type="date" name="end_date" class="form-control" required>
          </div>
          <div class="col-12 d-grid d-md-block">
            <button class="btn btn-primary mt-2" type="submit">Add</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h4 class="mb-0">Customers</h4></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead class="table-light"><tr><th>#</th><th>Name</th><th>Phone</th><th>Start</th><th>End</th><th>Receipt</th><th>Bill</th></tr></thead>
            <tbody>
              <?php $i=1; foreach(($customers ?? []) as $c): ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($c['customer_name']) ?></td>
                  <td><?= $c['phone_number'] ? htmlspecialchars($c['phone_number']) : '-' ?></td>
                  <td><?= htmlspecialchars($c['start_date'] ?? '') ?></td>
                  <td><?= htmlspecialchars($c['end_date'] ?? '') ?></td>
                  <td><a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>laundry/receipt/<?= (int)$c['id'] ?>">View</a></td>
                  <td><a class="btn btn-sm btn-primary" href="<?= BASE_URL ?>bill/create/<?= (int)$c['id'] ?>">Create Bill</a></td>
                </tr>
              <?php endforeach; ?>
              <?php if(empty($customers)): ?>
                <tr><td colspan="7" class="text-center text-muted small p-3">No customers yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
