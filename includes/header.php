<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/index.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<header class="header">
    <div class="header-content">
        <div class="header-logo">
            <img src="/Eduqueue-Queueing-System/img/SLC LOGO.png" alt="SLC logo" />
        </div>
        <div class="header-text">
            <p class="header-title">Saint Louis College</p>
            <p class="header-subtitle">City of San Fernando, La Union</p>
            <p class="header-system">Queueing Management System with Payment Tracking</p>
        </div>
    </div>
</header>
<script>
// On bfcache restore or back/forward navigation, force a reload so the
// server re-validates session state. Some browsers don't always set
// `event.persisted` for forward navigations, so also check the
// Navigation Timing API for a back_forward navigation type.
window.addEventListener('pageshow', function (event) {
    try {
        var navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
        var navType = (navEntries && navEntries.length) ? navEntries[0].type : null;
        var isBackForward = event.persisted || navType === 'back_forward';
        if (isBackForward) {
            // Reload from server so auth checks run (no-cache headers also help).
            window.location.reload();
        }
    } catch (e) {
        // Fallback: if something goes wrong, be conservative and reload when persisted is true
        if (event.persisted) {
            window.location.reload();
        }
    }
});
</script>