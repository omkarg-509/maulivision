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
                    <h4> Customer Details</h4>
                  </div>
                  <?php if (!empty($data['customer'])): ?>
                 <form method="POST" action="/public/customer/update/<?= urlencode($data['customer']['id']); ?>">
                    <div class="card-body">
                      <div class="form-group row mb-3">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($data['customer']['vid']); ?>">
                        <label class="col-sm-3 col-form-label text-center">Bill ID</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control"   value="<?= htmlspecialchars($data['customer']['bill_id']); ?>" disabled>
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        
                        <label class="col-sm-3 col-form-label text-center">Full Name</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" required name="name" value="<?= htmlspecialchars($data['customer']['name']); ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-center">Mobile Number</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" required name="mobile" value="<?= htmlspecialchars($data['customer']['mobile']); ?>">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-center">Address</label>
                        <div class="col-sm-9">
                          <textarea class="form-control" required name="address" rows="2"><?= htmlspecialchars($data['customer']['address']); ?></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3 text-center">
                          <button type="submit" class="btn btn-primary px-4">EDIT</button>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php else: ?>
    <p>Customer data not found.</p>
<?php endif; ?>
                </div>
                   </div>
              </div>
            </div>
         <div class="col-lg-12 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>Bill Calculator</h4>
                  <div>
                    <button type="button" class="btn btn-info btn-sm" onclick="generateBill()">
                      <i class="fa fa-calculator"></i> Generate Bill
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="sendWhatsApp()">
                      <i class="fa fa-whatsapp"></i> Send WhatsApp
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="downloadPDF()">
                      <i class="fa fa-download"></i> Download PDF
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Cow Milk Rate (₹ per liter)</label>
                        <input type="number" id="cowRate" class="form-control"  step="0.01" onchange="calculateBill()">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Buffalo Milk Rate (₹ per liter)</label>
                        <input type="number" id="buffaloRate" class="form-control"  step="0.01" onchange="calculateBill()">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Select Date Range</label>
                        <div class="row">
                          <div class="col-6">
                            <input type="date" id="startDate" class="form-control" onchange="filterByDate()">
                          </div>
                          <div class="col-6">
                            <input type="date" id="endDate" class="form-control" onchange="filterByDate()">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Quick Select</label>
                        <select class="form-control" onchange="quickDateSelect(this.value)">
                          <option value="">Select Period</option>
                          <option value="today">Today</option>
                          <option value="yesterday">Yesterday</option>
                          <option value="thisweek">This Week</option>
                          <option value="thismonth">This Month</option>
                          <option value="lastmonth">Last Month</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Bill Summary -->
                  <div class="row mt-3">
                    <div class="col-12">
                      <div class="card bg-light">
                        <div class="card-body">
                          <h5>Bill Summary</h5>
                          <div class="row">
                            <div class="col-md-3">
                              <strong>Cow Milk:</strong> <span id="totalCowLiters">0</span> L
                              <br><strong>Amount:</strong> ₹<span id="cowAmount">0</span>
                            </div>
                            <div class="col-md-3">
                              <strong>Buffalo Milk:</strong> <span id="totalBuffaloLiters">0</span> L
                              <br><strong>Amount:</strong> ₹<span id="buffaloAmount">0</span>
                            </div>
                            <div class="col-md-3">
                              <strong>Total Liters:</strong> <span id="grandTotalLiters">0</span> L
                              <br><strong>Total Amount:</strong> ₹<span id="grandTotalAmount">0</span>
                            </div>
                            <div class="col-md-3">
                              <strong>Days:</strong> <span id="totalDays">0</span>
                              <br><strong>Avg/Day:</strong> <span id="avgPerDay">0</span> L
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
         </div>
         
         <div class="col-lg-12 col-md-12 col-12 col-sm-12">
<div class="card">
  <div class="card-header">
    <h4>Milk Entries</h4>
  </div>
