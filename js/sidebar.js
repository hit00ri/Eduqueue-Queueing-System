// sidebar.js - Active page highlighting without modifying PHP files

document.addEventListener('DOMContentLoaded', function() {
    // Function to highlight the active page in sidebar
    function highlightActivePage() {
        // Get current page URL
        const currentUrl = window.location.href;
        
        // Get all sidebar links
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        
        // Find and highlight the active link
        sidebarLinks.forEach(link => {
            const linkUrl = link.getAttribute('href');
            
            // Check if current URL contains the link's URL (for relative paths)
            if (currentUrl.includes(linkUrl) && linkUrl !== '#') {
                // Remove active class from all links first
                sidebarLinks.forEach(l => l.classList.remove('active'));
                
                // Add active class to current link
                link.classList.add('active');
            }
        });
        
        // Fallback: If no link is active, try to match by page name
        if (!document.querySelector('.sidebar-link.active')) {
            const currentPage = currentUrl.split('/').pop() || 'index.php';
            
            sidebarLinks.forEach(link => {
                const linkHref = link.getAttribute('href');
                const linkPage = linkHref.split('/').pop();
                
                if (linkPage === currentPage) {
                    sidebarLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            });
        }
    }
    
    // Function to handle navigation and update active state
    function setupSidebarNavigation() {
        const sidebarLinks = document.querySelectorAll('.sidebar-link:not(.logout-container .sidebar-link)');
        
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // For demonstration - in real implementation, this would navigate naturally
                // Remove active class from all links
                sidebarLinks.forEach(l => l.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // In a real implementation, you wouldn't prevent default
                // e.preventDefault();
            });
        });
    }
    
    // Initialize the sidebar functionality
    highlightActivePage();
    setupSidebarNavigation();
    
    // Re-run when URL changes (for single-page applications)
    window.addEventListener('popstate', highlightActivePage);
});

// Additional utility function for dynamic content
function updateActiveSidebarLink(pageUrl) {
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    
    sidebarLinks.forEach(link => {
        link.classList.remove('active');
        
        const linkUrl = link.getAttribute('href');
        if (linkUrl === pageUrl || window.location.href.includes(linkUrl)) {
            link.classList.add('active');
        }
    });
}