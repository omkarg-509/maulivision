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
                  <form id="customerForm" method="POST">
                    <div class="card-body">
                       <div class="form-group row mb-3">
                        <input type="hidden" name="vid" value="<?php echo $_SESSION['vendor']['id']; ?>">
                        <label class="col-sm-3 col-form-label text-center">Bill ID</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" required name="bill_id" placeholder="Enter bill ID">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        
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
          <script>
          // $(document).ready(function() {
          //   $('#customerForm').off('submit').on('submit', function(e) {
          //     e.preventDefault();
          //     $('.loader').show();
          //     var form = $(this);
          //     var formData = form.serialize();

          //     $.ajax({
          //       url: '/public/customer/store',
          //       type: 'POST',
          //       data: formData,
          //       dataType: 'json',
          //       success: function(response) {
          //         $('.loader').hide();
          //         if (response.success) {
          //           toastr.success(response.message || 'Customer added successfully.');
          //           form[0].reset();
          //           // Optionally reload customer table if needed
          //           location.reload(); // Or call a function to reload the customer table via AJAX
          //         } else {
          //           toastr.error(response.message || 'Failed to add customer.');
          //         }
          //       },
          //       error: function(xhr) {
          //         $('.loader').hide();
          //         toastr.error('An error occurred. Please try again.');
          //       }
          //     });
          //   });
          // });
          </script>
         
  </div>

            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Customers Details</h4>
                </div>
                <div class="card-body" id="entries-table-container">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">ग्राहक</th>
                        <th scope="col">क्रमांक</th>

                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody id="entries-table-body">
                      <!-- Table rows will be loaded here by loadEntriesTable() via AJAX -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </section>       
    </div>
    
     

  </div>
 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
            function loadEntriesTable() {
              $.ajax({
                url: '/public/customer/list',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                  if (response.success && Array.isArray(response.data)) {
                    $('#entries-table-body').empty();
                    response.data.forEach(function(cust, idx) {
                      $('#entries-table-body').append(
                        `<tr>
                           <td>${idx + 1}</td>
                           <td>${cust.bill_id}</td>
                             <td>${cust.name}</td>
                             <td>${cust.mobile}</td>
                             <td>
                             <button class="btn btn-danger btn-sm delete-cust" data-id="${cust.id}">Delete</button>
                             </td>
                         </tr>`
                      );
                    });
                  } else {
                    $('#entries-table-body').html('<tr><td colspan="5" class="text-center">No entries found.</td></tr>');
                  }
                },
                error: function() {
                  $('#entries-table-body').html('<tr><td colspan="5" class="text-center">Failed to load entries.</td></tr>');
                }
              });
            }

            // Auto-load entries table on page load
            $(document).ready(function() {
              loadEntriesTable();
            });
            // Handle delete button click with AJAX
            $(document).on('click', '.delete-cust', function() {
              var entryId = $(this).data('id');
              if (confirm('Are you sure you want to delete this entry?')) {
              $.ajax({
                url: '/public/customer/delete/' + entryId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                if (response.success) {
                  toastr.success(response.message || 'Customer deleted successfully.');
                  loadEntriesTable();
                } else {
                  toastr.error(response.message || 'Failed to delete Customer.');
                }
                },
                error: function() {
                toastr.error('An error occurred. Please try again.');
                }
              });
              }
            });
            // Update form submit to reload table via AJAX
            $(document).ready(function() {
              
              $('#customerForm').off('submit').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                  url: '/public/customer/store',
                  type: 'POST',
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                    $('.loader').hide();
                    if (response.success) {
                      toastr.success(response.message || 'Customers added successfully.');
                      loadEntriesTable();
                      form[0].reset();
                      $('#cid').val('');
                    } else {
                      toastr.error(response.message || 'Failed to add customers.');
                    }
                  },
                  error: function(xhr) {
                    $('.loader').hide();
                    alert('An error occurred. Please try again.');
                  }
                });
              });
              
            });
            </script>