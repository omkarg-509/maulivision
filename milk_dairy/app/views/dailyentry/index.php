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
                    <div id="massages"></div>
                    <div class="showcustomers"></div>
                    <form method="POST" id="customerForm">
                    <div class="card-body">
                      <input type="hidden" class="form-control" name="vid" value="<?php echo htmlspecialchars($_SESSION['vendor']['id'] ?? ''); ?>" readonly>

                      <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Customer Name</label>
                      <div class="col-sm-9 position-relative">
                        <input type="text" class="form-control" id="customer_search" placeholder="Enter customer name or number" autocomplete="off" required>
                        <input type="hidden" name="cid" id="cid">
                      </div>
                      </div>



                    
                    <div class="form-group row mb-3 justify-content-center align-items-center">
                      <label class="col-sm-3 col-form-label text-center">
                      <i class="fa fa-calendar-alt me-2" id="calendarIcon" style="cursor:pointer;"></i> Date &amp; Time
                      </label>
                      <div class="col-sm-9 d-flex justify-content-center align-items-center">
                      <input type="date" class="form-control w-75 text-center" id="entry_date" name="entry_date" required>
                      <button type="button" class="btn btn-outline-secondary ms-2" id="setToday">
                        <i class="fa fa-calendar-check"></i> 
                      </button>
                      </div>
                    </div>

                    <script>
                      function setEntryDateToday(openPicker = false) {
                      const today = new Date();
                      const yyyy = today.getFullYear();
                      const mm = String(today.getMonth() + 1).padStart(2, '0');
                      const dd = String(today.getDate()).padStart(2, '0');
                      const val = `${yyyy}-${mm}-${dd}`;
                      const el = document.getElementById('entry_date');
                      if (el) {
                        el.value = val;
                        if (openPicker && typeof el.showPicker === 'function') {
                        el.showPicker();
                        } else if (openPicker) {
                        el.focus();
                        }
                      }
                      }

                      document.addEventListener('DOMContentLoaded', function(){
                      setEntryDateToday();
                      document.getElementById('setToday').addEventListener('click', function() {
                        setEntryDateToday(true);
                      });
                      document.getElementById('calendarIcon').addEventListener('click', function() {
                        const el = document.getElementById('entry_date');
                        if (el && typeof el.showPicker === 'function') {
                        el.showPicker();
                        } else if (el) {
                        el.focus();
                        }
                      });
                      });
                    </script>
                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Milk Type</label>
                      <div class="col-sm-9">
                        <div class="btn-group w-100" role="group" aria-label="Milk Type">
                          <input type="radio" class="btn-check" name="milktype" id="milk-buffalo" value="buffalo" autocomplete="off" required>
                          <label class="btn btn-outline-danger d-flex align-items-center justify-content-center" for="milk-buffalo">
                            <i class="fas fa-hippo me-2"></i> म्हैस
                          </label>

                          <input type="radio" class="btn-check" name="milktype" id="milk-cow" value="cow" autocomplete="off" required>
                          <label class="btn btn-outline-success d-flex align-items-center justify-content-center" for="milk-cow">
                            <i class="fas fa-cow me-2"></i> गाय
                          </label>
                        </div>
                      </div>
                    </div>
                    <!-- Font Awesome for icons -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Milk Liter</label>
                      <div class="col-sm-9">
                        <div class="btn-group w-100" role="group" aria-label="Milk Liter">
                          <?php
                            $liters = [0.5, 1.0, 1.5, 2.0];
                            foreach ($liters as $i => $val):
                              $id = 'liter' . str_replace('.', '', $val);
                          ?>
                            <input type="radio" class="btn-check" name="milkliter" id="<?= $id ?>" value="<?= $val ?>" autocomplete="off" required>
                            <label class="btn btn-outline-primary" for="<?= $id ?>" style="font-size:1.1rem;">
                              <?= $val ?> L
                            </label>
                          <?php endforeach; ?>
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

            <script>
            $(document).ready(function () {
              // Debounce helper
              function debounce(fn, delay){ let t; return function(){ clearTimeout(t); const a=arguments, c=this; t=setTimeout(()=>fn.apply(c,a), delay); }; }

              function renderSelectedCustomer(c, note){
                const html = `
                  <div class="card profile-widget">
                    <div class="profile-widget-header">
                      <h4 class="text-center">${c.name || ''}</h4>
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">ID</div>
                          <div class="profile-widget-item-value">${c.bill_id || ''}</div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Number</div>
                          <div class="profile-widget-item-value">${c.mobile || ''}</div>
                        </div>
                        
                      </div>
                      ${note ? `<div class="text-center text-muted" style="margin-top:6px;">${note}</div>` : ''}
                    </div>
                  </div>`;
                $(".showcustomers").html(html);
              }

              function bestMatch(term, list){
                term = term.trim();
                if (!list || !list.length) return null;
                const isDigits = /^\d+$/.test(term);
                const lower = term.toLowerCase();
                // exact priority: id -> bill_id -> name
                if (isDigits){
                  let m = list.find(x => String(x.id) === term);
                  if (m) return m;
                  m = list.find(x => String(x.bill_id) === term);
                  if (m) return m;
                } else {
                  let m = list.find(x => String(x.name).toLowerCase() === lower);
                  if (m) return m;
                }
                // fallback to first
                return list[0];
              }

              const doAutoSelect = debounce(function(){
                const keyword = $("#customer_search").val().trim();
                if (!keyword){ $(".showcustomers").empty(); $("#cid").val(''); return; }
                $.ajax({
                  url: "<?php echo BASE_URL; ?>customer/searchCustomer",
                  method: "GET",
                  data: { term: keyword },
                  dataType: "json",
                  success: function (data) {
                    if (Array.isArray(data) && data.length > 0) {
                      const selected = bestMatch(keyword, data);
                      const multi = data.length > 1 && !(selected && ((String(selected.bill_id)===keyword) || (String(selected.bill_id)===keyword) || (String(selected.name).toLowerCase()===keyword.toLowerCase())));
                      $("#cid").val(selected.id || '');
                      renderSelectedCustomer(selected, multi ? 'Multiple matches found — showing closest match' : '');
                    } else {
                      $("#cid").val('');
                      $(".showcustomers").html('<div class="text-center text-muted">No customer found.</div>');
                    }
                  },
                  error: function(){
                    $(".showcustomers").html('<div class="text-center text-muted">Search failed. Try again.</div>');
                  }
                });
              }, 250);

              $("#customer_search").on("keyup", doAutoSelect);

              // Form submit validation: ensure a customer got auto-selected
              $("form").on("submit", function (e) {
                if (!$("#cid").val()) {
                  alert("Please enter a valid customer (ID / Number / Name) to auto-select.");
                  e.preventDefault();
                }
              });
            });
           
            // Define BASE_URL for JavaScript usage
            var BASE_URL = "<?php echo BASE_URL; ?>";
            
            function loadEntriesTable() {
              // Show loading indicator
              $('#entries-table-body').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
              
              $.ajax({
                url: BASE_URL + 'dailyentry/list',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                  // console.log('loadEntriesTable response:', response); // Debug log
            
                  if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                    $('#entries-table-body').empty();
                    response.data.forEach(function(entry, idx) {
                      // Handle missing customer_name gracefully
                      let customerName = entry.customer_name || entry.name || 'Unknown Customer';
                      let milkType = '';
                      
                      // Handle milk type translation
                      if (entry.milktype === 'buffalo') {
                        milkType = 'म्हैस';
                      } else if (entry.milktype === 'cow') {
                        milkType = 'गाय';
                      } else {
                        milkType = entry.milktype || 'Unknown';
                      }
                      
                      $('#entries-table-body').append(
                        `<tr>
                           <td>${idx + 1}</td>
                           <td>${customerName}</td>
                           <td>${milkType}</td>
                           <td>${entry.milkliter || '0'} L</td>
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
                error: function(xhr, status, error) {
                  console.error('loadEntriesTable error:', xhr, status, error); // Debug log
                  $('#entries-table-body').html('<tr><td colspan="5" class="text-center">Failed to load entries. Please try again.</td></tr>');
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
                url: '<?php echo BASE_URL; ?>dailyentry/delete/' + entryId,
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
                  url: '<?php echo BASE_URL; ?>dailyentry/store',
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