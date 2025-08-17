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
                      <label class="col-sm-3 col-form-label text-center">Customer Name</label>
                      <div class="col-sm-9 position-relative">
                        <input type="text" class="form-control" id="customer_search" placeholder="Enter customer name or number" required>
                        <input type="hidden" name="cid" id="cid">
                        <div id="suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                      </div>
                    </div>
                    <script>
                    document.getElementById("customer_search").addEventListener("keyup", function() {
                      const keyword = this.value;
                      if (keyword.length >= 2) {
                        fetch(`/public/customer/searchCustomer?term=${encodeURIComponent(keyword)}`)
                          .then(res => res.json())
                          .then(data => {
                            const suggestions = document.getElementById("suggestions");
                            suggestions.innerHTML = '';
                            data.forEach(customer => {
                              const div = document.createElement("div");
                              div.classList.add("list-group-item", "list-group-item-action");
                              div.innerHTML = `${customer.name} (${customer.mobile})`;
                              div.onclick = function () {
                                document.getElementById("customer_search").value = customer.name;
                                document.getElementById("cid").value = customer.id;
                                suggestions.innerHTML = '';
                              };
                              suggestions.appendChild(div);
                            });
                          });
                      } else {
                        document.getElementById("suggestions").innerHTML = '';
                      }
                    });

                    document.querySelector("form").addEventListener("submit", function(e) {
                      if (!document.getElementById("cid").value) {
                        alert("Please select a customer from the suggestions.");
                        e.preventDefault();
                      }
                    });
                    </script>
                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Milk Type</label>
                      <div class="col-sm-9">
                        <select class="form-control" name="milktype" required>
                          <option value="">Select Milk Type</option>
                          <option value="buffalo">Buffalo</option>
                          <option value="cow">Cow</option>
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
                        <th scope="col">Customer</th>
                        <th scope="col">Type</th>
                        <th scope="col">Liter</th>
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

            <script>
            function loadEntriesTable() {
              $.ajax({
                url: '/public/dailyentry/list', // Create this endpoint to return the table rows HTML
                type: 'GET',
                success: function(html) {
                  // Parse the HTML if it's JSON, or just insert as is if it's HTML
                  // Assuming html is a string of <tr>...</tr>
                  // $('#entries-table-body').html(html);

                  // Optionally, you can loop through the rows and display name, milktype, milkliter in a notification or console
                  // Example: parse and log each row's data (if html is JSON array)
                  // If your endpoint returns JSON, you can do:
                  let entries = JSON.parse(html);
                  $('#entries-table-body').empty();
                  entries.forEach(function(entry, idx) {
                    $('#entries-table-body').append(
                      `<tr>
                         <td>${idx+1}</td>
                         <td>${entry.name}</td>
                         <td>${entry.milktype}</td>
                         <td>${entry.milkliter}</td>
                         <td>...</td>
                       </tr>`
                    );
                  });
                },
                },
                error: function() {
                  $('#entries-table-body').html('<tr><td colspan="5" class="text-center">Failed to load entries.</td></tr>');
                }
              });
            }

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
                loadEntriesTable();
            });
            </script>