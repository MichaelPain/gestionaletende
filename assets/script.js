
document.addEventListener('DOMContentLoaded', function () {
    // Toggle visibility of sections
    const toggles = document.querySelectorAll('.toggle-section');
    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const target = document.getElementById(targetId);
            if (target) {
                target.style.display = (target.style.display === 'none') ? 'block' : 'none';
            }
        });
    });

    // Highlight active navigation link
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(function (link) {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });

    // Confirm before deleting items
    const deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            if (!confirm('Sei sicuro di voler eliminare questo elemento?')) {
                e.preventDefault();
            }
        });
    });
});
