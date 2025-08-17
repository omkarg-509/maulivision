<?php require_once '../app/views/layouts/sidebar.php';?>


<div class="main-content">
  <div class="loader"></div>
  <div id="app">

       
  <section class="section">
     
          </div>
         <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            <div class="col-12 text-end"><a class="btn btn-primary mb-3 " href="/public/customer/create">Add Customer</a></div>
            
  <div class="card">
    <div class="card-header">
      <h4>Customers Details</h4>
    </div>
    <div class="card-body">

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
                             <td>
                              
                               ${cust.name}
                              
                             </td>
                             <td> <a href="tel:${cust.mobile}" title="Call ${cust.mobile}">${cust.mobile ? cust.mobile.replace(/^(\d{5})\d+$/, '$1...') : ''} </a></td>
                             <td>
                             <button class="btn btn-danger btn-sm delete-cust" data-id="${cust.id}"><i class="fa fa-trash"></i></button>
                             <a href="/public/customer/show/${cust.id}" 
                     title="View" class="btn btn-info btn-sm" style="margin-left: 8px;">
                    <i class="fa fa-eye"></i>
                    </a>
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
            </script>