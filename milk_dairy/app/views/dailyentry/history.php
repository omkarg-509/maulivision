<?php require_once '../app/views/layouts/sidebar.php';?>
<div class="main-content">
  <div class="loader" style="display:none"></div>
  <section class="section">
    <div class="section-header">
          <h4>दैनिक नोंद इतिहास</h4>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-sm-3">
                <label class="form-label">सुरुवातीची तारीख</label>
              <input type="date" name="start" id="startDate" class="form-control" required>
            </div>
            <div class="col-sm-3">
                <label class="form-label">शेवटची तारीख</label>
              <input type="date" name="end" id="endDate" class="form-control" required>
            </div>
            <div class="col-sm-3">
                <label class="form-label">ग्राहक शोधा</label>
                <input type="text" id="searchCustomer" class="form-control" placeholder="फिल्टर करण्यासाठी टाइप करा...">
            </div>
            <div class="col-sm-3">
                <button type="submit" class="btn btn-primary w-100">फिल्टर लावा</button>
            </div>
          </form>
          <div class="row mt-3" id="todayTotals" style="display:none;">
            <div class="col-12">
              <div class="alert alert-info py-2 mb-0">
                  <strong>आजचे एकूण:</strong> <span id="todayTotalLiters">0.00</span> L
                  (गाय: <span id="todayCowLiters">0.00</span> | म्हैस: <span id="todayBuffaloLiters">0.00</span>)
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body table-responsive" id="history-table-wrapper">
          <table class="table table-sm table-bordered" id="historyTable">
            <thead>
              <tr>
                  <th>तारीख</th>
                  <th>ग्राहक</th>
                  <th>गाय (L)</th>
                  <th>म्हैस (L)</th>
                  <th>एकूण (L)</th>
              </tr>
            </thead>
            <tbody id="historyBody">
                <tr><td colspan="5" class="text-center">डेटा पाहण्यासाठी तारीख श्रेणी निवडा.</td></tr>
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
  $('#historyBody').html('<tr><td colspan="5" class="text-center">लोड होत आहे...</td></tr>');
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
        updateTodayTotals(resp.data);
      }else{
        $('#historyBody').html('<tr><td colspan="5" class="text-center">निवडलेल्या कालावधीसाठी डेटा उपलब्ध नाही.</td></tr>');
        updateTodayTotals([]);
      }
    },
    error: function(){
      $('#historyBody').html('<tr><td colspan="5" class="text-center text-danger">डेटा लोड करण्यात अयशस्वी.</td></tr>');
      updateTodayTotals([]);
    }
  });
}

function updateTodayTotals(data){
  const todayStr = new Date().toISOString().slice(0,10); // YYYY-MM-DD
  let cow = 0, buffalo = 0;
  data.forEach(function(r){
    if(r.date === todayStr){
      cow += parseFloat(r.cow_liter)||0;
      buffalo += parseFloat(r.buffalo_liter)||0;
    }
  });
  const total = cow + buffalo;
  if(cow>0 || buffalo>0){
    $('#todayTotals').show();
  } else {
    $('#todayTotals').show(); // still show with zeros for clarity
  }
  $('#todayCowLiters').text(cow.toFixed(2));
  $('#todayBuffaloLiters').text(buffalo.toFixed(2));
  $('#todayTotalLiters').text(total.toFixed(2));
}
</script>
