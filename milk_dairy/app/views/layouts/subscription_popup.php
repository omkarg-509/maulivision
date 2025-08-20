<!-- Subscription Popup Modal -->
<div id="subscriptionModal" class="modal" tabindex="-1" role="dialog" style="display:none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Subscription Notice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeSubscriptionModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Your subscription is required to access all features. <a href="/public/subscription">View Subscription</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="window.location.href='/public/subscription'">Go to Subscription</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeSubscriptionModal()">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
function showSubscriptionModal() {
    var modal = document.getElementById('subscriptionModal');
    modal.style.display = 'block';
    modal.style.opacity = 0;
    modal.style.transition = 'opacity 0.3s ease';
    setTimeout(function() {
        modal.style.opacity = 1;
    }, 10);
}

function closeSubscriptionModal() {
    var modal = document.getElementById('subscriptionModal');
    modal.style.opacity = 0;
    setTimeout(function() {
        modal.style.display = 'none';
    }, 300);
}

// Example: Always show on page load (customize as needed)
document.addEventListener('DOMContentLoaded', function() {
    showSubscriptionModal();
});
</script>
