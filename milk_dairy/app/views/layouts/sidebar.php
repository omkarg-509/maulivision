<?php
$lng = $_SESSION['lng'] ?? 'en';
function t($en, $mr) {
  global $lng;
  return $lng === 'mr' ? $mr : $en;
}
?>
...
<li class="menu-header"><?= t('Main', 'मुख्य') ?></li>
<li class="active">
  <a href="<?=BASE_URL?>dashboard" class="nav-link ">
  <i class="fas fa-home"></i>
  <span><?= t('Dashboard', 'डॅशबोर्ड') ?></span>
  </a>
</li>
<li class="dropdown">
  <a href="#" class="nav-link has-dropdown">
  <i class="fas fa-users"></i>
  <span><?= t('Customers', 'ग्राहक') ?></span>
  </a>
  <ul class="dropdown-menu">
  <li class=""><a class="nav-link" href="<?=BASE_URL?>customer/create"><?= t('Create Customers', 'ग्राहक तयार करा') ?></a></li>
  <li><a class="nav-link" href="<?=BASE_URL?>customer/index"><?= t('Customers Details', 'ग्राहक तपशील') ?></a></li>
  </ul>
</li>
<li>
  <a href="<?=BASE_URL?>dailyentry/history" class="nav-link">
  <i class="fas fa-history"></i>
  <span><?= t('History', 'इतिहास') ?></span>
  </a>
</li>
<li class="">
  <a href="<?=BASE_URL?>auth/logout" class="nav-link">
  <i class="fas fa-sign-out-alt"></i>
  <span><?= t('Logout', 'बाहेर पडा') ?></span>
  </a>
</li>
