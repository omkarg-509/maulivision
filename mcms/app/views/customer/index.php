<?php require_once '../app/views/layouts/sidebar.php';?>


<div class="main-content">
  <div class="loader"></div>
  <div id="app">

       
  <section class="section">
     
          </div>
         <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            
            
  <div class="card">
    <div class="card-header">
      <h4>Customers Details</h4>
    </div>
 <div class="card-body">
                    
                   <thead>
  <tr>
    <th scope="col">#</th>
    <th scope="col">Full Name</th>
    <th scope="col">Mobile Number</th>
    <th scope="col">Time</th>
    <th scope="col">Amount</th>
    <th scope="col">Payment Method</th>
    <th scope="col">Action</th>
  </tr>
</thead>
<tbody>
  <?php if (!empty($data['customers'])): ?>
    <?php foreach ($data['customers'] as $index => $cust): ?>
      <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($cust['name']) ?></td>
        <td><?= htmlspecialchars($cust['mobile']) ?></td>
        <td><?= htmlspecialchars($cust['time'] ?? '') ?></td>
        <td><?= htmlspecialchars($cust['amount'] ?? '') ?></td>
        <td><?= htmlspecialchars($cust['payment_method'] ?? '') ?></td>
        <td>
          <a href="/public/customer/delete/<?= urlencode($cust['id']) ?>" 
             onclick="return confirm('Are you sure you want to delete this customer?');" 
             title="Delete">
            <i class="fa fa-trash text-danger"></i>
          </a>
          <a href="/public/customer/show/<?= urlencode($cust['id']) ?>" 
             title="View" class="btn btn-info btn-sm" style="margin-left: 8px;">
            <i class="fa fa-eye"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="7" class="text-center">No customers found.</td>
    </tr>
  <?php endif; ?>
</tbody>

      </div>
    </div>
  </div>


        </section>       
    </div>
    
     

  </div>
 


  