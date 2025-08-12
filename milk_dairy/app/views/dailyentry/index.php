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
                <form id="dailyEntryForm">
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
                          <input class="form-check-input" type="checkbox" name="milkliter" id="liter1" value="1.0" onclick="selectOnlyThis(this)">
                          <label class="form-check-label" for="liter1">1.0 Liter</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="milkliter" id="liter15" value="1.5" onclick="selectOnlyThis(this)">
                          <label class="form-check-label" for="liter15">1.5 Liter</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="milkliter" id="liter2" value="2.0" onclick="selectOnlyThis(this)">
                          <label class="form-check-label" for="liter2">2.0 Liter</label>
                        </div>
                      </div>
                    </div>
                    <script>
                    function selectOnlyThis(checkbox) {
                      var checkboxes = document.getElementsByName('milkliter');
                      checkboxes.forEach(function(item) {
                        if (item !== checkbox) item.checked = false;
                      });
                    }
                    </script>
                    <div class="form-group row">
                      <div class="col-sm-9 offset-sm-3 text-center">
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                      </div>
                    </div>
                    <div id="loginMessage" class="text-success text-center mb-2"></div>
                  </div>
                </form>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $('#dailyEntryForm').on('submit', function(e) {
          e.preventDefault();

          $.ajax({
            url: '/public/dailyentry/store',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
              if (response.status === 'success') {
                $('#loginMessage').text(response.message || 'Entry added successfully!');
                $('#dailyEntryForm')[0].reset();
                setTimeout(function() {
                  window.location.href = response.redirect;
                }, 1500);
              } else {
                $('#loginMessage').text(response.message).addClass('text-danger').removeClass('text-success');
              }
            },
            error: function() {
              $('#loginMessage').text('Something went wrong.').addClass('text-danger').removeClass('text-success');
            }
          });
        });
        </script>

                </div>
                   </div>
              </div>
            </div>
         <div class="col-lg-12 col-md-12 col-12 col-sm-12">
  <div class="card">
    <div class="card-header">
      <h4>Customers Details</h4>
    </div>
 <div class="card-body">
                    
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
          <tbody>
          <?php if (!empty($data['dailyEntries'])): ?>
            <?php foreach ($data['dailyEntries'] as $index => $cust): ?>
              <tr>
                <td><?=$index + 1 ?></td>
                <td><?=htmlspecialchars($cust['customer_name']) ?></td>
                <td><?=htmlspecialchars($cust['milktype']) ?></td>
                <td><?=htmlspecialchars(ucfirst($cust['milkliter'])) ?></td>
                <td>
                  <a href="/public/dailyentry/delete/<?= urlencode($cust['id']) ?>" 
                     onclick="return confirm('Are you sure you want to delete this milk Entries?');" 
                     title="Delete">
                    <i class="fa fa-trash text-danger"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center">No customers found.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>


        </section>       
    </div>
    
     

  </div>
 


  
