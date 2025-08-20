<!-- Subscription Popup Modal -->
<div id="subscriptionModal" class="modal" tabindex="-1" role="dialog" style="display:none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Icon added (Font Awesome example) -->
                <span style="font-size: 2rem; margin-right: 10px; color: #007bff;">
                    <i class="fas fa-crown"></i>
                </span>
                <h5 class="modal-title">Basic Plan - ₹699</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeSubscriptionModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    <strong>All features are included in the Basic Plan for just ₹699!</strong>
                </p>
                <ul>
                    <li>Unlimited access to all modules</li>
                    <li>Priority support</li>
                    <li>Regular updates</li>
                    <li>And much more...</li>
                </ul>
                <p>
                    <a href="/public/subscription">View Subscription Details</a>
                </p>
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
    }, 5000);
}

// Example: Always show on page load (customize as needed)
document.addEventListener('DOMContentLoaded', function() {
    showSubscriptionModal();
});
</script>
