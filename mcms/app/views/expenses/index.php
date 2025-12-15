<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-5 pt-4">
  <section class="section">
    <div class="section-header d-flex align-items-center flex-wrap gap-2">
      <h1 class="mb-0">Finance Manager</h1>
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

