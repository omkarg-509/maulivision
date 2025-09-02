<?php require_once '../app/views/layouts/sidebar.php';?>


<div class="main-content">
  <div class="loader"></div>
  <div id="app"> 

        <section class="section mt-5">

         <div class="row">

        
             <div class="col-lg-3 col-md-6 col-sm-6 col-6">
              <div class="card card-statistic-1">
                <a href="<?= BASE_URL ?>dashboard/vendors" class="stretched-link text-decoration-none">
                  <i class="fas fa-users card-icon col-green"></i>
                  <div class="card-wrap">
                    <div class="padding-20">
                      <div class="text-end">
                        <h5 class="font-light mb-0 text-dark">
                          <span class="text-success"><i class="ti-arrow-up"></i></span>
                          <?= isset($vendorCount) ? (int)$vendorCount : 0; ?>
                        </h5>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
               <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                <div class="card card-statistic-1">
                  <div class="card-wrap">
                    <div class="padding-20">
                      <div class="text-end">
                        <h5 class="font-light mb-0 text-dark">
                          <span class="text-success"><i class="ti-arrow-up"></i></span>
                          <!-- Placeholder metric; wire up when available -->
                          0
                        </h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        

        
            
          </div>
             </section>




 
        </div>

   
   
    
  </div>
