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
                  <form method="POST" action="/public/customer/store">
                    <div class="card-body">
                      <div class="form-group row mb-3">
                        <input type="hidden" name="vid" value="<?php echo $_SESSION['vendor']['id']; ?>">
                        <label class="col-sm-3 col-form-label text-center">Full Name</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" required name="name" placeholder="Enter full name">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-center">Mobile Number</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" required name="mobile" placeholder="Enter mobile number">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-center">Address</label>
                        <div class="col-sm-9">
                          <textarea class="form-control" required name="address" rows="2" placeholder="Enter address"></textarea>
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
    <div class="card-body" style="overflow-x:auto;">
      <table class="table table-sm">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Full Name</th>
            <th scope="col">Mobile Number</th>
            <!-- <th scope="col">Address</th> -->
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data['customers'])): ?>
            <?php foreach ($data['customers'] as $index => $cust): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($cust['name']) ?></td>
                <td><?= htmlspecialchars($cust['mobile']) ?></td>
                <!-- <td><?//= htmlspecialchars(ucfirst($cust['address'])) ?></td> -->
                <td>
                  <a href="/public/customer/delete/<?= urlencode($cust['id']) ?>" 
                     onclick="return confirm('Are you sure you want to delete this customer?');" 
                     title="Delete" class="btn btn-danger btn-sm"> 
                    <i class="fa fa-trash"></i>
                  </a>
                  <a href="/public/customer/show/<?= urlencode($cust['id']) ?>" 
                     title="View" class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i>
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
  </div>


        </section>       
    </div>
    
     

  </div>
 


  