<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-4 pt-2">
  <section class="section">
    <div class="section-header"><h1 class="mb-0">Create Bill</h1></div>
    <div class="card">
      <div class="card-body">
        <div class="mb-3">
          <strong>Customer:</strong>
          <?= htmlspecialchars($customer['customer_name']) ?>
          <?php if (!empty($customer['phone_number'])): ?>
            <span class="text-muted">(<?= htmlspecialchars($customer['phone_number']) ?>)</span>
          <?php endif; ?>
        </div>
        <form method="POST" action="<?= BASE_URL ?>bill/store" id="billForm">
          <input type="hidden" name="customer_id" value="<?= (int)$customer['id'] ?>">
          <div id="items">
            <div class="row g-2 align-items-end item-row">
              <div class="col-md-4">
                <label class="form-label small">Item</label>
                <input type="text" name="items[0][name]" class="form-control" required>
              </div>
              <div class="col-md-2">
                <label class="form-label small">Qty</label>
                <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" required>
              </div>
              <div class="col-md-2">
                <label class="form-label small">Weight (kg)</label>
                <input type="number" step="0.01" name="items[0][weight]" class="form-control">
              </div>
              <div class="col-md-2">
                <label class="form-label small">Price</label>
                <input type="number" step="0.01" name="items[0][price]" class="form-control" required>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-outline-secondary btn-sm add-row">+</button>
              </div>
            </div>
          </div>
          <div class="mt-3">
            <button class="btn btn-primary" type="submit">Save Bill</button>
            <a href="<?= BASE_URL ?>laundry/index" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
<script>
(function(){
  let idx = 1;
  document.addEventListener('click', function(e){
    if(e.target.classList.contains('add-row')){
      e.preventDefault();
      const container = document.getElementById('items');
      const tpl = `
        <div class="row g-2 align-items-end item-row mt-2">
          <div class="col-md-4">
            <input type="text" name="items[${idx}][name]" class="form-control" placeholder="Item" required>
          </div>
          <div class="col-md-2">
            <input type="number" name="items[${idx}][quantity]" class="form-control" value="1" min="1" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="items[${idx}][weight]" class="form-control" placeholder="kg">
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="items[${idx}][price]" class="form-control" placeholder="Price" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger btn-sm remove-row">-</button>
          </div>
        </div>`;
      container.insertAdjacentHTML('beforeend', tpl);
      idx++;
    }
    if(e.target.classList.contains('remove-row')){
      e.preventDefault();
      const row = e.target.closest('.item-row');
      row.parentNode.removeChild(row);
    }
  });
})();
</script>
