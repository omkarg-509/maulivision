<?php require_once '../app/views/layouts/sidebar.php';?>



<div class="main-content">
  <div class="loader"></div>
  <div id="app">

       
  <section class="section">
     
          <div class="section-body">
            <div class="row">
             
              <div class="col-12 col-md-12 col-lg-12">
             
                <div class="card">
                  <div class="card-header">
                    <h4> Customer Details</h4>
                  </div>
                  <?php if (!empty($data['customer'])): ?>
                 <form method="POST" action="/public/customer/update/<?= urlencode($data['customer']['id']); ?>">
                    <div class="card-body">
                      <div class="form-group row mb-3">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($data['customer']['vid']); ?>">
                        <label class="col-sm-3 col-form-label text-center">Full Name</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" required name="name" value="<?= htmlspecialchars($data['customer']['name']); ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-center">Mobile Number</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" required name="mobile" value="<?= htmlspecialchars($data['customer']['mobile']); ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-center">Address</label>
                        <div class="col-sm-9">
                          <textarea class="form-control" required name="address" rows="2"><?= htmlspecialchars($data['customer']['address']); ?></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3 text-center">
                          <button type="submit" class="btn btn-primary px-4">EDIT</button>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php else: ?>
    <p>Customer data not found.</p>
<?php endif; ?>
                </div>
                   </div>
              </div>
            </div>
         <div class="col-lg-12 col-md-12 col-12 col-sm-12">
<div class="card-body" style="overflow-x:auto;">
  <table class="table table-sm">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Milk Type</th>
        <th scope="col">Milk Liter</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $totalMilkLiter = 0;
      $days = [];
      if (!empty($data['milk_entries']) && is_array($data['milk_entries'])):
        foreach ($data['milk_entries'] as $index => $entry):
          $totalMilkLiter += floatval($entry['milkliter']);
          $date = date('Y-m-d', strtotime($entry['created_at']));
          $days[$date] = true;
      ?>
          <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($entry['created_at']) ?></td>
        <td><?= htmlspecialchars($entry['milktype']) ?></td>
        <td><?= htmlspecialchars($entry['milkliter']) ?></td>
        <td>
          <?php if (isset($entry['id'])): ?>
          <a href="/public/dailyentry/delete/<?= urlencode($entry['id']) ?>"
         onclick="return confirm('Are you sure you want to delete this entry?');"
         title="Delete" class="btn btn-danger btn-sm">
        <i class="fa fa-trash"></i>
          </a>
          <?php endif; ?>
        </td>
          </tr>
      <?php
        endforeach;
        $numberOfDays = count($days);
      ?>
        
        <td colspan="3" class="text-right font-weight-bold">Total Milk Liter</td>
        <td class="font-weight-bold"><?= $totalMilkLiter ?></td>
         <td colspan="3" class="text-right font-weight-bold">Number of Days</td>
        <td class="font-weight-bold"><?= $numberOfDays ?></td>
      </tr>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No milk entries found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

  </div>


        </section>       
    </div>
    
     

  </div>
 


  