<h2>Subscribe Now</h2>
<button id="rzp-button">Pay Now</button>
<script type="text/javascript" src="https://checkout.razorpay.com/v1/razorpay.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('rzp-button').onclick = function(e){
        var options = {
            "key": "oVKvMtV2cAxiNrlYxpe7uf0i", // Enter the Key ID generated from the Dashboard
            "amount": 1000, // Amount is in currency subunits. 1000 = 10.00
            "currency": "INR",
            "name": "Milk Dairy",
            "description": "Subscription Payment",
            "handler": function (response){
                // Redirect after payment success
                window.location.href = "thank-you.php";
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
        e.preventDefault();
    }
});
</script>
