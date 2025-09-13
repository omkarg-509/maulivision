<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>All Customers</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Customer Directory</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Date</th>
                  <th>Call</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $rows = array_values(array_filter($data['customers'] ?? [], function($r){
                    return !empty($r['mobile']);
                  }));
                ?>
                <?php if (!empty($rows)): ?>
                  <?php $i = 1; foreach ($rows as $c): ?>
                    <tr>
                      <td><?= $i++ ?></td>
                      <td><?= htmlspecialchars($c['name']) ?></td>
                      <td>
                        <?php if (!empty($c['mobile'])): ?>
                          <a href="tel:<?= htmlspecialchars($c['mobile']) ?>"><?= htmlspecialchars($c['mobile']) ?></a>
                        <?php else: ?>
                          <span class="text-muted">N/A</span>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($c['created_at'] ?? 'now'))) ?></td>
                      <td>
                        <?php if (!empty($c['mobile'])): ?>
                          <a class="btn btn-sm btn-success" href="tel:<?= htmlspecialchars($c['mobile']) ?>">
                            <i class="fa fa-phone"></i> Call
                          </a>
                        <?php else: ?>
                          <button class="btn btn-sm btn-secondary" disabled><i class="fa fa-phone"></i> Call</button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center">No customers found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>