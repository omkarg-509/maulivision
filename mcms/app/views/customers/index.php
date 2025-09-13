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
                <form id="customerForm" method="POST" action="<?= BASE_URL ?>customers/store">
                  <div class="card-body">
                    <input type="hidden" class="form-control" name="vid" value="<?php echo htmlspecialchars($_SESSION['vendor']['id'] ?? ''); ?>" readonly>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label text-center">Customer Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" name="name" placeholder="Enter Name" required>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label text-center">Customer Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" name="mobile" placeholder="Enter Number" >
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label text-center">In Time</label>
                      <div class="col-sm-9">
                      <input type="time" class="form-control form-control-sm" name="in_time" required>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label text-center">Amount</label>
                      <div class="col-sm-9">
                        <input type="number" step="0.01" class="form-control form-control-sm" name="amount" placeholder="Amount Paid" required>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label text-center">Staff</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" name="staff" placeholder="Staff" required>
                      </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                      <label class="col-sm-3 col-form-label text-center">Online Cash</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" name="payment_method" placeholder="Online Cash">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-9 offset-sm-3 text-center">
                        <button type="submit" class="btn btn-primary btn-sm px-4" id="saveBtn">Submit</button>
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
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="customersTable">
                      <thead class="thead-light">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Customer name</th>
                          <th scope="col">Number</th>
                          <th scope="col">In time</th>
                          <th scope="col">Amount</th>
                          <th scope="col">Staff</th>
                          <th scope="col">Payment Method</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody id="customersTbody">
                        <?php

                        $today = date('Y-m-d');
                        $hasTodayEntries = false;

                        if (!empty($data['customers'])):
                          foreach ($data['customers'] as $index => $cust):
                            // Check if created_at is today
                            $entryDate = isset($cust['created_at']) ? date('Y-m-d', strtotime($cust['created_at'])) : '';
                            if ($entryDate === $today):
                              $hasTodayEntries = true;
                        ?>
                          <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($cust['name']) ?></td>
                            <td><?= htmlspecialchars($cust['mobile']) ?></td>
                            <td><?= htmlspecialchars(date('h:i A', strtotime($cust['in_time']))) ?></td>
                            <td><?= htmlspecialchars($cust['amount']) ?></td>
                            <td><?= htmlspecialchars($cust['staff']) ?></td>
                            <td><?= htmlspecialchars($cust['payment_method']) ?></td>
                            <td>
                              <a href="<?= BASE_URL ?>customers/delete/<?= urlencode($cust['id']) ?>"
                                 onclick="return confirm('Are you sure you want to delete this milk Entries?');"
                                 title="Delete">
                                <i class="fa fa-trash text-danger"></i>
                              </a>
                            </td>
                          </tr>
                        <?php
                            endif;
                          endforeach;
                          if (!$hasTodayEntries):
                        ?>
                          <tr>
                            <td colspan="8" class="text-center">No customers found for today.</td>
                          </tr>
                        <?php
                          endif;
                        else:
                        ?>
                          <tr>
                            <td colspan="8" class="text-center">No customers found.</td>
                          </tr>
                        <?php endif; ?>   </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>   </section>       
    </div>
    
     

  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $('#customerForm').on('submit', function(e){
      e.preventDefault();
      const $btn = $('#saveBtn');
      $btn.prop('disabled', true).text('Saving...');
      $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
      }).done(function(res){
        if(res.status === 'success'){
          const d = res.data;
          // prepend new row (assumes today)
          const idx = $('#customersTbody tr').length + 1;
          const inTime = d.in_time ? new Date('1970-01-01T'+d.in_time).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '';
          const row = `
            <tr>
              <td>${idx}</td>
              <td>${$('<div/>').text(d.name||'').html()}</td>
              <td>${$('<div/>').text(d.mobile||'').html()}</td>
              <td>${$('<div/>').text(inTime).html()}</td>
              <td>${$('<div/>').text(d.amount||'').html()}</td>
              <td>${$('<div/>').text(d.staff||'').html()}</td>
              <td>${$('<div/>').text(d.payment_method||'').html()}</td>
              <td>
                <a href="<?= BASE_URL ?>customers/delete/${encodeURIComponent(d.id)}" onclick="return confirm('Are you sure you want to delete this entry?');" title="Delete">
                  <i class="fa fa-trash text-danger"></i>
                </a>
              </td>
            </tr>`;
          const $tbody = $('#customersTbody');
          const $empty = $tbody.find('td[colspan="8"]').closest('tr');
          if($empty.length){ $empty.remove(); }
          $tbody.prepend(row);
          $('#customerForm')[0].reset();
        } else {
          alert(res.message || 'Failed to save');
        }
      }).fail(function(){
        alert('Network error. Try again.');
      }).always(function(){
        $btn.prop('disabled', false).text('Submit');
      });
    });
  </script>
 


  