<div class="card-body" style="overflow-x:auto;">
  <table class="table table-sm" id="milkEntriesTable">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Milk Type</th>
        <th scope="col">Milk Liter</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $totalMilkLiter = 0;
      $totalCowLiter = 0;
      $totalBuffaloLiter = 0;
      $days = [];
      if (!empty($data['milk_entries']) && is_array($data['milk_entries'])):
        foreach ($data['milk_entries'] as $index => $entry):
          $totalMilkLiter += floatval($entry['milkliter']);
          
          // Track totals by milk type
          if ($entry['milktype'] === 'cow') {
            $totalCowLiter += floatval($entry['milkliter']);
          } elseif ($entry['milktype'] === 'buffalo') {
            $totalBuffaloLiter += floatval($entry['milkliter']);
          }
          
          // Format date for display
          $entryDate = date('d-m-Y', strtotime($entry['selected_date']));

      ?>
          <tr data-date="<?= htmlspecialchars($entry['selected_date']) ?>" data-milktype="<?= htmlspecialchars($entry['milktype']) ?>" data-liter="<?= htmlspecialchars($entry['milkliter']) ?>">
        <td><?= $index + 1 ?></td>
        <td><?= $entryDate ?></td>
        <td>
          <?php 
            $milkTypeDisplay = $entry['milktype'] === 'cow' ? '🐄 गाय' : ($entry['milktype'] === 'buffalo' ? '🐃 म्हैस' : htmlspecialchars($entry['milktype']));
            echo $milkTypeDisplay;
          ?>
        </td>
        <td><?= htmlspecialchars($entry['milkliter']) ?> L</td>
        <td>
          <?php if (isset($entry['id'])): ?>
          <button onclick="deleteEntry(<?= $entry['id'] ?>)" class="btn btn-danger btn-sm" title="Delete">
            <i class="fa fa-trash"></i>
          </button>
          <?php endif; ?>
        </td>
          </tr>
      <?php
        endforeach;
      
      ?>
      <tr class="table-info font-weight-bold">
        <td colspan="2" class="text-right">Total:</td>
        <td>🐄 <?= $totalCowLiter ?>L | 🐃 <?= $totalBuffaloLiter ?>L</td>
        <td><?= $totalMilkLiter ?> L</td>
        <td>-</td>
      </tr>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No milk entries found.</td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Global variables
let allEntries = [];
let filteredEntries = [];

// Initialize data from PHP
const customerData = {
    name: "<?= htmlspecialchars($data['customer']['name'] ?? '') ?>",
    billId: "<?= htmlspecialchars($data['customer']['bill_id'] ?? '') ?>",
    mobile: "<?= htmlspecialchars($data['customer']['mobile'] ?? '') ?>",
    id: "<?= htmlspecialchars($data['customer']['id'] ?? '') ?>"
};

// Load all entries into JavaScript
$(document).ready(function() {
    // Set default milk rates
    document.getElementById('cowRate').value = 50;
    document.getElementById('buffaloRate').value = 60;
    
    // Extract entries from table
    $('#milkEntriesTable tbody tr[data-date]').each(function() {
        const entry = {
            date: $(this).data('date'),
            milktype: $(this).data('milktype'),
            liter: parseFloat($(this).data('liter')) || 0
        };
        allEntries.push(entry);
    });
    
    filteredEntries = [...allEntries];
    
const now = new Date();

// First day of current month in UTC
const firstDay = new Date(Date.UTC(now.getFullYear(), now.getMonth(), 1));

// Last day of current month in UTC
const lastDay = new Date(Date.UTC(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59));

    
    // Set the date inputs to current month range
    document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
    document.getElementById('endDate').value = lastDay.toISOString().split('T')[0];
    
    // Apply the date filter and calculate bill
    filterByDate();
    calculateBill();
});

