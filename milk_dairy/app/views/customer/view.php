<?php if (!empty($data['customer'])): ?>
    <h2><?= $data['customer']['name'] ?></h2>
    <p>Mobile: <?= $data['customer']['mobile'] ?></p>
    <p>Address: <?= $data['customer']['address'] ?></p>
<?php else: ?>
    <p>Customer data not found.</p>
<?php endif; ?>
