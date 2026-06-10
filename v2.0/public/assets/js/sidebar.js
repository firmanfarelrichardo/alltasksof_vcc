(function () {
  document.addEventListener('click', function (event) {
    if (event.target.closest('[data-sidebar-toggle]')) {
      document.body.classList.toggle('sidebar-open');
      return;
    }

    if (event.target.closest('[data-sidebar-close]')) {
      document.body.classList.remove('sidebar-open');
    }
  });
})();
