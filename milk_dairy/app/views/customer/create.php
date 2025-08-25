<?php require_once __DIR__ . '/../layouts/sidebar.php';?>
   <?php
        // Example translations, you should replace with your actual translation logic
        $lang = isset($vendor['lng']) ? $vendor['lng'] : 'en';
        $translations = [
          'en' => [
            'add_customer' => 'Add Customer',
            'bill_id' => 'Bill ID',
            'full_name' => 'Full Name',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'submit' => 'Submit',
          ],
            'hi' => [
            'add_customer' => 'ग्राहक जोड़े',
            'bill_id' => 'बिल आईडी',
            'full_name' => 'पूर्ण नाव',
            'mobile' => 'मोबाइल',
            'address' => 'पत्ता',
            'submit' => 'सबमिट',
            ],
            'mr' => [
            'add_customer' => 'ग्राहक जोड़े',
            'bill_id' => 'बिल आयडी',
            'full_name' => 'पूर्ण नाव',
            'mobile' => 'मोबाइल',
            'address' => 'पत्ता',
            'submit' => 'सबमिट',
            ],
          // Add more languages as needed
        ];
        $t = $translations[$lang];
        ?>
<div class="main-content">
  <div class="loader" style="display:none;"></div>
  <div id="app">
    <section class="section">
      <div class="section-body">
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="card">
              <div class="card-header">
                <h4><?= $t['add_customer']; ?></h4>
              </div>
              <form id="customerForm" autocomplete="off">
                <div class="card-body">
                  <input type="hidden" name="vid" value="<?php echo $_SESSION['vendor']['id'] ?? ''; ?>">

                  <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><?= $t['bill_id']; ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="bill_id" placeholder="Enter bill ID" required>
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><?= $t['full_name']; ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="name" id="name" placeholder="Enter full name" required>
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><?= $t['mobile']; ?></label>
                    <div class="col-sm-9 d-flex gap-2">
                      <input type="tel" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile number" required>
                      <button type="button" class="btn btn-secondary" id="pickContactBtn" title="Pick from contacts">
                        <i class="fa fa-address-book"></i>
                      </button>
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><?= $t['address']; ?></label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="address" rows="2" placeholder="Enter address" required></textarea>
                    </div>
                  </div>

                  <div class="text-end">
                    <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                      <span id="btnText"><?= $t['submit']; ?></span>
                      <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" style="display:none;"></span>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="card h-100">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="m-0">Customers Details <small class="text-muted" id="entries-summary"></small></h4>
                <div class="d-flex gap-2">
                  <input id="entries-search" class="form-control form-control-sm" placeholder="Search name or mobile" style="min-width:150px;">
                  <button id="refreshEntries" class="btn btn-sm btn-outline-primary" title="Refresh"><i class="fa fa-sync"></i></button>
                </div>
              </div>

              <div class="card-body p-2">
                <div class="table-responsive">
                  <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>#</th>
                        <th>Bill ID</th>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th class="text-end">Action</th>
                      </tr>
                    </thead>
                    <tbody id="entries-table-body">
                      <tr><td colspan="5" class="text-center py-4">Loading...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Delete confirm modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-4">
        <h5 class="mb-3">Delete customer?</h5>
        <p class="text-muted mb-3">This action cannot be undone.</p>
        <div class="d-flex justify-content-center gap-2">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button id="confirmDeleteBtn" type="button" class="btn btn-danger btn-sm">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- required libs (if not included globally) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function($){
  const BASE_URL = '/public';
  let lastCustomers = [];
  let deleteId = null;
  let searchTimeout = null;

  function formatMobile(mobile) {
    if (!mobile) return '';
    const digits = mobile.replace(/\D/g,'');
    if (digits.length <= 6) return mobile;
    return digits.replace(/^(\d{3})(\d{3})(\d+)/, '$1 $2 $3');
  }

  function renderEntries(customers) {
    lastCustomers = customers || [];
    const $body = $('#entries-table-body');
    $body.empty();
    if (!lastCustomers.length) {
      $body.html('<tr><td colspan="5" class="text-center py-4">No customers found.</td></tr>');
      $('#entries-summary').text('');
      return;
    }
    let total = lastCustomers.length;
    $('#entries-summary').text(`${total} customers`);
    lastCustomers.forEach((cust, idx) => {
      const mobileLink = cust.mobile ? `<a href="tel:${cust.mobile}">${escapeHtml(formatMobile(cust.mobile))}</a>` : '';
      $body.append(`
        <tr data-id="${cust.id}">
          <td>${idx+1}</td>
          <td>${escapeHtml(cust.bill_id || '')}</td>
          <td>${escapeHtml(cust.name || '')}</td>
          <td>${mobileLink}</td>
          <td class="text-end">
            <button class="btn btn-danger btn-sm delete-cust" data-id="${cust.id}" title="Delete"><i class="fa fa-trash"></i></button>
            <a href="${BASE_URL}/customer/show/${cust.id}" class="btn btn-info btn-sm ms-1" title="View"><i class="fa fa-eye"></i></a>
          </td>
        </tr>
      `);
    });
  }

  function escapeHtml(s) {
    return String(s || '').replace(/[&<>"'`]/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','`':'&#96;'}[m]; });
  }

  function loadEntriesTable() {
    $.ajax({
      url: BASE_URL + '/customer/list',
      type: 'GET',
      dataType: 'json',
      success: function(res) {
        if (res && res.success && Array.isArray(res.data)) {
          renderEntries(res.data);
        } else {
          renderEntries([]);
          toastr.error(res.message || 'Failed to load customers.');
        }
      },
      error: function() {
        renderEntries([]);
        toastr.error('Failed to load customers. Check your network.');
      }
    });
  }

  // Debounced client-side search (filters lastCustomers)
  function applySearch(term) {
    term = (term || '').trim().toLowerCase();
    if (!term) {
      renderEntries(lastCustomers);
      return;
    }
    const filtered = lastCustomers.filter(c => {
      return (c.name || '').toLowerCase().includes(term) || (c.mobile || '').toLowerCase().includes(term) || (c.bill_id || '').toLowerCase().includes(term);
    });
    renderEntries(filtered);
  }

  // Contact picker fill
  $('#pickContactBtn').on('click', async function(){
    if ('contacts' in navigator && 'ContactsManager' in window) {
      try {
        const props = ['name','tel'];
        const opts = {multiple:false};
        const contacts = await navigator.contacts.select(props, opts);
        if (contacts && contacts.length) {
          const c = contacts[0];
          if (c.name && c.name.length) $('#name').val(Array.isArray(c.name)?c.name[0]:c.name);
          if (c.tel && c.tel.length) $('#mobile').val(c.tel[0]);
        }
      } catch(err){
        console.error(err);
        toastr.error('Contact picker not available or permission denied.');
      }
    } else {
      toastr.info('Contact Picker not supported in this browser.');
      const fallbackName = prompt('Contact name (optional):','');
      const fallbackTel = prompt('Contact mobile (optional):','');
      if (fallbackName) $('#name').val(fallbackName);
      if (fallbackTel) $('#mobile').val(fallbackTel);
    }
  });

  // Single submit handler
  $('#customerForm').on('submit', function(e){
    e.preventDefault();
    const $form = $(this);
    const $btn = $('#submitBtn');
    const $spinner = $('#btnSpinner');
    if ($btn.prop('disabled')) return;
    $btn.prop('disabled', true);
    $spinner.show();
    $('.loader').show();

    $.ajax({
      url: BASE_URL + '/customer/store',
      type: 'POST',
      data: $form.serialize(),
      dataType: 'json',
      success: function(resp){
        $('.loader').hide();
        $btn.prop('disabled', false);
        $spinner.hide();
        if (resp && resp.success) {
          toastr.success(resp.message || 'Customer added.');
          $form[0].reset();
          loadEntriesTable();
        } else if (resp && resp.duplicate) {
          toastr.warning(resp.message || 'Customer already exists.');
          // Optionally highlight duplicate field
        } else {
          toastr.error(resp.message || 'Failed to add customer.');
        }
      },
      error: function(xhr){
        $('.loader').hide();
        $btn.prop('disabled', false);
        $spinner.hide();
        toastr.error('Server error. Try again.');
        console.error(xhr);
      }
    });
  });

  // Refresh button
  $('#refreshEntries').on('click', function(){ loadEntriesTable(); });

  // Search input
  $('#entries-search').on('input', function(){
    clearTimeout(searchTimeout);
    const val = $(this).val();
    searchTimeout = setTimeout(()=> applySearch(val), 250);
  });

  // Delete flow using modal
  $(document).on('click', '.delete-cust', function(){
    deleteId = $(this).data('id');
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
  });

  $('#confirmDeleteBtn').on('click', function(){
    if (!deleteId) return;
    $('#confirmDeleteBtn').prop('disabled', true).text('Deleting...');
    $.ajax({
      url: BASE_URL + '/customer/delete/' + deleteId,
      type: 'POST',
      dataType: 'json',
      success: function(res){
        $('#confirmDeleteBtn').prop('disabled', false).text('Delete');
        const modalEl = document.getElementById('confirmDeleteModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        if (res && res.success) {
          toastr.success(res.message || 'Customer deleted.');
          loadEntriesTable();
        } else {
          toastr.error(res.message || 'Failed to delete customer.');
        }
      },
      error: function(xhr){
        $('#confirmDeleteBtn').prop('disabled', false).text('Delete');
        toastr.error('Delete failed.');
        console.error(xhr);
      }
    });
  });

  // initial load
  $(document).ready(function(){ loadEntriesTable(); });

})(jQuery);
</script>