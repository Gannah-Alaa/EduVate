document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('nav a');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            tab.classList.add('active');

            // Hide all sections
            tabs.forEach(t => {
                const sectionId = t.id.replace('tab-', '');
                const section = document.getElementById(sectionId);
                if (section) section.classList.add('d-none');
            });

            // Show the selected section
            const sectionId = tab.id.replace('tab-', '');
            const section = document.getElementById(sectionId);
            if (section) section.classList.remove('d-none');

            // Scroll to top of container for better UX
            if (section) section.parentElement.scrollIntoView({ behavior: 'smooth' });
        });
    });
});