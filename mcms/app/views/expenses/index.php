<?php require_once '../app/views/layouts/sidebar.php';?>
<div class="main-content">
  <div class="loader"></div>
  <div id="app">
      
  <section class="section">
     
          <div class="section-body">
            <div class="row">
             
              <div class="col-12 col-md-12 col-lg-12">
             
                <div class="">
                  <div class="col-12 text-center mb-4">
                    <h4>Add Customer</h4>
                  </div>
                <form id="customerForm" method="POST" action="<?= BASE_URL ?>customers/store">
                  <div class="">
                    <!-- vid is set server-side from session; no client-provided vid -->
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label-lg text-center">Customer Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="name" placeholder="Enter Name"  >
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label-lg text-center">Customer Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="mobile" placeholder="Enter Number">
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label-lg text-center">In Time</label>
                      <div class="col-sm-9">
                      <input type="time" class="form-control form-control-lg" name="in_time"  required>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label-lg text-center">Amount</label>
                      <div class="col-sm-9">
                        <input type="number" step="0.01" class="form-control form-control-lg" name="amount"  placeholder="Amount Paid" required>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label-lg text-center">Staff</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="staff" placeholder="Staff"  required>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label-lg text-center">Online Cash</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="payment_method"  placeholder="Online Cash">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-9 offset-lg-3 text-center mb-4">
                      <button type="submit" class="btn btn-primary btn-lg px-5" >Submit</button>
                      </div>
                    </div>
                  </div>
                </form>
                

                </div>
                   </div>
              </div>
              </section>       
    </div>
    
     

  </div>

  </script>