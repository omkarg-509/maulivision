<?php if (!defined('RAZORPAY_KEY_ID')) { require_once '../config/payment.php'; } ?>
<div id="subscriptionModal" class="modal" tabindex="-1" role="dialog" style="display:none; align-items:center; justify-content:center; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.55); z-index:9999;">
	<div class="modal-dialog" role="document" style="margin:0; max-width:400px; width:92%;">
		<div class="modal-content" style="border-radius:10px; overflow:hidden;">
			<div class="modal-header" style="display:flex; align-items:center;">
				<span style="font-size:1.8rem; margin-right:10px; color:#ffc107;"><i class="fas fa-crown"></i></span>
				<h5 class="modal-title" style="flex:1;">Activate Subscription</h5>
				<button type="button" class="close" onclick="closeSubscriptionModal()" style="background:none; border:none; font-size:1.5rem;">&times;</button>
			</div>
			<div class="modal-body" style="text-align:center;">
				<p><strong>Select a plan to continue using the app.</strong></p>
				<div class="list-group mb-3" id="planList">
					<label class="list-group-item d-flex justify-content-between align-items-center" style="cursor:pointer;">
						<span>
							<input type="radio" name="plan_id" value="basic_monthly" checked>
							Basic Monthly (₹699 / 30 days)
						</span>
						<span class="badge bg-primary">Popular</span>
					</label>
					<label class="list-group-item d-flex justify-content-between align-items-center" style="cursor:pointer;">
						<span>
							<input type="radio" name="plan_id" value="trial_week">
							Trial 7 Days (Free)
						</span>
						<span class="badge bg-success">Trial</span>
					</label>
				</div>
				<div id="subStatus" class="small text-danger mb-2" style="display:none;"></div>
			</div>
			<div class="modal-footer" style="display:flex; justify-content:center; gap:10px;">
				<button type="button" class="btn btn-primary" onclick="beginSubscription()" id="btnActivate">Proceed</button>
				<button type="button" class="btn btn-secondary" onclick="closeSubscriptionModal()">Later</button>
			</div>
		</div>
	</div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function showSubscriptionModal(){
	const m=document.getElementById('subscriptionModal'); if(!m) return; m.style.display='flex'; m.style.opacity=0; m.style.transition='opacity .25s'; setTimeout(()=>m.style.opacity=1,10);
}
function closeSubscriptionModal(snooze=true){ const m=document.getElementById('subscriptionModal'); if(!m) return; m.style.opacity=0; setTimeout(()=>{m.style.display='none';},250); if(snooze){ const dt=new Date(Date.now()+2*60*60*1000); document.cookie='hide_sub_popup_until='+dt.toISOString()+'; path=/'; } }
			<button type="button" class="btn btn-secondary" onclick="closeSubscriptionModal(true)">Later</button>
document.addEventListener('DOMContentLoaded',()=>{ showSubscriptionModal(); });

function beginSubscription(){
	const plan = document.querySelector('input[name="plan_id"]:checked').value;
	if(plan==='trial_week'){ // Instant activation without payment
		freeActivate(plan); return;
	}
	const btn = document.getElementById('btnActivate'); btn.disabled=true; btn.textContent='Creating order...';
	fetch('<?php echo BASE_URL; ?>subscription/createOrder', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'plan_id='+encodeURIComponent(plan)})
	.then(r=>r.json()).then(j=>{
		if(!j.success){ showStatus(j.message || 'Failed to create order'); btn.disabled=false; btn.textContent='Proceed'; return; }
		launchRazorpay(j);
	}).catch(()=>{ showStatus('Network error'); btn.disabled=false; btn.textContent='Proceed'; });
}

function launchRazorpay(payload){
	const {order,key,plan} = payload;
	const options = {
		key: key,
		amount: order.amount,
		currency: order.currency,
		name: 'Mauli Vision',
		description: plan.plan_name,
		order_id: order.id,
		handler: function (response){ verifyPayment(response, plan); },
		theme:{color:'#0d6efd'}
	};
	const rzp = new Razorpay(options);
	rzp.on('payment.failed', function (resp){ showStatus(resp.error.description || 'Payment failed'); document.getElementById('btnActivate').disabled=false; document.getElementById('btnActivate').textContent='Proceed'; });
	rzp.open();
}

function verifyPayment(resp, plan){
	const fd = new URLSearchParams();
	fd.append('razorpay_payment_id', resp.razorpay_payment_id);
	fd.append('razorpay_order_id', resp.razorpay_order_id);
	fd.append('razorpay_signature', resp.razorpay_signature);
	fd.append('plan_id', document.querySelector('input[name="plan_id"]:checked').value);
	showStatus('Verifying payment...');
	fetch('<?php echo BASE_URL; ?>subscription/verify', {method:'POST', body:fd})
		.then(r=>r.json())
		.then(j=>{
			if(j.success){ showStatus('Activated till '+j.end_date, 'green'); setTimeout(()=>{ closeSubscriptionModal(); location.reload(); },1000); }
			else { showStatus(j.message || 'Verification failed'); }
		}).catch(()=>showStatus('Verification network error'));
}

function freeActivate(planId){
	// Free plan activation: server skips signature check for zero-amount plan
	const fd = new URLSearchParams();
	fd.append('razorpay_payment_id','FREE-'+Date.now());
	fd.append('razorpay_order_id','FREE-'+Date.now());
	fd.append('razorpay_signature','manual');
	fd.append('plan_id', planId);
	showStatus('Activating trial...');
	fetch('<?php echo BASE_URL; ?>subscription/verify', {method:'POST', body:fd})
		.then(r=>r.json()).then(j=>{ if(j.success){ showStatus('Trial active till '+j.end_date,'green'); setTimeout(()=>{ closeSubscriptionModal(false); location.reload(); },900);} else { showStatus(j.message || 'Activation failed'); } })
		.catch(()=>showStatus('Network error during activation'));
}

function showStatus(msg,color){ const el=document.getElementById('subStatus'); if(!el) return; el.style.display='block'; el.style.color=color||'red'; el.textContent=msg; }
</script>
