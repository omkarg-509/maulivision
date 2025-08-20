<?php
// Simple billing page template
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Billing</h4>
                </div>
                <div class="card-body">
                    <form id="billingForm">
                        <div class="form-group">
                            <label for="customer">Customer Name</label>
                            <input type="text" class="form-control" id="customer" name="customer" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Bill</button>
                    </form>
                    <div id="billingMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('#billingForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/public/billing/create',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#billingMessage').text('Bill created successfully!').css('color', 'green');
                $('#billingForm')[0].reset();
            } else {
                $('#billingMessage').text(response.message).css('color', 'red');
            }
        },
        error: function() {
            $('#billingMessage').text('Something went wrong.').css('color', 'red');
        }
    });
});
</script>
