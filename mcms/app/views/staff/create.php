<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php require_once '../app/views/layouts/sidebar.php';?>


<div class="main-content">
  <div class="loader"></div>
  <div id="app">

       
  <section class="section">
     
          <div class="section-body">
            <div class="row">
             
              <div class="col-12 col-md-12 col-lg-12">
             
                <div class="card">
                  <div class="card-header col-12">
                    <h4>MILK DAILY ENTRY</h4>
                  </div>
                  <div id="messages"></div>
                <form method="POST" id="customerForm">
                  <div class="card-body">
                    <input type="hidden" class="form-control" name="vid" value="<?php echo htmlspecialchars($_SESSION['vendor']['id'] ?? ''); ?>" readonly>
                      <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Staff Name</label>
                         <div class="col-sm-9">
                      <input type="text" class="form-control" name="name" required>
                    </div>
                    </div>

                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Staff Number</label>
                         <div class="col-sm-9">
                      <input type="text" class="form-control" name="number" required>
                    </div>
                    </div>

                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Staff Address</label>
                         <div class="col-sm-9">
                      <input type="text" class="form-control" name="address" required>
                      <input type="hidden" class="form-control" name="status" value="1    ">
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
                        <th scope="col">Name</th>
                        <th scope="col">Number</th>
                        <th scope="col">Address</th>
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
  </div>
 


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


            <script>
            function loadEntriesTable() {
              $.ajax({
                url: '/public/staff/list',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                  if (response.success && Array.isArray(response.data)) {
                    $('#entries-table-body').empty();
                    response.data.forEach(function(entry, idx) {
                      $('#entries-table-body').append(
                        `<tr>
                           <td>${idx + 1}</td>
                             <td>${entry.name}</td>
                             <td>${entry.number}</td>
                           <td>${entry.address}</td>
                             <td>
                             <button class="btn btn-danger btn-sm delete-staff" data-id="${entry.id}">Delete</button>
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

            // Auto-load entries table on page load, handle delete and form submit in a single ready block
            $(document).ready(function() {
              loadEntriesTable();
            

              // Handle delete button click with AJAX
              $(document).on('click', '.delete-staff', function() {
                var staffId = $(this).data('id');
                if (confirm('Are you sure you want to delete this staff member?')) {
                  $.ajax({
                    url: '/public/staff/delete/' + staffId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                      if (response.success) {
                        toastr.success(response.message || 'Entry deleted successfully.');
                        loadEntriesTable();
                      } else {
                        toastr.error(response.message || 'Failed to delete entry.');
                      }
                    },
                    error: function() {
                      toastr.error('An error occurred. Please try again.');
                    }
                  });
                }
              });

              // Update form submit to reload table via AJAX
              $('#customerForm').off('submit').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $('.loader').show();
                $.ajax({
                  url: '/public/staff/store',
                  type: 'POST',
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                    $('.loader').hide();
                    if (response.success) {
                      toastr.success(response.message || 'Staff added successfully.');
                      loadEntriesTable();
                      form[0].reset();
                    } else {
                      toastr.error(response.message || 'Failed to add staff.');
                    }
                  },
                  error: function(xhr) {
                    $('.loader').hide();
                    toastr.error('An error occurred. Please try again.');
                  }
                });
              });
          });
            </script>