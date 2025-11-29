(function () {
    const path = location.pathname;

    const refreshPages = [
        "dashboard.php",
        "student_dashboard.php",
        "reports.php"
    ];

    if (refreshPages.some(page => path.endsWith(page))) {
        setInterval(refreshQueueData, 10000); // Refresh every 10 seconds
    }

    function refreshQueueData() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // STUDENT DASHBOARD ELEMENTS
                updateElementIfExists('.alert.alert-primary', doc); // Student queue card
                updateElementIfExists('.card.mb-3', doc); // Student now serving
                updateElementIfExists('.stats-box', doc); // Student statistics
                
                // CASHIER DASHBOARD ELEMENTS
                updateElementIfExists('.now-serving', doc); // Cashier now serving card
                updateElementIfExists('.list-unstyled', doc); // Cashier waiting list
                
                console.log('Queue data refreshed silently');
            })
            .catch(error => console.error('Refresh failed:', error));
    }

    function updateElementIfExists(selector, newDoc) {
        const currentElement = document.querySelector(selector);
        const newElement = newDoc.querySelector(selector);
        
        if (currentElement && newElement) {
            currentElement.innerHTML = newElement.innerHTML;
        }
    }
})();