// Calculate bill based on current filtered entries
function calculateBill() {
    const cowRate = parseFloat(document.getElementById('cowRate').value) || 0;
    const buffaloRate = parseFloat(document.getElementById('buffaloRate').value) || 0;
    
    let totalCowLiters = 0;
    let totalBuffaloLiters = 0;
    const uniqueDates = new Set();
    
    filteredEntries.forEach(entry => {
        if (entry.milktype === 'cow') {
            totalCowLiters += entry.liter;
        } else if (entry.milktype === 'buffalo') {
            totalBuffaloLiters += entry.liter;
        }
        uniqueDates.add(entry.date.split(' ')[0]); // Get date part only
    });
    
    const cowAmount = totalCowLiters * cowRate;
    const buffaloAmount = totalBuffaloLiters * buffaloRate;
    const grandTotalLiters = totalCowLiters + totalBuffaloLiters;
    const grandTotalAmount = cowAmount + buffaloAmount;
    const totalDays = uniqueDates.size;
    const avgPerDay = totalDays > 0 ? (grandTotalLiters / totalDays).toFixed(2) : 0;
    
    // Update UI
    document.getElementById('totalCowLiters').textContent = totalCowLiters.toFixed(2);
    document.getElementById('totalBuffaloLiters').textContent = totalBuffaloLiters.toFixed(2);
    document.getElementById('cowAmount').textContent = cowAmount.toFixed(2);
    document.getElementById('buffaloAmount').textContent = buffaloAmount.toFixed(2);
    document.getElementById('grandTotalLiters').textContent = grandTotalLiters.toFixed(2);
    document.getElementById('grandTotalAmount').textContent = grandTotalAmount.toFixed(2);
    document.getElementById('totalDays').textContent = totalDays;
    document.getElementById('avgPerDay').textContent = avgPerDay;
}

// Filter entries by date range
function filterByDate() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
        filteredEntries = [...allEntries];
    } else {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        filteredEntries = allEntries.filter(entry => {
            const entryDate = new Date(entry.date.split(' ')[0]);
            return entryDate >= start && entryDate <= end;
        });
    }
    
    calculateBill();
    highlightFilteredRows();
}

// Quick date selection
function quickDateSelect(period) {
    const now = new Date();
    let startDate, endDate;
    
    switch(period) {
        case 'today':
            startDate = endDate = now;
            break;
        case 'yesterday':
            startDate = endDate = new Date(now.getTime() - 24 * 60 * 60 * 1000);
            break;
        case 'thisweek':
            const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay()));
            startDate = startOfWeek;
            endDate = new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000);
            break;
        case 'thismonth':
            startDate = new Date(now.getFullYear(), now.getMonth(), 1);
            endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            break;
        case 'lastmonth':
            startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            endDate = new Date(now.getFullYear(), now.getMonth(), 0);
            break;
        default:
            return;
    }
    
    document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
    document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
    
    filterByDate();
}

// Highlight filtered rows in table
function highlightFilteredRows() {
    $('#milkEntriesTable tbody tr[data-date]').removeClass('table-warning');
    
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        $('#milkEntriesTable tbody tr[data-date]').each(function() {
            const rowDate = new Date($(this).data('date').split(' ')[0]);
            if (rowDate >= start && rowDate <= end) {
                $(this).addClass('table-warning');
            }
        });
    }
}

// Generate and display bill
function generateBill() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
        toastr.error('Please select date range first');
        return;
    }
    
    const billData = {
        customer: customerData,
        startDate: startDate,
        endDate: endDate,
        cowRate: parseFloat(document.getElementById('cowRate').value),
        buffaloRate: parseFloat(document.getElementById('buffaloRate').value),
        summary: {
            cowLiters: parseFloat(document.getElementById('totalCowLiters').textContent),
            buffaloLiters: parseFloat(document.getElementById('totalBuffaloLiters').textContent),
            cowAmount: parseFloat(document.getElementById('cowAmount').textContent),
            buffaloAmount: parseFloat(document.getElementById('buffaloAmount').textContent),
            totalLiters: parseFloat(document.getElementById('grandTotalLiters').textContent),
            totalAmount: parseFloat(document.getElementById('grandTotalAmount').textContent),
            totalDays: parseInt(document.getElementById('totalDays').textContent)
        }
    };
    
    // Show bill in modal or new window
    showBillModal(billData);
}

