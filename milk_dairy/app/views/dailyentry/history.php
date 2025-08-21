<?php require_once '../app/views/layouts/sidebar.php';?>
<div class="main-content">
  <div class="loader" style="display:none"></div>
  <section class="section">
    <div class="section-header">
      <h4>Daily Entry History</h4>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-sm-3">
              <label class="form-label">Start Date</label>
              <input type="date" name="start" id="startDate" class="form-control" required>
            </div>
            <div class="col-sm-3">
              <label class="form-label">End Date</label>
              <input type="date" name="end" id="endDate" class="form-control" required>
            </div>
            <div class="col-sm-3">
              <label class="form-label">Search Customer</label>
              <input type="text" id="searchCustomer" class="form-control" placeholder="Type to filter...">
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <div class="card-body table-responsive" id="history-table-wrapper">
          <table class="table table-sm table-bordered" id="historyTable">
            <thead>
              <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Cow (L)</th>
                <th>Buffalo (L)</th>
                <th>Total (L)</th>
              </tr>
            </thead>
            <tbody id="historyBody">
              <tr><td colspan="5" class="text-center">Select a date range to load data.</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
(function(){
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth()+1).padStart(2,'0');
  const firstDay = yyyy+'-'+mm+'-01';
  const currentDay = yyyy+'-'+mm+'-'+String(today.getDate()).padStart(2,'0');
  $('#startDate').val(firstDay);
  $('#endDate').val(currentDay);
  loadHistory(firstDay,currentDay);
})();

$('#filterForm').on('submit', function(e){
  e.preventDefault();
  const start = $('#startDate').val();
  const end = $('#endDate').val();
  loadHistory(start,end);
});

$('#searchCustomer').on('keyup', function(){
  const value = $(this).val().toLowerCase();
  $('#historyBody tr').filter(function(){
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
  });
});

function loadHistory(start,end){
  $('#historyBody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
  $.ajax({
    url: '<?php echo BASE_URL; ?>dailyentry/historyData',
    method: 'GET',
    data: { start, end },
    dataType: 'json',
    success: function(resp){
      if(resp.success && resp.data.length){
        let rows='';
        resp.data.forEach(function(r){
          const total = (parseFloat(r.cow_liter)||0)+(parseFloat(r.buffalo_liter)||0);
          rows += `<tr>
            <td>${r.date}</td>
            <td>${r.customer_name}</td>
            <td>${r.cow_liter || 0}</td>
            <td>${r.buffalo_liter || 0}</td>
            <td>${total.toFixed(2)}</td>
          </tr>`;
        });
        $('#historyBody').html(rows);
      }else{
        $('#historyBody').html('<tr><td colspan="5" class="text-center">No data found for selected range.</td></tr>');
      }
    },
    error: function(){
      $('#historyBody').html('<tr><td colspan="5" class="text-center text-danger">Failed to load data.</td></tr>');
    }
  });
}
</script>
