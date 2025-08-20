<?php
require_once '../app/models/Customer.php';
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // If using auth
             // Subscription Popup Modal
        echo <<<HTML
    <!-- Subscription Popup Modal -->
    <div id="subscriptionModal" class="modal" tabindex="-1" role="dialog" style="display:none; align-items: center; justify-content: center; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="modal-dialog" role="document" style="margin: 0; max-width: 90vw; width: 350px;">
        <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
            <div class="modal-header" style="display: flex; align-items: center;">
            <span style="font-size: 2rem; margin-right: 10px; color: #007bff;">
                <i class="fas fa-crown"></i>
            </span>
            <h5 class="modal-title" style="flex: 1;">Basic Plan - ₹699</h5>
            <button type="button" class="close" aria-label="Close" onclick="closeSubscriptionModal()" style="background: none; border: none; font-size: 1.5rem;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" style="text-align: center;">
            <p>
                <strong>Get all features for just ₹699!</strong>
            </p>
            <ul style="text-align: left; margin: 0 auto; max-width: 250px; font-size: 1rem;">
                <li>Unlimited modules</li>
                <li>Priority support</li>
                <li>Regular updates</li>
            </ul>
            <p style="margin-top: 1rem;">
                <a href="/public/subscription">More Info</a>
            </p>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: center; gap: 10px;">
            <button type="button" class="btn btn-primary" style="width: 120px;" onclick="window.location.href='/public/subscription'">Pay Now</button>
            <button type="button" class="btn btn-secondary" style="width: 120px;" onclick="closeSubscriptionModal()">No, Thanks</button>
            </div>
        </div>
        </div>
    </div>
    <style>
    @media (max-width: 600px) {
        #subscriptionModal .modal-dialog {
        width: 95vw !important;
        max-width: 95vw !important;
        }
        #subscriptionModal .modal-content {
        min-height: 60vh;
        }
    }
    </style>
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
        }, 500);
    }

    // Example: Always show on page load (customize as needed)
    document.addEventListener('DOMContentLoaded', function() {
        showSubscriptionModal();
    });
    </script>
    HTML;
        $this->view('dashboard/index');
    }
    
    
}
