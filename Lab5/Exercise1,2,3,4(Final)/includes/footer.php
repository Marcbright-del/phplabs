    <script>
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleButton = document.querySelector('.sidebar-toggle-btn');
        const pageTitle = document.getElementById('page-title'); // For dynamic title if needed

        if (toggleButton && sidebar && mainContent) {
            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });
        }

        // Persist sidebar state (optional, using localStorage)
        const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isSidebarCollapsed && sidebar && mainContent) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        }

        if (toggleButton && sidebar) {
            toggleButton.addEventListener('click', () => {
                // ... (toggle logic from previous example) ...
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        }
    </script>
</body>
</html>