// Send WhatsApp message (Professional/Classic format)
function sendWhatsApp() {
  const startDate = document.getElementById('startDate').value;
  const endDate = document.getElementById('endDate').value;

  if (!startDate || !endDate) {
    toastr.error('Please select date range first');
    return;
  }

  // Get vendor info from PHP session
  const vendor = {
    business_name: "<?= htmlspecialchars($_SESSION['vendor']['business_name'] ?? 'Rajnandini Dairy') ?>",
    number: "<?= htmlspecialchars($_SESSION['vendor']['business_number'] ?? '9822882755') ?>",
    address: "<?= htmlspecialchars($_SESSION['vendor']['business_address'] ?? 'Mhasoba Chowk, Gaywadi Nal') ?>"
  };

  // Get rates for calculation
  const cowRate = parseFloat(document.getElementById('cowRate').value) || 0;
  const buffaloRate = parseFloat(document.getElementById('buffaloRate').value) || 0;

  // Collect daily milk data for selected date range
  const dailyData = {};
  let totalCowForPeriod = 0;
  let totalBuffaloForPeriod = 0;

  filteredEntries.forEach(entry => {
    const entryDate = entry.date.split(' ')[0];
    if (!dailyData[entryDate]) {
      dailyData[entryDate] = { cow: 0, buffalo: 0 };
    }
    if (entry.milktype === 'cow') {
      dailyData[entryDate].cow += entry.liter;
      totalCowForPeriod += entry.liter;
    } else if (entry.milktype === 'buffalo') {
      dailyData[entryDate].buffalo += entry.liter;
      totalBuffaloForPeriod += entry.liter;
    }
  });

  // Prepare daily summary string with all entries, showing 1, 2, 3... for each date
  let dailySummary = '';
  let dayCount = 0;

  // Sort dates ascending
  const sortedDates = Object.keys(dailyData).sort();
  sortedDates.forEach((dateStr, idx) => {
    const d = dailyData[dateStr];
    const dayTotal = d.cow + d.buffalo;
    dailySummary += `\n${idx + 1}. ${formatDate(dateStr)} | Cow: ${d.cow.toFixed(2)}L | Buffalo: ${d.buffalo.toFixed(2)}L | Total: ${dayTotal.toFixed(2)}L`;
  });
  dayCount = sortedDates.length;

  // Calculate amounts
  const cowAmount = totalCowForPeriod * cowRate;
  const buffaloAmount = totalBuffaloForPeriod * buffaloRate;
  const totalAmount = cowAmount + buffaloAmount;
  const avgPerDay = dayCount > 0 ? ((totalCowForPeriod + totalBuffaloForPeriod) / dayCount).toFixed(2) : 0;

  const message = `
${vendor.business_name}
${vendor.address}
Contact: ${vendor.number}

Customer: ${customerData.name}
Bill ID: ${customerData.billId}
Period: ${formatDate(startDate)} to ${formatDate(endDate)}

--------------------------
Summary:
Cow Milk: ${totalCowForPeriod.toFixed(2)}L × ₹${cowRate} = ₹${cowAmount.toFixed(2)}
Buffalo Milk: ${totalBuffaloForPeriod.toFixed(2)}L × ₹${buffaloRate} = ₹${buffaloAmount.toFixed(2)}
--------------------------
Total Liters: ${(totalCowForPeriod + totalBuffaloForPeriod).toFixed(2)}L
Total Amount: ₹${totalAmount.toFixed(2)}
Days: ${dayCount}
Avg/Day: ${avgPerDay}L

--------------------------
Daily Details:
${dailySummary}

Thank you for your business!
  `.trim();

  const whatsappUrl = `https://wa.me/${customerData.mobile}?text=${encodeURIComponent(message)}`;
  window.open(whatsappUrl, '_blank');
}

