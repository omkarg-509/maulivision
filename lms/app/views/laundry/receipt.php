<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-4 pt-2">
  <section class="section">
    <div class="section-header"><h1 class="mb-0">Laundry Receipt</h1></div>

    <div class="card">
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-md-6">
            <h5 class="mb-1">Customer</h5>
            <div><strong>Name:</strong> <?= htmlspecialchars($customer['customer_name'] ?? 'Guest') ?></div>
            <div><strong>Phone:</strong> <?= !empty($customer['phone_number']) ? htmlspecialchars($customer['phone_number']) : '-' ?></div>
          </div>
          <div class="col-md-6 text-md-end">
            <h5 class="mb-1">Order</h5>
            <div><strong>Start Date:</strong> <?= htmlspecialchars($order['start_date'] ?? '') ?></div>
            <div><strong>End Date:</strong> <?= htmlspecialchars($order['end_date'] ?? '') ?></div>
          </div>
        </div>
        <hr>
        <div class="text-center">
          <a class="btn btn-primary" href="<?= BASE_URL ?>laundry/index">Back</a>
          <button class="btn btn-outline-secondary" onclick="window.print()">Print</button>
        </div>
      </div>
    </div>
  </section>
</div>
