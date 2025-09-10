<?php require_once '../app/views/layouts/sidebar.php';?>


<div class="main-content">
  <div class="loader"></div>
  <div id="app"> 

        <section class="section mt-5">

         <div class="row">

        
             <div class="col-lg-3 col-md-6 col-sm-6 col-6">
              <div class="card card-statistic-1"><a href="<?= BASE_URL ?>dashboard/vendors">
                <i class="fas fa-users card-icon col-green"></i>
                <div class="card-wrap">
                  <div class="padding-20">
                    <div class="text-end">
                      <div class="text-muted small">Vendors</div>
                    <h5 class="font-light mb-0 text-dark">
  <i class="ti-arrow-up text-success"></i>
  <?= isset($vendorCount) ? (int)$vendorCount : 0 ?>
</h5>

                    </div>
                  </div>
                </div> </a>
              </div>
            </div>
            <div class="col-lg-6 col-md-12">
              <div class="card h-100">
                <div class="card-header py-2"><h6 class="mb-0">Recent Vendors</h6></div>
                <div class="card-body p-0">
                  <div class="table-responsive" style="max-height:260px;">
                    <table class="table table-sm mb-0">
                      <thead class="table-light"><tr><th>Name</th><th>Business</th><th>Status</th></tr></thead>
                      <tbody>
                        <?php if(!empty($recentVendors)): foreach($recentVendors as $rv): ?>
                          <tr>
                            <td><?= htmlspecialchars($rv['full_name']) ?></td>
                            <td><?= htmlspecialchars($rv['business_name']) ?></td>
                            <td><span class="badge <?= $rv['status']==='active' ? 'bg-success':'bg-secondary' ?>"><?= htmlspecialchars($rv['status']) ?></span></td>
                          </tr>
                        <?php endforeach; else: ?>
                          <tr><td colspan="3" class="text-muted small">No vendors yet.</td></tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer py-2 text-end"><a href="<?= BASE_URL ?>vendor/index" class="small">View all</a></div>
              </div>
            </div>
               <div class="col-lg-3 col-md-6 col-sm-6 col-6">
              <div class="card card-statistic-1"><a href="#">
                <i class="fas fa-rupee-sign card-icon col-green"></i>
                <div class="card-wrap">
                  <div class="padding-20">
                    <div class="text-end">
                      <div class="text-muted small">Revenue</div>
                      <h5 class="font-light mb-0 text-dark">
                        <i class="ti-arrow-up text-success"></i>
                        <!-- TODO: Wire a real metric here -->
                        0
                      </h5>
                    </div>
                  </div>
                </div> </a>
              </div>
            </div>
        

        
            
          </div>
             </section>




 
        </div>

   
   
    
  </div>
