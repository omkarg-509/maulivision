<?php require_once '../app/views/layouts/sidebar.php'; ?>
<div class="main-content mt-4 pt-2">
  <section class="section">
    <div class="section-header"><h1 class="mb-0">Bill #<?= (int)$bill['id'] ?></h1></div>
    <div class="card">
      <div class="card-body">
        <div class="mb-3">
          <strong>Customer:</strong> <?= htmlspecialchars($bill['customer_name']) ?>
          <?php if (!empty($bill['phone_number'])): ?>
            <span class="text-muted">(<?= htmlspecialchars($bill['phone_number']) ?>)</span>
          <?php endif; ?>
        </div>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead class="table-light"><tr><th>#</th><th>Item</th><th>Qty</th><th>Weight</th><th>Price</th><th>Line Total</th></tr></thead>
            <tbody>
              <?php $i=1; $sum=0; foreach(($items??[]) as $it): $line = ($it['quantity'] * $it['price']); $sum += $line; ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($it['item_name']) ?></td>
                  <td><?= (int)$it['quantity'] ?></td>
                  <td><?= $it['weight'] !== null ? htmlspecialchars($it['weight']) : '-' ?></td>
                  <td><?= number_format((float)$it['price'], 2) ?></td>
                  <td><?= number_format($line, 2) ?></td>
                </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="5" class="text-end"><strong>Total</strong></td>
                <td><strong><?= number_format($sum, 2) ?></strong></td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php
          // Build WhatsApp message
          $lines = [];
          $lines[] = 'Bill #' . (int)$bill['id'];
          $lines[] = 'Customer: ' . ($bill['customer_name'] ?? '');
          if (!empty($bill['phone_number'])) { $lines[] = 'Phone: ' . $bill['phone_number']; }
          $lines[] = '';
          $lines[] = 'Items:';
          foreach (($items??[]) as $it) {
            $nm = $it['item_name'];
            $qt = (int)$it['quantity'];
            $pr = number_format((float)$it['price'],2);
            $ln = number_format(((float)$it['price'] * (int)$it['quantity']),2);
            $lines[] = "- {$nm} x{$qt} @ {$pr} = {$ln}";
          }
          $lines[] = '';
          $lines[] = 'Total: ' . number_format($sum,2);
          $waText = urlencode(implode("\n", $lines));
          $waNumber = preg_replace('/\D+/', '', (string)($bill['phone_number'] ?? ''));
          $waHref = $waNumber ? ("https://wa.me/" . $waNumber . "?text=" . $waText) : '';
        ?>
        <div class="mt-3 d-flex gap-2">
          <a href="<?= BASE_URL ?>bill/list" class="btn btn-light">Back to Bills</a>
          <button type="button" class="btn btn-outline-secondary" onclick="window.print()">Print</button>
          <?php if ($waHref): ?>
            <a class="btn btn-success" target="_blank" rel="noopener" href="<?= htmlspecialchars($waHref) ?>">Send via WhatsApp</a>
          <?php else: ?>
            <button type="button" class="btn btn-success" disabled title="No phone number">Send via WhatsApp</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</div>
