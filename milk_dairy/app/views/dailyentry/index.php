<?php require_once '../app/views/layouts/sidebar.php';?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

            <!-- Delete confirmation modal -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Confirm delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this entry?</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-sm btn-danger">Delete</button>
                  </div>
                </div>
              </div>
            </div>

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
                    <div class="form-group row mb-3">
                      <label class="col-sm-3 col-form-label text-center">Use date for</label>
                      <div class="col-sm-9 d-flex flex-wrap align-items-center">
                        <div class="form-check me-3">
                          <input class="form-check-input" type="radio" name="date_use_mode" id="use_selected_date" value="selected_date" >
                          <label class="form-check-label" for="use_selected_date">Selected date</label>
                        </div>
                        <div class="form-check me-3">
                          <input class="form-check-input" type="radio" name="date_use_mode" id="use_created_at" value="created_at" checked>
                          <label class="form-check-label" for="use_created_at">Created at (now)</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="setCreatedAtFromSelected" />
                          <label class="form-check-label" for="setCreatedAtFromSelected">Also set created_at from selected date</label>
                        </div>
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
                <div class="card-header d-flex align-items-center justify-content-between">
                      <h4 class="m-0">Customers Details <small class="text-muted" id="entries-summary"></small></h4>
                      <div class="d-flex">
                        <input id="entries-search" class="form-control form-control-sm me-2" placeholder="Search by name/type..." style="min-width:180px;">
                        <button id="refreshEntries" class="btn btn-sm btn-outline-secondary me-2" title="Refresh"><i class="fa fa-sync"></i></button>
                        <button id="exportCsv" class="btn btn-sm btn-outline-primary" title="Export CSV"><i class="fa fa-download"></i> CSV</button>
                      </div>
                    </div>
                    <div class="card-body" id="entries-table-container">
      <table class="table table-sm">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">ग्राहक</th>
                        <th scope="col">प्रकार</th>
                        <th scope="col">लिटर</th>
        <th scope="col">Date</th>
                    
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
            (function(){
              // Define BASE_URL early for scripts
              var BASE_URL = "<?php echo BASE_URL; ?>";

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
                if (isDigits){
                  let m = list.find(x => String(x.id) === term);
                  if (m) return m;
                  m = list.find(x => String(x.bill_id) === term);
                  if (m) return m;
                } else {
                  let m = list.find(x => String(x.name).toLowerCase() === lower);
                  if (m) return m;
                }
                return list[0];
              }

              const doAutoSelect = debounce(function(){
                const keyword = $("#customer_search").val().trim();
                if (!keyword){ $(".showcustomers").empty(); $("#cid").val(''); return; }
                $.ajax({
                  url: BASE_URL + "customer/searchCustomer",
                  method: "GET",
                  data: { term: keyword },
                  dataType: "json",
                  success: function (data) {
                    if (Array.isArray(data) && data.length > 0) {
                      const selected = bestMatch(keyword, data);
                      const multi = data.length > 1 && !(selected && ((String(selected.bill_id)===keyword) || (String(selected.name).toLowerCase()===keyword.toLowerCase())));
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

              // Store last loaded entries for client-side filtering/export
              var lastEntries = [];

              function updateSummary(entries){
                var total = entries.length;
                var liters = entries.reduce(function(s, e){ return s + (parseFloat(e.milkliter) || 0); }, 0);
                $('#entries-summary').text(` — ${total} entries, ${liters} L total`);
              }
              function renderEntries(entries) {
                lastEntries = entries || [];
                updateSummary(lastEntries);
                if (!Array.isArray(lastEntries) || lastEntries.length === 0) {
                  $('#entries-table-body').html('<tr><td colspan="6" class="text-center">No entries found.</td></tr>');
                  return;
                }
                $('#entries-table-body').empty();
                lastEntries.forEach(function(entry, idx) {
                  // Show only first and last name (split by space, take first and last parts)
                  let fullName = entry.customer_name || entry.name || 'Unknown Customer';
                  let nameParts = fullName.trim().split(/\s+/);
                  let displayName = nameParts.length > 1
                    ? nameParts[0] + ' ' + nameParts[nameParts.length - 1]
                    : nameParts[0];

                  // Milk type in Marathi
                  let milkType = (entry.milktype === 'buffalo') ? 'म्हैस' : (entry.milktype === 'cow' ? 'गाय' : (entry.milktype || 'Unknown'));

                  // Show only number in liter (no "L")
                  let milkLiter = entry.milkliter || '0';

                  // Show date as "MM-DD"
                  let dateVal = '';
                  if (entry.selected_date || entry.created_at) {
                    let d = (entry.selected_date || entry.created_at).substring(0, 10);
                    let parts = d.split('-');
                    if (parts.length === 3) {
                      dateVal = parts[1] + '-' + parts[2];
                    } else {
                      dateVal = d;
                    }
                  }

                  $('#entries-table-body').append(
                    `<tr data-name="${(displayName+'').toLowerCase()}" data-type="${(entry.milktype||'').toLowerCase()}">
                       <td>${idx + 1}</td>
                       <td>${displayName}</td>
                       <td>${milkType}</td>
                       <td>${milkLiter}</td>
                       <td>${dateVal}</td>
                     </tr>`
                  );
                });
              }

              function currentDateField(){
                var mode = $('input[name="date_use_mode"]:checked').val();
                // For listing, when mode is created_at, we filter by created_at; else selected_date
                return (mode === 'created_at') ? 'created_at' : 'selected_date';
              }

              function loadEntriesTable() {
                $('#entries-table-body').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');
                var d = $('#entry_date').val();
                var df = currentDateField();
                $.ajax({
                  url: BASE_URL + 'dailyentry/list',
                  type: 'GET',
                  dataType: 'json',
                  data: d ? { date: d, dateField: df } : {},
                  success: function(response) {
                    if (response.success && Array.isArray(response.data)) {
                      renderEntries(response.data);
                    } else {
                      renderEntries([]);
                    }
                  },
                  error: function(xhr, status, error) {
                    console.error('loadEntriesTable error:', xhr, status, error);
                    $('#entries-table-body').html('<tr><td colspan="6" class="text-center">Failed to load entries. Please try again.</td></tr>');
                  }
                });
              }

              $(document).ready(function () {
                // Bind events
                $("#customer_search").on("keyup", doAutoSelect);

                // Form submit validation (prevent submit if no customer selected)
                $("form").on("submit", function (e) {
                  if (!$("#cid").val()) {
                    alert("Please enter a valid customer (ID / Number / Name) to auto-select.");
                    e.preventDefault();
                    return false;
                  }
                });

                // Delete flow using modal confirmation
                var selectedEntryId = null;
                $(document).on('click', '.delete-entry', function() {
                  selectedEntryId = $(this).data('id');
                  var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                  modal.show();
                });

                $('#confirmDeleteBtn').on('click', function(){
                  if (!selectedEntryId) return;
                  var btn = $(this).prop('disabled', true).text('Deleting...');
                  $.ajax({
                    url: BASE_URL + 'dailyentry/delete/' + selectedEntryId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                      btn.prop('disabled', false).text('Delete');
                      var modalEl = document.getElementById('confirmDeleteModal');
                      var modal = bootstrap.Modal.getInstance(modalEl);
                      if (modal) modal.hide();
                      if (response.success) {
                        toastr.success(response.message || 'Entry deleted successfully.');
                        loadEntriesTable();
                      } else {
                        toastr.error(response.message || 'Failed to delete entry.');
                      }
                      selectedEntryId = null;
                    },
                    error: function() {
                      btn.prop('disabled', false).text('Delete');
                      toastr.error('An error occurred. Please try again.');
                    }
                  });
                });

                // Client-side search/filter
                $('#entries-search').on('input', debounce(function(){
                  var q = $(this).val().toLowerCase().trim();
                  if (!q) {
                    // show all
                    $('#entries-table-body tr').show();
                    updateSummary(lastEntries);
                    return;
                  }
                  var filtered = lastEntries.filter(function(e){
                    var name = (e.customer_name||e.name||'').toLowerCase();
                    var type = (e.milktype||'').toLowerCase();
                    return name.indexOf(q) !== -1 || type.indexOf(q) !== -1 || String(e.id||'').indexOf(q) !== -1 || String(e.bill_id||'').indexOf(q) !== -1;
                  });
                  // re-render rows from filtered set
                  if (filtered.length === 0) {
                    $('#entries-table-body').html('<tr><td colspan="5" class="text-center">No entries match.</td></tr>');
                  } else {
                    renderEntries(filtered);
                  }
                }, 200));

                // Reload on date or mode change
                $('#entry_date').on('change', function(){ loadEntriesTable(); });
                $('input[name="date_use_mode"]').on('change', function(){ loadEntriesTable(); });

                // Refresh & export
                $('#refreshEntries').on('click', function(){
                  loadEntriesTable();
                });

                function exportToCsv(filename, rows) {
                  if (!rows || !rows.length) return toastr.info('No data to export');
                  var headers = ['#','Customer','Type','Liters','ID','Bill ID','Date'];
                  var csv = headers.join(',') + '\n';
                  rows.forEach(function(r, i){
                    var dateVal = r.selected_date || (r.created_at ? r.created_at.substring(0,10) : '');
                    var line = [i+1, '"'+(r.customer_name||r.name||'')+'"', (r.milktype||''), (r.milkliter||''), (r.id||''), (r.bill_id||''), dateVal];
                    csv += line.join(',') + '\n';
                  });
                  var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                  var link = document.createElement('a');
                  var url = URL.createObjectURL(blob);
                  link.setAttribute('href', url);
                  link.setAttribute('download', filename);
                  link.style.visibility = 'hidden';
                  document.body.appendChild(link);
                  link.click();
                  document.body.removeChild(link);
                  URL.revokeObjectURL(url);
                }

                $('#exportCsv').on('click', function(){
                  exportToCsv('entries.csv', lastEntries);
                });

                // Submit via AJAX and reload table
                $('#customerForm').off('submit').on('submit', function(e) {
                  e.preventDefault();
                  var form = $(this);
                  var formData = form.serializeArray();
                  // Optionally set created_at from selected date at 00:00:00 to preserve chosen day
                  if ($('#setCreatedAtFromSelected').is(':checked')){
                    var sel = $('#entry_date').val();
                    if (sel){
                      // Use 00:00 time so grouping by DATE(created_at) matches selected day
                      formData.push({name:'created_at', value: sel + ' 00:00:00'});
                    }
                  }
                  // Convert to query string
                  formData = $.param(formData);
                  $('.loader').show();
                  $.ajax({
                    url: BASE_URL + 'dailyentry/store',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                      $('.loader').hide();
                        if (response.success) {
                        toastr.success(response.message || 'Entry added successfully.');
                        loadEntriesTable();
                        // Reset all fields except date
                        var dateVal = $('#entry_date').val();
                        form[0].reset();
                        $('#cid').val('');
                        $('#entry_date').val(dateVal);
                        // Keep date mode selections after reset
                        $('#use_selected_date').prop('checked', true);
                        $('#setCreatedAtFromSelected').prop('checked', false);
                        
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

                // Initial load
                loadEntriesTable();
              });
            })();
            </script>