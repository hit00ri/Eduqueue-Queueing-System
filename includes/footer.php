<?php

?>
<footer class="footer">
  <p>&copy; 2025 Saint Louis College of San Fernando, La Union. All rights reserved.</p>
</footer>

<!-- Confirm Logout Modal (app-wide) -->
<div id="confirmLogoutModal" class="confirm-logout-modal" aria-hidden="true" style="display:none;">
  <div class="confirm-logout-backdrop"></div>
  <div class="confirm-logout-dialog">
    <div class="confirm-logout-header">
      <h5>Confirm Logout</h5>
    </div>
    <div class="confirm-logout-body">
      <p>Are you sure you want to log out? Your current session will be ended.</p>
    </div>
    <div class="confirm-logout-footer">
      <button id="confirmLogoutCancel" class="btn btn-outline-secondary">Cancel</button>
      <button id="confirmLogoutProceed" class="btn btn-primary">Logout</button>
    </div>
  </div>
</div>

<style>
/* Modal styles using the system accent color */
.confirm-logout-modal { position: fixed; inset: 0; z-index: 2000; display:flex; align-items:center; justify-content:center; }
.confirm-logout-backdrop { position:absolute; inset:0; background: rgba(0,0,0,0.45); }
.confirm-logout-dialog { position:relative; width: 360px; max-width: 92%; background: var(--card-bg); border-radius: 10px; box-shadow: 0 12px 40px rgba(3, 61, 122, 0.25); overflow: hidden; border:1px solid var(--border); }
.confirm-logout-header { padding: 14px 18px; background: linear-gradient(90deg, var(--accent), #003d7a); color: white; }
.confirm-logout-header h5 { margin:0; font-size:1rem; }
.confirm-logout-body { padding: 18px; color: var(--text); }
.confirm-logout-footer { padding: 12px; display:flex; gap:10px; justify-content:flex-end; background: transparent; }
.confirm-logout-footer .btn { min-width: 90px; }
</style>

<script>
// Simple modal logic: intercept clicks on anchors with .confirm-logout
(function(){
  var modal = document.getElementById('confirmLogoutModal');
  var backdrop = modal && modal.querySelector('.confirm-logout-backdrop');
  var cancelBtn = document.getElementById('confirmLogoutCancel');
  var proceedBtn = document.getElementById('confirmLogoutProceed');
  var targetHref = null;

  function showModal(href) {
    targetHref = href;
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function hideModal() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    targetHref = null;
  }

  document.addEventListener('click', function(e){
    var el = e.target;
    // Walk up to anchor
    while (el && el !== document.body) {
      if (el.matches && el.matches('a.confirm-logout')) break;
      el = el.parentNode;
    }
    if (!el || el === document.body) return;
    e.preventDefault();
    var href = el.getAttribute('href');
    if (!href) return;
    showModal(href);
  }, false);

  if (cancelBtn) cancelBtn.addEventListener('click', function(){ hideModal(); });
  if (backdrop) backdrop.addEventListener('click', function(){ hideModal(); });
  if (proceedBtn) proceedBtn.addEventListener('click', function(){
    if (targetHref) {
      // allow normal navigation
      window.location.href = targetHref;
    }
  });
})();
</script>