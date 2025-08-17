<h2>Subscribe Now</h2>
<button id="rzp-button">Pay Now</button>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-button').onclick = function(e){
    var options = {
        "key": "9VaUdxAxxPjJHBb0UVURRZ3T", // Enter the Key ID generated from the Dashboard
        "amount": "1000", // Amount is in currency subunits. 1000 = 10.00
        "currency": "INR",
        "name": "Milk Dairy",
        "description": "Subscription Payment",
        "handler": function (response){
            // Redirect after payment success
            window.location.href = "/thank-you.php";
        },
        "theme": {
            "color": "#3399cc"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
}
</script>
