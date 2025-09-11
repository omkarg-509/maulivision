<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>
<div class="main-content">
  <div class="loader"></div>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Add Customer</h4>
            </div>
            <form method="POST" action="<?= htmlspecialchars(BASE_URL.'customers/store') ?>">
              <div class="card-body">
                <input type="hidden" class="form-control" name="vid" value="<?= htmlspecialchars($_SESSION['vendor']['id'] ?? '') ?>" readonly>
                <div class="row mb-2 align-items-center">
                  <label class="col-sm-3 col-form-label text-center">Customer Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Enter Name" required>
                  </div>
                </div>
                <div class="row mb-2 align-items-center">
                  <label class="col-sm-3 col-form-label text-center">Customer Number</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="mobile" placeholder="Enter Number">
                  </div>
                </div>
                <div class="row mb-2 align-items-center">
                  <label class="col-sm-3 col-form-label text-center">In Time</label>
                  <div class="col-sm-9">
                    <input type="time" class="form-control form-control-sm" name="in_time" required>
                  </div>
                </div>
                <div class="row mb-2 align-items-center">
                  <label class="col-sm-3 col-form-label text-center">Amount</label>
                  <div class="col-sm-9">
                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" placeholder="Amount Paid" required>
                  </div>
                </div>
                <div class="row mb-2 align-items-center">
                  <label class="col-sm-3 col-form-label text-center">Staff</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="staff" placeholder="Staff" required>
                  </div>
                </div>
                <div class="row mb-2 align-items-center">
                  <label class="col-sm-3 col-form-label text-center">Online / Cash</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="payment_method" placeholder="Payment Method">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-9 offset-sm-3 text-center">
                    <button type="submit" class="btn btn-primary btn-sm px-4">Submit</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Customers Details (Today)</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Customer Name</th>
                      <th scope="col">Number</th>
                      <th scope="col">In Time</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Staff</th>
                      <th scope="col">Payment Method</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    $today = date('Y-m-d');
                    $counter = 1;
                    $hasTodayEntries = false;
                    if (!empty($data['customers'])):
                      foreach ($data['customers'] as $cust):
                        $entryDate = isset($cust['created_at']) ? date('Y-m-d', strtotime($cust['created_at'])) : '';
                        if ($entryDate === $today):
                          $hasTodayEntries = true; ?>
                          <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($cust['name']) ?></td>
                            <td><?= htmlspecialchars($cust['mobile']) ?></td>
                            <td><?= htmlspecialchars(date('h:i A', strtotime($cust['in_time']))) ?></td>
                            <td><?= htmlspecialchars($cust['amount']) ?></td>
                            <td><?= htmlspecialchars($cust['staff']) ?></td>
                            <td><?= htmlspecialchars($cust['payment_method']) ?></td>
                            <td>
                              <a href="<?= htmlspecialchars(BASE_URL.'customers/delete/'.urlencode($cust['id'])) ?>"
                                 onclick="return confirm('Delete this entry?');" title="Delete">
                                <i class="fa fa-trash text-danger"></i>
                              </a>
                            </td>
                          </tr>
                        <?php endif; endforeach; endif; ?>
                        <?php if (!$hasTodayEntries): ?>
                          <tr>
                            <td colspan="8" class="text-center">No customers found for today.</td>
                          </tr>
                        <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php require_once '../layouts/footer.php'; ?>
                        else:

                        ?>

                          <tr>

                            <td colspan="6" class="text-center">No customers found.</td>

