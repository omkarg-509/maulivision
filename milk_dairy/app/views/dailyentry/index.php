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
                  <div id="massages"></div>
                <form method="POST" id="customerForm">
                  <div class="card-body">
                    <input type="hidden" class="form-control" name="vid" value="<?php echo htmlspecialchars($_SESSION['vendor']['id'] ?? ''); ?>" readonly>
                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Date & Time</label>
                      <div class="col-sm-9 position-relative">
                        <input type="text" class="form-control" id="indian_datetime" name="entry_datetime" disabled>
                      </div>
                    </div>

                      <script>
                        function updateIndianDateTime() {
                          const now = new Date();
                          const options = {
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit', second: '2-digit',
                            hour12: true,
                            timeZone: 'Asia/Kolkata'
                          };
                          const formatter = new Intl.DateTimeFormat('en-IN', options);
                          document.getElementById('indian_datetime').value = formatter.format(now).replace(',', '');
                        }
                        updateIndianDateTime();
                        setInterval(updateIndianDateTime, 1000);
                      </script>
                      <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Customer Name</label>
                      <div class="col-sm-9 position-relative">
                        <input type="text" class="form-control" id="customer_search" placeholder="Enter customer name or number" required>
                        <input type="hidden" name="cid" id="cid">
                        <div id="suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                      </div>
                    </div>
                    <script>
                   
                    </script>
                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Milk Type</label>
                      <div class="col-sm-9">
                        <select class="form-control" name="milktype" required>
                          <option value="">Select Milk Type</option>
                            <option value="buffalo">म्हैस</option>
                            <option value="cow">गाय</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Milk Liter</label>
                      <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="milkliter" id="liter1" value="1.0">
                          <label class="form-check-label" for="liter1">1.0 Liter</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="milkliter" id="liter15" value="1.5">
                          <label class="form-check-label" for="liter15">1.5 Liter</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="milkliter" id="liter2" value="2.0">
                          <label class="form-check-label" for="liter2">2.0 Liter</label>
                        </div>
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
                        <th scope="col">ग्राहक</th>
                        <th scope="col">प्रकार</th>
                        <th scope="col">लिटर</th>
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
// $(document).ready(function() {

//   $('#customerForm').on('submit', function(e) {
//     e.preventDefault();
//     var form = $(this);
//     var formData = form.serialize();

//     $.ajax({
//       url: '/public/dailyentry/store', // Adjust to your actual endpoint
//       type: 'POST',
//       data: formData,
//       dataType: 'json',
//       // beforeSend: function() {
//       //   $('.loader').show();
//       // },
//       success: function(response) {
//         $('.loader').hide();
//         if (response.success) {
          
//     toastr.success(response.message || 'Entry added successfully.');
//           setTimeout(function() {
//             location.reload();
//           }, 1200);
     
//         } else {
//             toastr.error(response.message || 'Failed to add entry.');
//         }
//       },
//       error: function(xhr) {
//         $('.loader').hide();
//         alert('An error occurred. Please try again.');
//       }
//     });
//   });
// });
</script>

            <script> $(document).ready(function () {
    // customer search
    $("#customer_search").on("keyup", function () {
      let keyword = $(this).val();

      if (keyword.length >= 2) {
        $.ajax({
          url: "/public/customer/searchCustomer",
          method: "GET",
          data: { term: keyword },
          dataType: "json",
          success: function (data) {
            let suggestions = $("#suggestions");
            suggestions.html("");

            data.forEach(function (customer) {
              let div = $("<div>")
                .addClass("list-group-item list-group-item-action")
                .html(customer.name + " (" + customer.mobile + ")")
                .on("click", function () {
                  $("#customer_search").val(customer.name);
                  $("#cid").val(customer.id);
                  suggestions.html("");
                });

              suggestions.append(div);
            });
          },
        });
      } else {
        $("#suggestions").html("");
      }
    });

    // form submit validation
    $("form").on("submit", function (e) {
      if (!$("#cid").val()) {
        alert("Please select a customer from the suggestions.");
        e.preventDefault();
      }
    });
  });
            function loadEntriesTable() {
              $.ajax({
                url: '/public/dailyentry/list',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                  if (response.success && Array.isArray(response.data)) {
                    $('#entries-table-body').empty();
                    response.data.forEach(function(entry, idx) {
                      $('#entries-table-body').append(
                        `<tr>
                           <td>${idx + 1}</td>
                             <td>${entry.customer_name}</td>
                             <td>${entry.milktype === 'buffalo' ? 'म्हैस' : (entry.milktype === 'cow' ? 'गाय' : entry.milktype)}</td>
                           <td>${entry.milkliter}</td>
                             <td>
                             <button class="btn btn-danger btn-sm delete-entry" data-id="${entry.id}">Delete</button>
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
            $(document).on('click', '.delete-entry', function() {
              var entryId = $(this).data('id');
              if (confirm('Are you sure you want to delete this entry?')) {
              $.ajax({
                url: '/public/dailyentry/delete/' + entryId,
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
            $(document).ready(function() {
              
              $('#customerForm').off('submit').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                  url: '/public/dailyentry/store',
                  type: 'POST',
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                    $('.loader').hide();
                    if (response.success) {
                      toastr.success(response.message || 'Entry added successfully.');
                      loadEntriesTable();
                      form[0].reset();
                      $('#cid').val('');
                    } else {
                      toastr.error(response.message || 'Failed to add entry.');
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