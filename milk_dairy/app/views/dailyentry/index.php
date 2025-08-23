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
                  <div class="searchResults"></div>
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



                    
                    <div class="form-group row mb-3 justify-content-center align-items-center">
                      <label class="col-sm-3 col-form-label text-center">Date &amp; Time</label>
                      <div class="col-sm-9 d-flex justify-content-center align-items-center">
                        <input type="date" class="form-control w-75 text-center" id="entry_datetime" name="entry_datetime" required>
                        <button type="button" class="btn btn-outline-secondary ms-2" id="setNow">Now</button>
                      </div>
                    </div>

                    <script>
                      // return a string suitable for datetime-local input (YYYY-MM-DDTHH:MM)
                      function getDateTimeLocalForTimeZone(timeZone) {
                        const now = new Date();
                        const dtf = new Intl.DateTimeFormat('en-GB', {
                          timeZone: timeZone,
                          year: 'numeric', month: '2-digit', day: '2-digit',
                          hour: '2-digit', minute: '2-digit', hour12: false
                        });
                        const parts = dtf.formatToParts(now);
                        const map = {};
                        parts.forEach(p => map[p.type] = p.value);
                        // parts include day, month, year, hour, minute
                        const y = map.year, m = map.month, d = map.day, hh = map.hour, mm = map.minute;
                        return `${y}-${m}-${d}T${hh}:${mm}`;
                      }

                      function setEntryDateTimeNow() {
                        const val = getDateTimeLocalForTimeZone('Asia/Kolkata');
                        const el = document.getElementById('entry_datetime');
                        if (el) el.value = val;
                      }

                      // initialize once on load
                      document.addEventListener('DOMContentLoaded', function(){
                        setEntryDateTimeNow();
                        document.getElementById('setNow').addEventListener('click', setEntryDateTimeNow);
                      });
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
                          <input class="form-check-input" type="radio" name="milkliter" id="liter1" value="0.5">
                          <label class="form-check-label" for="liter1">0.5 Liter</label>
                        </div>
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
<style>
  /* minimal spacing for search cards */
  #summary-search-wrap{margin:10px 0 20px;}
  .profile-widget{border:1px solid #e3e6f0;border-radius:8px;padding:10px;margin-bottom:12px;}
  .profile-widget-header h4{margin:0 0 8px 0;}
  .profile-widget-items{display:flex;gap:12px;justify-content:center;}
  .profile-widget-item{padding:8px 10px;border-radius:6px;background:#f8f9fc;min-width:140px;text-align:center;}
  .profile-widget-item-label{font-size:12px;color:#6c757d;}
  .profile-widget-item-value{font-weight:600;font-size:16px;}
</style>
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
    // Live customer search with AJAX
    $("#customer_search").on("keyup", function () {
      let keyword = $(this).val().trim();

      if (keyword !== "") {
      $.ajax({
        url: "<?php echo BASE_URL; ?>customer/searchCustomer",
        method: "GET",
        data: { term: keyword },
        dataType: "json",
        success: function (data) {
        let suggestions = $("#suggestions");
        suggestions.html("");

        if (Array.isArray(data) && data.length > 0) {
          data.forEach(function (customer) {
          let div = $("<div>")
            .addClass("list-group-item list-group-item-action")
            .html(customer.bill_id + " : " + customer.name)
            .on("click", function () {
            $("#customer_search").val(customer.name);
            $("#cid").val(customer.id);
            suggestions.html("");
            });

          suggestions.append(div);
          });
        } else {
          suggestions.html('<div class="list-group-item">No results found.</div>');
        }
        }
      });
      } else {
      $("#suggestions").html("");
      }
    });

    // Form submit validation: ensure customer is selected from suggestions
    $("form").on("submit", function (e) {
      if (!$("#cid").val()) {
      alert("Please select a customer from the suggestions.");
      e.preventDefault();
      }
    });
            function loadEntriesTable() {
              // Show loading indicator
              $('#entries-table-body').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
              
              $.ajax({
                url: '<?php echo BASE_URL; ?>dailyentry/list',
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

            // --- Summary search UI and logic (always visible) ---
            (function(){
              const container = $("<div class='card'><div class='card-header'><h4>Search Customers (Summary)</h4></div><div class='card-body' id='summary-search-wrap'><input type='text' id='summary_search' class='form-control' placeholder='Type name / bill id / mobile'><div id='searchResults' class='mt-3'></div></div></div>");
              $(".section-body .row .col-12.col-md-12.col-lg-12").append(container);
            })();

            function debounce(fn, delay){ let t; return function(){ clearTimeout(t); const a=arguments; const c=this; t=setTimeout(()=>fn.apply(c,a), delay); }; }

            function renderCards(items){
              const wrap = $("#searchResults");
              if (!items || !items.length){ wrap.html('<div class="text-center text-muted">No results.</div>'); return; }
              let html = '';
              items.forEach(it => {
                const name = it.name ? String(it.name) : '—';
                const totalAmt = (it.total_amount !== undefined) ? it.total_amount.toFixed(0) : '0';
                const totalLit = (it.total_liters !== undefined) ? Number(it.total_liters).toFixed(0) : '0';
                const lastDate = it.last_date ? it.last_date : '-';
                html += `
                  <div class="card profile-widget">
                    <div class="profile-widget-header">
                      <h4 class="text-center">${name}</h4>
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Total Amount</div>
                          <div class="profile-widget-item-value">${totalAmt}</div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Total</div>
                          <div class="profile-widget-item-value">${totalLit}</div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Last Date</div>
                          <div class="profile-widget-item-value">${lastDate}</div>
                        </div>
                      </div>
                    </div>
                  </div>`;
              });
              wrap.html(html);
            }

            const doSearch = debounce(function(){
              const q = $('#summary_search').val().trim();
              if (!q){ $('#searchResults').empty(); return; }
              $.ajax({
                url: '<?php echo BASE_URL; ?>dailyentry/searchSummary',
                data: { term: q },
                dataType: 'json',
                success: function(res){ if (res && res.success){ renderCards(res.data); } else { renderCards([]); } },
                error: function(){ renderCards([]); }
              });
            }, 300);
            $(document).on('keyup', '#summary_search', doSearch);
            </script>