<?php require_once '../app/views/layouts/sidebar.php';?>


<div class="main-content">
  <div class="loader"></div>
  <div id="app">

       
  <section class="section">
     
          </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <form method="get" action="">
              <div class="input-group">
                <input type="date" name="filter_date" class="form-control" value="<?= isset($_GET['filter_date']) ? htmlspecialchars($_GET['filter_date']) : date('Y-m-d') ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">Filter</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <?php
          $filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');
          $hasFilteredEntries = false;
        ?>
                    
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Customers Details</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover">
                      <thead class="thead-light">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Customer name</th>
                          <th scope="col">Number</th>
                          <th scope="col">In time</th>
                          <th scope="col">Amount</th>
                          <th scope="col">Payment Method</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        $today = date('Y-m-d');
                        $hasTodayEntries = false;

                        if (!empty($data['customers'])):
                          foreach ($data['customers'] as $index => $cust):
                            // Check if created_at is today
                            $entryDate = isset($cust['created_at']) ? date('Y-m-d', strtotime($cust['created_at'])) : '';
                            if ($entryDate === $today):
                              $hasTodayEntries = true;
                        ?>
                          <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($cust['name']) ?></td>
                            <td><?= htmlspecialchars($cust['mobile']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($cust['in_time'])) ?></td>
                            <td><?= htmlspecialchars($cust['amount']) ?></td>
                            <td><?= htmlspecialchars($cust['payment_method']) ?></td>
                            <td>
                              <a href="/public/dailyentry/delete/<?= urlencode($cust['id']) ?>"
                                 onclick="return confirm('Are you sure you want to delete this milk Entries?');"
                                 title="Delete">
                                <i class="fa fa-trash text-danger"></i>
                              </a>
                            </td>
                          </tr>
                        <?php
                            endif;
                          endforeach;
                          if (!$hasTodayEntries):
                        ?>
                          <tr>
                            <td colspan="6" class="text-center">No customers found for today.</td>
                          </tr>
                        <?php
                          endif;
                        else:
                        ?>
                          <tr>
                            <td colspan="6" class="text-center">No customers found.</td>
                          </tr>
                        <?php endif; ?>   </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            

        </section>       
    </div>
    
     

  </div>
 


  