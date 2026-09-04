document.addEventListener('DOMContentLoaded', () => {
    // 1. Theme Toggle Logic
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const body = document.body;

    // Check saved theme in localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        body.className = savedTheme;
    }

    themeToggleBtn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        // Save preference
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark-mode');
        } else {
            localStorage.setItem('theme', '');
        }
    });

    // 2. View Switching Logic
    const navItems = document.querySelectorAll('.nav-item');
    const views = document.querySelectorAll('.view-section');
    const pageTitle = document.getElementById('page-title');

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked nav item
            item.classList.add('active');

            // Hide all views
            views.forEach(view => view.classList.remove('active'));

            // Show targeted view
            const targetId = item.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');

            // Update Page Title
            pageTitle.textContent = item.querySelector('span').textContent;
        });
    });

    // 3. Modal Logic
    const btnAddOrg = document.getElementById('btn-add-org');
    const modalAddOrg = document.getElementById('modal-add-org');
    const btnCloseModal = document.getElementById('btn-close-modal');
    const btnCancelModal = document.getElementById('btn-cancel-modal');

    function openModal(modal) {
        modal.classList.add('active');
    }

    function closeModal(modal) {
        modal.classList.remove('active');
    }

    if (btnAddOrg) {
        btnAddOrg.addEventListener('click', () => openModal(modalAddOrg));
    }
    
    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', () => closeModal(modalAddOrg));
    }

    if (btnCancelModal) {
        btnCancelModal.addEventListener('click', () => closeModal(modalAddOrg));
    }

    // Close modal when clicking outside
    modalAddOrg.addEventListener('click', (e) => {
        if (e.target === modalAddOrg) {
            closeModal(modalAddOrg);
        }
    });
});
