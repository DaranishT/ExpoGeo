document.addEventListener("DOMContentLoaded", function() {
  const dropdownBtn = document.querySelector('.dropdown-btn');
  const dropdownMenu = document.querySelector('.dropdown-menu');

  if (dropdownBtn && dropdownMenu) {
    // Toggle dropdown on button click
    dropdownBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdownMenu.classList.toggle('show');
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown')) {
        dropdownMenu.classList.remove('show');
      }
    });
  }
});