// Download PDF
function downloadPDF() {
  const startDate = document.getElementById('startDate').value;
  const endDate = document.getElementById('endDate').value;

  if (!startDate || !endDate) {
    toastr.error('Please select date range first');
    return;
  }

  // Get rates
  const cowRate = parseFloat(document.getElementById('cowRate').value) || 0;
  const buffaloRate = parseFloat(document.getElementById('buffaloRate').value) || 0;

  // Format dates as yyyy-mm-dd for URL
  const formattedStart = startDate;
  const formattedEnd = endDate;

  // Open PDF in new window for selected date range and rates
  const pdfUrl = `<?php echo BASE_URL; ?>customer/pdf/${customerData.id}/${formattedStart}/${formattedEnd}?cow_rate=${encodeURIComponent(cowRate)}&buffalo_rate=${encodeURIComponent(buffaloRate)}`;
  window.open(pdfUrl, '_blank');
}

// Delete entry with AJAX
function deleteEntry(entryId) {
    if (!confirm('Are you sure you want to delete this entry?')) {
        return;
    }
    
    $.ajax({
        url: `<?php echo BASE_URL; ?>dailyentry/delete/${entryId}`,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.success('Entry deleted successfully');
                location.reload(); // Reload to update the data
            } else {
                toastr.error(response.message || 'Failed to delete entry');
            }
        },
        error: function() {
            toastr.error('An error occurred while deleting');
        }
    });
}

// Show bill modal
function showBillModal(billData) {
  // Get vendor info from PHP session (passed via JS)
  const vendor = {
    business_name: "<?= htmlspecialchars($_SESSION['vendor']['business_name'] ?? 'Rajnandini Dairy') ?>",
    number: "<?= htmlspecialchars($_SESSION['vendor']['business_number'] ?? '9822882755') ?>",
    address: "<?= htmlspecialchars($_SESSION['vendor']['business_address'] ?? 'Mhasoba Chowk, Gaywadi Nal') ?>"
  };

  const modalHtml = `
  <div class="modal fade" id="billModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">🥛 Milk Bill - ${billData.customer.name}</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-3">
            <h4>${vendor.business_name}</h4>
            <p>${vendor.address}<br>Phone: ${vendor.number}</p>
          </div>
          <hr>
          <div class="row mb-3">
            <div class="col-6"><strong>Customer:</strong> ${billData.customer.name}</div>
            <div class="col-6"><strong>Bill ID:</strong> ${billData.customer.billId}</div>
            <div class="col-6"><strong>Period:</strong> ${formatDate(billData.startDate)} to ${formatDate(billData.endDate)}</div>
            <div class="col-6"><strong>Total Days:</strong> ${billData.summary.totalDays}</div>
          </div>
          <table class="table table-bordered">
            <tr>
              <th>Milk Type</th>
              <th>Quantity (L)</th>
              <th>Rate (₹/L)</th>
              <th>Amount (₹)</th>
            </tr>
            <tr>
              <td>🐄 Cow Milk</td>
              <td>${billData.summary.cowLiters}</td>
              <td>${billData.cowRate}</td>
              <td>₹${billData.summary.cowAmount.toFixed(2)}</td>
            </tr>
            <tr>
              <td>🐃 Buffalo Milk</td>
              <td>${billData.summary.buffaloLiters}</td>
              <td>${billData.buffaloRate}</td>
              <td>₹${billData.summary.buffaloAmount.toFixed(2)}</td>
            </tr>
            <tr class="table-info">
              <th>Total</th>
              <th>${billData.summary.totalLiters} L</th>
              <th>-</th>
              <th>₹${billData.summary.totalAmount.toFixed(2)}</th>
            </tr>
          </table>
          <p class="text-center mt-3"><em>Please arrange to pay the bill amount immediately.</em></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="sendWhatsApp()">
            <i class="fa fa-whatsapp"></i> Send WhatsApp
          </button>
          <button type="button" class="btn btn-primary" onclick="downloadPDF()">
            <i class="fa fa-download"></i> Download PDF
          </button>
          <button type="button"  data-bs-dismiss="modal" aria-label="Close" class="close btn btn-secondary">Close</button>
        </div>
      </div>
    </div>
  </div>`;

  // Remove existing modal and add new one
  $('#billModal').remove();
  $('body').append(modalHtml);
  $('#billModal').modal('show');
}

// Format date helper
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-IN');
}
</script>
 


  