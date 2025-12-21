// Mobile Menu Toggle for Page Hero Header
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobilePanel = document.querySelector('.mobile-menu-panel');
    const mobileClose = document.querySelector('.mobile-menu-close');
    
    if (mobileToggle && mobilePanel) {
        mobileToggle.addEventListener('click', function() {
            mobilePanel.classList.add('active');
        });
    }
    
    if (mobileClose && mobilePanel) {
        mobileClose.addEventListener('click', function() {
            mobilePanel.classList.remove('active');
        });
    }
    
    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (mobilePanel && mobilePanel.classList.contains('active')) {
            if (!mobilePanel.contains(e.target) && !mobileToggle.contains(e.target)) {
                mobilePanel.classList.remove('active');
            }
        }
    });
});
