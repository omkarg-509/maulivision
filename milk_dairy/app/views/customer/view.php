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
  <div class="card">
    <div class="card-header">
      <h4>Milk Entries</h4>
    </div>
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
          <?php $milk_entries = $customerModel->getDailyEntries($data['customer']['vid'], $data['customer']['id']); ?>
          <?php if (!empty($data['milk_entries']) && is_array($data['milk_entries'])): ?>
            <?php foreach ($data['milk_entries'] as $index => $entry): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($entry['date']) ?></td>
                <td><?= htmlspecialchars($entry['milk_type']) ?></td>
                <td><?= htmlspecialchars($entry['milk_liter']) ?></td>
                <td>
                  <a href="/public/milk_entry/delete/<?= urlencode($entry['id']) ?>"
                     onclick="return confirm('Are you sure you want to delete this entry?');"
                     title="Delete" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i>
                  </a>
                  <a href="/public/milk_entry/show/<?= urlencode($entry['id']) ?>"
                     title="View" class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center">No milk entries found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  </div>


        </section>       
    </div>
    
     

  </div>
 


  