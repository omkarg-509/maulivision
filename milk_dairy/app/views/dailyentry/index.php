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
                    <h4>Add Customer</h4>
                  </div>
                <form method="POST" action="/public/dailyentry/store">
                  <div class="card-body">
                    <input type="hidden" class="form-control" name="vid" value="<?php echo htmlspecialchars($_SESSION['vendor']['id'] ?? ''); ?>" readonly>

                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Customer Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="customer_name" placeholder="Enter Name" required>
                      </div>
                    </div>

                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Customer Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="customer_number" placeholder="Enter Number" required>
                      </div>
                    </div>

                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">In Time</label>
                      <div class="col-sm-9">
                        <input type="time" class="form-control" name="in_time" required>
                      </div>
                    </div>

                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Amount Paid</label>
                      <div class="col-sm-9">
                        <input type="number" step="0.01" class="form-control" name="amount_paid" placeholder="Amount Paid" required>
                      </div>
                    </div>

                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Amount Unpaid</label>
                      <div class="col-sm-9">
                        <input type="number" step="0.01" class="form-control" name="amount_unpaid" placeholder="Amount Unpaid" required>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-9 offset-sm-3 text-center">
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                      </div>
                    </div>
                  </div>
                </form>
                

                </div>
                   </div>
              </div>
            </div>
         <div class="col-lg-12 col-md-12 col-12 col-sm-12">
  <div class="card">
    <div class="card-header">
      <h4>Customers Details</h4>
    </div>
 <div class="card-body">
                    
                    <table class="table table-sm">
          <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Customer</th>
                          <th scope="col">Type</th>
                          <th scope="col">Liter</th>
                          <th scope="col">Action</th>
                         
                        </tr>
                        
                      </thead>
          <tbody>
          <?php if (!empty($data['dailyEntries'])): ?>
            <?php foreach ($data['dailyEntries'] as $index => $cust): ?>
              <tr>
                <td><?=$index + 1 ?></td>
                <td><?=htmlspecialchars($cust['customer_name']) ?></td>
                <td><?=htmlspecialchars($cust['milktype']) ?></td>
                <td><?=htmlspecialchars(ucfirst($cust['milkliter'])) ?></td>
                <td>
                  <a href="/public/dailyentry/delete/<?= urlencode($cust['id']) ?>" 
                     onclick="return confirm('Are you sure you want to delete this milk Entries?');" 
                     title="Delete">
                    <i class="fa fa-trash text-danger"></i>
                  </a>
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


        </section>       
    </div>
    
     

  </div>
 


  
